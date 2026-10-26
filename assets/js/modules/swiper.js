// =============================================================================
// Swiper module — enqueue only where a slider is rendered:
//   montela_enqueue_module( 'swiper' );
//
// Markup: any .swiper element with a [data-swiper] attribute. Per-slider
// options can be passed as JSON, e.g. data-swiper='{"slidesPerView": 3}'.
// Only the Swiper modules imported below are bundled — add more as needed.
// =============================================================================
import Swiper from 'swiper';
import { A11y, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/a11y';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { domReady } from '../utils/helpers.js';

const defaults = {
  modules: [A11y, Navigation, Pagination],
  slidesPerView: 1,
  spaceBetween: 16,
};

domReady(() => {
  document.querySelectorAll('[data-swiper]').forEach((el) => {
    let options = {};

    try {
      options = JSON.parse(el.dataset.swiper || '{}');
    } catch (error) {
      console.warn('[swiper] Invalid data-swiper JSON', el, error);
    }

    const navigation = {
      nextEl: el.querySelector('.swiper-button-next'),
      prevEl: el.querySelector('.swiper-button-prev'),
    };
    const pagination = el.querySelector('.swiper-pagination');

    new Swiper(el, {
      ...defaults,
      ...(navigation.nextEl && navigation.prevEl ? { navigation } : {}),
      ...(pagination ? { pagination: { el: pagination, clickable: true } } : {}),
      ...options,
    });
  });
});
