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
│   ├── components/       # button.php, button-icon.php, icon-list.php
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
| `activity` — Activities | `post_type_activity.json` | `/aktivitaeten/{slug}/` (sem arquivo) | título, imagem destacada ("Card image"), excerto, revisões | Sem editor de blocos: o conteúdo do single é feito de módulos ACF. Lido por uma secção da homepage. |

### Taxonomias

| Taxonomia | JSON | Em | Notas |
|-----------|------|----|-------|
| `season` — Seasons | `taxonomy_season.json` | `activity` | Hierárquica (checkboxes no editor), sem URLs públicos, coluna na lista do admin. Termos **`sommer`** e **`winter`** — os slugs são usados no código (filtro Sommer/Winter); criar em cada ambiente, não renomear os slugs. Uma actividade pode ter as duas. |

### Templates por implementar
- **Single Activity** (`single-activity.php`) — Figma: desktop
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
| `group_module_hero` — Module — Hero | `modules/hero.php` | `section_hero_image` (image, ID), `section_hero_video` (file mp4, URL, opcional), `section_hero_title_line_1` (text), `section_hero_title_line_2` (text), `section_hero_title_line_3` (text) |
| `group_module_intro` — Module — Intro | `modules/intro.php` | `section_intro_title` (textarea, `new_lines: br`), `section_intro_text` (textarea) |
| `group_module_explore` — Module — Explore | `modules/explore.php` | `section_explore_title` (text), `section_explore_image` (image), `section_explore_text` (textarea), `section_explore_button` (link), `section_explore_slides` (gallery) |
| `group_module_image_band` — Module — Image band | `modules/image-band.php` | `section_image_band_image` (image) |

### Páginas
Um grupo por page template, com um **tab por secção**, na ordem da página; cada
tab clona o grupo do módulo (Seamless, sem prefixo).

| Grupo | Template | Tabs → clone |
|-------|----------|--------------|
| `group_page_home` — Page — Home | `page-templates/page-home.php` | Hero → `group_module_hero` · Intro → `group_module_intro` · Apartments (campos próprios, `pages/home/find-apartment.php`) · Direct booking (campos próprios, `pages/home/direct-book.php`) · Gallery (campos próprios, `pages/home/gallery.php`) · Explore → `group_module_explore` · Image band → `group_module_image_band` |

Secções só de uma página têm os campos **directamente no grupo da página** (no
seu tab), com o prefixo da secção: `section_find_apartment_*`,
`section_direct_book_*`, `section_gallery_*`. Listas com ícone (repeater → `icon`, `title`, `text`) usam o componente `components/icon-list.php`.

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
