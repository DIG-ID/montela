<?php
/**
 * Full-bleed photo strip: a Swiper slider of photos at their own width (wide
 * and narrow crops alternate), with prev/next arrows over its bottom-right
 * corner on tablet and desktop, below it on mobile.
 *
 * Used by the homepage Gallery section and the Photo slider module. Figma:
 * homepage gallery strip (desktop 5466:15471, tablet 5426:4383, mobile
 * 5453:8475); the same strip under Kids Playground (5895:19891) and Lounge &
 * Bar (5895:20871). Styles in _components/_photo-slider.sass.
 *
 *     get_template_part(
 *         'template-parts/components/photo-slider',
 *         null,
 *         array(
 *             'images' => get_field( 'section_foo_images' ),
 *             'class'  => 'section-foo__slider',
 *         )
 *     );
 *
 * Enqueues the Swiper module itself. On templates that already enqueue it on
 * wp_enqueue_scripts (homepage) this is a no-op; elsewhere its CSS is printed
 * in the footer — add the template to montela_enqueue_assets() to avoid that.
 *
 * Arguments ($args):
 * - images (int[])  Required. Attachment IDs, in order.
 * - class  (string) Optional. Extra classes for the root element (spacing).
 *
 * @package montela
 * @subpackage Component
 * @since 1.16.0
 */

$slider_images = ! empty( $args['images'] ) && is_array( $args['images'] ) ? $args['images'] : array();

if ( ! $slider_images ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$slider_class = 'photo-slider' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );

// Slides: auto width (set per slide in CSS), 4px apart on mobile, 20px above (23 in Figma — the gutter rule).
$slider_options = array(
	'slidesPerView' => 'auto',
	'spaceBetween'  => 4,
	'breakpoints'   => array(
		768 => array( 'spaceBetween' => 20 ),
	),
);
?>
<div class="<?php echo esc_attr( $slider_class ); ?>" data-swiper-root>
	<div class="swiper photo-slider__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $slider_options ) ); ?>">
		<div class="swiper-wrapper">
			<?php
			// The photo covers a slide of fixed height, so its rendered width is
			// height × its own ratio (or the slide width, if that is wider) — not
			// the slide width that sizes="auto" would report. WordPress adds
			// "auto" to lazy images; switch it off for these.
			add_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );
			foreach ( $slider_images as $slider_index => $slider_image ) :
				$slider_meta  = wp_get_attachment_metadata( $slider_image );
				$slider_ratio = ( ! empty( $slider_meta['width'] ) && ! empty( $slider_meta['height'] ) ) ? $slider_meta['width'] / $slider_meta['height'] : 1.5;
				$slider_ratio = max( $slider_ratio, 0 === $slider_index % 2 ? 951 / 832 : 746 / 832 );
				$slider_sizes = sprintf(
					'(min-width: 1280px) %dpx, (min-width: 768px) %dpx, %dpx',
					ceil( 832 * $slider_ratio ),
					ceil( 552 * $slider_ratio ),
					ceil( 192 * $slider_ratio )
				);
				?>
				<div class="swiper-slide photo-slider__slide">
					<?php
					echo wp_get_attachment_image(
						$slider_image,
						'full',
						false,
						array(
							'class' => 'photo-slider__img',
							'sizes' => $slider_sizes,
						)
					);
					?>
				</div>
				<?php
			endforeach;
			remove_filter( 'wp_img_tag_add_auto_sizes', '__return_false' );
			?>
		</div>
	</div>

	<?php if ( count( $slider_images ) > 1 ) : ?>
		<div class="photo-slider__nav">
			<div class="theme-container">
				<div class="slider-nav photo-slider__nav-buttons">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
