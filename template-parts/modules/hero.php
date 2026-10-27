<?php
/**
 * Hero module: full-screen image (or video) with the page title.
 *
 * Fields: acf-json/group_module_hero.json, cloned into each page's field
 * group. Figma: hero-desktop 5594:16771, hero-tablet 5426:4317,
 * hero-mobile 5594:16778.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'hero_' for a clone named 'hero'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.4.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$hero_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$hero_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$hero_image = get_field( $hero_prefix . 'section_hero_image', $hero_ctx );
$hero_video = get_field( $hero_prefix . 'section_hero_video', $hero_ctx );
$hero_lines = array_filter(
	array(
		1 => get_field( $hero_prefix . 'section_hero_title_line_1', $hero_ctx ),
		2 => get_field( $hero_prefix . 'section_hero_title_line_2', $hero_ctx ),
		3 => get_field( $hero_prefix . 'section_hero_title_line_3', $hero_ctx ),
	)
);

// No title and no image: nothing worth drawing.
if ( ! $hero_lines && ! $hero_image ) {
	return;
}
?>
<section class="section-hero">
	<div class="section-hero__media" aria-hidden="true">
		<?php
		if ( $hero_image ) :
			// Decorative background and the page's LCP element: empty alt, loaded eagerly.
			echo wp_get_attachment_image(
				$hero_image,
				'full',
				false,
				array(
					'class'         => 'section-hero__img',
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
		endif;

		if ( $hero_video ) :
			// Same image as poster, so browsers that paint the video box before
			// the first frame (iOS Safari) show the image instead of black.
			$hero_poster = $hero_image ? wp_get_attachment_image_url( $hero_image, 'full' ) : '';
			?>
			<video class="section-hero__video" autoplay muted loop playsinline preload="metadata"<?php echo $hero_poster ? ' poster="' . esc_url( $hero_poster ) . '"' : ''; ?>>
				<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
			</video>
		<?php endif; ?>
	</div>

	<?php if ( $hero_lines ) : ?>
		<div class="theme-container section-hero__content">
			<h1 class="title-h1 section-hero__title">
				<?php
				foreach ( $hero_lines as $hero_n => $hero_line ) :
					// Keep hyphenated words whole ("Saas-Fee/"): at these sizes the
					// browser would otherwise wrap after the hyphen.
					$hero_line = preg_replace( '/(\S+-\S+)/u', '<span class="whitespace-nowrap">$1</span>', esc_html( $hero_line ) );
					?>
					<span class="section-hero__line section-hero__line--<?php echo esc_attr( $hero_n ); ?>"><?php echo wp_kses( $hero_line, array( 'span' => array( 'class' => array() ) ) ); ?></span>
				<?php endforeach; ?>
			</h1>
		</div>
	<?php endif; ?>
</section>
