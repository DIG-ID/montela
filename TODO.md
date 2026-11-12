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
  - o botão "Apartments entdecken" aponta para `#` — ligar à página Apartments.
    O "Zu allen Services" já aponta para a Leistungen em local (também na
    Apartments); em produção, ligar depois de criar a página.
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
  `CLAUDE.md` e os ícones. O botão "Zu allen Services" vai para a Leistungen
  (ver Aufenthalt).
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
- [ ] **Leistungen** (`page-templates/page-services.php`) — feita (todas as
  secções do Figma). Pendente:
  - a caixa do "Frühstück" está vazia no Figma por baixo do título (NOTES
    C19) — o campo Text existe; confirmar se leva texto (folha de conteúdo);
  - o título do bloco dos cães repete o da secção "Flexibel wohnen…" (NOTES
    C20) — confirmar o texto na folha de conteúdo;
  - o bloco do Aqua Allalin talvez não leve imagem (nota da designer, NOTES
    C22) — confirmar;
  - fotos do Figma em baixa resolução: hero, foto 1 do Space, cão e varanda
    do Magic Pass (1400px); sauna do Aqua Allalin (998px) — pedir originais;
  - o item "Services" do mega-menu aponta para `#` (ligar em cada ambiente);
  - botões a ligar (em local estão em `#`): "Zur Lounge & Bar" e "Zum Shop"
    (Lounge & Shop) → páginas Lounge & Bar e Shop; "Mehr erfahren"
    (Gepäckservice) → secção SBB Gepäcktransport da Anreise & Lage; "Anfrage
    senden" (Reinigung) → Info & Kontakt (ou um mailto — confirmar); "Zum
    Resort" (Alles nah beieinander) → Unser Resort.
  Em produção:
  - Sync dos grupos: Page — Services (novo), Module — Space (novo), Page —
    Stay (o Space da Aufenthalt passou a módulo — os valores mantêm-se),
    Module — Cards with icon (Button nos cards), Module — Features (Layout,
    Note, Button), Module — Resort sections (só as instruções do texto),
    Module — Box text (campo novo Button colour);
  - criar a página (filha da Aufenthalt, slug `leistungen`, "Services
    Template") e preencher, por tab: Hero (linha 2 Custom 185px, linha 3
    Right), Intro, Space (Columns, sem botão, itens sem ícone), Cards with
    icon (Frame, ícones walnut, um botão por card), Breakfast (título, duas
    fotos e o bloco dos cães — texto com a lista em `<ul>`), Resort (título e
    três cards), Features (Cream, Centred, ícones walnut, notas e botões) e
    Resort sections (três blocos de parceiros, sem botão) e Box text (Cream,
    sem lista, título, texto, botão "Zum Resort" com Button colour Green).
- [ ] **Mapa** — módulo feito (`modules/map.php`, desde a 1.53.0): homepage,
  Aufenthalt, Unsere Region e Unser Resort. As ilustrações e os pins estão
  nas Theme Settings (tab Map); no staging, preencher lá e em cada página o
  título / texto / features. Falta:
  - Shop ("mapa adiado" no `CLAUDE.md`) — confirmar no Figma;
  - textos dos pins (só o 1 tem texto e botão) e o G / C do inverno (NOTES B26).
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

- [ ] Validar o schema no staging com o Rich Results Test ou o Schema Markup
  Validator: a homepage (Hotel), uma oferta e uma activity. O staging tem
  password: colar o código-fonte da página em vez do URL.
- [ ] Rever os schemas quando o conteúdo do site estiver todo completo: os
  campos que alimentam cada um (preços, datas, dados dos apartamentos,
  morada/telefone/e-mail do footer, imagens, excertos) e voltar a validar.
- [ ] Opcional: coordenadas GPS (`geo`) do Hotel no schema — não há campo;
  seria preciso acrescentá-lo às Theme Settings.

Headings — revisão das 45 páginas do staging a 2026-11-11: a hierarquia está
correcta (um `<h1>`, sem saltos de nível). Ficou por resolver:

- [ ] **Leistungen — título duplicado** (conteúdo): o `<h3>` do bloco dos cães,
  debaixo de "Frühstück", diz "Flexibel wohnen mit passenden Leistungen" — o
  mesmo texto do `<h2>` da secção Space. Admin: Leistungen → tab Breakfast →
  Dogs → Title.
- [ ] **Títulos que parecem cortados ou repetidos** (conteúdo):
  - oferta "Montela Mountain & Pleasure", "Im Package enthalten": os itens
    "Wasserdichter" e "Frühstück direkt" parecem frases a meio;
  - Tagesausflug Leukerbad: os cards "Therme & Gemmi kombinieren" e "Gemmi
    kombinieren", quase iguais;
  - os 9 apartamentos têm o mesmo título da intro, "Ihr Zuhause in den Bergen"
    (o texto é próprio de cada um) — para SEO, um título por apartamento.
- [ ] **Activities — um título por estação** (código): na homepage, na Erkunden
  e na Unsere Region os cards do Sommer e do Winter estão os dois no HTML,
  todos `<h3>` debaixo do mesmo `<h2>` ("Familien" aparece duas vezes). Um
  título escondido (`sr-only`) "Sommer" / "Winter" por grupo — h3, com os
  cards a h4 — diz a um leitor de ecrã que são estações diferentes.
  `template-parts/modules/activities.php`, `components/card-activity.php`
  (já recebe `title_tag`).
- [ ] Opcional — **Fitnessraum**: as caixas de utilização ("Rücksichtsvoll
  trainieren"…) são `<h3>` sem `<h2>` próprio e ficam dentro da secção
  "Trainieren während Ihrer Ferien". Um título de secção (mesmo `sr-only`)
  em `pages/fitnessroom/fitness-usage.php` separava-as.

## Antes do lançamento

- [ ] Yoast → Settings → Site representation: Organization, com nome
  ("Montela Apartment-Hotel & Resort") e logo. Sem os dois, o Yoast não gera
  o nó Organization e o schema do Hotel (`inc/schema.php`) não sai.
- [ ] Tirar o `noindex, nofollow` e passar a página Design System a privada.
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
