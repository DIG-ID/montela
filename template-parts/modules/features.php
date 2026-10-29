<?php
/**
 * Features module: a full-width band — cream or sage — with a title, a
 * text and a list of features, each an icon, a bold title and a short text
 * (components/icon-list.php). Two columns of features on desktop, one below.
 *
 * Fields: acf-json/group_module_features.json, cloned into each page's
 * field group (with "Prefix field names" when it appears twice). Figma
 * "Section Green Background_with logos" (Anreise & Lage): desktop
 * 5747:13926 (cream) / 5679:15353 (sage), tablet 5747:13980 / 5679:15376,
 * mobile 5747:13948 / 5679:15409.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'arrival_car_' for a clone named 'arrival_car'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.26.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$feat_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$feat_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$feat_style = get_field( $feat_prefix . 'section_features_style', $feat_ctx );
$feat_title = get_field( $feat_prefix . 'section_features_title', $feat_ctx );
$feat_text  = get_field( $feat_prefix . 'section_features_text', $feat_ctx );
$feat_items = get_field( $feat_prefix . 'section_features_items', $feat_ctx );

if ( ! $feat_title && ! $feat_items ) {
	return;
}

$feat_title_id = wp_unique_id( 'section-features-title-' );
$feat_class    = 'section-features section-features--' . ( 'sage' === $feat_style ? 'sage' : 'cream' );
?>
<section class="<?php echo esc_attr( $feat_class ); ?>"<?php echo $feat_title ? ' aria-labelledby="' . esc_attr( $feat_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-features__grid">
			<?php if ( $feat_title ) : ?>
				<h2 id="<?php echo esc_attr( $feat_title_id ); ?>" class="title-h3 section-features__title"><?php echo wp_kses( $feat_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $feat_text ) : ?>
				<p class="body-text section-features__text"><?php echo esc_html( $feat_text ); ?></p>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/components/icon-list',
				null,
				array(
					'items' => $feat_items,
					'class' => 'section-features__list',
				)
			);
			?>
		</div>
	</div>
</section>
