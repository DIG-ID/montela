---
description: Translating Figma frames to code — how to work out the project grid and find a column.
---

# Figma → código

O MCP do Figma devolve **posições absolutas num canvas** — não é grid-aware.
Não devolve colunas, nem gutters, nem espaçamentos entre componentes: a coluna
tem de ser calculada à mão a partir dos pixéis.

## A grelha deste projecto

Preencher no arranque, a partir dos frames reais, **antes** de escrever a
primeira secção. **Ainda por preencher no Montela**; é isto que define o
`.theme-container` e o `.theme-grid` em `assets/sass/_layout/`.

| Breakpoint | Canvas | Container | Colunas | Coluna | Margens | Tailwind |
|---|---|---|---|---|---|---|
| Desktop | | | | | | `grid-cols-12 gap-5` |
| Tablet | | | | | | `grid-cols-6 gap-5` |
| Mobile | | | | | | `grid-cols-2 gap-5` |

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
