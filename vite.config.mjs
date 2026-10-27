// vite.config.mjs

import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { defineConfig, loadEnv } from 'vite';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';

// Entry points — each one is resolved by key in dist/.vite/manifest.json
// from inc/vite.php (e.g. montela_vite_enqueue_style( 'x', 'assets/sass/main.sass' )).
//
// JS is split so WP Rocket's "Delay JavaScript execution" only needs to skip
// the small critical bundle (main.js). Animations and feature modules are
// separate entries, enqueued globally (animations) or only where used
// (modules, via montela_enqueue_module()). Each module imports its own CSS,
// which inc/vite.php enqueues from the manifest.
const entries = [
  'assets/js/main.js',
  'assets/js/animations.js',
  'assets/js/modules/swiper.js',
  'assets/js/modules/fancybox.js',
  'assets/js/modules/isotope.js',
  'assets/js/modules/activities.js',
  'assets/sass/main.sass',
  'assets/sass/admin-login.sass',
  'assets/sass/admin-dashboard.sass',
  'assets/sass/admin-bar.sass',
];

const port = 5173;

// File the dev server writes on start (and removes on exit) so WordPress
// knows to load assets from it instead of dist/. Never committed.
const hotFile = path.resolve('dist/hot');

// Local by WPEngine certificates, reused so the dev server runs on HTTPS
// with the same trusted cert as the site (no mixed-content errors).
const certPath = path.join(
  os.homedir(),
  os.platform() === 'win32'
    ? 'AppData/Roaming/Local/run/router/nginx/certs'
    : 'Library/Application Support/Local/run/router/nginx/certs'
);

/**
 * Returns the HTTPS options for the dev server, or false when the Local
 * certificates for the host are not found (falls back to plain HTTP).
 *
 * @param {string} host Local dev hostname (e.g. project-name.digid).
 * @returns {{key: Buffer, cert: Buffer}|false}
 */
function localHttps(host) {
  const key = path.join(certPath, `${host}.key`);
  const cert = path.join(certPath, `${host}.crt`);

  if (!fs.existsSync(key) || !fs.existsSync(cert)) {
    console.warn(`\n[vite] Local certificates for "${host}" not found in ${certPath} — dev server falls back to HTTP.\n`);
    return false;
  }

  return { key: fs.readFileSync(key), cert: fs.readFileSync(cert) };
}

/**
 * WordPress integration: writes/removes the hot file and triggers a full
 * page reload whenever a PHP template changes (replaces BrowserSync).
 *
 * @returns {import('vite').Plugin}
 */
function wordpress() {
  return {
    name: 'montela-wordpress',
    apply: 'serve',
    configureServer(server) {
      const { origin } = server.config.server;

      server.httpServer?.once('listening', () => {
        fs.mkdirSync(path.dirname(hotFile), { recursive: true });
        fs.writeFileSync(hotFile, origin);
      });

      const clean = () => {
        if (fs.existsSync(hotFile)) {
          fs.rmSync(hotFile);
        }
      };

      process.on('exit', clean);
      ['SIGINT', 'SIGTERM', 'SIGHUP'].forEach((signal) => {
        process.on(signal, () => {
          clean();
          process.exit();
        });
      });

      server.watcher.on('change', (file) => {
        if (file.endsWith('.php')) {
          server.ws.send({ type: 'full-reload', path: '*' });
        }
      });
    },
  };
}

export default defineConfig(({ mode }) => {
  // Local dev hostname — override per machine in an untracked .env.local
  // (gitignored) so releases never wipe your local URL:
  //   DEV_HOST=my-site.digid
  const env = loadEnv(mode, process.cwd(), '');
  const host = env.DEV_HOST || 'montela.digid'; // replace with local dev URL (or use .env.local)

  const https = localHttps(host);
  const origin = `${https ? 'https' : 'http'}://${host}:${port}`;

  return {
    // Relative base so url() references inside the compiled CSS resolve
    // against dist/ wherever the theme folder lives.
    base: './',
    publicDir: false,

    plugins: [wordpress()],

    css: {
      devSourcemap: true,
      preprocessorOptions: {
        sass: {
          // The partials still use @import — silence until migrated to @use/@forward.
          silenceDeprecations: ['import'],
        },
      },
      postcss: {
        plugins: [tailwindcss('./tailwind.config.js'), autoprefixer()],
      },
    },

    server: {
      host: '127.0.0.1',
      port,
      strictPort: true,
      https,
      origin,
      cors: { origin: [`https://${host}`, `http://${host}`] },
      allowedHosts: [host],
      hmr: { host },
      watch: {
        ignored: ['**/vendor/**', '**/php-stubs/**', '**/dist/**'],
      },
    },

    build: {
      outDir: 'dist',
      emptyOutDir: true,
      manifest: true,
      sourcemap: mode !== 'production',
      // Keep SVGs as separate (cached) files instead of data URIs: icons used
      // as CSS masks would otherwise be inlined into the render-blocking
      // main.css, twice each once autoprefixer adds -webkit-mask.
      assetsInlineLimit: (file) => (file.endsWith('.svg') ? false : undefined),
      rolldownOptions: {
        input: entries,
        output: {
          entryFileNames: 'js/[name]-[hash].js',
          chunkFileNames: 'js/[name]-[hash].js',
          assetFileNames: ({ names }) =>
            names.some((name) => name.endsWith('.css'))
              ? 'css/[name]-[hash][extname]'
              : 'assets/[name]-[hash][extname]',
        },
      },
    },
  };
});
