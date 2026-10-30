<?php
/**
 * Homepage: "Premium Hotel Stay" — two photos, text, button, a list of
 * services with icons and a full-bleed photo slider.
 *
 * Fields: "Gallery" tab of acf-json/group_page_home.json. Figma:
 * section-gallery-desktop 5466:15471, tablet 5426:4383, mobile 5453:8475.
 * The slider is the shared components/photo-slider.php (Swiper module,
 * enqueued for the homepage template in inc/enqueue.php).
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

?>
<section class="section-gallery"<?php echo $gal_title ? ' aria-labelledby="' . esc_attr( $gal_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-gallery__grid">
			<?php if ( $gal_title ) : ?>
				<h3 id="<?php echo esc_attr( $gal_title_id ); ?>" class="title-h3 section-gallery__title">
					<?php echo wp_kses( $gal_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h3>
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

	<?php
	get_template_part(
		'template-parts/components/gallery-slider',
		null,
		array(
			'images' => $gal_images,
			'class'  => 'section-gallery__slider',
		)
	);
	?>
</section>
