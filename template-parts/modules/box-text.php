<?php
/**
 * Box text module: a small italic title, a title and a text, in one of two
 * styles chosen by the editor:
 * - box:  a walnut-framed block — title and text side by side on desktop.
 *         Figma: Box_text_desktop 5767:15892, tablet 5767:15900, mobile 5767:15910.
 * - band: a full-width sage band, cream text — text in a column on the right
 *         on desktop. Figma: "Section Green Background_with h2" desktop
 *         5679:15246, tablet 5679:15254, mobile 5679:15232.
 * Stacked below desktop in both.
 *
 * Fields: acf-json/group_module_box_text.json, cloned into each page's field
 * group.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'box_' for a clone named 'box'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$box_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$box_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$box_style   = 'band' === get_field( $box_prefix . 'section_box_text_style', $box_ctx ) ? 'band' : 'box';
$box_eyebrow = get_field( $box_prefix . 'section_box_text_eyebrow', $box_ctx );
$box_title   = get_field( $box_prefix . 'section_box_text_title', $box_ctx );
$box_text    = get_field( $box_prefix . 'section_box_text_text', $box_ctx );

if ( ! $box_title && ! $box_text ) {
	return;
}

$box_title_id = wp_unique_id( 'section-box-text-title-' );
?>
<section class="section-box-text section-box-text--<?php echo esc_attr( $box_style ); ?>"<?php echo $box_title ? ' aria-labelledby="' . esc_attr( $box_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-box-text__box">
				<div class="section-box-text__inner">
					<?php if ( $box_eyebrow ) : ?>
						<p class="title-h2 section-box-text__eyebrow"><?php echo esc_html( $box_eyebrow ); ?></p>
					<?php endif; ?>

					<?php if ( $box_title ) : ?>
						<h2 id="<?php echo esc_attr( $box_title_id ); ?>" class="title-h3 section-box-text__title"><?php echo esc_html( $box_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $box_text ) : ?>
						<div class="section-box-text__text"><?php echo wp_kses_post( $box_text ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
