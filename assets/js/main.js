// =============================================================================
// Critical entry — loaded on every page and safe to exclude from WP Rocket's
// "Delay JavaScript execution". Keep it small and library-free: only what the
// site needs to work before any user interaction (navigation, mega menu,
// sticky header, accessibility helpers).
//
// Non-essential features live in their own entries so WP Rocket can delay
// them (see vite.config.mjs and montela_enqueue_module() in inc/enqueue.php):
//   - assets/js/animations.js       GSAP, ScrollTrigger, Lenis
//   - assets/js/modules/swiper.js   sliders
//   - assets/js/modules/fancybox.js lightbox
//   - assets/js/modules/isotope.js  filterable grids
// =============================================================================

// =============================================================================
// Utilities
// =============================================================================
// import { debounce, isTouchDevice } from './utils/helpers.js';

// =============================================================================
// DOM ready
// =============================================================================
document.addEventListener('DOMContentLoaded', () => {

  // ---------------------------------------------------
  // Project-specific critical initialisation goes here
  // ---------------------------------------------------

});
