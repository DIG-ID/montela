<?php
/**
 * Testimonials module: a framed slider of guest reviews (quote and author),
 * one at a time, cross-fading.
 *
 * Content comes from the Testimonial post type (acf-json/post_type_testimonial.json):
 * the post title is the author line ("Martin Ahrens 5* bei Google"), the
 * quote is the testimonial_quote field. The page picks which ones, and in
 * what order, in a Relationship field (acf-json/group_module_testimonials.json,
 * cloned into each page's field group); left empty, all testimonials are
 * shown. Figma: section-testimonials-desktop 5588:16241, tablet 5426:4570,
 * mobile 5460:15447.
 *
 * Enqueues the Swiper module itself; for the CSS to land in <head>, also
 * enqueue it on wp_enqueue_scripts for the templates that use this module
 * (see inc/enqueue.php).
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'testimonials_' for a clone named 'testimonials'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.12.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$tst_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$tst_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$tst_title = get_field( $tst_prefix . 'section_testimonials_title', $tst_ctx );
$tst_ids   = get_field( $tst_prefix . 'section_testimonials_items', $tst_ctx );

// Nothing picked: every testimonial, in admin order.
if ( ! $tst_ids ) {
	$tst_ids = get_posts(
		array(
			'post_type'        => 'testimonial',
			'posts_per_page'   => -1,
			'fields'           => 'ids',
			'orderby'          => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'suppress_filters' => false, // Let WPML keep the current language.
		)
	);
}

// Published, with a quote.
$tst_items = array();
foreach ( (array) $tst_ids as $tst_id ) {
	$tst_quote = get_field( 'testimonial_quote', $tst_id );
	if ( 'publish' === get_post_status( $tst_id ) && $tst_quote ) {
		$tst_items[] = array(
			'quote'  => $tst_quote,
			'author' => get_the_title( $tst_id ),
		);
	}
}

if ( ! $tst_items ) {
	return;
}

$tst_many = count( $tst_items ) > 1;

if ( $tst_many && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$tst_title_id = wp_unique_id( 'section-testimonials-title-' );
$tst_swiper   = array(
	'effect'        => 'fade',
	'fadeEffect'    => array( 'crossFade' => true ),
	'speed'         => 600,
	'loop'          => true,
	'slidesPerView' => 1,
	'spaceBetween'  => 0,
);
?>
<section class="section-testimonials"<?php echo $tst_title ? ' aria-labelledby="' . esc_attr( $tst_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-testimonials__frame" data-swiper-root>
			<?php if ( $tst_title ) : ?>
				<h2 id="<?php echo esc_attr( $tst_title_id ); ?>" class="title-h2 section-testimonials__title"><?php echo esc_html( $tst_title ); ?></h2>
			<?php endif; ?>

			<div class="swiper section-testimonials__swiper"<?php echo $tst_many ? ' data-swiper="' . esc_attr( wp_json_encode( $tst_swiper ) ) . '"' : ''; ?>>
				<div class="swiper-wrapper">
					<?php foreach ( $tst_items as $tst_item ) : ?>
						<figure class="swiper-slide section-testimonials__slide">
							<blockquote class="body-quote section-testimonials__quote">
								<p><?php echo esc_html( $tst_item['quote'] ); ?></p>
							</blockquote>
							<?php if ( $tst_item['author'] ) : ?>
								<figcaption class="body-text section-testimonials__author"><?php echo esc_html( $tst_item['author'] ); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $tst_many ) : ?>
				<div class="slider-nav slider-nav--walnut section-testimonials__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Vorherige Bewertung', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Nächste Bewertung', 'montela' ); ?>"></button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
