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
 * - cream: the band's layout on the page background, with walnut text, a
 *         walnut button and walnut icons ("Section Green Background_with
 *         logos", Apartments page): desktop 5812:15532, tablet 5812:15556,
 *         mobile 5812:15589.
 * Stacked below desktop in all of them.
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
 *                        around on desktop; cream: none) — e.g. Kids
 *                        Playground and Lounge & Bar, where the box closes a
 *                        page section.
 * - style   (string)     Optional. 'box', 'band' or 'cream', replacing the
 *                        field — e.g. the apartment pages, always cream.
 * - fallback_id (int)    Optional. A post whose fields fill in, one by one,
 *                        the ones left empty on post_id — the apartment
 *                        pages fall back on the Apartments page (since
 *                        1.51.0). The button colour goes with the button.
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
$box_backup = ! empty( $args['fallback_id'] ) ? $args['fallback_id'] : 0;

/*
 * Reads a field, from the fallback post when empty here. Returns the value
 * and the post it came from.
 */
$box_get = static function ( $name ) use ( $box_prefix, $box_ctx, $box_backup ) {
	$value = get_field( $box_prefix . $name, $box_ctx );
	if ( ! $value && $box_backup ) {
		return array( get_field( $box_prefix . $name, $box_backup ), $box_backup );
	}
	return array( $value, $box_ctx );
};

$box_style   = ! empty( $args['style'] ) ? $args['style'] : $box_get( 'section_box_text_style' )[0];
$box_style   = in_array( $box_style, array( 'band', 'cream' ), true ) ? $box_style : 'box';
$box_is_band = 'box' !== $box_style;
$box_eyebrow = $box_get( 'section_box_text_eyebrow' )[0];
$box_title   = $box_get( 'section_box_text_title' )[0];
$box_text    = $box_get( 'section_box_text_text' )[0];

// Button and list: bands only (the fields are hidden for the box). The
// colour is read where the button came from.
list( $box_button, $box_btn_src ) = $box_is_band ? $box_get( 'section_box_text_button' ) : array( null, $box_ctx );

$box_btn_sg = $box_is_band && 'sage' === get_field( $box_prefix . 'section_box_text_button_colour', $box_btn_src );
$box_items  = $box_is_band ? $box_get( 'section_box_text_items' )[0] : null;

if ( ! $box_title && ! $box_text ) {
	return;
}

// The cream band keeps the band's layout: it carries both classes.
$box_class  = 'section-box-text section-box-text--' . ( $box_is_band ? 'band' : 'box' );
$box_class .= 'cream' === $box_style ? ' section-box-text--cream' : '';
$box_class .= $box_items ? ' section-box-text--has-list' : '';

// Margins per style; a page can pass its own in $args['class']. The cream
// band has none: on the page's own colour, its padding is the spacing.
if ( ! empty( $args['class'] ) ) {
	$box_class .= ' ' . $args['class'];
} elseif ( 'band' === $box_style ) {
	$box_class .= ' mb-[128px] md:mb-[192px] xl:my-[192px]';
} elseif ( 'box' === $box_style ) {
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
						<p class="title-h2 section-box-text__eyebrow anim-fade-up"><?php echo montela_text( $box_eyebrow ); ?></p>
					<?php endif; ?>

					<?php if ( $box_title ) : ?>
						<h2 id="<?php echo esc_attr( $box_title_id ); ?>" class="title-h3 section-box-text__title anim-fade-up"><?php echo montela_text( $box_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $box_text ) : ?>
						<div class="section-box-text__text anim-fade-up"><?php echo wp_kses_post( $box_text ); ?></div>
					<?php endif; ?>

					<?php if ( is_array( $box_button ) && ! empty( $box_button['url'] ) ) : ?>
						<div class="section-box-text__button anim-fade-up">
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array_merge( $box_button, array( 'style' => $box_btn_sg ? 'secondary' : ( 'cream' === $box_style ? 'primary' : 'outline' ) ) )
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
							'class' => 'section-box-text__list anim-fade-up anim-children',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
