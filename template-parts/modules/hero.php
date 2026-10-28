<?php
/**
 * Hero module: background image (or video) with the page title in up to
 * three lines.
 *
 * Fields: acf-json/group_module_hero.json, cloned into each page's field
 * group. Each title line has a desktop position field (left / indented
 * 128px / custom indent in px / centre / right); "Full screen" makes the hero
 * fill the screen on desktop instead of 900px. Figma: hero-child-page-desktop 5618:10003 (default),
 * hero-desktop 5594:16771 (homepage), hero-tablet 5426:4317,
 * hero-mobile 5594:16778.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'hero_' for a clone named 'hero'.
 * - variant (string)     Optional. 'home' for the homepage exception (full
 *                        screen on desktop, bigger first line, see _hero.sass).
 *                        Chosen by the template, not by the editor.
 *
 * @package montela
 * @subpackage Section
 * @since 1.4.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$hero_ctx     = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$hero_prefix  = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
$hero_is_home = ! empty( $args['variant'] ) && 'home' === $args['variant'];

$hero_class = 'section-hero';
if ( $hero_is_home ) {
	$hero_class .= ' section-hero--home';
} elseif ( get_field( $hero_prefix . 'section_hero_fullscreen', $hero_ctx ) ) {
	$hero_class .= ' section-hero--full';
}

$hero_image = get_field( $hero_prefix . 'section_hero_image', $hero_ctx );
$hero_video = get_field( $hero_prefix . 'section_hero_video', $hero_ctx );
$hero_lines = array_filter(
	array(
		1 => get_field( $hero_prefix . 'section_hero_title_line_1', $hero_ctx ),
		2 => get_field( $hero_prefix . 'section_hero_title_line_2', $hero_ctx ),
		3 => get_field( $hero_prefix . 'section_hero_title_line_3', $hero_ctx ),
	)
);

// Desktop position of each line; anything unexpected falls back to the default.
$hero_aligns   = array( 'left', 'indent', 'custom', 'center', 'right' );
$hero_defaults = array(
	1 => 'left',
	2 => 'left',
	3 => 'right',
);

// No title and no image: nothing worth drawing.
if ( ! $hero_lines && ! $hero_image ) {
	return;
}

// Each line's markup. Hyphenated words are kept whole ("Saas-Fee/"): at
// these sizes the browser would otherwise wrap after the hyphen.
$hero_rendered = array();
foreach ( $hero_lines as $hero_n => $hero_line ) {
	$hero_align = get_field( $hero_prefix . 'section_hero_title_line_' . $hero_n . '_align', $hero_ctx );

	$hero_align = in_array( $hero_align, $hero_aligns, true ) ? $hero_align : $hero_defaults[ $hero_n ];

	// Custom: the editor's indent in px, applied on desktop (_hero.sass).
	$hero_indent = 'custom' === $hero_align ? absint( get_field( $hero_prefix . 'section_hero_title_line_' . $hero_n . '_indent', $hero_ctx ) ) : null;

	$hero_rendered[ $hero_n ] = array(
		'text'   => preg_replace( '/(\S+-\S+)/u', '<span class="whitespace-nowrap">$1</span>', esc_html( $hero_line ) ),
		'align'  => $hero_align,
		'indent' => $hero_indent,
	);
}
$hero_kses = array( 'span' => array( 'class' => array() ) );

// The last line is the italic one, however many lines there are.
$hero_last = $hero_rendered ? array_key_last( $hero_rendered ) : 0;
?>
<section class="<?php echo esc_attr( $hero_class ); ?>">
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

	<?php if ( $hero_rendered && ! $hero_is_home ) : ?>
		<div class="theme-container section-hero__content">
			<h1 class="title-h1 section-hero__title">
				<?php foreach ( $hero_rendered as $hero_n => $hero_line ) : ?>
					<span class="section-hero__line section-hero__line--<?php echo esc_attr( $hero_n ); ?><?php echo $hero_last === $hero_n ? ' section-hero__line--last' : ''; ?> section-hero__line--<?php echo esc_attr( $hero_line['align'] ); ?>"<?php echo null !== $hero_line['indent'] ? ' style="--hero-indent: ' . esc_attr( $hero_line['indent'] ) . 'px"' : ''; ?>><?php echo wp_kses( $hero_line['text'], $hero_kses ); ?></span>
				<?php endforeach; ?>
			</h1>
		</div>
	<?php elseif ( $hero_rendered ) : ?>
		<?php
		/*
		 * Homepage: on desktop the big title wipes away after a few seconds
		 * and comes back smaller in the bottom-left corner (_hero.sass). The
		 * big one is a decorative copy, hidden from assistive technology; the
		 * real <h1> is the small one, there from the start. Below desktop
		 * the big copy is the only one shown, and the <h1> is screen-reader
		 * text.
		 */
		?>
		<div class="theme-container section-hero__content">
			<div class="title-h1 section-hero__title section-hero__title--intro" aria-hidden="true">
				<?php foreach ( $hero_rendered as $hero_n => $hero_line ) : ?>
					<span class="section-hero__line section-hero__line--<?php echo esc_attr( $hero_n ); ?><?php echo $hero_last === $hero_n ? ' section-hero__line--last' : ''; ?> section-hero__line--<?php echo esc_attr( $hero_line['align'] ); ?>"<?php echo null !== $hero_line['indent'] ? ' style="--hero-indent: ' . esc_attr( $hero_line['indent'] ) . 'px"' : ''; ?>><?php echo wp_kses( $hero_line['text'], $hero_kses ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="theme-container section-hero__end">
			<h1 class="section-hero__title section-hero__title--end">
				<?php foreach ( $hero_rendered as $hero_n => $hero_line ) : ?>
					<span class="section-hero__line section-hero__line--<?php echo esc_attr( $hero_n ); ?><?php echo $hero_last === $hero_n ? ' section-hero__line--last' : ''; ?>"><?php echo wp_kses( $hero_line['text'], $hero_kses ); ?></span>
				<?php endforeach; ?>
			</h1>
		</div>
	<?php endif; ?>
</section>
