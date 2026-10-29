# TODO — Montela

Trabalho pendente do projecto. Ao fechar um item, apagá-lo daqui e registar a
alteração no `CHANGELOG.md`. Os nodes do Figma de cada template estão no
`CLAUDE.md` (secção Templates).

---

## Por construir

- [ ] **Aufenthalt** (`page-templates/page-stay.php`) — tem hero, intro, "Viel
  Raum für gemeinsame Ferien", Services, "Alles da, was Ferien einfacher
  macht" e Testimonials. Falta o mapa (design por aprovar; nodes no `CLAUDE.md`). Pendente:
  - a foto do hero no Figma tem só 1400px — pedir o original em alta resolução;
  - os botões "Apartments entdecken", "Premium Stay entdecken" e "Zu allen
    Services" apontam para `#` — faltam as páginas de destino.
- [ ] **Mapa** — design por aprovar. Entra em dois sítios:
  - homepage, entre a Gallery e o Explore (`page-templates/page-home.php`);
  - Unsere Region, entre os Highlights e os Tagesausflüge (`page-templates/page-region.php`).
  Ao construir: chave em `wp-config.php` (`MONTELA_GOOGLE_MAPS_API_KEY`) e o
  template na lista de `montela_enqueue_google_maps()` (`inc/enqueue.php`).
  Rever as margens das secções vizinhas, que hoje são provisórias por causa do
  mapa (`_cards-icon.sass`, `_box-text.sass`).
- [ ] **Animações** — `assets/js/animations.js` ainda não tem animações do
  projecto (só o setup do GSAP/Lenis). Lembrar: estados iniciais via
  `gsap.from()`, nunca `opacity: 0` no CSS.
- [ ] **Estilo dos links inline** — `_typography.sass` tem a secção "Links" por
  definir a partir do design system.
- [ ] **Página 404** — `_pages/_404.sass` está vazio; o template ainda é o do starter.

## Correcções

- [ ] `template-parts/footer-main.php` — o docblock refere
  `acf-json/group_options_footer.json`; o grupo é o
  `group_options_theme_settings.json`.
- [ ] `CLAUDE.md` — na tabela de CPTs, o `offer` ainda diz que os cards da
  página de listagem estão "por fazer"; já existem
  (`template-parts/pages/offers/cards.php`, v1.21.0).
- [ ] `404.php` e `archive.php` — strings fonte em alemão; a regra do projecto
  é inglês por omissão (as traduções vêm do WPML / `.pot`).

## A verificar

- [ ] **Testimonials** — o conteúdo actual é provisório (vão ser reviews
  escolhidas de várias fontes e inseridas à mão). O testimonial 212 não tem
  título, por isso aparece sem a linha do autor. A homepage não tem o título
  "Was Gäste an Montela lieben" preenchido; a Aufenthalt tem.

- [ ] **Redirects dos URLs antigos** — o changelog (1.18.0 e 1.23.0) diz que
  `/aktivitaeten/{slug}/` e `/tagesausflug/{slug}/` redireccionam para os novos.
  Não há código no tema para isso: confirmar que o WordPress o faz sozinho
  (palpite de URL nos 404) ou criar os redirects.
- [ ] **Safelist do Tailwind** — os títulos aceitam `<br class="…">` vindo do
  editor (ver `.claude/rules/editor-content.md`). Confirmar que as classes
  usadas no conteúdo existem no CSS compilado; senão criar o `safelist`.

## SEO / Schema

- [ ] Preencher no `CLAUDE.md` o mapeamento contexto → schema (Yoast Schema
  API) e implementar: p.ex. `offer` → `Offer`, `activity` / `tagesausflug`
  → `TouristAttraction`, testimonials → `Review`.

## Antes do lançamento

- [ ] Gerar o `.pot`: `wp i18n make-pot . languages/montela.pot --domain=montela`.
- [ ] `wp-config.php` em produção: `DISALLOW_FILE_EDIT`, `WP_DEBUG` desligado
  (ver `SETUP.md`, passo 13).
- [ ] WP Rocket: "Delay JavaScript execution" activo, com o `main.js` como
  única excepção do tema.
- [ ] SG Security activo.
- [ ] Formulários CF7 (quando existirem): locale `de_CH` (ver
  `.claude/rules/editor-content.md`).
- [ ] Criar os termos `sommer` e `winter` da taxonomia Season e fazer o Sync
  dos grupos ACF em cada ambiente.
