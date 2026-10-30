---
description: Translating Figma frames to code — how to work out the project grid and find a column.
---

# Figma → código

O MCP do Figma devolve **posições absolutas num canvas** — não é grid-aware.
Não devolve colunas, nem gutters, nem espaçamentos entre componentes: a coluna
tem de ser calculada à mão a partir dos pixéis.

## A grelha deste projecto

É isto que define o `.theme-container` e o `.theme-grid` em
`assets/sass/_layout/_grid.sass`. Frames da homepage: desktop `5980:17214`,
tablet `5426:4308`, mobile `5426:4185`.

| Breakpoint | Canvas | Container | Colunas | Coluna | Margens | Tailwind |
|---|---|---|---|---|---|---|
| Desktop | 1920 | 1820 | 12 | 133.33px | 50px | `grid-cols-12 gap-x-5` |
| Tablet | 834 | 706 | 6 | 101px | 64px | `grid-cols-6 gap-x-5` |
| Mobile | 393 | 329 | 2 | 154.5px | 32px | `grid-cols-2 gap-x-5` |

Desktop confirmado na geometria (colunas 2–11 = x 203, 1514px de largura).
Gutter de 20px em todos os breakpoints; o `.theme-grid` só define o gap entre
colunas — o espaço entre linhas é de cada secção.

**Espaço horizontal entre colunas, cards e slides: sempre 20px** (o gutter da
grelha), mesmo quando o Figma desenha 17, 18, 19, 23 ou 25px — a regra é a
consistência, não o valor exacto do frame. Vale para grelhas próprias das
secções (`gap-x-5`) e para o `spaceBetween` dos sliders Swiper. Excepção: a
galeria (`components/gallery-slider.php`) mantém 4px em mobile. Ao seguir o
Figma, anotar no comentário do SASS o valor original ("20px — 18 in Figma").

O gutter costuma ser constante em todos os breakpoints (`gap-5` = 20px nos
projectos recentes) — confirmar no frame antes de assumir.

## Achar a coluna de um elemento

```
unidade = largura_da_coluna + gutter
coluna N começa em x = margem_esquerda + (N − 1) × unidade
col = floor((element_left_px − margem_esquerda) / unidade) + 1
```

O inset usado pela maioria das secções (colunas 2 a 11 de 12) é
`xl:col-start-2 xl:col-span-10`.

## Workflow

1. **Um breakpoint de cada vez** — desktop, confirmar, depois tablet, depois mobile.
2. **Uma secção de cada vez** — usar o node URL da secção, não o da página inteira: menos ruído e menos tokens.
3. Pedir sempre **screenshot + variáveis** junto com o design context.
4. Confirmar com **screenshot + grid overlay** depois de construir — é a forma mais rápida de apanhar um erro de coluna.
5. Cruzar com o `_vars.sass` e o `tailwind.config.js` para reutilizar tokens existentes em vez de inventar valores.

## Limitações a aceitar cedo

- Máscaras e formas recortadas do Figma (clip-path complexo) **não se replicam** em HTML/CSS — simplificar para crops rectangulares.
- O layout absoluto do Figma não mapeia 1:1 para um grid CSS responsivo; o frame dá o desenho, não a implementação.
- Um frame desenhado a 1920 dá um único passo. Os valores para os breakpoints intermédios são **interpolados**, e isso deve ficar escrito no comentário — para o próximo que lá mexer saber que não foi medido.
- Os números do frame podem não fechar: já aconteceu um grupo de 1514px conter 1515px de conteúdo. Quando a soma não dá, é o desenho que está à mão, não o código que está errado — tratar a medida como tecto e deixar o CSS ceder.

## Tags de título: a tag segue a estrutura, a classe segue o Figma

O Figma diz o **aspecto** de cada título no estilo de texto (no Dev Mode, o
comentário em Typography: `/* Tablet/H3 */`) — isso dá a **classe**
(`.title-h3`). A **tag** segue a estrutura da página, não o estilo:

- `<h1>`: um por página (o hero, ou a faixa do título).
- `<h2>`: o título de **cada secção** — irmãs lado a lado, a intro incluída.
  É o caso normal de um título com estilo H3: `<h2 class="title-h3">`.
- `<h3>`: títulos **dentro** de uma secção (cards, itens de lista, blocos);
  `<h4>` só dentro de um `<h3>`.
- Nunca saltar níveis (`h2` → `h4`). Um "eyebrow" (linha pequena por cima do
  título, ex.: "SaastalCard") não é título: `<p class="title-h2">`.
- Um componente com título (cards, `icon-list`) não fixa o nível: recebe-o por
  argumento (`title_tag`), com o default do caso mais comum.

Porquê (Daniel, Outubro 2026): com as secções em `<h3>` debaixo do `<h2>` da
intro, um leitor de ecrã que navega por `<h2>` só encontra a intro — a página
parece ter uma secção. O SEO não ganha nada com a tag a seguir o tamanho.
O tamanho continua a ser o desenhado em cada frame: um frame de tablet com
"Desktop/H3" fica com 90/80, anotado no SASS.
