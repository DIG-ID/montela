// =============================================================================
// Isotope module (filterable grid) — enqueue only where it is used:
//   montela_enqueue_module( 'isotope' );
//
// Markup:
//   <div data-isotope-filters="grid-id">
//     <button type="button" data-filter="*">All</button>
//     <button type="button" data-filter=".cat-a">A</button>
//   </div>
//   <div id="grid-id" data-isotope>
//     <div class="grid-item cat-a">…</div>
//   </div>
// =============================================================================
import Isotope from 'isotope-layout';
import imagesLoaded from 'imagesloaded';
import { domReady } from '../utils/helpers.js';

domReady(() => {
  document.querySelectorAll('[data-isotope]').forEach((grid) => {
    const iso = new Isotope(grid, {
      itemSelector: '.grid-item',
      layoutMode: 'fitRows',
    });

    // Re-layout once images have their final dimensions.
    imagesLoaded(grid).on('progress', () => iso.layout());

    const filters = document.querySelector(`[data-isotope-filters="${grid.id}"]`);

    if (!filters) {
      return;
    }

    filters.addEventListener('click', (event) => {
      const button = event.target.closest('[data-filter]');

      if (!button) {
        return;
      }

      iso.arrange({ filter: button.dataset.filter });

      filters.querySelectorAll('[data-filter]').forEach((item) => {
        item.setAttribute('aria-pressed', item === button ? 'true' : 'false');
      });
    });
  });
});
