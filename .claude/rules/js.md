---
description: JavaScript house style — entry point, jQuery in an IIFE, Alpine, script enqueueing through Vite.
paths:
  - "assets/js/**/*.js"
  - "vite.config.mjs"
---

# JavaScript — convenções de escrita

### Entry points
O JS está dividido para o WP Rocket poder atrasar tudo menos o crítico (desde a
v1.1.0 — ver a secção Build do `CLAUDE.md`):

- `assets/js/main.js` — **só o crítico** (menu, mega-menu, header). Sem
  bibliotecas nem jQuery. É o único que pode ficar nas excepções do Delay JS.
- `assets/js/animations.js` — GSAP + ScrollTrigger + Lenis (setup em `gsap.js`).
  Global, mas atrasável: o conteúdo tem de ser visível sem ele.
- `assets/js/modules/*.js` — Swiper, Fancybox, Isotope. Cada um importa o seu
  CSS e é carregado só onde é usado, com `montela_enqueue_module( 'swiper' )`.

Código que possa ser atrasado usa `domReady()` (`utils/helpers.js`) em vez de
`DOMContentLoaded` — com o Delay JS o evento pode já ter disparado:

```javascript
import { domReady } from './utils/helpers.js';

domReady(() => {
  // Project-specific initialisation goes here
});
```

O `google-maps.js` não passa pelo Vite: é enfileirado só nos templates que precisam dele (ver `inc/enqueue.php`).

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

O `theme-scripts` (`main.js`) já não depende de jQuery. Só acrescentar `array( 'jquery' )` a um entry que o use mesmo — são ~30 KB a mais nas páginas onde carrega.

### Comentários
Em **inglês (en-EN)**, como todo o código.
