---
description: SASS and Tailwind house style — @apply, naming, breakpoints, recurring layout patterns.
paths:
  - "assets/sass/**/*.sass"
  - "tailwind.config.js"
---

# SASS / Tailwind — convenções de escrita

### Naming
- Tailwind para layout/spacing/responsive; SASS para componentes custom — não duplicar.
- Classes custom em kebab-case, semânticas. BEM-inspired em componentes complexos: `menu-toggle`, `menu-toggle__bars`, `bar bar--top`.
- Secções: `.section-{nome}` · Botões: `.btn`, `.btn-primary`… · Cards: `.card-{tipo}`.
- Wrappers de layout: `.theme-container` (largura máxima + margens) e `.theme-grid` (as 12/6/2 colunas), em `_layout/_grid.sass` — valores na tabela do `figma.md`.
- Manter a ordem de imports do `main.sass` já existente; adicionar módulos/secções na pasta correspondente (`_modules/`, `_components/`, `_pages/`…).

### @apply (estilo da casa)
As classes custom nos ficheiros SASS constroem-se com utilitários Tailwind via **`@apply`**, não com propriedades CSS soltas:

```sass
.title-main
  @apply font-primary font-medium tracking-[0.3px] text-[30px] leading-[34px] md:text-[56px] md:leading-[60px] xl:text-[76px] xl:leading-[80px]
```

- Uma classe = uma linha `@apply` com os utilitários; usar **valores arbitrários** quando não há token (`text-[82px]`, `leading-[100px]`, `tracking-[0.5px]`).
- **Responsive** com os breakpoints por omissão do Tailwind, nunca redefinidos no `tailwind.config.js`. Vocabulário: **mobile** < `md` (768) · **tablet** entre `md` e `xl` · **desktop** ≥ `xl` (1280). Mobile-first: valor base, depois `xl:`; só usar `md:` quando o design dá um valor próprio para tablet.
- **Tipografia sem cor** — as classes de título/texto não trazem cor; define-se no container para os filhos (incl. `<p>` do wpautop) herdarem. Excepção: links inline.
- Fonte via os tokens `font-primary` / `font-secondary` do `tailwind.config.js`.
- CSS puro só quando não há utilitário equivalente (ex.: `grid-template-columns: repeat(12, ...)` no `.theme-grid`).

### Especificidade — duas armadilhas que já custaram tempo
- `@tailwind utilities` é emitido **antes** destes parciais. Uma classe escrita aqui ganha a um utilitário de igual especificidade só pela ordem — mas perde para uma classe partilhada se tiver a mesma contagem. Para sobrepor uma classe partilhada (`.title-hero` num módulo), usar **duas classes** (`.section-foo .title-hero`), não confiar na ordem dos ficheiros.
- Um utilitário `px-*` numa media query posterior **repõe** `padding-right`. Quando um lado é diferente do outro (um botão com seta à direita), escrever `pl-*` e `pr-*` separados, nunca `px-*`.

### Padrões recorrentes
```sass
// Colunas de grid com altura igual (imagem enche a célula esticada).
// No HTML: .theme-grid.items-stretch — e a imagem precisa de h-full, não de
// uma altura fixa, ou fica aquém da linha quando a coluna de texto cresce.
.section-foo__img
  @media (min-width: 1280px)
    height: 100%
  img
    width: 100%
    height: 100%
    object-fit: cover

// Imagem que "sangra" até à direita do viewport, além do container.
// $container = a largura máxima do .theme-container do projecto.
.section-foo__wide-img
  @media (min-width: #{$container + 1px})
    margin-right: calc(-1 * (100vw - #{$container}) / 2)
    width: calc(100% + (100vw - #{$container}) / 2)
.section-foo
  overflow: hidden   // evitar scrollbar horizontal

// Linha de botões de largura fixa que tem de caber numa só linha:
// a largura do frame é um TECTO, não o ponto de partida — senão o browser
// quebra a linha um botão antes do que devia.
.section-foo__item
  @apply grow shrink
  flex-basis: 263px
  max-width: 287px
```

### Margens verticais entre secções
Margens de irmãos adjacentes **colapsam**: o espaço entre duas secções é a **maior** das duas margens, não a soma. Baixar só o `margin-top` da secção de baixo não muda nada se a de cima tiver uma margem maior — **as duas têm de mudar**. Documentar o valor no comentário de ambas.
