<?php
/**
 * Unsere Region — "Ausflüge ab Saas-Grund": centred title and text over a
 * slider of day trip cards. Four cards in view on desktop, one on tablet
 * and mobile; arrows below.
 *
 * Cards: components/card-preview.php (closed, they open on hover).
 *
 * Content: the Tagesausflüge post type — the ones picked in the "Day trips"
 * relationship, in that order, or all of them (oldest first) when it is
 * empty. Fields: tab "Day trips" of acf-json/group_page_region.json. Figma:
 * "Cards with image section" desktop 5753:14981, tablet 5753:15055, mobile
 * 5753:15098. Enqueues the Swiper module itself.
 *
 * @package montela
 * @subpackage Section
 * @since 1.23.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$trips_title = get_field( 'section_trips_title' );
$trips_text  = get_field( 'section_trips_text' );
$trips_ids   = get_field( 'section_trips_items' );

if ( ! $trips_ids ) {
	$trips_ids = get_posts(
		array(
			'post_type'        => 'tagesausflug',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'fields'           => 'ids',
			'orderby'          => 'date',
			'order'            => 'ASC',
			'suppress_filters' => false, // Let WPML keep the current language.
		)
	);
}

$trips_ids = array_filter(
	(array) $trips_ids,
	function ( $trips_id ) {
		return 'publish' === get_post_status( $trips_id );
	}
);

if ( ! $trips_ids ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$trips_title_id = wp_unique_id( 'section-trips-title-' );
$trips_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 20,
	'breakpoints'   => array(
		1280 => array( 'slidesPerView' => 4 ),
	),
);
?>
<section class="section-trips"<?php echo $trips_title ? ' aria-labelledby="' . esc_attr( $trips_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-trips__inner" data-swiper-root>
				<?php if ( $trips_title || $trips_text ) : ?>
					<div class="section-trips__head">
						<?php if ( $trips_title ) : ?>
							<h2 id="<?php echo esc_attr( $trips_title_id ); ?>" class="title-h3 section-trips__title"><?php echo esc_html( $trips_title ); ?></h2>
						<?php endif; ?>
						<?php if ( $trips_text ) : ?>
							<p class="body-text section-trips__text"><?php echo esc_html( $trips_text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="swiper section-trips__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $trips_swiper ) ); ?>">
					<div class="swiper-wrapper">
						<?php foreach ( $trips_ids as $trips_id ) : ?>
							<div class="swiper-slide section-trips__slide">
								<?php get_template_part( 'template-parts/components/card-preview', null, array( 'post_id' => $trips_id ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="slider-nav slider-nav--walnut section-trips__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous day trip', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next day trip', 'montela' ); ?>"></button>
				</div>
			</div>
		</div>
	</div>
</section>
