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

// The mega menu (main.js) locks the page behind it: pause the smooth scroll.
document.addEventListener('montela:menu', (event) => {
  if (event.detail.open) {
    lenis.stop();
  } else {
    lenis.start();
  }
});

// Delayed by WP Rocket, this may run after DOMContentLoaded has fired.
domReady(() => {

  // ---------------------------------------------------
  // Project-specific animations go here
  // ---------------------------------------------------

});
