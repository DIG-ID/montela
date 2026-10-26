// =============================================================================
// Fancybox module (lightbox) — enqueue only where it is used:
//   montela_enqueue_module( 'fancybox' );
//
// Markup: links with [data-fancybox] (same value = same gallery).
// =============================================================================
import { Fancybox } from '@fancyapps/ui';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import { domReady } from '../utils/helpers.js';

domReady(() => {
  Fancybox.bind('[data-fancybox]', {});
});
