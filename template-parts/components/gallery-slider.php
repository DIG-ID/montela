<?php
/**
 * Full-bleed photo slider: slides alternate two widths (951 and 746 by 832
 * on desktop), arrows over the bottom-right corner (centred below on
 * mobile). Styles: _components/_gallery-slider.sass.
 *
 * Used by the homepage gallery (template-parts/pages/home/gallery.php) and
 * the offer single (template-parts/offer/package.php). Needs the Swiper
 * module, which it enqueues; enqueue it on wp_enqueue_scripts too for the
 * CSS to land in <head> (inc/enqueue.php).
 *
 *     get_template_part(
 *         'template-parts/components/gallery-slider',
 *         null,
 *         array(
 *             'images' => get_field( 'section_foo_images' ),
 *             'class'  => 'section-foo__slider',
 *         )
 *     );
 *
 * Arguments ($args):
 * - images (int[])  Required. Attachment IDs.
 * - class  (string) Optional. Extra classes for the slider (e.g. its margins).
 *
 * @package montela
 * @subpackage Component
 * @since 1.21.0
 */

$slider_images = ! empty( $args['images'] ) && is_array( $args['images'] ) ? $args['images'] : array();

if ( ! $slider_images ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$slider_class = 'gallery-slider' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );

// Slides: auto width (set per slide in CSS), 4px apart on mobile, 20px above
// (the grid gutter).
$slider_swiper = array(
	'slidesPerView' => 'auto',
	'spaceBetween'  => 4,
	'breakpoints'   => array(
		768 => array( 'spaceBetween' => 20 ),
	),
);
?>
<div class="<?php echo esc_attr( $slider_class ); ?>" data-swiper-root>
	<div class="swiper gallery-slider__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $slider_swiper ) ); ?>">
		<div class="swiper-wrapper">
			<?php foreach ( $slider_images as $slider_image ) : ?>
				<div class="swiper-slide gallery-slider__slide">
					<?php
					echo wp_get_attachment_image(
						$slider_image,
						'large',
						false,
						array(
							'class' => 'gallery-slider__img',
							'sizes' => '(min-width: 1280px) 50vw, (min-width: 768px) 76vw, 56vw',
						)
					);
					?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( count( $slider_images ) > 1 ) : ?>
		<div class="gallery-slider__nav">
			<div class="theme-container">
				<div class="slider-nav gallery-slider__nav-buttons">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
