<?php
/**
 * Icon-only square button (Figma "arrow only" and "hero button").
 *
 * Renders a link when a URL is given, otherwise a <button> for JS-driven
 * controls (slider arrows, scroll cue):
 *
 *     get_template_part(
 *         'template-parts/components/button-icon',
 *         null,
 *         array(
 *             'icon'  => 'arrow-right',
 *             'label' => __( 'Nächstes Foto', 'montela' ),
 *             'class' => 'swiper-button-next',
 *         )
 *     );
 *
 * Arguments ($args):
 * - icon   (string) Optional. 'arrow-right' (default), 'arrow-down' or 'home'.
 * - label  (string) Required. Accessible name — the button has no visible text.
 * - url    (string) Optional. Renders an <a> instead of a <button>.
 * - target (string) Optional. '_blank' opens in a new tab (links only).
 * - class  (string) Optional. Extra classes.
 *
 * @package montela
 * @subpackage Component
 * @since 1.3.0
 */

$btn_label = ! empty( $args['label'] ) ? $args['label'] : '';

// An icon-only control without an accessible name is unusable — skip it.
if ( ! $btn_label ) {
	return;
}

$btn_icon   = ( ! empty( $args['icon'] ) && in_array( $args['icon'], array( 'arrow-right', 'arrow-down', 'home' ), true ) ) ? $args['icon'] : 'arrow-right';
$btn_url    = ! empty( $args['url'] ) ? $args['url'] : '';
$btn_target = ! empty( $args['target'] ) ? $args['target'] : '';
$btn_class  = 'btn-icon btn-icon--' . $btn_icon . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );

if ( $btn_url ) :
	?>
	<a class="<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $btn_url ); ?>" aria-label="<?php echo esc_attr( $btn_label ); ?>"<?php echo '_blank' === $btn_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>></a>
	<?php
else :
	?>
	<button class="<?php echo esc_attr( $btn_class ); ?>" type="button" aria-label="<?php echo esc_attr( $btn_label ); ?>"></button>
	<?php
endif;
