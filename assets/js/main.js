// =============================================================================
// Critical entry — loaded on every page and safe to exclude from WP Rocket's
// "Delay JavaScript execution". Keep it small and library-free: only what the
// site needs to work before any user interaction (navigation, mega menu,
// sticky header, sticky CTA, accessibility helpers).
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
// Mega menu
// =============================================================================
// The header toggle opens #mega-menu (template-parts/mega-menu.php) by
// putting `.is-menu-open` on <body>; the CSS does the rest. While open:
// - the page behind is `inert` and does not scroll (until the close
//   animation ends). Lenis lives in the delayed animations.js, so it is told
//   through the `montela:menu` event;
// - an item with children swaps the grid for its sub-menu view, "Back"
//   swaps it back;
// - Esc steps back out of a sub-menu first, then closes the menu.
function initMegaMenu() {
  const toggle = document.querySelector('.header-main__toggle');
  if (!toggle) {
    return;
  }

  const menu = document.getElementById('mega-menu');
  const nav = menu ? menu.querySelector('.mega-menu__nav') : null;
  const header = document.getElementById('header-main');
  const background = document.querySelectorAll('#main-content, .footer-main, .sticky-cta');

  const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';
  const openSub = () => (nav ? nav.querySelector('.mega-menu__sub.is-open') : null);

  // Shows the sub-menu of `button`, or the grid again when it is null. The
  // view on screen leaves first (.is-leaving, 250ms "out" animation in
  // _mega-menu.sass), then the views are swapped and the new one comes in.
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let switching = false;

  const showSub = (button) => {
    if (switching) {
      return;
    }
    const leaving = openSub() || nav.querySelector('.mega-menu__grid');
    switching = true;
    leaving.classList.add('is-leaving');
    setTimeout(() => {
      leaving.classList.remove('is-leaving');
      switching = false;
      swapView(button);
    }, reduceMotion.matches ? 0 : 250);
  };

  const swapView = (button) => {
    const current = openSub();
    if (current) {
      current.classList.remove('is-open');
      const opener = nav.querySelector(`[aria-controls="${current.id}"]`);
      opener.setAttribute('aria-expanded', 'false');
      if (!button) {
        nav.classList.remove('is-sub');
        nav.classList.add('is-back');
        opener.focus();
      }
    }

    if (button) {
      const sub = document.getElementById(button.getAttribute('aria-controls'));
      button.setAttribute('aria-expanded', 'true');
      nav.classList.remove('is-back');
      nav.classList.add('is-sub');
      sub.classList.add('is-open');
      sub.querySelector('a').focus();
    }
  };

  // Page scroll lock. Hiding the scrollbar would widen the page and shift
  // everything mid-animation, so its width is put back as padding on <html>
  // and as --scroll-lock-gap for the fixed header (_header.sass). Zero with
  // overlay scrollbars (macOS default).
  const lockScroll = (lock) => {
    const root = document.documentElement;
    const gap = lock ? window.innerWidth - root.clientWidth : 0;
    root.style.overflow = lock ? 'hidden' : '';
    root.style.paddingRight = gap ? `${gap}px` : '';
    root.style.setProperty('--scroll-lock-gap', `${gap}px`);
    document.dispatchEvent(new CustomEvent('montela:menu', { detail: { open: lock } }));
  };

  // Closing lasts until the curtain is up (900ms, _mega-menu.sass). Until
  // then the page stays locked and the header fixed (`.is-menu-closing`),
  // or the scrollbar and the header would jump in mid-animation.
  let closing = null;

  const setOpen = (open) => {
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? toggle.dataset.labelClose : toggle.dataset.labelOpen);
    background.forEach((el) => {
      el.inert = open;
    });

    clearTimeout(closing);
    if (open) {
      if (!document.body.classList.contains('is-menu-closing')) {
        // The header turns from absolute to fixed: pin it where it is on
        // screen, or it jumps by however far the page was scrolled. Scrolled
        // out of view, it takes its normal top (the CSS one) instead.
        const rect = header.getBoundingClientRect();
        header.style.top = rect.bottom > 0 ? `${rect.top}px` : '';
        lockScroll(true);
      }
      document.body.classList.remove('is-menu-closing');
      document.body.classList.add('is-menu-open');
      // After the panel turns visible (visibility is transitioned).
      setTimeout(() => menu?.querySelector('.mega-menu__item')?.focus(), 50);
      return;
    }

    document.body.classList.remove('is-menu-open');
    document.body.classList.add('is-menu-closing');
    closing = setTimeout(() => {
      document.body.classList.remove('is-menu-closing');
      header.style.top = '';
      lockScroll(false);
      // Back to the grid for the next opening.
      if (nav && openSub()) {
        openSub().classList.remove('is-open');
        nav.classList.remove('is-sub', 'is-back');
        nav.querySelectorAll('.mega-menu__grid [aria-expanded]').forEach((el) => el.setAttribute('aria-expanded', 'false'));
      }
    }, 900);
  };

  toggle.addEventListener('click', () => {
    setOpen(!isOpen());
  });

  if (nav) {
    nav.addEventListener('click', (event) => {
      const opener = event.target.closest('.mega-menu__grid [aria-controls]');
      if (opener) {
        showSub(opener);
      } else if (event.target.closest('.mega-menu__back')) {
        showSub(null);
      }
    });
  }

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape' || event.defaultPrevented || !isOpen()) {
      return;
    }
    if (openSub()) {
      showSub(null);
    } else {
      setOpen(false);
      toggle.focus();
    }
  });
}

// =============================================================================
// Header language switcher
// =============================================================================
// The box with the current language opens the list of the others. Closes on
// a second click, a click outside or Esc.
function initLangSwitcher() {
  const switcher = document.querySelector('.header-main__lang-switcher');
  if (!switcher) {
    return;
  }

  const button = switcher.querySelector('button');
  const list = switcher.querySelector('.header-main__langs');

  // A class, not the hidden attribute: hidden would cut the staircase
  // animation in _header.sass.
  const isOpen = () => list.classList.contains('is-open');

  const setOpen = (open) => {
    button.setAttribute('aria-expanded', String(open));
    list.classList.toggle('is-open', open);
  };

  button.addEventListener('click', () => {
    setOpen(!isOpen());
  });

  document.addEventListener('click', (event) => {
    if (isOpen() && !switcher.contains(event.target)) {
      setOpen(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isOpen()) {
      // Handled: the mega menu's Esc listener leaves it alone.
      event.preventDefault();
      setOpen(false);
      button.focus();
    }
  });
}

// =============================================================================
// Sticky CTA
// =============================================================================
// The corner button (template-parts/sticky-cta.php) opens and closes the
// card above it with `.is-open`. Also closes on Esc and on a click outside.
// While the footer is on screen the whole thing hides (`.is-hidden`) and
// closes, so it never covers the footer's links.
function initStickyCta() {
  const cta = document.querySelector('[data-sticky-cta]');
  if (!cta) {
    return;
  }

  const toggle = cta.querySelector('.sticky-cta__toggle');
  const panel = cta.querySelector('.sticky-cta__panel');

  const isOpen = () => cta.classList.contains('is-open');

  const setOpen = (open) => {
    cta.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? toggle.dataset.labelClose : toggle.dataset.labelOpen);
    // Closed, the card's link stays out of the tab order.
    panel.inert = !open;
  };

  setOpen(false);

  toggle.addEventListener('click', () => {
    setOpen(!isOpen());
  });

  document.addEventListener('click', (event) => {
    if (isOpen() && !cta.contains(event.target)) {
      setOpen(false);
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !event.defaultPrevented && isOpen()) {
      // Handled: the mega menu's Esc listener leaves it alone.
      event.preventDefault();
      setOpen(false);
      toggle.focus();
    }
  });

  const footer = document.querySelector('.footer-main');
  if (footer && 'IntersectionObserver' in window) {
    new IntersectionObserver(([entry]) => {
      cta.classList.toggle('is-hidden', entry.isIntersecting);
      if (entry.isIntersecting) {
        setOpen(false);
      }
    }).observe(footer);
  }
}

// =============================================================================
// Sticky header
// =============================================================================
// Once the header has scrolled out of view, it comes back fixed at the top as
// a compact sage bar (`.is-header-stuck` on <body>, _header.sass), and turns
// back into the normal header at the top of the page. While the mega menu
// locks the page, the state does not change.
function initStickyHeader() {
  const header = document.getElementById('header-main');
  if (!header) {
    return;
  }

  const body = document.body;
  // The normal header's height: measured while it is not stuck.
  let threshold = header.offsetHeight;
  let ticking = false;

  const update = () => {
    ticking = false;
    if (body.classList.contains('is-menu-open') || body.classList.contains('is-menu-closing')) {
      return;
    }
    if (!body.classList.contains('is-header-stuck')) {
      threshold = header.offsetHeight;
    }
    body.classList.toggle('is-header-stuck', window.scrollY > threshold);
  };

  const onScroll = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  update();
}

// =============================================================================
// DOM ready
// =============================================================================
document.addEventListener('DOMContentLoaded', () => {
  // Language switcher and sticky CTA first: their Esc listeners must run
  // before the menu's.
  initLangSwitcher();
  initStickyCta();
  initMegaMenu();
  initStickyHeader();
});
