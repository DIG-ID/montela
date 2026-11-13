// =============================================================================
// Animations entry — GSAP, ScrollTrigger and Lenis smooth scroll.
//
// Loaded on every page but NOT critical: WP Rocket may delay it until the
// first user interaction. Content must therefore be fully visible without
// this file — set initial states (opacity, transforms) here with GSAP
// (e.g. gsap.from()), never in the CSS, and don't make the hero/LCP depend
// on an entrance animation.
// =============================================================================
import { gsap, ScrollTrigger, lenis } from './gsap.js';
import { domReady } from './utils/helpers.js';
import { initReveal, initParallax, watchPageHeight } from './animations/reveal.js';

// The mega menu (main.js) locks the page behind it: pause the smooth scroll.
document.addEventListener('montela:menu', (event) => {
  if (event.detail.open) {
    lenis.stop();
  } else {
    lenis.start();
  }
});

// Pixels of scroll over which the hero's scroll cue fades out. Above the
// domReady() call: it may run straight away.
const SCROLL_CUE_FADE = 400;

// Delayed by WP Rocket, this may run after DOMContentLoaded has fired.
domReady(() => {

  // Scroll reveals by class (anim-fade-up…) — settings and the class list
  // at the top of animations/reveal.js.
  initReveal();
  initParallax();
  watchPageHeight();

  heroScrollCue();

});

/**
 * Hero scroll cue (modules/hero.php): fades out over the first
 * SCROLL_CUE_FADE pixels of scroll, tied to the scroll position (scrub), so
 * it comes back on the way up. Without this file it simply stays.
 */
function heroScrollCue() {
  const cue = document.querySelector('.section-hero__scroll');
  if (!cue) {
    return;
  }

  gsap.to(cue, {
    autoAlpha: 0,
    ease: 'none',
    scrollTrigger: {
      start: 0,
      end: SCROLL_CUE_FADE,
      scrub: 0.5,
    },
  });
}
