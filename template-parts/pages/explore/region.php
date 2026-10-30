<?php
/**
 * Erkunden — region section: title, text and button with up to three
 * photos ("Saas-Grund: echt, ruhig und gut erreichbar").
 *
 * Desktop: the text beside a large photo, two more photos staggered below.
 * Tablet: title on top, text beside photo 1, photo 2 below. Mobile: all
 * stacked. The large photo is desktop only (agreed with the client).
 *
 * Fields: tab "Region" of acf-json/group_page_explore.json. Figma: desktop
 * 5767:15887, tablet 5767:15926, mobile 5550:10758.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$region_title  = get_field( 'section_region_title' );
$region_text   = get_field( 'section_region_text' );
$region_button = get_field( 'section_region_button' );
$region_large  = get_field( 'section_region_image_large' );
$region_photo1 = get_field( 'section_region_image_1' );
$region_photo2 = get_field( 'section_region_image_2' );

if ( ! $region_title && ! $region_text ) {
	return;
}

$region_title_id = wp_unique_id( 'section-region-title-' );

// Photo slot (class modifier) => [ attachment ID, sizes ]. The large photo
// is display: none below desktop, so lazy loading never fetches it there.
$region_photos = array(
	'large'  => array( $region_large, '(min-width: 1280px) 56vw, 1px' ),
	'photo1' => array( $region_photo1, '(min-width: 1280px) 39vw, (min-width: 768px) 340px, 100vw' ),
	'photo2' => array( $region_photo2, '(min-width: 1280px) 540px, (min-width: 768px) 344px, 100vw' ),
);
?>
<section class="section-region"<?php echo $region_title ? ' aria-labelledby="' . esc_attr( $region_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-region__grid">
			<div class="section-region__copy">
				<?php if ( $region_title ) : ?>
					<h3 id="<?php echo esc_attr( $region_title_id ); ?>" class="title-h3 section-region__title"><?php echo wp_kses( $region_title, array( 'br' => array( 'class' => array() ) ) ); ?></h3>
				<?php endif; ?>

				<div class="section-region__body">
					<?php if ( $region_text ) : ?>
						<p class="body-text section-region__text"><?php echo esc_html( $region_text ); ?></p>
					<?php endif; ?>

					<?php
					if ( $region_button ) :
						get_template_part( 'template-parts/components/button', null, array_merge( $region_button, array( 'style' => 'secondary' ) ) );
					endif;
					?>
				</div>
			</div>

			<?php
			foreach ( $region_photos as $region_slot => $region_photo ) :
				if ( ! $region_photo[0] ) {
					continue;
				}
				?>
				<figure class="section-region__photo section-region__photo--<?php echo esc_attr( $region_slot ); ?>">
					<?php
					echo wp_get_attachment_image(
						$region_photo[0],
						'large' === $region_slot ? 'full' : 'large',
						false,
						array(
							'class' => 'section-region__img',
							'sizes' => $region_photo[1],
						)
					);
					?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
