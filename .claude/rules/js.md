---
description: JavaScript house style — entry point, jQuery in an IIFE, Alpine, script enqueueing through Vite.
paths:
  - "assets/js/**/*.js"
  - "vite.config.mjs"
---

# JavaScript — convenções de escrita

### Entry point
O `assets/js/main.js` traz o GSAP sempre e as restantes libs comentadas — descomentar por projecto, em vez de importar tudo:

```javascript
import './gsap.js';
// import './swiper.js';
// import './fancybox.js';
import { debounce, isTouchDevice } from './utils/helpers.js';

document.addEventListener('DOMContentLoaded', () => {
  // Project-specific initialisation goes here
});
```

O `google-maps.js` não entra aqui: é enfileirado só nos templates que precisam dele (ver `inc/enqueue.php`).

> O Montela arrancou do Theme Starter Vite v2.0.2. A partir da v2.2.0 o starter
> divide o JS em `main.js` (só o crítico), `animations.js` (GSAP + Lenis) e
> `modules/*.js` (Swiper, Fancybox, Isotope, carregados só onde são usados),
> para o WP Rocket poder atrasar tudo menos o crítico. O Montela **ainda não tem
> essa divisão** — se for adoptada, esta secção passa a seguir a do starter.

### jQuery sempre em IIFE
Não há `jquery` no npm: usar o do WordPress (`window.jQuery`), com `'jquery'` nas dependências do enqueue.
```javascript
/* globals jQuery */
(function ($) {
  document.addEventListener('DOMContentLoaded', function () { /* ... */ });
})(window.jQuery);
```

### Alpine.js (quando usado)
`<style>[x-cloak]{display:none !important;}</style>` + `x-data`, `x-show`, `x-cloak`, `:aria-expanded`.

### Enqueue
Sempre via `inc/vite.php`, pelo **caminho fonte** do entry — nunca um caminho de `dist/` à mão (os ficheiros têm hash no nome) nem um número de versão (o hash já faz o cache-busting):
```php
montela_vite_enqueue_script( 'theme-foo', 'assets/js/foo.js', array( 'jquery' ) );
```
O loader já os põe no footer, como ES modules (`type="module"`).

A dependência `array( 'jquery' )` do `theme-scripts` é herdada do starter, mas **não é obrigatória**: GSAP, Lenis e Swiper não precisam de jQuery. Se o `main.js` não a usar, tirar — são ~30 KB a mais em cada página.

### Comentários
Em **inglês (en-EN)**, como todo o código.
