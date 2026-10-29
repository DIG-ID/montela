<?php
/**
 * Anreise & Lage — location ("Direkt zu Montela"): a cream panel with the
 * title, the address and a Google Maps button beside a slider of photos of
 * places nearby, each with its name (desktop); panel over the slider below.
 * Last section of the page: on tablet and desktop it runs on behind the
 * footer's booking box (_arrival.sass).
 *
 * The design leaves the photo half as a placeholder, with a note asking
 * for 4–5 photos of main places with their names; built as a slider with
 * arrows (no autoplay), like the Explore module. Fields: tab "Location" of
 * acf-json/group_page_arrival.json. Figma "Next to footer_explore": desktop
 * 5769:16301, tablet 5769:16291, mobile 5769:16282. Enqueues the Swiper
 * module itself.
 *
 * @package montela
 * @subpackage Section
 * @since 1.26.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$location_title   = get_field( 'section_location_title' );
$location_name    = get_field( 'section_location_name' );
$location_address = get_field( 'section_location_address' );
$location_button  = get_field( 'section_location_button' );
$location_slides  = get_field( 'section_location_slides' );

$location_slides = array_values(
	array_filter(
		(array) $location_slides,
		function ( $location_slide ) {
			return ! empty( $location_slide['image'] );
		}
	)
);

if ( ! $location_title && ! $location_address && ! $location_slides ) {
	return;
}

$location_many = count( $location_slides ) > 1;

if ( $location_many && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$location_title_id = wp_unique_id( 'section-location-title-' );
$location_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 0,
);
?>
<section class="section-location"<?php echo $location_title ? ' aria-labelledby="' . esc_attr( $location_title_id ) . '"' : ''; ?>>
	<div class="section-location__panel">
		<?php if ( $location_title ) : ?>
			<h2 id="<?php echo esc_attr( $location_title_id ); ?>" class="title-h3 section-location__title"><?php echo wp_kses( $location_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $location_name || $location_address ) : ?>
			<address class="section-location__address">
				<?php if ( $location_name ) : ?>
					<span class="section-location__name"><?php echo esc_html( $location_name ); ?></span>
				<?php endif; ?>
				<?php if ( $location_address ) : ?>
					<span class="section-location__lines"><?php echo wp_kses( $location_address, array( 'br' => array() ) ); ?></span>
				<?php endif; ?>
			</address>
		<?php endif; ?>

		<?php
		if ( is_array( $location_button ) ) :
			get_template_part( 'template-parts/components/button', null, array_merge( $location_button, array( 'style' => 'outline' ) ) );
		endif;
		?>
	</div>

	<?php if ( $location_slides ) : ?>
		<div class="section-location__slider" data-swiper-root>
			<div class="swiper section-location__swiper"<?php echo $location_many ? ' data-swiper="' . esc_attr( wp_json_encode( $location_swiper ) ) . '"' : ''; ?>>
				<div class="swiper-wrapper">
					<?php foreach ( $location_slides as $location_slide ) : ?>
						<figure class="swiper-slide section-location__slide">
							<?php
							echo wp_get_attachment_image(
								$location_slide['image'],
								'full',
								false,
								array(
									'class' => 'section-location__img',
									'sizes' => '(min-width: 1280px) 50vw, 100vw',
								)
							);
							?>
							<?php if ( ! empty( $location_slide['caption'] ) ) : ?>
								<figcaption class="title-h2 section-location__caption"><?php echo esc_html( $location_slide['caption'] ); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $location_many ) : ?>
				<div class="slider-nav slider-nav--sage section-location__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
