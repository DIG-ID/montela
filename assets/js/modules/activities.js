// =============================================================================
// Activities module — enqueued by template-parts/modules/activities.php:
//   montela_enqueue_module( 'activities' );
//
// - Season switch: an ARIA tablist ([data-activities-tabs]). Click, or the
//   arrow / Home / End keys, select a season and show its cards panel; the
//   cream pill (.section-activities__indicator) slides to the selected tab.
// - Carousel: each [data-activities-carousel]'s [data-activities-track] is
//   a scroll-snap row (CSS does the sliding); [data-activities-prev] /
//   [-next] scroll it by one view and are disabled at either end. Homepage
//   module: one card on mobile, a grid from tablet up (arrows hidden).
//   Aktivitäten page: also pages of four cards on tablet and desktop.
// No library: a native scroll-snap track keeps working while hidden behind
// the switch, where a JS slider would measure 0px.
// =============================================================================
import { domReady } from '../utils/helpers.js';

function initTabs(tablist) {
  const tabs = [...tablist.querySelectorAll('[role="tab"]')];
  const indicator = tablist.querySelector('.section-activities__indicator');

  // Moves the cream pill onto the selected tab (CSS transitions it).
  const placeIndicator = () => {
    const tab = tabs.find((t) => t.getAttribute('aria-selected') === 'true');
    if (!indicator || !tab) {
      return;
    }
    tablist.style.setProperty('--indicator-x', `${tab.offsetLeft}px`);
    tablist.style.setProperty('--indicator-y', `${tab.offsetTop}px`);
    tablist.style.setProperty('--indicator-w', `${tab.offsetWidth}px`);
    tablist.style.setProperty('--indicator-h', `${tab.offsetHeight}px`);
  };

  if (indicator) {
    placeIndicator();
    tablist.classList.add('is-ready');
    // Re-measure when the tabs change size (breakpoints, web font swap).
    new ResizeObserver(placeIndicator).observe(tablist);
    // Transitions only after the first paint, so the pill doesn't slide in
    // from the corner on load.
    requestAnimationFrame(() => requestAnimationFrame(() => tablist.classList.add('is-animated')));
  }

  const select = (tab) => {
    tabs.forEach((other) => {
      const selected = other === tab;
      const panel = document.getElementById(other.getAttribute('aria-controls'));
      other.setAttribute('aria-selected', String(selected));
      other.tabIndex = selected ? 0 : -1;
      if (panel) {
        panel.hidden = !selected;
        if (selected) {
          const track = panel.querySelector('[data-activities-track]');
          if (track) {
            track.scrollLeft = 0;
          }
        }
      }
    });
    placeIndicator();
  };

  tablist.addEventListener('click', (event) => {
    const tab = event.target.closest('[role="tab"]');
    if (tab) {
      select(tab);
    }
  });

  tablist.addEventListener('keydown', (event) => {
    const index = tabs.indexOf(document.activeElement);
    if (index === -1) {
      return;
    }
    const keys = {
      ArrowLeft: index - 1,
      ArrowRight: index + 1,
      Home: 0,
      End: tabs.length - 1,
    };
    if (!(event.key in keys)) {
      return;
    }
    event.preventDefault();
    const next = tabs[(keys[event.key] + tabs.length) % tabs.length];
    select(next);
    next.focus();
  });
}

function initCarousel(panel) {
  const track = panel.querySelector('[data-activities-track]');
  const prev = panel.querySelector('[data-activities-prev]');
  const next = panel.querySelector('[data-activities-next]');
  if (!track || !prev || !next) {
    return;
  }

  // One view plus the gap: a single card on mobile, a page of four on the
  // Aktivitäten page (template-parts/pages/activities/season.php) — the
  // scrolled items are always as wide as the track.
  const step = () => track.clientWidth + (parseFloat(getComputedStyle(track).columnGap) || 0);

  const update = () => {
    const max = track.scrollWidth - track.clientWidth - 1;
    prev.disabled = track.scrollLeft <= 0;
    next.disabled = track.scrollLeft >= max;
  };

  prev.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
  next.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
  track.addEventListener('scroll', update, { passive: true });
  // Hidden panels measure 0 until shown: check again when they appear.
  new ResizeObserver(update).observe(track);
  update();
}

domReady(() => {
  document.querySelectorAll('[data-activities-tabs]').forEach(initTabs);
  document.querySelectorAll('[data-activities-carousel]').forEach(initCarousel);
});
