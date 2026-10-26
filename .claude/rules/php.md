---
description: PHP house style — escaping, ACF access, template parts, hooks.
---

# PHP — convenções de escrita

> Padrões concretos de "como escrevemos componentes". Complementa (não repete) as
> secções de PHPDoc, Semântica HTML5 e i18n do `CLAUDE.md`.
>
> Prefixo `montela_`, text domain `montela`.

### PHP — output & escaping
- Sempre `<?php echo ?>` — **nunca** `<?= ?>`.
- Escaping por tipo: `esc_url()` (URLs/`href`/`src`), `esc_attr()` (atributos), `esc_html()` (texto), `wp_kses_post()` (HTML rico de ACF/editor).
- Imagens de media library: `wp_get_attachment_image( $id, 'full', false, array( 'class' => '...' ) )` — nunca montar `<img>` à mão quando há attachment ID.
- Markup limitado vindo de um campo (um `<br>` num título, por exemplo) passa por `wp_kses()` com uma lista explícita, não por `wp_kses_post()`:
  ```php
  echo wp_kses( $title, array( 'br' => array( 'class' => array() ) ) );
  ```
  `esc_html()` imprimiria a tag como texto; `wp_kses_post()` abriria a porta a imagens e links num campo que só precisa de uma quebra de linha. Ver `editor-content.md`.

### PHP — acesso a campos ACF
```php
// Single com fallback
$image_id = get_field( 'hero_image' ) ?: get_post_thumbnail_id( $post_id );

// Link (devolve array) — validar antes de usar
$button = get_field( 'section_hero_button' );
if ( $button ) :
	$link_url    = $button['url'];
	$link_title  = $button['title'];
	$link_target = $button['target'] ?: '_self';
endif;

// Repeater
if ( have_rows( 'items' ) ) :
	while ( have_rows( 'items' ) ) : the_row();
		$sub = get_sub_field( 'sub_field_name' );
	endwhile;
endif;

// Group (devolve array) — usar ! empty() para evitar notices
$group  = get_field( 'section_group_name' );
$title  = ( $group && ! empty( $group['title'] ) ) ? $group['title'] : 'Fallback';
$img_id = ( $group && ! empty( $group['image'] ) ) ? $group['image'] : null;
```

**Link — ler SEMPRE o `target`.** O campo Link guarda `url`, `title` e `target`. Ler só o
`url` deita os outros fora e **não avisa**: o editor liga o "Open link in a new tab", o
valor fica guardado, e a página ignora-o. Já aconteceu duas vezes neste projecto.

```php
$foo_target = ( is_array( $foo_link ) && ! empty( $foo_link['target'] ) ) ? $foo_link['target'] : '';
// Na <a>, o rel anda sempre com o target — sem noopener a página que abre fica com
// acesso a esta pelo window.opener.
echo '_blank' === $foo_target ? ' target="_blank" rel="noopener noreferrer"' : '';
```

Passar o array inteiro com `array_merge( $foo_link, array( 'style' => '...' ) )` ao
`components/button.php` já faz o correcto. O erro está sempre nos templates que
escolhem só o `url` para os `$args` de um componente de card — e um card que aceita
`url` deve aceitar `target` ao lado, ou cada chamador tem de remontar a `<a>`.

**Naming ACF:** snake_case com prefixo de contexto — `section_hero_title`, `section_hero_button`, `events_cpt_place`, `socials_instagram`.

**Clone:** clonar sempre o **grupo**, nunca um repeater dentro dele — um repeater clonado guarda uma referência composta que o `have_rows()` não resolve, e o admin continua a mostrar os valores enquanto a página não desenha nada.

**Nomes de variáveis:** o PHPCS rejeita `$title`, `$tag`, `$link` e outros globais reservados do WordPress como variáveis locais num template. Prefixar com uma abreviatura da secção — `$hero_title`, `$cta_link`.

### PHP — template parts & hooks
- Toda a secção via `get_template_part()`:
  ```php
  get_template_part( 'template-parts/modules/usp', 'band' );   // secção reutilizável
  get_template_part( 'template-parts/pages/home/hero' );       // secção só da home
  get_template_part( 'template-parts/posts/post', 'content' );
  ```
- **Onde colocar o ficheiro da secção** (decidir por reutilização):
  - Secção **reutilizada em ≥2 páginas/templates** → `template-parts/modules/` (PHP) + estilo em `_modules/` (SASS).
  - Secção **presa a uma só página** → `template-parts/pages/{página}/` (PHP) + estilo em `_pages/` (SASS).
- Um módulo recebe o contexto por `$args`, nunca o assume: `post_id` (para ler das Theme Options numa archive, onde não há contexto de post) e `prefix` (para o mesmo módulo servir duas secções da mesma página).
  ```php
  $foo_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
  $foo_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
  ```
- Um módulo sai cedo quando não tem conteúdo (`return`), em vez de desenhar uma secção vazia.
- Hooks: `function montela_nome() { ... } add_action( 'hook', 'montela_nome' );` — prefixo do tema sempre.

### PHP — dois erros a evitar (verificados em projectos anteriores)
1. **Body text de ACF textarea (`new_lines: wpautop`) já vem em `<p>`** — nunca voltar a envolver em `<p>` (cria `<p><p>` inválido e perde as classes Tailwind). Usar `<div>`:
   ```php
   // ERRADO: <p class="text-... pb-10"><?php echo wp_kses_post( $body ); ?></p>
   <div class="pb-10"><?php echo wp_kses_post( $body ); ?></div>
   ```
2. **Cor de texto em fundos escuros** — as classes de tipografia não trazem cor. Definir a cor **no container**, para os filhos (incl. `<p>` gerados por wpautop) herdarem:
   ```php
   <div class="bg-montela-walnut text-montela-cream section-foo__text"> ... </div>
   ```
   Tokens de cor do Montela (ver `tailwind.config.js` / `_vars.sass`): `montela-cream` `#F8F8F2`, `montela-sage` `#8F9B87`, `montela-mustard` `#E2B529`, `montela-walnut` `#4E3C2A`.
