<?php
/**
 * Homepage: "Premium Hotel Stay" — two photos, text, button, a list of
 * services with icons and a full-bleed photo slider.
 *
 * Fields: "Gallery" tab of acf-json/group_page_home.json. Figma:
 * section-gallery-desktop 5466:15471, tablet 5426:4383, mobile 5453:8475.
 * The slider needs the Swiper module (enqueued for the homepage template in
 * inc/enqueue.php).
 *
 * @package montela
 * @subpackage Section
 * @since 1.5.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$gal_title   = get_field( 'section_gallery_title' );
$gal_text    = get_field( 'section_gallery_text' );
$gal_button  = get_field( 'section_gallery_button' );
$gal_image_1 = get_field( 'section_gallery_image_1' );
$gal_image_2 = get_field( 'section_gallery_image_2' );
$gal_items   = get_field( 'section_gallery_items' );
$gal_images  = get_field( 'section_gallery_images' );

if ( ! $gal_title && ! $gal_text && ! $gal_images ) {
	return;
}

$gal_title_id = wp_unique_id( 'section-gallery-title-' );

// Slides: auto width (set per slide in CSS), 4px apart on mobile, 23px above.
$gal_swiper = array(
	'slidesPerView' => 'auto',
	'spaceBetween'  => 4,
	'breakpoints'   => array(
		768 => array( 'spaceBetween' => 23 ),
	),
);
?>
<section class="section-gallery"<?php echo $gal_title ? ' aria-labelledby="' . esc_attr( $gal_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-gallery__grid">
			<?php if ( $gal_title ) : ?>
				<h2 id="<?php echo esc_attr( $gal_title_id ); ?>" class="title-h3 section-gallery__title">
					<?php echo wp_kses( $gal_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( $gal_image_1 ) : ?>
				<figure class="section-gallery__photo section-gallery__photo--1">
					<?php
					echo wp_get_attachment_image(
						$gal_image_1,
						'large',
						false,
						array(
							'class' => 'section-gallery__img',
							'sizes' => '(min-width: 1280px) 39vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $gal_text ) : ?>
				<p class="body-text section-gallery__text"><?php echo esc_html( $gal_text ); ?></p>
			<?php endif; ?>

			<?php if ( $gal_button ) : ?>
				<div class="section-gallery__button">
					<?php get_template_part( 'template-parts/components/button', null, array_merge( $gal_button, array( 'style' => 'secondary' ) ) ); ?>
				</div>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/components/icon-list',
				null,
				array(
					'items' => $gal_items,
					'class' => 'section-gallery__list',
				)
			);
			?>

			<?php if ( $gal_image_2 ) : ?>
				<figure class="section-gallery__photo section-gallery__photo--2">
					<?php
					echo wp_get_attachment_image(
						$gal_image_2,
						'large',
						false,
						array(
							'class' => 'section-gallery__img',
							'sizes' => '31vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $gal_images ) : ?>
		<div class="section-gallery__slider" data-swiper-root>
			<div class="swiper section-gallery__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $gal_swiper ) ); ?>">
				<div class="swiper-wrapper">
					<?php foreach ( $gal_images as $gal_image ) : ?>
						<div class="swiper-slide section-gallery__slide">
							<?php
							echo wp_get_attachment_image(
								$gal_image,
								'large',
								false,
								array(
									'class' => 'section-gallery__img',
									'sizes' => '(min-width: 1280px) 50vw, (min-width: 768px) 76vw, 56vw',
								)
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( count( $gal_images ) > 1 ) : ?>
				<div class="section-gallery__nav">
					<div class="theme-container">
						<div class="slider-nav section-gallery__nav-buttons">
							<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
							<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
