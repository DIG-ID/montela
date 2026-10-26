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

// Delayed by WP Rocket, this may run after DOMContentLoaded has fired.
domReady(() => {

  // ---------------------------------------------------
  // Project-specific animations go here
  // ---------------------------------------------------

});
