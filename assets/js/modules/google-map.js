// =============================================================================
// Google map module — enqueued by template-parts/pages/arrival/location.php:
//   montela_enqueue_module( 'google-map' );
//
// A Google Maps JavaScript API map without the places of interest (hotels,
// cafés, shops, bus stops), which the plain embed iframe cannot hide, and
// with a Montela pin. Used when wp-config.php defines MONTELA_GOOGLE_MAPS_KEY
// and the page has coordinates; otherwise the template prints the iframe.
//
// - The API loads only when the map nears the viewport (it is the last
//   section of the page).
// - Wheel: Google's "cooperative" gestures — the page keeps scrolling and
//   Ctrl + wheel zooms (one finger scrolls, two pan on touch). Lenis skips
//   wheel events with Ctrl, so the two never fight.
// - The pin is a button: hover, focus or tap shows a popup with the name and
//   address (data-name, data-address — a JSON list of lines); Esc closes.
// - The pin is an OverlayView, not a Marker (deprecated) nor an
//   AdvancedMarkerElement (needs a cloud Map ID, which ignores `styles`).
// - A key that fails (wrong, not enabled, wrong referrer) or a script that
//   does not load falls back to the embed iframe (data-fallback).
// =============================================================================
import { domReady } from '../utils/helpers.js';

const ZOOM = 17;
const CALLBACK = '__montelaGoogleMapsReady';

// Hide every point of interest and the transit icons; streets, river and
// parks stay.
const STYLES = [
  { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
  { featureType: 'poi.business', stylers: [{ visibility: 'off' }] },
  { featureType: 'transit', elementType: 'labels.icon', stylers: [{ visibility: 'off' }] },
];

// Walnut drop with a cream dot (Montela tokens).
const PIN_SVG =
  '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="48" viewBox="0 0 36 48" aria-hidden="true" focusable="false">' +
  '<path d="M18 0C8.06 0 0 8.06 0 18c0 13.5 18 30 18 30s18-16.5 18-30C36 8.06 27.94 0 18 0Z" fill="#4E3C2A"/>' +
  '<circle cx="18" cy="18" r="7" fill="#F8F8F2"/></svg>';

const maps = [];
let apiPromise = null;

/**
 * Loads the Maps JavaScript API once.
 *
 * @param {string} key      API key.
 * @param {string} language Map language (two letters).
 * @returns {Promise<void>}
 */
function loadApi(key, language) {
  if (window.google?.maps?.importLibrary) {
    return Promise.resolve();
  }
  if (!apiPromise) {
    apiPromise = new Promise((resolve, reject) => {
      window[CALLBACK] = () => {
        delete window[CALLBACK];
        resolve();
      };
      const params = new URLSearchParams({
        key,
        v: 'weekly',
        loading: 'async',
        language,
        region: 'CH',
        callback: CALLBACK,
      });
      const script = document.createElement('script');
      script.src = `https://maps.googleapis.com/maps/api/js?${params}`;
      script.async = true;
      script.onerror = reject;
      document.head.append(script);
    });
  }
  return apiPromise;
}

/**
 * Replaces a map with Google's embed iframe.
 *
 * @param {HTMLElement} el Map element.
 */
function fallback(el) {
  const src = el.dataset.fallback;
  if (!src || el.dataset.fellBack) {
    return;
  }
  el.dataset.fellBack = '1';
  const iframe = document.createElement('iframe');
  iframe.className = 'section-location__iframe';
  iframe.src = src;
  iframe.title = el.getAttribute('aria-label') || '';
  iframe.loading = 'lazy';
  iframe.referrerPolicy = 'no-referrer-when-downgrade';
  iframe.allowFullscreen = true;
  el.replaceWith(iframe);
}

// Google calls this when the key is rejected.
window.gm_authFailure = () => maps.forEach(fallback);

/**
 * Builds the pin: a button with the address popup.
 *
 * @param {string}   name  Place name.
 * @param {string[]} lines Address lines.
 * @returns {HTMLElement}
 */
function createPin(name, lines) {
  const pin = document.createElement('div');
  pin.className = 'section-location__pin';

  if (!name && !lines.length) {
    pin.innerHTML = PIN_SVG;
    return pin;
  }

  const popupId = `section-location-popup-${Math.random().toString(36).slice(2, 8)}`;
  const button = document.createElement('button');
  button.type = 'button';
  button.className = 'section-location__pin-button';
  button.setAttribute('aria-expanded', 'false');
  button.setAttribute('aria-controls', popupId);
  button.setAttribute('aria-label', name || lines[0]);
  button.innerHTML = PIN_SVG;

  const popup = document.createElement('div');
  popup.className = 'section-location__popup';
  popup.id = popupId;
  if (name) {
    const strong = document.createElement('strong');
    strong.className = 'section-location__popup-name';
    strong.textContent = name;
    popup.append(strong);
  }
  lines.forEach((line) => {
    const span = document.createElement('span');
    span.textContent = line;
    popup.append(span);
  });

  let pinned = false;
  const set = (open) => {
    pin.classList.toggle('is-open', open);
    button.setAttribute('aria-expanded', String(open));
  };

  pin.addEventListener('mouseenter', () => set(true));
  pin.addEventListener('mouseleave', () => set(pinned));
  button.addEventListener('focus', () => set(true));
  button.addEventListener('blur', () => {
    pinned = false;
    set(false);
  });
  // Tap (touch has no hover) or click keeps it open until the next one.
  button.addEventListener('click', () => {
    pinned = !pinned;
    set(pinned);
  });
  pin.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      pinned = false;
      set(false);
    }
  });

  pin.append(popup, button);
  return pin;
}

/**
 * Draws one map.
 *
 * @param {HTMLElement} el Map element.
 */
async function initMap(el) {
  const position = { lat: Number(el.dataset.lat), lng: Number(el.dataset.lng) };

  try {
    await loadApi(el.dataset.key, el.dataset.language || 'de');
    const { Map, OverlayView } = await window.google.maps.importLibrary('maps');

    const map = new Map(el, {
      center: position,
      zoom: ZOOM,
      styles: STYLES,
      gestureHandling: 'cooperative',
      // Top right, under fullscreen: the footer's box may cover the bottom.
      cameraControlOptions: { position: window.google.maps.ControlPosition.RIGHT_TOP },
      zoomControlOptions: { position: window.google.maps.ControlPosition.RIGHT_TOP },
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: true,
      clickableIcons: false,
    });

    const name = el.dataset.name || '';
    let lines = [];
    try {
      lines = JSON.parse(el.dataset.address || '[]');
    } catch {
      lines = [];
    }

    class Pin extends OverlayView {
      onAdd() {
        this.el = createPin(name, lines);
        OverlayView.preventMapHitsAndGesturesFrom(this.el);
        this.getPanes().overlayMouseTarget.append(this.el);
      }

      draw() {
        const point = this.getProjection().fromLatLngToDivPixel(position);
        if (point) {
          this.el.style.left = `${point.x}px`;
          this.el.style.top = `${point.y}px`;
        }
      }

      onRemove() {
        this.el?.remove();
      }
    }

    new Pin().setMap(map);

    // The footer's box covers the bottom of the map (--map-covered, set in
    // _arrival.sass): centre the pin in what shows, once the projection
    // exists.
    const covered = parseFloat(getComputedStyle(el).getPropertyValue('--map-covered')) || 0;
    if (covered) {
      const listener = map.addListener('projection_changed', () => {
        const projection = map.getProjection();
        if (!projection) {
          return;
        }
        listener.remove();
        const point = projection.fromLatLngToPoint(position);
        point.y += covered / 2 / 2 ** map.getZoom();
        map.setCenter(projection.fromPointToLatLng(point));
      });
    }
  } catch {
    fallback(el);
  }
}

domReady(() => {
  document.querySelectorAll('[data-google-map]').forEach((el) => {
    maps.push(el);
    if (!('IntersectionObserver' in window)) {
      initMap(el);
      return;
    }
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries.some((entry) => entry.isIntersecting)) {
          observer.disconnect();
          initMap(el);
        }
      },
      { rootMargin: '400px 0px' }
    );
    observer.observe(el);
  });
});
