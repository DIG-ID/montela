// =============================================================================
// Swiper module — enqueue only where a slider is rendered:
//   montela_enqueue_module( 'swiper' );
//
// Markup: any .swiper element with a [data-swiper] attribute. Per-slider
// options can be passed as JSON, e.g. data-swiper='{"slidesPerView": 3}'.
//
// Controls are looked up inside the closest [data-swiper-root] (or the
// slider itself), so they can sit outside the .swiper element:
//   [data-swiper-prev] / [data-swiper-next] — custom arrow buttons
//   .swiper-button-prev / .swiper-button-next — Swiper's default arrows
//   .swiper-pagination
// Only the Swiper modules imported below are bundled — add more as needed.
// EffectFade: pass {"effect": "fade", "fadeEffect": {"crossFade": true}}.
// =============================================================================
import Swiper from 'swiper';
import { A11y, EffectFade, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/a11y';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import { domReady } from '../utils/helpers.js';

const defaults = {
  modules: [A11y, EffectFade, Navigation, Pagination],
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

    const root = el.closest('[data-swiper-root]') || el;
    const navigation = {
      nextEl: root.querySelector('[data-swiper-next], .swiper-button-next'),
      prevEl: root.querySelector('[data-swiper-prev], .swiper-button-prev'),
    };
    const pagination = root.querySelector('.swiper-pagination');

    new Swiper(el, {
      ...defaults,
      ...(navigation.nextEl && navigation.prevEl ? { navigation } : {}),
      ...(pagination ? { pagination: { el: pagination, clickable: true } } : {}),
      ...options,
    });
  });
});
