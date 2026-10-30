#!/usr/bin/env node
/**
 * QA check: renders a page at exact viewport widths in headless Brave/Chrome
 * (DevTools Protocol, no npm packages — Node 22's built-in WebSocket) and
 * reports layout faults found in the DOM, plus a full-page screenshot per
 * width.
 *
 * Usage:
 *   node .claude/tools/qa-check.mjs <url> [--widths 320,393,768] [--out dir]
 *
 * Defaults: the widths below, output in ./qa-report (outside git: pass an
 * --out in your scratchpad). Prints a JSON report; exit code 1 when any
 * width has faults.
 *
 * Checks per width:
 * - page-overflow   the page scrolls sideways (scrollWidth > viewport)
 * - offscreen       elements sticking out of the viewport, not clipped by an
 *                   overflow:hidden/clip ancestor
 * - clipped-text    text cut by its own box (overflow hidden + scrollWidth)
 * - overlap         links/buttons overlapping an image or another control
 * - img-alt         <img> without an alt attribute
 * - headings        not exactly one <h1>, or a skipped heading level
 */

import { spawn } from 'node:child_process';
import { mkdirSync, writeFileSync, mkdtempSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const DEFAULT_WIDTHS = [320, 360, 375, 393, 414, 600, 767, 768, 800, 834, 1024, 1279, 1280, 1366, 1440, 1535, 1536, 1920, 2560];
const BROWSERS = [
  '/Applications/Brave Browser.app/Contents/MacOS/Brave Browser',
  '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
];
const PORT = 9333;
const VIEWPORT_HEIGHT = 900;

const args = process.argv.slice(2);
const url = args.find((a) => !a.startsWith('--') && /^https?:/.test(a));
if (!url) {
  console.error('Usage: qa-check.mjs <url> [--widths 320,393] [--out dir]');
  process.exit(2);
}
const opt = (name) => {
  const i = args.indexOf(`--${name}`);
  return i > -1 ? args[i + 1] : null;
};
const widths = opt('widths') ? opt('widths').split(',').map(Number) : DEFAULT_WIDTHS;
const outDir = opt('out') || 'qa-report';
mkdirSync(outDir, { recursive: true });

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

// -- Browser -----------------------------------------------------------------
const { existsSync } = await import('node:fs');
const bin = BROWSERS.find((b) => existsSync(b));
if (!bin) {
  console.error('No Brave or Chrome found.');
  process.exit(2);
}
const browser = spawn(bin, [
  '--headless=new',
  `--remote-debugging-port=${PORT}`,
  `--user-data-dir=${mkdtempSync(join(tmpdir(), 'qa-'))}`,
  '--ignore-certificate-errors',
  '--hide-scrollbars',
  '--no-first-run',
  'about:blank',
], { stdio: 'ignore' });

let target;
for (let i = 0; i < 50 && !target; i++) {
  try {
    const list = await (await fetch(`http://127.0.0.1:${PORT}/json/list`)).json();
    target = list.find((t) => t.type === 'page');
  } catch {
    await sleep(100);
  }
}
if (!target) {
  browser.kill();
  console.error('Browser did not start.');
  process.exit(2);
}

// -- CDP ---------------------------------------------------------------------
const ws = new WebSocket(target.webSocketDebuggerUrl);
await new Promise((r) => ws.addEventListener('open', r, { once: true }));
let seq = 0;
const pending = new Map();
const listeners = [];
ws.addEventListener('message', (e) => {
  const msg = JSON.parse(e.data);
  if (msg.id && pending.has(msg.id)) {
    pending.get(msg.id)(msg);
    pending.delete(msg.id);
  } else if (msg.method) {
    listeners.forEach((l) => l(msg));
  }
});
const send = (method, params = {}) => new Promise((resolve, reject) => {
  const id = ++seq;
  pending.set(id, (m) => (m.error ? reject(new Error(`${method}: ${m.error.message}`)) : resolve(m.result)));
  ws.send(JSON.stringify({ id, method, params }));
});
const once = (method) => new Promise((r) => {
  const l = (m) => {
    if (m.method === method) {
      listeners.splice(listeners.indexOf(l), 1);
      r(m);
    }
  };
  listeners.push(l);
});

await send('Page.enable');
await send('Runtime.enable');

// Runs in the page. Returns the list of faults.
const AUDIT = `(() => {
  const vw = window.innerWidth;
  const faults = [];
  const label = (el) => {
    let s = el.tagName.toLowerCase();
    if (el.id) s += '#' + el.id;
    if (el.classList.length) s += '.' + [...el.classList].slice(0, 3).join('.');
    return s;
  };
  const visible = (el) => {
    const cs = getComputedStyle(el);
    const r = el.getBoundingClientRect();
    // Visually hidden text (sr-only, skip-link at rest) is a 1px absolute box: not a fault.
    const srOnly = cs.position === 'absolute' && r.width <= 1 && r.height <= 1;
    return cs.display !== 'none' && cs.visibility !== 'hidden' && r.width > 0 && r.height > 0 && !srOnly;
  };
  const clippedBy = (el) => {
    for (let p = el.parentElement; p && p !== document.body; p = p.parentElement) {
      const cs = getComputedStyle(p);
      if (/(hidden|clip)/.test(cs.overflowX)) return p;
    }
    return null;
  };

  // Elements the page should not be scanned for (WP admin bar, WPML banner, fixed overlays closed).
  const skip = (el) => el.closest('#wpadminbar, .otgs-development-site-front-end, #mega-menu:not(.is-open)');

  const docW = document.documentElement.scrollWidth;
  if (docW > vw + 1) faults.push({ check: 'page-overflow', detail: 'page is ' + docW + 'px wide in a ' + vw + 'px viewport' });

  const all = [...document.querySelectorAll('body *')].filter((el) => !skip(el) && visible(el));

  for (const el of all) {
    const r = el.getBoundingClientRect();
    if ((r.right > vw + 1 || r.left < -1) && !clippedBy(el)) {
      const parent = el.parentElement && el.parentElement.getBoundingClientRect();
      const parentAlsoOut = parent && (parent.right > vw + 1 || parent.left < -1) && !clippedBy(el.parentElement);
      if (!parentAlsoOut) faults.push({ check: 'offscreen', el: label(el), detail: 'left ' + Math.round(r.left) + ', right ' + Math.round(r.right) + ' (viewport ' + vw + ')' });
    }
    const cs = getComputedStyle(el);
    if (/(hidden|clip)/.test(cs.overflowX) && el.scrollWidth > el.clientWidth + 1 && el.textContent.trim() && !el.querySelector('img, video, picture, svg') && el.children.length === 0) {
      faults.push({ check: 'clipped-text', el: label(el), detail: 'content ' + el.scrollWidth + 'px in a ' + el.clientWidth + 'px box: "' + el.textContent.trim().slice(0, 40) + '"' });
    }
  }

  const controls = all.filter((el) => el.matches('a, button, input, select, textarea') && !el.closest('a, button') !== false);
  const media = all.filter((el) => el.matches('img, video'));
  const rect = (el) => el.getBoundingClientRect();
  const hit = (a, b) => a.left < b.right - 1 && b.left < a.right - 1 && a.top < b.bottom - 1 && b.top < a.bottom - 1;
  for (let i = 0; i < controls.length; i++) {
    const a = controls[i];
    const ra = rect(a);
    for (const m of media) {
      if (a.contains(m) || m.contains(a)) continue;
      // Content deliberately laid over a background image (heroes) shares a section with it.
      const sec = m.closest('section, header, footer, figure');
      if (sec && sec.contains(a) && getComputedStyle(m).position === 'absolute') continue;
      if (hit(ra, rect(m))) faults.push({ check: 'overlap', el: label(a), detail: 'overlaps image ' + label(m) });
    }
    for (let j = i + 1; j < controls.length; j++) {
      const b = controls[j];
      if (a.contains(b) || b.contains(a)) continue;
      if (hit(ra, rect(b))) faults.push({ check: 'overlap', el: label(a), detail: 'overlaps control ' + label(b) });
    }
  }

  for (const img of document.querySelectorAll('img')) {
    if (!img.hasAttribute('alt')) faults.push({ check: 'img-alt', el: label(img), detail: img.currentSrc || img.src });
  }

  const hs = [...document.querySelectorAll('h1, h2, h3, h4, h5, h6')].filter((h) => !skip(h));
  const h1 = hs.filter((h) => h.tagName === 'H1').length;
  if (h1 !== 1) faults.push({ check: 'headings', detail: h1 + ' <h1> on the page' });
  let prev = 0;
  for (const h of hs) {
    const lvl = Number(h.tagName[1]);
    if (prev && lvl > prev + 1) faults.push({ check: 'headings', el: label(h), detail: 'h' + prev + ' → h' + lvl + ': "' + h.textContent.trim().slice(0, 40) + '"' });
    prev = lvl;
  }
  return faults;
})()`;

// -- Run ---------------------------------------------------------------------
const report = { url, widths: {} };
let failed = false;

for (const w of widths) {
  await send('Emulation.setDeviceMetricsOverride', { width: w, height: VIEWPORT_HEIGHT, deviceScaleFactor: 1, mobile: w < 768 });
  const loaded = once('Page.loadEventFired');
  await send('Page.navigate', { url });
  await loaded;
  // Fonts, lazy images and entry animations.
  await send('Runtime.evaluate', { expression: 'document.fonts.ready.then(() => true)', awaitPromise: true });
  await send('Runtime.evaluate', {
    expression: `(async () => {
      for (let y = 0; y < document.documentElement.scrollHeight; y += ${VIEWPORT_HEIGHT}) { window.scrollTo(0, y); await new Promise(r => setTimeout(r, 120)); }
      window.scrollTo(0, 0); await new Promise(r => setTimeout(r, 800));
    })()`,
    awaitPromise: true,
  });

  const { result } = await send('Runtime.evaluate', { expression: AUDIT, returnByValue: true });
  const faults = result.value || [];
  if (faults.length) failed = true;

  // Full page, keeping 100vh sections at the viewport height.
  const { result: h } = await send('Runtime.evaluate', { expression: 'document.documentElement.scrollHeight', returnByValue: true });
  const shot = await send('Page.captureScreenshot', {
    format: 'png',
    captureBeyondViewport: true,
    clip: { x: 0, y: 0, width: w, height: Math.min(h.value, 16000), scale: 1 },
  });
  const file = join(outDir, `${w}.png`);
  writeFileSync(file, Buffer.from(shot.data, 'base64'));

  report.widths[w] = { screenshot: file, faults };
}

writeFileSync(join(outDir, 'report.json'), JSON.stringify(report, null, 2));
console.log(JSON.stringify(report, null, 2));
ws.close();
browser.kill();
process.exit(failed ? 1 : 0);
