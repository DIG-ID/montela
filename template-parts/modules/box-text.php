<?php
/**
 * Box text module: a small italic title, a title and a text, in one of two
 * styles chosen by the editor:
 * - box:  a walnut-framed block — title and text side by side on desktop.
 *         Figma: Box_text_desktop 5767:15892, tablet 5767:15900, mobile 5767:15910.
 * - band: a full-width sage band, cream text — text in a column on the right
 *         on desktop. Figma: "Section Green Background_with h2" desktop
 *         5679:15246, tablet 5679:15254, mobile 5679:15232. Optionally a
 *         button under the title and a list with icons below, two columns on
 *         desktop ("Section Green Background_with logos", Stay page):
 *         desktop 5679:15089, tablet 5679:15112, mobile 5679:15145.
 * Stacked below desktop in both.
 *
 * Fields: acf-json/group_module_box_text.json, cloned into each page's field
 * group.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'box_' for a clone named 'box'.
 * - class   (string)     Optional. Margins around the section, replacing the
 *                        style's default (box: 128 / 192 / 192 above and
 *                        144 / 216 / 190 below; band: 128 / 192 below, 192
 *                        around on desktop) — e.g. Kids Playground and Lounge
 *                        & Bar, where the box closes a page section.
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

// Button and list: green band only (the fields are hidden for the box).
$box_button = 'band' === $box_style ? get_field( $box_prefix . 'section_box_text_button', $box_ctx ) : null;
$box_items  = 'band' === $box_style ? get_field( $box_prefix . 'section_box_text_items', $box_ctx ) : null;

if ( ! $box_title && ! $box_text ) {
	return;
}

$box_class = 'section-box-text section-box-text--' . $box_style . ( $box_items ? ' section-box-text--has-list' : '' );

// Margins per style; a page can pass its own in $args['class'].
if ( ! empty( $args['class'] ) ) {
	$box_class .= ' ' . $args['class'];
} elseif ( 'band' === $box_style ) {
	$box_class .= ' mb-[128px] md:mb-[192px] xl:my-[192px]';
} else {
	$box_class .= ' mt-[128px] mb-[144px] md:mt-[192px] md:mb-[216px] xl:mb-[190px]';
}

$box_title_id = wp_unique_id( 'section-box-text-title-' );
?>
<section class="<?php echo esc_attr( $box_class ); ?>"<?php echo $box_title ? ' aria-labelledby="' . esc_attr( $box_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-box-text__box">
				<div class="section-box-text__inner">
					<?php if ( $box_eyebrow ) : ?>
						<h2 class="title-h2 section-box-text__eyebrow"><?php echo esc_html( $box_eyebrow ); ?></h2>
					<?php endif; ?>

					<?php if ( $box_title ) : ?>
						<h3 id="<?php echo esc_attr( $box_title_id ); ?>" class="title-h3 section-box-text__title"><?php echo esc_html( $box_title ); ?></h3>
					<?php endif; ?>

					<?php if ( $box_text ) : ?>
						<div class="section-box-text__text"><?php echo wp_kses_post( $box_text ); ?></div>
					<?php endif; ?>

					<?php if ( is_array( $box_button ) && ! empty( $box_button['url'] ) ) : ?>
						<div class="section-box-text__button">
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array_merge( $box_button, array( 'style' => 'outline' ) )
							);
							?>
						</div>
					<?php endif; ?>

					<?php
					get_template_part(
						'template-parts/components/icon-list',
						null,
						array(
							'items' => $box_items,
							'class' => 'section-box-text__list',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
