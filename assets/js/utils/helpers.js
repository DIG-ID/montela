/**
 * General-purpose utility functions.
 */

/**
 * Debounce — delays a function call until after a given wait time.
 * Useful for resize/scroll listeners.
 *
 * @param {Function} fn   Function to debounce.
 * @param {number}   wait Wait time in milliseconds.
 * @returns {Function}
 */
export function debounce(fn, wait = 200) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), wait);
  };
}

/**
 * Returns true if the current device is touch-based.
 *
 * @returns {boolean}
 */
export function isTouchDevice() {
  return window.matchMedia('(hover: none)').matches;
}

/**
 * Runs a callback once the DOM is ready, even if DOMContentLoaded has
 * already fired (e.g. when WP Rocket delays the script until the first
 * user interaction).
 *
 * @param {Function} fn Callback to run.
 */
export function domReady(fn) {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fn);
  } else {
    fn();
  }
}
