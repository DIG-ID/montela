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
  - os botões "Apartments entdecken" e "Zu allen Services" apontam para `#`
    — faltam as páginas de destino (ou ligar o primeiro à página Apartments).
    O "Premium Stay entdecken" (Services, também na Angebote e na Apartments)
    já aponta para a Premium Stay em local; em produção, ligar depois de criar
    a página.
- [ ] **Apartments** (`page-templates/page-apartments.php`) e **Single Apartment**
  (`single-apartment.php`) — feitos. Falta o conteúdo dos 9 apartamentos
  (fotos 1–3, foto da Ausstattung, galeria, intro, amenities). Em local há dois
  apartamentos de teste (`apartment-1-schlafzimmer-1-badezimmer` completo,
  `apartment-2-schlafzimmer-1-badezimmer` só com o card). Em local só há uma
  oferta: o slider "Für jede Auszeit…" não foi visto com 4+ cards.
  Em produção: Sync do CPT, da taxonomia e dos grupos (incl. Module — Offer
  cards); criar a página Apartments (filha da Aufenthalt) e preencher os tabs
  Services (o conteúdo da Aufenthalt), Included ("Inklusive Leistungen", estilo
  Cream band, ícones walnut), Testimonials (título) e Offers (título, texto,
  botão para a Angebote & Packages); criar os 11 termos com os slugs do
  `CLAUDE.md` e os ícones. O botão "Zu allen Services" aponta para `#` — falta
  a página de destino (como na Aufenthalt).
- [ ] **Premium Stay** (`page-templates/page-premium-stay.php`) — feita. Pendente:
  - a foto do hero no Figma tem só 1400px (como na Aufenthalt) — pedir o original;
  - o item "Premium Stay" do mega-menu aponta para `#` (menus são conteúdo:
    ligar à página em cada ambiente).
  Em produção: Sync dos grupos `group_page_premium_stay` e Module — Services
  comparison (o texto passou a aceitar quebras de linha); criar a página (filha
  da Aufenthalt, slug `premium-stay`, "Premium Stay Template") e preencher Hero,
  Intro, Services (os planos da Aufenthalt, o texto do Figma desktop, sem botão)
  e Text with photos (Desktop layout: Staggered; botão para a Apartments) e
  Cards with icon ("Lage & Resort", estilo Filled, ícones creme).
  Sync também do Module — Text with photos (campo novo Desktop layout).
- [ ] **Mapa** — design por aprovar. Entra em vários sítios:
  - homepage, entre a Gallery e o Explore (`page-templates/page-home.php`);
  - Unsere Region, entre os Highlights e os Tagesausflüge (`page-templates/page-region.php`);
  - Aufenthalt, entre o Extras e os Testimonials;
  - Unser Resort e Shop ("mapa adiado" no `CLAUDE.md`).
  Ao construir: chave em `wp-config.php` (`MONTELA_GOOGLE_MAPS_API_KEY`) e o
  template na lista de `montela_enqueue_google_maps()` (`inc/enqueue.php`).
  Rever as margens das secções vizinhas, que hoje são provisórias por causa do
  mapa — desde a 1.32.0 estão no PHP (`modules/cards-icon.php`,
  `modules/box-text.php`, classes por omissão ou `$args['class']`).
- [ ] **Animações** — `assets/js/animations.js` ainda não tem animações do
  projecto (só o setup do GSAP/Lenis). Lembrar: estados iniciais via
  `gsap.from()`, nunca `opacity: 0` no CSS.
- [ ] **Estilo dos links inline** — `_typography.sass` tem a secção "Links" por
  definir a partir do design system.
- [ ] **Timeline do Montela Weg** — os anos 2020–2026 ainda estão em Lorem
  Ipsum (conteúdo, no tab Timeline da página).

## A verificar

- [ ] **Testimonials** — o conteúdo actual é provisório (vão ser reviews
  escolhidas de várias fontes e inseridas à mão). O testimonial 212 não tem
  título, por isso aparece sem a linha do autor. No staging, a homepage não
  tem o título "Was Gäste an Montela lieben" preenchido (em local tem; a
  Aufenthalt tem).
- [ ] **Sticky CTA** — o título nas Theme Settings é "TITLE (H2)" (local e
  staging) e sai como `<h2>` em todas as páginas: preencher ou esvaziar.
- [ ] **WP Pusher no staging** — o Push-to-Deploy não está a actualizar sozinho
  (o staging ficou na 1.31.0 até um "Update theme" manual a 2026-10-30): ver o
  webhook do GitHub e o Deploy log. Depois de cada deploy, Sync do ACF
  (grupos, CPTs e taxonomias novos da 1.32.0 e da 1.33.0).
- [ ] **Safelist do Tailwind** — os títulos aceitam `<br class="…">` vindo do
  editor (ver `.claude/rules/editor-content.md`). Confirmar que as classes
  usadas no conteúdo existem no CSS compilado; senão criar o `safelist`.

## SEO / Schema

- [ ] Preencher no `CLAUDE.md` o mapeamento contexto → schema (Yoast Schema
  API) e implementar: p.ex. `offer` → `Offer`, `activity` / `tagesausflug`
  → `TouristAttraction`, testimonials → `Review`. Já feito: página FAQ →
  `FAQPage` (`inc/schema.php`, v1.29.0).

## Antes do lançamento

- [ ] Gerar o `.pot`: `wp i18n make-pot . languages/montela.pot --domain=montela`.
- [ ] `wp-config.php` em produção: `DISALLOW_FILE_EDIT`, `WP_DEBUG` desligado
  (ver `SETUP.md`, passo 13).
- [ ] WP Rocket: "Delay JavaScript execution" activo, com o `main.js` como
  única excepção do tema.
- [ ] SG Security activo.
- [ ] CF7 (o formulário da Info & Kontakt já existe): locale `de_CH`, para
  as mensagens de validação saírem formais e sem "ß" (ver
  `.claude/rules/editor-content.md`).
- [ ] Criar os termos `sommer` e `winter` da taxonomia Season e fazer o Sync
  dos grupos ACF em cada ambiente.
