---
description: HTML and media that come from the database — the Tailwind safelist, editor-uploaded images, and Contact Form 7 markup.
---

# Conteúdo vindo do editor

Regras para tudo o que **não está no código**: markup escrito num campo ACF, um
formulário do Contact Form 7, uma imagem carregada na media library. Estas
quatro coisas já custaram tempo em projectos reais; todas falham em silêncio.

---

## 1. O Tailwind nunca lê a base de dados

O Tailwind gera CSS a partir dos ficheiros que o `content` do
`tailwind.config.js` manda ler — `.php`, `.js`, `.sass`. **Nunca lê a base de
dados.** Uma classe escrita num campo ACF, num formulário do CF7 ou no editor
do WordPress não existe no CSS compilado, e não há erro nenhum a avisar: o
elemento simplesmente não é estilizado.

Qualquer classe que viva na base de dados tem de estar no `safelist`. **O
Montela ainda não tem nenhum** — criar quando o projecto precisar do primeiro:

```js
// tailwind.config.js
safelist: [
  'hidden',
  'md:hidden',
  'md:inline',
  'xl:hidden',
  'xl:inline',
],
```

### Caso real — quebras de linha responsivas nos títulos

Um `<br>` com classe, escrito pelo editor num campo de título, permite quebrar
a linha só nalguns tamanhos:

| Onde quebra | Markup |
|---|---|
| Só mobile (< 768px) | `<br class="md:hidden">` |
| Só tablet (768–1279px) | `<br class="hidden md:inline xl:hidden">` |
| Tablet e mobile (< 1280px) | `<br class="xl:hidden">` |
| Todos os tamanhos | `<br>` |

**Pôr sempre um espaço de um dos lados do `<br>`.** Nos tamanhos em que fica
`display:none`, é esse espaço que separa as duas palavras:

```
Massgeschneidert<br class="md:hidden"> für Sie
```

Sem o espaço, no desktop sai `Massgeschneidertfür Sie`.

Isto só funciona nos campos cujo template deixa passar `class` no `<br>` — ou
seja, os que usam `wp_kses( $valor, array( 'br' => array( 'class' => array() ) ) )`.
Num campo escapado com `esc_html()` o `<br>` sai **impresso como texto** no meio
do título. Ao criar um campo de título novo, decidir já qual dos dois é.

**Precisas de outro breakpoint?** Acrescenta ao `safelist` e recompila. Escrever
`2xl:hidden` sem isso não faz nada.

---

## 2. Imagens carregadas pelo editor dimensionam-se pelo ficheiro

`h-auto` (ou `w-auto`) numa imagem vinda da media library entrega a decisão do
tamanho a quem fez o upload. Duas pessoas carregam dois ficheiros e a página
fica com dois tamanhos diferentes.

Aconteceu com logos de parceiros numa fila: com `xl:h-auto`, cada um
renderizava à sua altura intrínseca — um a **31px**, outro a **53px**, outro a
**68px**, outro acima de **150px**.

**Dar sempre uma altura fixa por breakpoint** a imagens que o editor carrega e
que têm de ficar consistentes entre páginas:

```php
'class' => 'w-auto h-[32px] md:h-[71px] xl:h-[101px] max-w-full',
```

Dimensionar por **altura** e não por largura: marcas altas e marcas largas
partilham o mesmo slot, e por largura a alta é ampliada até destoar.

Nota: uma marca empilhada (ícone + duas linhas de texto) fica mais estreita que
uma de uma linha à mesma altura. É geometricamente correcto; se destoar, a
saída é pedir o ficheiro em versão horizontal, não mexer no número.

---

## 3. O Contact Form 7 injecta markup dentro do fluxo

O CF7 insere o aviso de validação (`.wpcf7-not-valid-tip`) **dentro** do
`.wpcf7-form-control-wrap` de cada campo. Em fluxo normal, um campo que falha
empurra para baixo tudo o que vem a seguir — e como os campos falham todos ao
mesmo tempo, salta a coluna inteira.

Tirar do fluxo e deixar assentar no espaço que a coluna já deixa entre campos:

```sass
.wpcf7-form-control-wrap
  @apply relative

.wpcf7-not-valid-tip
  @apply absolute left-0 top-full
```

**Confirmar que há folga antes de aplicar.** O aviso ocupa 20px: num formulário
com gaps de 24px (mobile) e 32px (tablet+) cabe; num com gaps de 8 a 20px, o
aviso absoluto aterra em cima do campo seguinte e fica pior do que estava.

O `.wpcf7-response-output` (a caixa de confirmação/erro por baixo do form)
**fica no fluxo** — essa deve mesmo ocupar espaço.

### O markup do formulário vive na base de dados

O template do CF7 é escrito no plugin, não no tema, e por isso o Tailwind
também não o lê (ver ponto 1). As classes que esse template usa têm de estar
**declaradas no SASS do tema**, não escritas como utilitários no formulário.
Deixar o template a copiar num comentário no topo do parcial SASS que o estiliza
— é a única forma de ele não se perder.

Daí decorre um erro fácil: apontar uma página para o formulário errado. Dois
formulários com campos parecidos mas classes de módulos diferentes renderizam
um deles completamente sem estilo, sem erro nenhum. Ao ligar uma página a um
formulário, confirmar que as classes do template são as do módulo que o vai
desenhar.

---

## 4. O CF7 traz as suas próprias strings traduzidas

As mensagens de validação vêm do ficheiro de tradução do plugin, não do tema.
O locale escolhido decide o tratamento e a ortografia:

- `de_DE` é a variante **informal** do WordPress (du) — *"Bitte überprüfe das"*
- `de_DE_formal` e `de_CH` são formais (Sie) — *"Bitte überprüfen Sie das"*

Num site suíço, `de_CH` resolve duas coisas de uma vez: é formal e usa
ortografia suíça (**`ss`, nunca `ß`**). O `de_DE` do CF7 traz 13 strings com
`ß` (`Dateigröße`, `Schließen`), que destoam do resto do site.

Corrigir o locale, não as strings uma a uma. O que faltar no `de_CH` traduz-se
no WPML String Translation.
