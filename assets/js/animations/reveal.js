// =============================================================================
// Reveal — scroll animations driven by classes (GSAP + ScrollTrigger).
//
// Add a class to an element and it animates in when it enters the screen:
//
//   anim-fade          fade in, in place
//   anim-fade-up       fade in, rising        (from below)
//   anim-fade-down     fade in, dropping      (from above)
//   anim-fade-left     fade in from the left  (moves to the right)
//   anim-fade-right    fade in from the right (moves to the left)
//   anim-zoom-in       fade in, growing slightly
//
// Modifiers, next to a type:
//
//   anim-children      animate each direct child instead of the element
//                      (lists, card grids) — the type goes on the parent
//   anim-repeat        play every time: it hides again when scrolled back
//                      above it, and plays again on the way down
//   anim-out           fade out as it leaves through the top (tied to the
//                      scroll, like the hero's scroll cue). Works alone too.
//
// On its own:
//
//   anim-parallax      on an image (or a media wrapper) whose parent clips
//                      it (overflow: hidden): the image moves slower than
//                      the page. Anywhere on the page:
//                        - at the top of the page (the hero): still on
//                          load, then lags behind as the page scrolls away;
//                        - further down: drifts across the frame while the
//                          frame crosses the screen; it is enlarged by the
//                          speed (0.15 = 15%) so no edge ever shows.
//                      data-anim-speed="0.3" overrides the default
//                      (REVEAL.parallax at the top, REVEAL.parallaxInPage
//                      further down).
//
// Optional per element, in seconds: data-anim-delay="0.2",
// data-anim-duration="1.4".
//
// Staggering: everything that enters in the same moment plays one after the
// other, in reading order (top to bottom, then left to right) — a title, a
// text and a photo side by side on desktop come in as a staircase; stacked
// on mobile, each comes in when it reaches the screen. No grouping needed.
//
// No flash: an element is only hidden if it is below the screen when this
// runs (WP Rocket may delay this file until the first interaction — what
// the visitor already sees is never hidden and replayed). Without this file
// everything is simply visible, and so it is with "reduce motion".
//
// Put the class on a wrapper, not on an element that has its own transform
// (Tailwind translate / scale) — the animation would override it. Don't nest
// animated elements (the child would fade twice).
// =============================================================================
import { gsap, ScrollTrigger } from '../gsap.js';
import { debounce } from '../utils/helpers.js';

// -----------------------------------------------------------------------------
// Settings — every reveal on the site.
// -----------------------------------------------------------------------------
export const REVEAL = {
  duration: 1.1, // seconds per element
  ease: 'power3.out',
  stagger: 0.15, // seconds between elements entering together
  distance: 50, // px — up / down
  distanceX: 60, // px — left / right
  scale: 0.94, // zoom-in start
  start: 'top 88%', // element top crosses 88% of the screen height
  batchInterval: 0.1, // seconds — elements entering within this window stagger together
  outStart: 'top 15%', // anim-out: fading starts here…
  outEnd: 'bottom top', // …and ends when the element has left the screen
  parallax: 0.35, // anim-parallax at the top of the page: share of the scroll it lags behind (0 = none, 1 = fixed)
  parallaxInPage: 0.15, // anim-parallax further down: travel and zoom, as a share of the frame's height
};

// Start state of each type (the end state is always "as in the CSS").
const TYPES = {
  'anim-fade': () => ({}),
  'anim-fade-up': () => ({ y: REVEAL.distance }),
  'anim-fade-down': () => ({ y: -REVEAL.distance }),
  'anim-fade-left': () => ({ x: -REVEAL.distanceX }),
  'anim-fade-right': () => ({ x: REVEAL.distanceX }),
  'anim-zoom-in': () => ({ scale: REVEAL.scale }),
};

const TYPE_SELECTOR = Object.keys(TYPES)
  .map((type) => `.${type}`)
  .join(',');

// Inline styles the reveal leaves behind are removed at the end, so the
// element is back to its CSS (hover transitions, Tailwind transforms…).
const CLEAR = 'opacity,transform,transition';

/**
 * Start state of an element, from its own type class or its parent's (anim-children).
 *
 * @param {Element} el Element to animate.
 * @returns {Object} GSAP vars.
 */
function hiddenState(el) {
  const source = el.matches(TYPE_SELECTOR) ? el : el.parentElement;
  const type = Object.keys(TYPES).find((name) => source.classList.contains(name));

  // transition: none — a CSS transition on opacity / transform (buttons,
  // cards) would fight the tween every frame and flicker.
  return { opacity: 0, transition: 'none', ...TYPES[type]() };
}

/**
 * Number from a data attribute, or the fallback.
 *
 * @param {Element} el       Element.
 * @param {string}  name     Dataset key.
 * @param {number}  fallback Default value.
 * @returns {number}
 */
function dataNumber(el, name, fallback) {
  const value = parseFloat(el.dataset[name]);
  return Number.isNaN(value) ? fallback : value;
}

/**
 * Reading order: top to bottom, then left to right (rows within 10px count
 * as the same line).
 *
 * @param {Element[]} elements Elements entering together.
 * @returns {Element[]}
 */
function readingOrder(elements) {
  return elements
    .map((el) => ({ el, rect: el.getBoundingClientRect() }))
    .sort((a, b) => (Math.abs(a.rect.top - b.rect.top) > 10 ? a.rect.top - b.rect.top : a.rect.left - b.rect.left))
    .map((item) => item.el);
}

/**
 * Plays a batch: each element from its start state to its CSS, staggered.
 *
 * @param {Element[]} batch Elements that entered together.
 */
function play(batch) {
  // Jumped past (anchor link, End key, restored scroll): already above the
  // screen — show at once, and leave them out of the staircase.
  const above = batch.filter((el) => el.getBoundingClientRect().bottom <= 0);
  show(above);

  readingOrder(batch.filter((el) => !above.includes(el))).forEach((el, index) => {
    gsap.to(el, {
      opacity: 1,
      x: 0,
      y: 0,
      scale: 1,
      duration: dataNumber(el, 'animDuration', REVEAL.duration),
      delay: index * REVEAL.stagger + dataNumber(el, 'animDelay', 0),
      ease: REVEAL.ease,
      overwrite: true,
      clearProps: CLEAR,
    });
  });
}

/**
 * Shows a batch at once, without animating — elements scrolled past in a
 * jump (an anchor link, the End key, a restored scroll position), which
 * would otherwise stay hidden above the screen.
 *
 * @param {Element[]} batch Elements that left through the top.
 */
function show(batch) {
  batch.forEach((el) => {
    gsap.killTweensOf(el);
    gsap.set(el, { clearProps: CLEAR });
  });
}

/**
 * Hides a batch again (anim-repeat, scrolled back above it).
 *
 * @param {Element[]} batch Elements that left through the bottom.
 */
function hide(batch) {
  batch.forEach((el) => {
    gsap.killTweensOf(el);
    gsap.set(el, hiddenState(el));
  });
}

/**
 * The elements to reveal inside a root: the typed elements, or their
 * children with anim-children.
 *
 * @param {ParentNode} root Where to look.
 * @returns {Element[]}
 */
function targets(root) {
  const found = [];
  root.querySelectorAll(TYPE_SELECTOR).forEach((el) => {
    if (el.classList.contains('anim-children')) {
      found.push(...el.children);
    } else {
      found.push(el);
    }
  });
  return found;
}

/**
 * Sets up the reveals inside a root (the whole page by default). Can be
 * called again on content added later (AJAX): elements already set up are
 * skipped.
 *
 * @param {ParentNode} [root=document] Where to look.
 */
export function initReveal(root = document) {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  const once = [];
  const repeat = [];

  targets(root).forEach((el) => {
    if (el.dataset.animReady) {
      return;
    }
    el.dataset.animReady = '1';

    // Already on screen: the visitor may have seen it — leave it alone.
    // Not rendered (display: none — a closed popup, a breakpoint-only
    // element): it would never trigger and stay hidden when it opens.
    if (!el.getClientRects().length || ScrollTrigger.isInViewport(el)) {
      return;
    }

    gsap.set(el, hiddenState(el));
    const parent = el.matches(TYPE_SELECTOR) ? el : el.parentElement;
    (parent.classList.contains('anim-repeat') ? repeat : once).push(el);
  });

  // clamp(): an element too close to the end of the page to ever reach the
  // start line plays when the page is scrolled to the bottom.
  const start = `clamp(${REVEAL.start})`;

  if (once.length) {
    ScrollTrigger.batch(once, { start, interval: REVEAL.batchInterval, once: true, onEnter: play, onLeave: show });
  }
  if (repeat.length) {
    ScrollTrigger.batch(repeat, { start, interval: REVEAL.batchInterval, onEnter: play, onLeave: show, onEnterBack: play, onLeaveBack: hide });
  }

  root.querySelectorAll('.anim-out').forEach((el) => {
    if (el.dataset.animOutReady) {
      return;
    }
    el.dataset.animOutReady = '1';
    gsap.to(el, {
      opacity: 0,
      ease: 'none',
      scrollTrigger: { trigger: el, start: REVEAL.outStart, end: REVEAL.outEnd, scrub: true },
    });
  });
}

/**
 * Parallax (anim-parallax). Two cases, by where the frame (the parent) sits:
 *
 * - At the top of the page (the hero): from scroll 0 until the frame has
 *   left through the top, the element moves down by speed × the frame's
 *   height. Still on load; the gap it opens is above the screen.
 * - Further down: while the frame crosses the screen (its top at the
 *   bottom → its bottom at the top), the element moves from −half to +half
 *   of speed × the frame's height, scaled up by the same share so it always
 *   covers the frame.
 *
 * Off with "reduce motion".
 *
 * @param {ParentNode} [root=document] Where to look.
 */
export function initParallax(root = document) {
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  root.querySelectorAll('.anim-parallax').forEach((el) => {
    if (el.dataset.animParallaxReady) {
      return;
    }
    el.dataset.animParallaxReady = '1';
    const frame = el.parentElement;

    // At the top: the frame starts within the header's height of the page top.
    const atTop = frame.getBoundingClientRect().top + window.scrollY < 100;

    if (atTop) {
      const speed = dataNumber(el, 'animSpeed', REVEAL.parallax);
      gsap.to(el, {
        y: () => frame.offsetHeight * speed,
        ease: 'none',
        scrollTrigger: {
          trigger: frame,
          start: 'top top',
          end: 'bottom top',
          scrub: true,
          invalidateOnRefresh: true, // re-measure the height on resize
        },
      });
      return;
    }

    const speed = dataNumber(el, 'animSpeed', REVEAL.parallaxInPage);
    gsap.set(el, { scale: 1 + speed, transformOrigin: '50% 50%' });
    gsap.fromTo(
      el,
      { y: () => (-frame.offsetHeight * speed) / 2 },
      {
        y: () => (frame.offsetHeight * speed) / 2,
        ease: 'none',
        scrollTrigger: {
          trigger: frame,
          start: 'top bottom',
          end: 'bottom top',
          scrub: true,
          invalidateOnRefresh: true,
        },
      },
    );
  });
}

/**
 * Keeps the trigger positions right when the page height changes after
 * load (lazy images, sliders, accordions) — ScrollTrigger only measures on
 * load and resize by itself.
 */
export function watchPageHeight() {
  if (!('ResizeObserver' in window)) {
    return;
  }
  let height = document.body.offsetHeight;
  const refresh = debounce(() => ScrollTrigger.refresh(), 250);
  new ResizeObserver(() => {
    if (document.body.offsetHeight !== height) {
      height = document.body.offsetHeight;
      refresh();
    }
  }).observe(document.body);
}
