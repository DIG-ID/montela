// =============================================================================
// Map module — enqueued by template-parts/modules/map.php:
//   montela_enqueue_module( 'map' );
//
// Pan  = native scroll of the viewport (both axes; momentum and touch for
//        free) + drag with the mouse.
// Zoom = the canvas width in % — the pins sit in % of the artwork, so they
//        follow it. Buttons, wheel (towards the cursor) and pinch.
//
// - The wheel only zooms once the map has been clicked, so the page never
//   gets stuck scrolling on it; a hint says so. Leaving the map with the
//   mouse or clicking outside turns it off again; always on in fullscreen.
//   When the wheel zooms, stopPropagation keeps Lenis (listening on window)
//   from scrolling the page at the same time; at the zoom limits the event
//   goes through and the page scrolls.
// - Pins: hover (mouse) or click / tap / keyboard opens the popup, which
//   lives outside the scrolling viewport (or overflow would clip it) and is
//   placed next to the pin — beside it from tablet up, above or below it on
//   mobile. It follows the pin while panning and closes when the pin leaves
//   the view. Esc and a click outside close it.
// - Season switch: cross-fades the layers (CSS), keeping zoom and position;
//   the inactive layer is inert.
// - Fullscreen on the map element; the button stays hidden where the API is
//   missing (iPhone).
//
// Behaviour validated in an earlier prototype (kitchen-sink theme).
// =============================================================================
import { domReady } from '../utils/helpers.js';

const ZOOM_STEP = 1.5;
const ZOOM_RANGE = 4; // Max zoom, relative to the minimum (cover) zoom.
const WHEEL_STEP = 1.2;
// Mobile starts with the artwork twice as wide as the frame, centred on the
// first pin (the Montela houses) — the scale validated in the prototype.
const MOBILE_ZOOM = 2;
const POPUP_MARGIN = 8;

const mobileQuery = window.matchMedia('(max-width: 767px)');
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

const fullscreenEnabled = () => document.fullscreenEnabled || document.webkitFullscreenEnabled;
const fullscreenElement = () => document.fullscreenElement || document.webkitFullscreenElement;

/**
 * Wires up one map.
 *
 * @param {HTMLElement} map Element with [data-map].
 */
function initMap(map) {
  const viewport = map.querySelector('[data-map-viewport]');
  const canvas = map.querySelector('[data-map-canvas]');
  const popup = map.querySelector('[data-map-popup]');
  const popupBody = map.querySelector('[data-map-popup-body]');
  const popupClose = map.querySelector('[data-map-popup-close]');
  const zoomInBtn = map.querySelector('[data-map-zoom-in]');
  const zoomOutBtn = map.querySelector('[data-map-zoom-out]');
  const resetBtn = map.querySelector('[data-map-reset]');
  const fullscreenBtn = map.querySelector('[data-map-fullscreen]');
  const hint = map.querySelector('[data-map-hint]');
  const pins = [...map.querySelectorAll('[data-map-pin]')];
  const images = [...canvas.querySelectorAll('img')];
  const layers = [...map.querySelectorAll('[data-map-layer]')];
  const seasonBtns = [...map.querySelectorAll('[data-map-season]')];
  const activeLayer = () => layers.find((layer) => layer.classList.contains('is-active')) || layers[0];

  if (!viewport || !canvas || !images.length) {
    return;
  }

  // Artwork ratio from the width / height attributes (known before load).
  const imageWidth = parseFloat(images[0].getAttribute('width'));
  const imageHeight = parseFloat(images[0].getAttribute('height'));
  const imageRatio = imageWidth && imageHeight ? imageHeight / imageWidth : 1;

  let zoomMin = 1;
  let zoom = 1; // Rendered zoom.
  let targetZoom = 1; // Where the zoom animation is heading.
  const zoomOrigin = { x: 0, y: 0 };
  let zoomRaf = null;
  let sizesUpgraded = false;

  // ---------------------------------------------------------------------------
  // Zoom
  // ---------------------------------------------------------------------------

  const zoomMax = () => zoomMin * ZOOM_RANGE;
  const clampZoom = (value) => Math.min(zoomMax(), Math.max(zoomMin, value));

  // Minimum zoom: the artwork covers the whole frame, no empty space.
  function updateZoomMin() {
    zoomMin = Math.max(1, viewport.clientHeight / (viewport.clientWidth * imageRatio));
  }

  // Once zoomed in, ask the browser for a larger srcset candidate (the
  // original upload, see map.php).
  function upgradeImageSizes() {
    if (sizesUpgraded || zoom <= zoomMin + 0.001) {
      return;
    }
    sizesUpgraded = true;
    images.forEach((img) => {
      img.sizes = `${Math.ceil(viewport.clientWidth * zoomMax())}px`;
    });
  }

  /**
   * Renders a zoom level, keeping the content point under (originX,
   * originY) — viewport coordinates — in place.
   */
  function applyZoom(next, originX, originY) {
    const ratioX = (viewport.scrollLeft + originX) / canvas.offsetWidth;
    const ratioY = (viewport.scrollTop + originY) / canvas.offsetHeight;

    zoom = next;
    canvas.style.width = `${zoom * 100}%`;

    viewport.scrollLeft = ratioX * canvas.offsetWidth - originX;
    viewport.scrollTop = ratioY * canvas.offsetHeight - originY;
    upgradeImageSizes();
  }

  function updateZoomButtons() {
    zoomInBtn.disabled = targetZoom >= zoomMax() - 0.001;
    zoomOutBtn.disabled = targetZoom <= zoomMin + 0.001;
  }

  // Exponential ease towards the target zoom.
  function stepZoomAnimation() {
    const diff = targetZoom - zoom;
    if (Math.abs(diff) < 0.003) {
      applyZoom(targetZoom, zoomOrigin.x, zoomOrigin.y);
      zoomRaf = null;
      return;
    }
    applyZoom(zoom + diff * 0.16, zoomOrigin.x, zoomOrigin.y);
    zoomRaf = window.requestAnimationFrame(stepZoomAnimation);
  }

  /** Animated zoom (buttons, wheel). */
  function zoomTo(next, originX = viewport.clientWidth / 2, originY = viewport.clientHeight / 2) {
    targetZoom = clampZoom(next);
    zoomOrigin.x = originX;
    zoomOrigin.y = originY;
    updateZoomButtons();

    if (reducedMotion.matches) {
      applyZoom(targetZoom, originX, originY);
      return;
    }
    if (!zoomRaf) {
      zoomRaf = window.requestAnimationFrame(stepZoomAnimation);
    }
  }

  /** Instant zoom (pinch: the fingers drive the motion). */
  function setZoomImmediate(next, originX, originY) {
    if (zoomRaf) {
      window.cancelAnimationFrame(zoomRaf);
      zoomRaf = null;
    }
    targetZoom = clampZoom(next);
    applyZoom(targetZoom, originX, originY);
    updateZoomButtons();
  }

  /**
   * Initial view: from tablet up the minimum zoom, centred — the Figma
   * framing. Mobile starts zoomed in on the first pin.
   */
  function setInitialView() {
    if (zoomRaf) {
      window.cancelAnimationFrame(zoomRaf);
      zoomRaf = null;
    }
    updateZoomMin();

    let focusX = 50;
    let focusY = 50;
    let initialZoom = zoomMin;

    const firstPin = activeLayer()?.querySelector('[data-map-pin]');
    if (mobileQuery.matches && firstPin && fullscreenElement() !== map) {
      focusX = parseFloat(firstPin.style.getPropertyValue('--x'));
      focusY = parseFloat(firstPin.style.getPropertyValue('--y'));
      initialZoom = clampZoom(Math.max(zoomMin, MOBILE_ZOOM));
    }

    zoom = initialZoom;
    targetZoom = initialZoom;
    canvas.style.width = `${zoom * 100}%`;
    viewport.scrollLeft = (focusX / 100) * canvas.offsetWidth - viewport.clientWidth / 2;
    viewport.scrollTop = (focusY / 100) * canvas.offsetHeight - viewport.clientHeight / 2;
    updateZoomButtons();
    upgradeImageSizes();
  }

  zoomInBtn.addEventListener('click', () => zoomTo(targetZoom * ZOOM_STEP));
  zoomOutBtn.addEventListener('click', () => zoomTo(targetZoom / ZOOM_STEP));
  resetBtn.addEventListener('click', () => {
    closePopup();
    setInitialView();
  });

  // ---------------------------------------------------------------------------
  // Wheel: only zooms once the map has been clicked
  // ---------------------------------------------------------------------------

  let wheelActive = false;
  let hintTimer = null;

  function setWheelActive(active) {
    wheelActive = active;
    map.classList.toggle('is-wheel-active', active);
    if (active) {
      window.clearTimeout(hintTimer);
      hint?.classList.remove('is-visible');
    }
  }

  function showHint() {
    if (!hint) {
      return;
    }
    hint.classList.add('is-visible');
    window.clearTimeout(hintTimer);
    hintTimer = window.setTimeout(() => hint.classList.remove('is-visible'), 1500);
  }

  map.addEventListener('pointerdown', (event) => {
    if (event.pointerType === 'mouse') {
      setWheelActive(true);
    }
  });

  map.addEventListener('pointerleave', (event) => {
    if (event.pointerType === 'mouse' && fullscreenElement() !== map) {
      setWheelActive(false);
    }
  });

  viewport.addEventListener('wheel', (event) => {
    if (!wheelActive && fullscreenElement() !== map) {
      showHint();
      return;
    }
    const next = clampZoom(targetZoom * (event.deltaY < 0 ? WHEEL_STEP : 1 / WHEEL_STEP));
    if (Math.abs(next - targetZoom) < 0.001) {
      return; // At a limit: let the page (Lenis) scroll.
    }
    event.preventDefault();
    event.stopPropagation();
    const rect = viewport.getBoundingClientRect();
    zoomTo(next, event.clientX - rect.left, event.clientY - rect.top);
  }, { passive: false });

  // ---------------------------------------------------------------------------
  // Pinch zoom + mouse drag-to-pan (Pointer Events)
  // ---------------------------------------------------------------------------

  const activePointers = new Map();
  let pinchStartDistance = 0;
  let pinchStartZoom = 1;
  let dragStart = null;

  const pinchDistance = () => {
    const [a, b] = [...activePointers.values()];
    return Math.hypot(a.x - b.x, a.y - b.y);
  };

  viewport.addEventListener('pointerdown', (event) => {
    activePointers.set(event.pointerId, { x: event.clientX, y: event.clientY });

    if (event.pointerType === 'touch' && activePointers.size === 2) {
      pinchStartDistance = pinchDistance();
      pinchStartZoom = zoom;
    }

    // Mouse drag-to-pan (touch panning is the native scroll). Not on a pin,
    // or the click would never reach it.
    if (event.pointerType === 'mouse' && event.button === 0 && !event.target.closest('button, a')) {
      event.preventDefault();
      viewport.setPointerCapture(event.pointerId);
      dragStart = {
        x: event.clientX,
        y: event.clientY,
        scrollLeft: viewport.scrollLeft,
        scrollTop: viewport.scrollTop,
      };
      viewport.classList.add('is-dragging');
    }
  });

  viewport.addEventListener('pointermove', (event) => {
    if (!activePointers.has(event.pointerId)) {
      return;
    }
    activePointers.set(event.pointerId, { x: event.clientX, y: event.clientY });

    if (event.pointerType === 'touch' && activePointers.size === 2 && pinchStartDistance > 0) {
      const rect = viewport.getBoundingClientRect();
      const [a, b] = [...activePointers.values()];
      setZoomImmediate(
        pinchStartZoom * (pinchDistance() / pinchStartDistance),
        (a.x + b.x) / 2 - rect.left,
        (a.y + b.y) / 2 - rect.top,
      );
    }

    if (dragStart && event.pointerType === 'mouse') {
      viewport.scrollLeft = dragStart.scrollLeft - (event.clientX - dragStart.x);
      viewport.scrollTop = dragStart.scrollTop - (event.clientY - dragStart.y);
    }
  });

  const releasePointer = (event) => {
    activePointers.delete(event.pointerId);
    if (activePointers.size < 2) {
      pinchStartDistance = 0;
    }
    if (event.pointerType === 'mouse') {
      dragStart = null;
      viewport.classList.remove('is-dragging');
    }
  };

  ['pointerup', 'pointercancel', 'pointerleave'].forEach((type) => {
    viewport.addEventListener(type, releasePointer);
  });

  // ---------------------------------------------------------------------------
  // Popup
  // ---------------------------------------------------------------------------

  let activePin = null;
  let hoverCloseTimer = null;

  /**
   * Places the popup next to the active pin, inside the map. Tablet and
   * desktop: beside it (right, or left when there is no room), centred on
   * it vertically. Mobile: full width (CSS), below the pin or above it.
   * Closes it once the pin has been panned out of view.
   */
  function positionPopup() {
    if (!activePin) {
      return;
    }

    const mapRect = map.getBoundingClientRect();
    const viewRect = viewport.getBoundingClientRect();
    const pinRect = activePin.getBoundingClientRect();
    const pinX = pinRect.left + pinRect.width / 2;
    const pinY = pinRect.top + pinRect.height / 2;

    if (pinX < viewRect.left || pinX > viewRect.right || pinY < viewRect.top || pinY > viewRect.bottom) {
      closePopup();
      return;
    }

    const gap = pinRect.width / 2 + 12;
    const width = popup.offsetWidth;
    const height = popup.offsetHeight;
    const x = pinX - mapRect.left;
    const y = pinY - mapRect.top;
    const maxTop = mapRect.height - height - POPUP_MARGIN;
    let left;
    let top;

    if (mobileQuery.matches) {
      left = POPUP_MARGIN;
      top = y + gap + height <= mapRect.height - POPUP_MARGIN ? y + gap : y - gap - height;
    } else {
      left = x + gap;
      if (left + width > mapRect.width - POPUP_MARGIN) {
        left = x - gap - width;
      }
      top = y - height / 2;
    }

    left = Math.max(POPUP_MARGIN, left);
    top = Math.max(POPUP_MARGIN, Math.min(top, maxTop));
    popup.style.transform = `translate(${Math.round(left)}px, ${Math.round(top)}px)`;
  }

  function openPopup(pin) {
    const content = document.getElementById(pin.dataset.content);
    if (!content) {
      return;
    }

    if (activePin && activePin !== pin) {
      activePin.classList.remove('is-active');
      activePin.setAttribute('aria-expanded', 'false');
    }

    activePin = pin;
    pin.classList.add('is-active');
    pin.setAttribute('aria-expanded', 'true');

    popupBody.innerHTML = content.innerHTML;
    popup.hidden = false;
    positionPopup();
  }

  function closePopup() {
    window.clearTimeout(hoverCloseTimer);
    if (!activePin) {
      return;
    }
    activePin.classList.remove('is-active');
    activePin.setAttribute('aria-expanded', 'false');
    activePin = null;
    popup.hidden = true;
  }

  pins.forEach((pin, index) => {
    pin.style.setProperty('--pin-index', String(index));

    // Click / tap / keyboard toggles.
    pin.addEventListener('click', () => {
      if (activePin === pin) {
        closePopup();
      } else {
        openPopup(pin);
      }
    });

    // Hover opens, on mouse pointers only.
    pin.addEventListener('pointerenter', (event) => {
      if (event.pointerType !== 'mouse' || dragStart) {
        return;
      }
      window.clearTimeout(hoverCloseTimer);
      openPopup(pin);
    });

    pin.addEventListener('pointerleave', (event) => {
      if (event.pointerType === 'mouse') {
        hoverCloseTimer = window.setTimeout(closePopup, 300);
      }
    });
  });

  // Keep the popup open while the mouse is over it.
  popup.addEventListener('pointerenter', () => window.clearTimeout(hoverCloseTimer));
  popup.addEventListener('pointerleave', (event) => {
    if (event.pointerType === 'mouse') {
      hoverCloseTimer = window.setTimeout(closePopup, 300);
    }
  });

  popupClose.addEventListener('click', () => {
    const pinToRefocus = activePin;
    closePopup();
    pinToRefocus?.focus();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && activePin && !fullscreenElement()) {
      const pinToRefocus = activePin;
      closePopup();
      pinToRefocus.focus();
    }
  });

  // A click outside the map closes the popup and turns the wheel off.
  document.addEventListener('click', (event) => {
    if (map.contains(event.target)) {
      return;
    }
    closePopup();
    setWheelActive(false);
  });

  // Follow the pin while panning / zooming.
  let positionRaf = null;
  viewport.addEventListener('scroll', () => {
    if (!activePin || positionRaf) {
      return;
    }
    positionRaf = window.requestAnimationFrame(() => {
      positionRaf = null;
      positionPopup();
    });
  }, { passive: true });

  // ---------------------------------------------------------------------------
  // Season switch
  // ---------------------------------------------------------------------------

  // The cream pill slides to the active season, as in the Activities
  // switch (activities.js): measured into --indicator-*, transitioned in CSS.
  const seasons = map.querySelector('[data-map-seasons]');
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
    // Re-measure when the buttons change size (breakpoints, web font swap).
    new ResizeObserver(placeIndicator).observe(seasons);
    // Transitions only after the first paint, so the pill doesn't slide in
    // from the corner on load.
    requestAnimationFrame(() => requestAnimationFrame(() => seasons.classList.add('is-animated')));
  }

  seasonBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const season = btn.dataset.mapSeason;
      closePopup();

      layers.forEach((layer) => {
        const isActive = layer.dataset.mapLayer === season;
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

  // ---------------------------------------------------------------------------
  // Fullscreen
  // ---------------------------------------------------------------------------

  if (fullscreenBtn && fullscreenEnabled()) {
    fullscreenBtn.hidden = false;

    fullscreenBtn.addEventListener('click', () => {
      if (fullscreenElement() === map) {
        (document.exitFullscreen || document.webkitExitFullscreen).call(document);
      } else {
        (map.requestFullscreen || map.webkitRequestFullscreen).call(map);
      }
    });

    const onFullscreenChange = () => {
      fullscreenBtn.setAttribute('aria-pressed', String(fullscreenElement() === map));
      closePopup();
      // Wait for the new frame size before recomputing the view.
      window.requestAnimationFrame(setInitialView);
    };

    document.addEventListener('fullscreenchange', onFullscreenChange);
    document.addEventListener('webkitfullscreenchange', onFullscreenChange);
  }

  // ---------------------------------------------------------------------------
  // Resize + entry animation
  // ---------------------------------------------------------------------------

  let lastWidth = viewport.clientWidth;
  new ResizeObserver(() => {
    // Height-only changes (mobile browser bars) keep the user's view.
    if (viewport.clientWidth === lastWidth) {
      return;
    }
    lastWidth = viewport.clientWidth;
    closePopup();
    setInitialView();
  }).observe(viewport);

  // The pins' halos pulse once, staggered, when the map scrolls into view.
  if (reducedMotion.matches || !('IntersectionObserver' in window)) {
    map.classList.add('is-revealed');
  } else {
    const observer = new IntersectionObserver((entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        map.classList.add('is-revealed');
        observer.disconnect();
      }
    }, { threshold: 0, rootMargin: '0px 0px -15% 0px' });
    observer.observe(map);
  }

  setInitialView();
}

// Delayed by WP Rocket, this may run after DOMContentLoaded has fired.
domReady(() => {
  document.querySelectorAll('[data-map]').forEach(initMap);
});
