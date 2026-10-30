<?php
/**
 * Text button with arrow (Figma "Buttons"), rendered as a link.
 *
 * Takes an ACF Link array merged with the style, so url, title and target
 * all come through:
 *
 *     get_template_part(
 *         'template-parts/components/button',
 *         null,
 *         array_merge( $cta_link, array( 'style' => 'secondary' ) )
 *     );
 *
 * Arguments ($args):
 * - url     (string) Required. Link URL.
 * - title   (string) Required. Link text.
 * - target  (string) Optional. '_blank' opens in a new tab.
 * - style   (string) Optional. 'primary' (default), 'secondary', 'outline',
 *                    'ghost' (cream outline, for sage backgrounds) or 'back'
 *                    (sage, arrow on the left pointing back).
 * - icon    (string) Optional. 'arrow-right' (default) or 'arrow-down' (a
 *                    download, e.g. a menu PDF). Since 1.13.0.
 * - class   (string) Optional. Extra classes for the link.
 * - context (string) Optional. Screen-reader-only words after the title, for
 *                    links that repeat on a page ("Learn more" + card title).
 *
 * @package montela
 * @subpackage Component
 * @since 1.3.0
 */

$btn_url   = ! empty( $args['url'] ) ? $args['url'] : '';
$btn_title = ! empty( $args['title'] ) ? $args['title'] : '';

// Nothing to link to, or nothing to say: draw nothing.
if ( ! $btn_url || ! $btn_title ) {
	return;
}

$btn_target  = ! empty( $args['target'] ) ? $args['target'] : '';
$btn_style   = ( ! empty( $args['style'] ) && in_array( $args['style'], array( 'primary', 'secondary', 'outline', 'ghost', 'back' ), true ) ) ? $args['style'] : 'primary';
$btn_icon    = ( ! empty( $args['icon'] ) && 'arrow-down' === $args['icon'] ) ? 'arrow-down' : 'arrow-right';
$btn_class   = 'btn btn-' . $btn_style . ( 'arrow-down' === $btn_icon ? ' btn--arrow-down' : '' ) . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
$btn_context = ! empty( $args['context'] ) ? $args['context'] : '';
?>
<a class="<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $btn_url ); ?>"<?php echo '_blank' === $btn_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
	<span class="btn__label">
		<?php echo esc_html( $btn_title ); ?>
		<?php if ( $btn_context ) : ?>
			<span class="sr-only">: <?php echo esc_html( $btn_context ); ?></span>
		<?php endif; ?>
		<?php if ( '_blank' === $btn_target ) : ?>
			<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'montela' ); ?></span>
		<?php endif; ?>
	</span>
	<span class="btn__icon" aria-hidden="true"></span>
</a>
