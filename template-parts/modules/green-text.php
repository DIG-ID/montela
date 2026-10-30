<?php
/**
 * Green text module: full-width sage band with a large cream title and one
 * or more paragraphs — side by side on desktop, stacked below.
 *
 * Fields: acf-json/group_module_green_text.json. Figma "Section Green
 * Background": Bike Hotel desktop 5895:19868, tablet 5895:20607, mobile
 * 5895:20378 (also Stay, Apartments, Our Region, Getting Here).
 *
 * Arguments ($args):
 * - row     (bool)       Optional. True inside a Flexible Content loop (fields read with get_sub_field()).
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names".
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

$gt_title = montela_module_field( 'section_green_text_title', $args );
$gt_text  = montela_module_field( 'section_green_text_text', $args );

if ( ! $gt_title && ! $gt_text ) {
	return;
}

$gt_title_id = wp_unique_id( 'section-green-text-title-' );
?>
<section class="section-green-text"<?php echo $gt_title ? ' aria-labelledby="' . esc_attr( $gt_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<?php if ( $gt_title ) : ?>
				<h2 id="<?php echo esc_attr( $gt_title_id ); ?>" class="title-h3 section-green-text__title">
					<?php echo wp_kses( $gt_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>
			<?php if ( $gt_text ) : ?>
				<div class="body-text section-green-text__text max-w-[333px] md:max-w-none xl:max-w-[585px]"><?php echo wp_kses_post( $gt_text ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
