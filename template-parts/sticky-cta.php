<?php
/**
 * Sticky CTA: a sage card pinned to the bottom-right corner of every page.
 *
 * Closed, it is the sage "hero button" (house icon); a click opens the card
 * above it — title, text and a button — and the icon turns into a down
 * arrow that closes it again. Esc and a click outside also close it. Hides
 * itself while the footer is on screen (main.js, _sticky-cta.sass).
 *
 * Fields: Theme Settings → Sticky CTA (sticky_cta_title, sticky_cta_text,
 * sticky_cta_link). Nothing is printed without a title or a button.
 * Figma: closed 5594:17195, open 5426:5271.
 *
 * @package montela
 * @subpackage Section
 * @since 1.14.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$cta_title = get_field( 'sticky_cta_title', 'option' );
$cta_text  = get_field( 'sticky_cta_text', 'option' );
$cta_link  = get_field( 'sticky_cta_link', 'option' );
$cta_url   = ( is_array( $cta_link ) && ! empty( $cta_link['url'] ) ) ? $cta_link['url'] : '';

if ( ! $cta_title && ! $cta_url ) {
	return;
}

$cta_link_title = ! empty( $cta_link['title'] ) ? $cta_link['title'] : $cta_title;
$cta_target     = ( is_array( $cta_link ) && ! empty( $cta_link['target'] ) ) ? $cta_link['target'] : '';
$cta_name       = $cta_title ? $cta_title : $cta_link_title;

/* translators: %s: sticky call-to-action title. */
$cta_label_open = sprintf( __( 'Open: %s', 'montela' ), $cta_name );
/* translators: %s: sticky call-to-action title. */
$cta_label_close = sprintf( __( 'Close: %s', 'montela' ), $cta_name );
?>
<aside class="sticky-cta" aria-label="<?php echo esc_attr( $cta_name ); ?>" data-sticky-cta>
	<div id="sticky-cta-panel" class="sticky-cta__panel">
		<?php if ( $cta_title ) : ?>
			<p class="sticky-cta__title"><?php echo esc_html( $cta_title ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_text ) : ?>
			<p class="sticky-cta__text"><?php echo esc_html( $cta_text ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_url ) : ?>
			<a class="sticky-cta__btn" href="<?php echo esc_url( $cta_url ); ?>"<?php echo '_blank' === $cta_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
				<?php echo esc_html( $cta_link_title ); ?>
				<?php if ( '_blank' === $cta_target ) : ?>
					<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'montela' ); ?></span>
				<?php endif; ?>
			</a>
		<?php endif; ?>
	</div>

	<button
		type="button"
		class="btn-icon sticky-cta__toggle"
		aria-expanded="false"
		aria-controls="sticky-cta-panel"
		aria-label="<?php echo esc_attr( $cta_label_open ); ?>"
		data-label-open="<?php echo esc_attr( $cta_label_open ); ?>"
		data-label-close="<?php echo esc_attr( $cta_label_close ); ?>"
	></button>
</aside>
