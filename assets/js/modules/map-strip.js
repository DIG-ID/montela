// =============================================================================
// Map strip module — enqueued by template-parts/modules/map-strip.php:
//   montela_enqueue_module( 'map-strip' );
//
// A cropped, static view of the illustrated map: no zoom or pan, only the
// pins and their popup.
//
// - Pins outside the crop (it changes per breakpoint) or under the footer's
//   box (--covered, set in _map-strip.sass) are hidden, so no one tabs to an
//   invisible button.
// - Hover (mouse) opens the popup; click / tap / Enter toggles it; Esc, the
//   close button and a click outside close it.
// - Desktop: the popup sits beside the pin, centred on it vertically — on
//   its left, or its right when there is no room. Tablet / mobile: its
//   corner just off the pin, above-left, flipping right / below. Figma: its
//   corner on the pin.
// - Season switch (the Map module's): cross-fades the layers (CSS); the
//   inactive one is inert. The cream pill slides as in map.js.
// - The places light up one after the other the first time the strip
//   scrolls into view (CSS, .is-revealed).
// =============================================================================
import { domReady } from '../utils/helpers.js';

const MARGIN = 8;
const desktopQuery = window.matchMedia('(min-width: 1280px)');

function initStrip(strip) {
  const frame = strip.querySelector('.section-map-strip__frame');
  const pins = [...strip.querySelectorAll('[data-map-strip-pin]')];
  const popup = strip.querySelector('[data-map-strip-popup]');
  const body = strip.querySelector('[data-map-strip-body]');
  const close = strip.querySelector('[data-map-strip-close]');
  let active = null;
  let hoverTimer = null;

  const layers = [...strip.querySelectorAll('[data-map-strip-layer]')];
  const seasons = strip.querySelector('[data-map-strip-seasons]');
  const seasonBtns = [...strip.querySelectorAll('[data-map-strip-season]')];

  // Hide the pins the frame does not show; number the others per layer for
  // the entry animation.
  function updatePins() {
    const frameRect = frame.getBoundingClientRect();
    const covered = parseFloat(getComputedStyle(frame).getPropertyValue('--covered')) || 0;
    const bottom = frameRect.bottom - covered;
    const counts = new Map();

    pins.forEach((pin) => {
      const layer = pin.closest('[data-map-strip-layer]');
      const index = counts.get(layer) || 0;
      pin.hidden = false;
      const rect = pin.getBoundingClientRect();
      const x = rect.left + rect.width / 2;
      const y = rect.top + rect.height / 2;
      const inside = x - rect.width / 2 >= frameRect.left && x + rect.width / 2 <= frameRect.right && y - rect.height / 2 >= frameRect.top && y + rect.height / 2 <= bottom;
      pin.hidden = !inside;
      if (inside) {
        pin.style.setProperty('--pin-index', String(index));
        counts.set(layer, index + 1);
      }
    });

    if (active && active.hidden) {
      closePopup();
    }
  }

  function position() {
    if (!active) {
      return;
    }
    const stripRect = strip.getBoundingClientRect();
    const pinRect = active.getBoundingClientRect();
    const x = pinRect.left + pinRect.width / 2 - stripRect.left;
    const y = pinRect.top + pinRect.height / 2 - stripRect.top;
    const width = popup.offsetWidth;
    const height = popup.offsetHeight;

    let left;
    let top;

    if (desktopQuery.matches) {
      // Desktop: beside the pin, centred on it vertically, the disc + 12px
      // clear — on its left, or its right when there is no room (Daniel,
      // 13.11.2026; Figma puts the card's corner on the pin).
      const gap = pinRect.width / 2 + 12;
      left = x - gap - width;
      if (left < MARGIN) {
        left = x + gap;
      }
      top = y - height / 2;
    } else {
      // Tablet and mobile: the corner off the disc, diagonally — above-left,
      // flipping right / below when there is no room.
      const gap = pinRect.width / 2 + 4;
      left = x - gap - width;
      if (left < MARGIN) {
        left = x + gap;
      }
      top = y - gap - height;
      if (top < MARGIN) {
        top = y + gap;
      }
    }

    // Never past the bottom of what shows (the footer's box covers the rest).
    const covered = parseFloat(getComputedStyle(frame).getPropertyValue('--covered')) || 0;
    const maxTop = frame.offsetHeight - covered - height - MARGIN;

    left = Math.max(MARGIN, Math.min(left, stripRect.width - width - MARGIN));
    top = Math.max(MARGIN, Math.min(top, maxTop));
    popup.style.transform = `translate(${Math.round(left)}px, ${Math.round(top)}px)`;
  }

  function openPopup(pin) {
    const content = document.getElementById(pin.dataset.content);
    if (!content) {
      return;
    }
    window.clearTimeout(hoverTimer);
    if (active && active !== pin) {
      active.classList.remove('is-active');
      active.setAttribute('aria-expanded', 'false');
    }
    active = pin;
    pin.classList.add('is-active');
    pin.setAttribute('aria-expanded', 'true');
    body.innerHTML = content.innerHTML;
    popup.hidden = false;
    position();
  }

  function closePopup() {
    window.clearTimeout(hoverTimer);
    if (!active) {
      return;
    }
    active.classList.remove('is-active');
    active.setAttribute('aria-expanded', 'false');
    active = null;
    popup.hidden = true;
  }

  const closeLater = (event) => {
    if (event.pointerType === 'mouse') {
      hoverTimer = window.setTimeout(closePopup, 300);
    }
  };

  pins.forEach((pin) => {
    pin.addEventListener('click', () => (active === pin ? closePopup() : openPopup(pin)));
    pin.addEventListener('pointerenter', (event) => {
      if (event.pointerType === 'mouse') {
        openPopup(pin);
      }
    });
    pin.addEventListener('pointerleave', closeLater);
  });

  popup.addEventListener('pointerenter', () => window.clearTimeout(hoverTimer));
  popup.addEventListener('pointerleave', closeLater);

  close.addEventListener('click', () => {
    const pin = active;
    closePopup();
    pin?.focus();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && active) {
      const pin = active;
      closePopup();
      pin.focus();
    }
  });

  document.addEventListener('click', (event) => {
    if (!strip.contains(event.target)) {
      closePopup();
    }
  });

  let resizeRaf = null;
  window.addEventListener('resize', () => {
    if (resizeRaf) {
      return;
    }
    resizeRaf = window.requestAnimationFrame(() => {
      resizeRaf = null;
      updatePins();
      position();
    });
  });

  // Season switch.
  const indicator = seasons?.querySelector('.section-map__indicator');
  const placeIndicator = () => {
    const btn = seasonBtns.find((b) => b.classList.contains('is-active'));
    if (!indicator || !btn) {
      return;
    }
    seasons.style.setProperty('--indicator-x', `${btn.offsetLeft}px`);
    seasons.style.setProperty('--indicator-y', `${btn.offsetTop}px`);
    seasons.style.setProperty('--indicator-w', `${btn.offsetWidth}px`);
    seasons.style.setProperty('--indicator-h', `${btn.offsetHeight}px`);
  };

  if (indicator) {
    placeIndicator();
    seasons.classList.add('is-ready');
    new ResizeObserver(placeIndicator).observe(seasons);
    // Transitions only after the first paint, so the pill doesn't slide in on load.
    requestAnimationFrame(() => requestAnimationFrame(() => seasons.classList.add('is-animated')));
  }

  seasonBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const season = btn.dataset.mapStripSeason;
      closePopup();
      layers.forEach((layer) => {
        const isActive = layer.dataset.mapStripLayer === season;
        layer.classList.toggle('is-active', isActive);
        layer.inert = !isActive;
      });
      seasonBtns.forEach((other) => {
        const isActive = other === btn;
        other.classList.toggle('is-active', isActive);
        other.setAttribute('aria-pressed', String(isActive));
      });
      placeIndicator();
    });
  });

  updatePins();

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          observer.disconnect();
          strip.classList.add('is-revealed');
        }
      },
      { threshold: 0.4 }
    );
    observer.observe(frame);
  }
}

domReady(() => {
  document.querySelectorAll('[data-map-strip]').forEach(initStrip);
});
