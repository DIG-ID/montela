<?php
/**
 * Intro module: centred title and lead text over the mountains line drawing.
 *
 * Fields: acf-json/group_module_intro.json, cloned into each page's field
 * group. Figma: section-intro-desktop 5588:16245, intro (tablet) 5426:4346,
 * intro-mobile 5594:16952.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'intro_' for a clone named 'intro'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.4.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$intro_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$intro_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$intro_title = get_field( $intro_prefix . 'section_intro_title', $intro_ctx );
$intro_text  = get_field( $intro_prefix . 'section_intro_text', $intro_ctx );

if ( ! $intro_title && ! $intro_text ) {
	return;
}

$intro_title_id = wp_unique_id( 'section-intro-title-' );
?>
<section class="section-intro"<?php echo $intro_title ? ' aria-labelledby="' . esc_attr( $intro_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-intro__content">
				<?php if ( $intro_title ) : ?>
					<h2 id="<?php echo esc_attr( $intro_title_id ); ?>" class="title-intro section-intro__title">
						<?php echo wp_kses( $intro_title, array( 'br' => array( 'class' => array() ) ) ); ?>
					</h2>
				<?php endif; ?>
				<?php if ( $intro_text ) : ?>
					<p class="body-intro section-intro__text"><?php echo esc_html( $intro_text ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
