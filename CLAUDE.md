# CLAUDE.md — Montela

## Language
- Responder sempre em **português de Portugal** no chat.
- Comentários no código devem ser em **inglês (en-EN)**.

---

## Stack

- **WordPress** custom theme (`text-domain: montela`)
- **WooCommerce**
- **Tailwind CSS v3** + **Vite** (via PostCSS)
- **WordPress 6.9+, PHP 8.2+**
- **SASS** (estrutura modular em `assets/sass/`)
- **JS** em `assets/js/`
- **Dist** compilado em `dist/`

### Dependências JS notáveis
- GSAP, Lenis (smooth scroll), Swiper, Isotope, Fancyapps UI, imagesloaded

---

## Estrutura de Ficheiros

```
montela/
├── assets/
│   ├── js/
│   └── sass/
│       ├── _vars.sass
│       ├── _base-styles.sass
│       ├── _components/
│       ├── _modules/
│       ├── _pages/
│       ├── _posts/
│       └── main.sass
├── dist/                   # compiled assets (não editar directamente)
├── inc/                    # PHP includes do tema (vite.php = loader de assets)
├── page-templates/         # Page templates WordPress
├── acf-json/               # field groups do ACF (Local JSON)
├── template-parts/
│   ├── components/       # button.php, button-icon.php, icon-list.php, gallery-slider.php, card-activity.php, card-preview.php…
│   ├── modules/          # secções reutilizadas (hero, intro…)
│   ├── pages/            # secções de uma só página
│   ├── footer-main.php
│   ├── header-main.php
├── functions.php
├── vite.config.mjs
├── tailwind.config.js
├── phpcs.xml.dist
└── style.css
```

---

## Comandos de Desenvolvimento

```bash
# Servidor de desenvolvimento Vite (HMR; NÃO escreve dist/)
npm run dev

# Watch: build de desenvolvimento para dist/ a cada alteração (sem HMR/reload)
npm run watch

# Build de produção (escreve dist/ — correr antes de commit/deploy)
npm run prod

# Lint PHP (PHPCS)
npm run php:lint

# Auto-fix PHP (PHPCBF)
npm run php:fix
```

---

## Build (Vite)

- **Config**: `vite.config.mjs`. Os entry points estão na lista `entries` (JS e Sass); um bundle novo (ex.: CSS de uma página específica) = acrescentar aí.
- **Enqueue**: sempre via `inc/vite.php`, referindo o entry pelo **caminho fonte** — nunca caminhos de `dist/` hardcoded (os ficheiros têm hash no nome):
  ```php
  montela_vite_enqueue_style( 'theme-styles', 'assets/sass/main.sass' );
  montela_vite_enqueue_script( 'theme-scripts', 'assets/js/main.js', array( 'jquery' ) );
  ```
  Em produção lê `dist/.vite/manifest.json`; em dev (`npm run dev`, ambiente `local`/`development`) carrega do servidor Vite com HMR. Os scripts são ES modules (`type="module"`).
- **Dev server**: HTTPS com os certificados do Local, no hostname `DEV_HOST` (fallback em `vite.config.mjs`, override por máquina em `.env.local`, gitignored). Alterações a `.php` fazem full reload. O servidor escreve `dist/hot` enquanto corre; se ficar órfão, o PHP ignora-o (testa a porta).
- **Divisão do JS (WP Rocket / PageSpeed)**: o `main.js` é o bundle **crítico** — pequeno, sem bibliotecas nem jQuery, só o que o site precisa antes de qualquer interacção (menu, mega-menu, header). É o **único** JS do tema que pode ficar nas excepções do *Delay JavaScript execution* do WP Rocket. Tudo o resto é entry separada:
  - `assets/js/animations.js` — GSAP + ScrollTrigger + Lenis, global mas não crítico (pode ser atrasado). O conteúdo tem de ser visível sem este ficheiro: estados iniciais via GSAP (`gsap.from()`), nunca `opacity: 0` no CSS; o hero/LCP não depende de animação de entrada.
  - `assets/js/modules/*.js` — Swiper, Fancybox, Isotope (cada um importa o seu CSS). Enqueue **só onde são usados** com `montela_enqueue_module( 'swiper' )`; para módulos acima da dobra, chamar no `wp_enqueue_scripts` (CSS no `<head>`).
  - Módulo novo = ficheiro em `assets/js/modules/`, entry em `vite.config.mjs` e nome no mapa de `montela_enqueue_module()`. Nunca importar bibliotecas no `main.js` nem o CSS delas no `main.sass`.
  - Código que possa ser atrasado usa `domReady()` (`utils/helpers.js`) em vez de `DOMContentLoaded` — com Delay JS o evento já pode ter disparado.
- **jQuery**: usar o jQuery do WordPress (`window.jQuery`, dependência `'jquery'` no enqueue) — não há `jquery` no npm nem autoload.
- **`url()` no Sass**: relativo ao ficheiro `.sass` fonte (ex.: `assets/fonts/...`); o Vite copia para `dist/assets/` com hash.
- **Sass**: os partials ainda usam `@import` (aviso de deprecação silenciado em `vite.config.mjs`). Código novo pode usar módulos `sass:*` (`@use "sass:color"`) em vez de funções globais (`darken()`, etc.).
- **Antes de commit/deploy**: `npm run prod` — o `npm run dev` não escreve `dist/` e o `npm run watch` deixa lá uma build de desenvolvimento (não minificada, com source maps).
- **Deploy em produção**: usar o skill `/deploy` (`.claude/skills/deploy/`). Verificações específicas deste build:
  - o build apaga os ficheiros com hash antigos — incluir também essas remoções no commit (`git add -A dist`);
  - confirmar que `dist/.vite/manifest.json` foi regenerado e que não existe `dist/hot` (marcador do servidor de dev, gitignored);
  - verificação pós-deploy: os assets carregam com nomes com hash novos (cache-busting via `inc/vite.php` + manifest).

---

## Coding Standards

Todo o código PHP **deve** seguir:
- [PHP Coding Standards (PHPCS)](https://github.com/PHPCSStandards/PHP_CodeSniffer/)
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

Regras activas (ver `phpcs.xml.dist`): `WordPress-Core`, `WordPress-Docs`, `WordPress-Extra`.

Antes de entregar código PHP, verificar com `npm run php:lint`. Usar `npm run php:fix` para correcções automáticas.

---

## Custom Post Types

Registados pelo **ACF Pro** (Local JSON, `acf-json/post_type_*.json`) — não há
`register_post_type()` no tema. Editar no admin (ACF → Post Types) grava de
volta no JSON; depois de um deploy, fazer Sync.

| Post type | JSON | URL | Suporta | Notas |
|-----------|------|-----|---------|-------|
| `activity` — Activities | `post_type_activity.json` | `/erkunden/aktivitaeten/{slug}/` (sem arquivo) — por baixo da página Aktivitäten. O slug repete o caminho da página: se a Erkunden ou a Aktivitäten mudarem de slug, mudar também aqui. Noutras línguas, traduzir o slug no WPML (Post Types Translation). | título, imagem destacada ("Card image"), excerto, revisões | Sem editor de blocos: o conteúdo do single é feito de módulos ACF. Lido por uma secção da homepage. |
| `offer` — Angebote & Packages | `post_type_offer.json` | `/angebote-packages/{slug}/` (sem arquivo) — por baixo da página Angebote & Packages, como as activities | título, imagem destacada ("Card image"), excerto, revisões | Single: `single-offer.php` (hero + `template-parts/offer/intro.php`). O slug repete o caminho da página; noutras línguas, traduzir no WPML. Os cards da página de listagem são feitos a partir dos posts (por fazer). |
| `tagesausflug` — Tagesausflüge (Day Trips) | `post_type_tagesausflug.json` | `/erkunden/unsere-region/{slug}/` (sem arquivo) — por baixo da página Unsere Region, como as activities. Não criar páginas-filhas da Unsere Region: o URL delas seria apanhado pelo CPT (404). Se a Erkunden ou a Unsere Region mudarem de slug, mudar também aqui; noutras línguas, traduzir no WPML. | título, imagem destacada ("Kartenbild"), excerto, revisões | Labels em alemão. Single: `single-tagesausflug.php`. Os cards de preview estão na Unsere Region (secção "Ausflüge ab Saas-Grund"). |
| `testimonial` — Testimonials | `post_type_testimonial.json` | nenhum (não público: sem single nem arquivo) | título, revisões | Título = linha do autor ("Martin Ahrens 5* bei Google"); a quote é o campo `testimonial_quote` (grupo `group_cpt_testimonial`). Lido pelo módulo Testimonials. |

### Taxonomias

| Taxonomia | JSON | Em | Notas |
|-----------|------|----|-------|
| `season` — Seasons | `taxonomy_season.json` | `activity` | Hierárquica (checkboxes no editor), sem URLs públicos, coluna na lista do admin. Termos **`sommer`** e **`winter`** — os slugs são usados no código (filtro Sommer/Winter); criar em cada ambiente, não renomear os slugs. Uma actividade pode ter as duas. |

### Templates
- **Single Tagesausflug** (`single-tagesausflug.php`, CPT `tagesausflug`) — hero + intro (módulos), "Über das Reiseziel" (`template-parts/day-trip/destination.php`: foto, texto, destaques com ícone, galeria), Cards with icon (módulo, com o texto opcional), Explore (módulo). Campos no grupo `group_cpt_day_trip`. Figma: desktop
  [5426:15059](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-15059&m=dev),
  tablet [5426:17664](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17664&m=dev),
  mobile [5426:16736](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16736&m=dev).
- **Single Offer** (`single-offer.php`, CPT `offer`) — hero (módulo) + "Zurück zur Übersicht" (vai para a página com o template das ofertas — `montela_get_page_by_template()`, `inc/helpers.php`) + intro (título, caixa de preço, texto) + "Im Package enthalten" (lista com ícones e foto só desktop) com a galeria + condições e validade + Text with photos (módulo). Secções em `template-parts/offer/`. Campos no grupo `group_cpt_offer`. Figma: desktop
  [5426:14430](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-14430&m=dev),
  tablet [5426:17370](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17370&m=dev),
  mobile [5426:16423](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16423&m=dev).
- **Der Montela Weg / The Montela Way** (`page-templates/page-montela-way.php`, "Montela Way Template"; `/der-montela-weg/`) — hero (Full screen), intro, valores ("Freiheit trifft…", `pages/montela-way/values.php`: linhas de foto + textos, a foto troca de lado a cada linha) história ("Unsere Geschichte", `pages/montela-way/history.php`) e timeline (`pages/montela-way/timeline.php`: slider Swiper em fade, a toda a largura, um ano por slide — painel sage + foto; 2020–2026 ainda em Lorem Ipsum), futuro ("Unser Blick in die Zukunft", `pages/montela-way/future.php`: texto e lista com bolinhas ao lado de uma foto) e fecho ("Erleben Sie Montela selbst", `pages/montela-way/closing.php`). Campos no grupo `group_page_montela_way`. Figma: desktop
  [5426:15417](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-15417&m=dev),
  tablet [5426:17754](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17754&m=dev),
  mobile [5426:16837](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16837&m=dev).
- **Aufenthalt / Stay** (`page-templates/page-stay.php`, "Stay Template"; página principal, `/aufenthalt/`) — hero (Full screen), intro, "Viel Raum für gemeinsame Ferien" (`template-parts/pages/stay/space.php`), Services comparison (módulo; mesmo conteúdo da Angebote), "Alles da, was Ferien einfacher macht" (Box text `band` com botão e lista) e Testimonials (módulo; vazio = todos). O hero desktop do Figma tem as linhas 1 e 2 a 150px (cópia do hero da homepage); fica a 123px, como as outras páginas interiores. Campos no grupo `group_page_stay`. Figma: desktop
  [5426:8346](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-8346&m=dev),
  tablet [5426:9261](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-9261&m=dev),
  mobile [5426:8673](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-8673&m=dev).
  Secções por fazer, por ordem (nodes desktop / tablet / mobile):
  - Mapa "Alles nah, alles einfach" — mapa Sommer/Winter, zoom, popups com pontos numerados e uma fila de ícones: 5838:17168 / 5838:16851 / 5838:16921.
- **Angebote & Packages / Offers** (`page-templates/page-offers.php`, "Offers Template"; `/angebote-packages/`) — hero (Full screen), intro, cards das ofertas (uma linha por oferta activa — "Valid until" hoje ou depois, ou sem data — por ordem de "Valid from"; lado alterna em desktop), comparação de serviços. Campos no grupo `group_page_offers`. Figma: desktop
  [5426:14165](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-14165&m=dev),
  tablet [5426:18068](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-18068&m=dev),
  mobile [5426:17172](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17172&m=dev).
- **Aktivitäten / Activities** (`page-templates/page-activities.php`, "Activities Template"; subpágina da Erkunden, `/erkunden/aktivitaeten/` — os singles do CPT `activity` ficam por baixo dela) — hero, intro, secções Sommer e Winter, "Saas-Grund als Ausgangspunkt" (Text with photos, 1 foto). A escolha das activities por estação é a função `montela_get_season_activities()` (`inc/helpers.php`), partilhada com o módulo Activities. Campos no grupo `group_page_activities`. Figma: desktop
  [5426:14590](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-14590&m=dev),
  tablet [5426:17294](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17294&m=dev),
  mobile [5426:16343](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16343&m=dev).
- **Unsere Region / Our Region** (`page-templates/page-region.php`, "Our Region Template"; subpágina da Erkunden) — hero, intro, Saas-Grund, Highlights, SaastalCard (faixa verde), activities. "Ausflüge ab Saas-Grund" (cards dos Tagesausflüge) entre os Highlights e a faixa verde. Por fazer: o mapa (design por aprovar), entre os Highlights e os cards. Campos no grupo `group_page_region`. Figma: desktop
  [5426:14763](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-14763&m=dev),
  tablet [5426:17416](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17416&m=dev),
  mobile [5426:16474](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16474&m=dev).
- **Erkunden / Explore** (`page-templates/page-explore.php`, "Explore Template") — hero (Full screen), intro, region, activities, box text. Campos no grupo `group_page_explore` (um tab por módulo, clones Seamless). Figma: desktop
  [5426:16171](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16171&m=dev),
  tablet [5426:17252](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17252&m=dev),
  mobile [5426:16296](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16296&m=dev).
- **Single Activity** (`single-activity.php`) — hero, intro, dois blocos Text with checklist (o mesmo módulo duas vezes, clones com prefixo `activity_block_1_` / `activity_block_2_`), Cards with icon, Activities (só a estação da actividade — `montela_get_activity_seasons()`, `inc/helpers.php` — e sem ela própria nos cards). Entre o Cards with icon e o Activities, os info cards ("Activities all_flip over", `template-parts/activity/info.php` — secção própria do post, não módulo: 2 a 4 cards com conteúdo escrito no post, o mesmo `components/card-activity.php` passado por argumentos; botão só com link, que pode ser um PDF). Campos no grupo `group_cpt_activity` (um tab por módulo, clones Seamless). Figma: desktop
  [5426:15217](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-15217&m=dev),
  tablet [5426:17534](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-17534&m=dev),
  mobile [5426:16593](https://www.figma.com/design/f3bMaIW2Y0RPvteAzqmGKa/Design--c%C3%B3pia-?node-id=5426-16593&m=dev).

---

## ACF Field Groups

Os field groups vivem em **`acf-json/`** (Local JSON do ACF): versionados no git,
chegam a produção com o deploy e o ACF grava lá as alterações feitas no admin.
Depois de editar um grupo noutro ambiente, usar **Sync** no admin.

- **`description` de cada field group: no máximo 50 caracteres.**
- **WPML (ACFML):** todos os grupos em modo **Expert** —
  `"acfml_field_group_mode": "advanced"` — com a preferência em cada campo
  (`wpml_cf_preferences`): imagens e vídeo **copy** (`1`), o resto **translate**
  (`2`). Tabs não levam preferência; clones levam `1` (são contentores — os
  campos de um clone Seamless herdam a preferência definida no grupo do módulo).
- Ao editar um JSON à mão, actualizar o `modified` (timestamp Unix), senão o ACF
  não oferece o Sync em ambientes onde o grupo já está na base de dados.

### Módulos — grupos inactivos, para clonar
Cada módulo (`template-parts/modules/`) tem o seu grupo, **inactivo** (não aparece
sozinho em nenhum ecrã). A página clona o grupo inteiro (Display: Seamless).
Se o mesmo módulo entrar duas vezes na mesma página, activar "Prefix field names"
no clone e passar o prefixo ao módulo:
`get_template_part( 'template-parts/modules/hero', null, array( 'prefix' => 'hero_' ) );`

| Grupo | Módulo | Campos |
|-------|--------|--------|
| `group_module_hero` — Module — Hero | `modules/hero.php` | `section_hero_image` (image, ID), `section_hero_video` (file mp4, URL, opcional), `section_hero_fullscreen` (true/false — ecrã inteiro em desktop em vez de 900px), `section_hero_title_line_{1,2,3}` (text) + `section_hero_title_line_{1,2,3}_align` (button group: left / indent 128px / custom / center / right — posição em desktop) + `section_hero_title_line_{1,2,3}_indent` (number, px, só com custom). A última linha preenchida é a itálica. Padrão = 900px em desktop; a homepage passa `array( 'variant' => 'home' )` (sempre ecrã inteiro, linha 1 maior). |
| `group_module_intro` — Module — Intro | `modules/intro.php` | `section_intro_title` (textarea, `new_lines: br`), `section_intro_text` (textarea) |
| `group_module_explore` — Module — Explore | `modules/explore.php` | `section_explore_title` (text), `section_explore_image` (image), `section_explore_text` (textarea), `section_explore_button` (link), `section_explore_slides` (gallery) |
| `group_module_image_band` — Module — Image band | `modules/image-band.php` | `section_image_band_image` (image) |
| `group_module_activities` — Module — Activities | `modules/activities.php` | `section_activities_title` (text), `section_activities_text` (textarea), `section_activities_button` (link), `section_activities_summer` / `section_activities_winter` (relationship → `activity`, filtrado por Season `sommer` / `winter`, máx. 4, IDs; vazio = as 4 primeiras da estação). Args opcionais: `seasons` (só essas estações; uma só = sem switch) e `exclude` (uma activity a tirar dos cards). Cards: `components/card-activity.php` (imagem destacada, título, excerpt, permalink). Estação aberta ao carregar: inverno de Dezembro a Abril. |
| `group_module_testimonials` — Module — Testimonials | `modules/testimonials.php` | `section_testimonials_title` (text), `section_testimonials_items` (relationship → `testimonial`, IDs, pela ordem escolhida; vazio = todos). Slider Swiper em fade, com loop e sem autoplay. |
| `group_module_box_text` — Module — Box text | `modules/box-text.php` | `section_box_text_style` (button group: `box` — caixa com moldura walnut, título e texto lado a lado em desktop / `band` — faixa sage a toda a largura, texto creme, texto numa coluna à direita em desktop), `section_box_text_eyebrow` (text, título pequeno em itálico), `section_box_text_title` (text), `section_box_text_text` (textarea, `wpautop` — linha vazia = parágrafo novo); só no `band`: `section_box_text_button` (link, botão `outline` por baixo do título) e `section_box_text_items` (repeater `icon` (SVG creme) / `title` / `text` — lista com ícone, duas colunas em desktop). |
| `group_module_cards_icon` — Module — Cards with icon | `modules/cards-icon.php` | `section_cards_icon_title` (text), `section_cards_icon_text` (textarea, opcional), `section_cards_icon_items` (repeater: `icon` image, `title`, `subtitle`, `text`). Slider Swiper: 4 cards em desktop, 2 em tablet, 1 em mobile; setas escondem-se quando cabem todos. |
| `group_module_text_photos` — Module — Text with photos | `modules/text-photos.php` | `section_find_apartment_title` (textarea, `br`), `_text`, `_button` (link), `_image_1`, `_image_2` (opcional). Título, texto e botão ao lado de 1 ou 2 fotos — o layout de tablet/mobile segue o número de fotos. Os nomes e as chaves (`field_page_home_find_apartment_*`) vêm de quando era secção só da homepage: **não mudar**, o conteúdo existente depende deles. |
| `group_module_text_list` — Module — Text with checklist | `modules/text-list.php` | `section_text_list_layout` (button group: `left` / `right` — lado da foto em tablet/desktop), `_title` (textarea, `br`), `_text` (textarea, `wpautop`), `_items` (textarea, uma linha por item, cada uma com visto), `_image`. Título por cima; foto de um lado, texto + checklist do outro; em mobile a foto fica entre o texto e a lista. |
| `group_module_services` — Module — Services comparison | `modules/services.php` | `section_services_title` (textarea, `br`), `_text`, `_button` (link), `_plans` (repeater: `image`, `title`, `subtitle`, `features` — textarea, uma linha por item, cada uma com visto). Duas colunas em desktop, slider abaixo. |

### Páginas
Um grupo por page template, com um **tab por secção**, na ordem da página; cada
tab clona o grupo do módulo (Seamless, sem prefixo).

| Grupo | Template | Tabs → clone |
|-------|----------|--------------|
| `group_page_home` — Page — Home | `page-templates/page-home.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Apartments → `group_module_text_photos` · Direct booking (campos próprios, `pages/home/direct-book.php`) · Gallery (campos próprios, `pages/home/gallery.php`) · Explore → `group_module_explore` · Image band → `group_module_image_band` · Activities → `group_module_activities` · Testimonials → `group_module_testimonials` |
| `group_page_explore` — Page — Explore | `page-templates/page-explore.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Region (campos próprios, `pages/explore/region.php`: `section_region_title` (textarea, `br`), `_text`, `_button`, `_image_large` (só desktop), `_image_1`, `_image_2`) · Activities → `group_module_activities` · Box text → `group_module_box_text` |
| `group_page_region` — Page — Our Region | `page-templates/page-region.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Saas-Grund (campos próprios, `pages/region/place.php`: `section_place_title`, `_text`, `_image_1`, `_image_2`, `_items` repeater `icon` + `title`) · Highlights → `group_module_cards_icon` · Day trips (campos próprios, `pages/region/trips.php`: `section_trips_title`, `_text`, `_items` — relationship → `tagesausflug`, pela ordem escolhida; vazio = todos, os mais antigos primeiro. Card: `components/card-preview.php` — imagem destacada, título, excerto; em desktop fechado, abre no hover; em mobile/tablet foto inteira em cima e painel sempre aberto) · SaastalCard → `group_module_box_text` (style `band`) · Activities → `group_module_activities` |
| `group_page_activities` — Page — Activities | `page-templates/page-activities.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Summer / Winter (campos próprios, `pages/activities/season.php` chamado duas vezes com `array( 'season' => 'summer' \| 'winter' )`: `section_{summer,winter}_eyebrow`, `_title`, `_text`, `_activities` — relationship sem máximo, filtrado pela estação; vazio = todas da estação; mais de 4 = slider em páginas de 4) · Saas-Grund → `group_module_text_photos` |
| `group_page_stay` — Page — Stay | `page-templates/page-stay.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Space (campos próprios, `pages/stay/space.php`: `section_space_title` (textarea, `br`), `_text`, `_button`, `_image_1`, `_image_2`, `_items` repeater `icon` / `title` / `text`) · Services → `group_module_services` · Extras → `group_module_box_text` (style `band`, com botão e lista) · Testimonials → `group_module_testimonials` (falta o mapa, entre o Extras e os Testimonials) |
| `group_page_offers` — Page — Offers | `page-templates/page-offers.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · (cards das ofertas — sem campos: `pages/offers/cards.php` lê o CPT `offer`) · Services → `group_module_services` |
| `group_page_montela_way` — Page — Montela Way | `page-templates/page-montela-way.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Values (campos próprios: `section_values_title` (textarea, `br`), `_text`, `_rows` repeater `image` + `texts` (repeater `title` a negrito / `text`)) · History (`section_history_image`, `_title` (textarea, `br`), `_text`, `_highlight_title`, `_highlight_text`) · Timeline (`section_timeline_items` repeater: `image`, `year`, `title`, `text`) · Future (`section_future_title` (textarea, `br`), `_text`, `_items` (textarea, uma linha por item), `_image`) · Closing (`section_closing_title` (textarea, `br`), `_text`) |
| `group_cpt_offer` — Offer | `single-offer.php` (post type `offer`) | Hero → `group_module_hero` · Intro (campos próprios, `template-parts/offer/intro.php`: `section_offer_intro_title` (textarea, `br`), `section_offer_intro_text` (textarea, `wpautop`), `section_offer_price` ("CHF 799"; o "Ab" é string do tema), `section_offer_price_unit` ("/ Person"), `section_offer_booking` (link; vazio = `header_booking_link` das Theme Settings)) · Included (`section_offer_package_title`, `_package_image` (só desktop), `_package_items` repeater `icon` / `title` / `text`) · Gallery (`section_offer_gallery`, slider `components/gallery-slider.php`) · Conditions (`section_offer_extra_title`, `_extra_text`, `section_offer_conditions` repeater `title` / `text` (`wpautop`), `section_offer_valid_from` / `_valid_to` (date picker, `Ymd` — formatados por `montela_get_offer_validity()`)) · Text with photos → `group_module_text_photos` · Card (`section_offer_duration` — "3 Nächte"; o card usa também o título, a imagem destacada, o excerto e as datas) |
| `group_cpt_day_trip` — Tagesausflug | `single-tagesausflug.php` (post type `tagesausflug`) | Hero → `group_module_hero` · Intro → `group_module_intro` · Destination (`section_destination_title`, `_text` (`wpautop`), `_image`, `_items` repeater `icon` / `title` / `text`) · Gallery (`section_destination_gallery`) · Getting there → `group_module_cards_icon` · Experience → `group_module_explore` |
| `group_cpt_activity` — Activity | `single-activity.php` (post type `activity`) | Hero → `group_module_hero` · Intro → `group_module_intro` · Text block 1 / Text block 2 → `group_module_text_list` (clones com "Prefix field names": `activity_block_1` / `activity_block_2`) · Cards with icon → `group_module_cards_icon` · Info cards (campos próprios, `template-parts/activity/info.php`: `section_activity_info_title` (textarea, `br`), `_text`, `_items` repeater de 2 a 4: `image`, `title`, `text`, `link` (opcional — sem link, sem botão; para um PDF, colar o URL do ficheiro)) · Activities → `group_module_activities` (o template passa `seasons` e `exclude` ao módulo) |

Secções só de uma página têm os campos **directamente no grupo da página** (no
seu tab), com o prefixo da secção: `section_direct_book_*`, `section_gallery_*`. Listas com ícone (repeater → `icon`, `title`, `text`) usam o componente `components/icon-list.php`.

### Theme Settings (options page)
A própria página de opções também vive no `acf-json/` — sem código PHP:
`ui_options_page_theme_settings.json` (slug `montela-theme-settings`,
capability `edit_theme_options`, autoload). Chega aos outros ambientes pelo
**Sync** do ACF (ACF → Options Pages), como os field groups. Ler os campos com
`get_field( 'campo', 'option' )`.

Um só grupo para a página, `group_options_theme_settings` — Options — Theme
Settings, com um tab por zona. Campos novos do site inteiro entram aqui, num tab
próprio.

| Tab | Usado em | Campos |
|-----|----------|--------|
| Header | `template-parts/header-main.php`, `template-parts/mega-menu.php` | `header_booking_link` (link, array — botão "Jetzt buchen"; vazio = escondido), `header_menu_image` (image, ID — foto do mega-menu, desktop) |
| Sticky CTA | `template-parts/sticky-cta.php` (em todas as páginas, via `footer.php`) | `sticky_cta_title` (text), `sticky_cta_text` (textarea), `sticky_cta_link` (link). Sem título nem botão = não aparece. Botão fixo no canto inferior direito que abre o cartão; comportamento em `main.js`. |
| Footer: Call to action | `template-parts/footer-main.php` | `footer_cta_title` (text, `<br>` permitido; vazio = sem caixa), `footer_cta_text`, `footer_cta_link` (link) |
| Footer: Newsletter & contact | `template-parts/footer-main.php` | `footer_newsletter_text`, `footer_address` (textarea, `br`), `footer_phone`, `footer_email`, `socials_facebook`, `socials_instagram` (url) |
| Footer: Partner logos | `template-parts/footer-main.php` | `footer_partners` (repeater: `image` ID, `link`) |

Menus: `main-menu` (header desktop), `mega-menu` (mega-menu; 2 níveis), `footer-menu` (footer, 4 por coluna), `legal-menu` (barra de copyright).

> **A preencher** com os field groups das restantes páginas e dos CPTs.

---

## SEO / Schema

- Plugin: **Yoast SEO** (`wordpress-seo`)
- Schema customizado via **Yoast Schema API** — usar filtros `wpseo_schema_graph_pieces` e `wpseo_schema_*`
- Não criar schema manual com `wp_head` se o Yoast já o suportar.

> **A preencher** com o mapeamento contexto → schema (ex.: CPT de eventos → `schema.org/Event`, página FAQ → `schema.org/FAQPage`).

| Contexto | Schema | Campos principais |
|----------|--------|-------------------|
| —        | —      | —                 |

---

## Plugins

> Atenção: alguns plugins são **apenas locais** (debug, migração, deploy) e não estão em produção.

### Stack standard (presente em ~100% dos sites live — assumir ao desenvolver)
- `advanced-custom-fields-pro` + `acfml` — campos ACF (multilingual)
- WPML: `sitepress-multilingual-cms` + `wpml-string-translation` + `wp-seo-multilingual` + `wpml-mailchimp-for-wp` — multilíngue
- `wordpress-seo` (Yoast) + `acf-content-analysis-for-yoast-seo` — SEO/Schema
- `woocommerce` + `woocommerce-multilingual` — loja
- `contact-form-7` + `contact-form-7-multilingual` — formulários
- `mailchimp-for-wp` — newsletter
- `wp-rocket` — cache, minify, lazyload (**normalmente só activo em live**)
- `sg-security` — hardening (o `inc/security.php` do tema cobre apenas o que este não cobre)
- `safe-svg` — sanitização de uploads SVG
- `simple-maintenance-mode` — plugin interno dig.id ([repo](https://github.com/DIG-ID/simple-maintenance-mode)): modo de manutenção com toggle no admin bar, HTTP 503 + `Retry-After`, bypass para admins. O template é personalizável por tema via override em `themes/<tema>/simple-maintenance-mode/` — a lógica fica sempre no plugin, nunca no tema

Ferramentas dev/deploy (sem impacto no código do tema): `wp-migrate-db`, `wppusher`, `regenerate-thumbnails`.

### Regras
- Não usar plugins adicionais — preferir código no tema ou plugins já instalados.
- **Não duplicar funcionalidade do stack**: lazyload/minify/cache é do WP Rocket; hardening base é do SG Security; schema é via Yoast API; sanitização de SVG é do safe-svg.
- Código sempre compatível com WPML: strings traduzíveis, `wpml_object_id` para IDs hardcoded, campos ACF traduzíveis via acfml.
- Plugins fora do stack standard (ex.: `age-gate`, `woocommerce-gateway-stripe`, `flexible-coupons-pro`, `woo-variation-swatches`) são **específicos de cada projecto** — documentar no CLAUDE.md do projecto.

---

## Documentação de Referência

Ao desenvolver, seguir sempre a documentação oficial como fonte de autoridade para boas práticas, exemplos e dúvidas. Em caso de conflito entre abordagens, a documentação oficial prevalece. Para APIs recentes ou casos duvidosos, **consultar a documentação online em vez de assumir**.

- **Theme Handbook** — https://developer.wordpress.org/themes/ (estrutura, template hierarchy, boas práticas de temas)
- **WordPress Developer Resources** — https://developer.wordpress.org/ (code reference de funções/hooks/classes, APIs)
- **Coding Standards** — https://developer.wordpress.org/coding-standards/ (PHP, HTML, CSS, JS, acessibilidade)
- **ACF** — https://www.advancedcustomfields.com/resources/
- **WooCommerce** — https://developer.woocommerce.com/docs/
- **WPML** — https://wpml.org/documentation/ (hooks e compatibilidade multilíngue)
- **Yoast Schema API** — https://developer.yoast.com/features/schema/

### Front-end (atenção às versões instaladas — ver `package.json`)
- **Tailwind CSS v3** — https://v3.tailwindcss.com/docs (o starter usa `^3.4` via PostCSS no Vite; **não** usar docs/sintaxe da v4 nem o plugin `@tailwindcss/vite`)
- **GSAP v3** — https://gsap.com/docs/v3/
- **Lenis** — https://github.com/darkroomengineering/lenis/blob/main/README.md
- **Swiper** — https://swiperjs.com/swiper-api (o starter usa `^12`; não usar docs de versões antigas)
- **Fancyapps UI (Fancybox v5)** — https://fancyapps.com/fancybox/
- **Vite** — https://vite.dev/guide/ (o starter usa `^8`, com Rolldown; não usar docs de versões antigas — ex.: `build.rolldownOptions` em vez de `build.rollupOptions`)

> Regra: antes de usar exemplos da documentação front-end, confirmar a versão instalada no `package.json` — APIs de Tailwind/Swiper mudam entre major versions.

---

## PHPDoc — Documentação de Código (obrigatório)

Todos os ficheiros PHP **devem** começar com um file docblock com `@package`, `@subpackage` e `@since`:

```php
<?php
/**
 * Short description of what this file does.
 *
 * @package montela
 * @subpackage Template
 * @since 1.0.0
 */
```

### Taxonomia de `@subpackage`
| `@subpackage` | Usar em |
|---|---|
| `Core` | `functions.php`, `header.php`, `footer.php`, `index.php` |
| `Template` | Root templates (`single.php`, `page.php`, `archive.php`, `404.php`, `search.php`) e `page-templates/*` |
| `Section` | `template-parts/` — secções de página (hero, header-main, footer-main, etc.) |
| `Component` | `template-parts/` — partes pequenas e reutilizáveis (cards, botões, breadcrumbs) |
| `Module` | Funcionalidades front-end compostas (sliders, accordions, galerias) |
| `Functionality` | `inc/` — features do tema (security, performance, enqueue, admin, setup) |
| `Helpers` | `inc/helpers.php`, `inc/theme-template-tags.php` |

### Regras
- `@since` = versão do tema em que o ficheiro/função foi introduzido (sincronizado com o `CHANGELOG.md`).
- Todas as funções têm docblock com descrição, `@param` e `@return` (standard `WordPress-Docs` do PHPCS).
- Docblocks e comentários sempre em **inglês (en-EN)**.
- Ao criar ficheiros novos, nunca usar comentários de linha (`// ...`) como cabeçalho — sempre docblock completo.

---

## Semântica HTML5 & Boas Práticas (obrigatório)

- Usar sempre os elementos semânticos correctos: `<header>`, `<nav>`, `<main>` (um único por página), `<section>`, `<article>`, `<aside>`, `<footer>`, `<figure>`/`<figcaption>`, `<time datetime="">`.
- **Um único `<h1>` por página**; hierarquia de headings sem saltos (`h1` → `h2` → `h3`).
- `<section>` só quando tem heading próprio; caso contrário usar `<div>`.
- Acessibilidade: skip-link para `#main-content`, `aria-label` em `<nav>` e botões icon-only, `alt` sempre definido nas imagens (vazio se decorativa), estados de foco visíveis.
- Imagens: usar `wp_get_attachment_image()` (gera `srcset`/`sizes`); `loading="lazy"` por omissão, `loading="eager"` + `fetchpriority="high"` apenas no hero/LCP.
- Schema/structured data: **sempre via Yoast Schema API (JSON-LD)** — nunca microdata (`itemscope`/`itemtype`) inline no HTML.
- Escaping sempre no output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`. Inputs com sanitização e nonces quando aplicável.
- Strings sempre traduzíveis (`__()`, `esc_html__()`, etc.) com text domain `montela`, e em **inglês** por omissão.
- Estas regras aplicam-se a todo o código novo e a qualquer ficheiro tocado (boy-scout rule: ao editar, corrigir a semântica/documentação desse ficheiro).

---

## i18n / Traduções

- Todas as strings do tema são traduzíveis (`__()`, `esc_html__()`, etc.) com text domain `montela`, em **inglês** por omissão.
- **Gerar o `.pot` com WP-CLI** (disponível no shell do Local by WPEngine), idealmente antes do lançamento:
  ```bash
  wp i18n make-pot . languages/montela.pot --domain=montela
  ```
- Com WPML no stack, o `.pot` não é obrigatório (o String Translation faz scan do tema), mas é boa prática: o WPML importa-o e o tema funciona com `.po`/`.mo` nativos em sites sem WPML.
- Não usar Loco Translate ou plugins equivalentes para isto (regra: sem plugins extra).
- `wpml-config.xml` na raiz do tema (quando existir): define defaults de tradução para CPTs, taxonomias e custom fields (traduzir vs. copiar) — ajustar por projecto conforme os campos ACF.

---

## Versionamento & Changelog

O tema segue **Semantic Versioning** (`MAJOR.MINOR.PATCH`) e mantém um `CHANGELOG.md` no formato [Keep a Changelog](https://keepachangelog.com/).

> Projecto arrancado a partir do **dig.id Theme Starter Vite v2.0.2** (recomeçou em `1.0.0` — ver `CHANGELOG.md`).

### Quando incrementar
| Tipo de alteração | Exemplo | Incremento |
|---|---|---|
| Correcção de bug / ajuste pequeno | fix num template, correcção CSS | **PATCH** — `1.0.0` → `1.0.1` |
| Nova funcionalidade / implementação | novo template, novo CPT, nova secção | **MINOR** — `1.0.1` → `1.1.0` |
| Mudança estrutural / incompatível | redesign, remoção de funcionalidade | **MAJOR** — `1.1.0` → `2.0.0` |

### Regras (obrigatório em cada alteração de código)
1. **No fim de cada tarefa concluída**, sugerir sempre a actualização do `CHANGELOG.md` e do `Version:` no `style.css` para reflectir a alteração (propor o novo número segundo a tabela acima).
2. Adicionar a entrada no `CHANGELOG.md` numa secção `## [X.Y.Z] — YYYY-MM-DD`, sob a categoria adequada: `Added`, `Changed`, `Fixed`, `Removed`, `Deprecated` ou `Security`.
3. O número de versão em `style.css` e a última versão do `CHANGELOG.md` devem estar sempre sincronizados.
4. Entradas do changelog **sempre em inglês (en-EN)**, curtas e orientadas ao que mudou para quem usa o tema (não detalhes internos de implementação).
5. Sempre que a versão muda (changelog + `style.css` actualizados), sugerir também uma **mensagem de commit para o push no GitHub** — em inglês, curta, no formato `vX.Y.Z: summary of changes` — para que changelog, versão do tema e histórico git fiquem em sintonia.
6. Opcional: marcar as versões publicadas com uma tag (`git tag vX.Y.Z && git push origin vX.Y.Z`).

---

## Convenções de Escrita de Código

> Vivem em `.claude/rules/` — ficheiros carregados automaticamente em cada
> sessão, com a mesma prioridade deste ficheiro.
>
> | Ficheiro | Cobre | Carrega |
> |---|---|---|
> | `php.md` | escaping, campos ACF, template parts, hooks | sempre |
> | `sass.md` | `@apply`, naming, breakpoints, padrões de layout, colapso de margens | ao tocar em `assets/sass/` ou `tailwind.config.js` |
> | `js.md` | entry point, jQuery em IIFE, Alpine, enqueue via Vite | ao tocar em `assets/js/` |
> | `editor-content.md` | safelist do Tailwind, imagens do editor, markup e locale do CF7 | sempre |
> | `figma.md` | a grelha do projecto e como achar a coluna | sempre |
>
> A grelha em `figma.md` está preenchida (12/6/2 colunas, gutter 20px) e
> implementada em `assets/sass/_layout/_grid.sass`.

---

## Convenções Gerais

- Não usar plugins desnecessários — preferir código no tema.
- Não instalar dependências npm ou composer sem confirmação explícita.
- Ao adicionar template parts, seguir a estrutura existente em `template-parts/`.
- Assets CSS/JS são compilados via `npm run prod` (ver secção Build) — nunca editar `dist/` directamente.
- Text domain do tema: `montela`.
