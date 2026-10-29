<?php
/**
 * Text with checklist module: a title over a photo beside the text and a
 * checklist. The photo goes left or right (tablet and desktop); on mobile
 * it sits between the text and the checklist.
 *
 * Fields: acf-json/group_module_text_list.json, cloned into each page's
 * field group. The checklist is a textarea, one item per line. Figma
 * (Single Activity): desktop 5787:18260 (photo left) and 5787:18261 (photo
 * right), tablet 5604:21813 / 5604:21814, mobile 5601:21532 / 5605:21865.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'activity_block_1_' for
 *                        a clone named 'activity_block_1'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$tl_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$tl_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$tl_title  = get_field( $tl_prefix . 'section_text_list_title', $tl_ctx );
$tl_text   = get_field( $tl_prefix . 'section_text_list_text', $tl_ctx );
$tl_items  = get_field( $tl_prefix . 'section_text_list_items', $tl_ctx );
$tl_image  = get_field( $tl_prefix . 'section_text_list_image', $tl_ctx );
$tl_layout = get_field( $tl_prefix . 'section_text_list_layout', $tl_ctx );

$tl_items = $tl_items ? array_filter( array_map( 'trim', preg_split( '/\R/', $tl_items ) ) ) : array();

if ( ! $tl_title && ! $tl_text && ! $tl_items && ! $tl_image ) {
	return;
}

$tl_title_id = wp_unique_id( 'section-text-list-title-' );
$tl_class    = 'section-text-list' . ( 'right' === $tl_layout ? ' section-text-list--right' : '' );
?>
<section class="<?php echo esc_attr( $tl_class ); ?>"<?php echo $tl_title ? ' aria-labelledby="' . esc_attr( $tl_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-text-list__grid">
			<?php if ( $tl_title ) : ?>
				<h2 id="<?php echo esc_attr( $tl_title_id ); ?>" class="title-h3 section-text-list__title"><?php echo wp_kses( $tl_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $tl_text ) : ?>
				<div class="body-text section-text-list__text"><?php echo wp_kses_post( $tl_text ); ?></div>
			<?php endif; ?>

			<?php if ( $tl_image ) : ?>
				<figure class="section-text-list__photo">
					<?php
					echo wp_get_attachment_image(
						$tl_image,
						'large',
						false,
						array(
							'class' => 'section-text-list__img',
							'sizes' => '(min-width: 1280px) 44vw, (min-width: 768px) 343px, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $tl_items ) : ?>
				<ul class="body-text section-text-list__items">
					<?php foreach ( $tl_items as $tl_item ) : ?>
						<li><?php echo esc_html( $tl_item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
