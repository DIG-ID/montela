<?php
/**
 * Single Tagesausflug — "Über das Reiseziel": title, text, a photo and the
 * destination's highlights (icon, bold title, text), then the full-bleed
 * photo slider.
 *
 * Desktop: photo on columns 1–6, title and text on 8–12; the highlights in
 * two columns on 6–12 below. Tablet and mobile: title, text, photo,
 * highlights (one column), all stacked.
 *
 * Fields: tabs "Destination" and "Gallery" of acf-json/group_cpt_day_trip.json.
 * List: components/icon-list.php. Slider: components/gallery-slider.php.
 * Figma: desktop 5787:17868, tablet 5593:20515, mobile 5593:20546.
 *
 * @package montela
 * @subpackage Section
 * @since 1.22.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$dest_title  = get_field( 'section_destination_title' );
$dest_text   = get_field( 'section_destination_text' );
$dest_image  = get_field( 'section_destination_image' );
$dest_items  = get_field( 'section_destination_items' );
$dest_images = get_field( 'section_destination_gallery' );

if ( ! $dest_title && ! $dest_text && ! $dest_items && ! $dest_images ) {
	return;
}

$dest_title_id = wp_unique_id( 'section-destination-title-' );
?>
<section class="section-destination"<?php echo $dest_title ? ' aria-labelledby="' . esc_attr( $dest_title_id ) . '"' : ''; ?>>
	<?php if ( $dest_title || $dest_text || $dest_image || $dest_items ) : ?>
		<div class="theme-container">
			<div class="theme-grid section-destination__grid">
				<?php if ( $dest_title ) : ?>
					<h2 id="<?php echo esc_attr( $dest_title_id ); ?>" class="title-h3 section-destination__title"><?php echo esc_html( $dest_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $dest_text ) : ?>
					<div class="body-text section-destination__text"><?php echo wp_kses_post( $dest_text ); ?></div>
				<?php endif; ?>

				<?php if ( $dest_image ) : ?>
					<figure class="section-destination__photo">
						<?php
						echo wp_get_attachment_image(
							$dest_image,
							'large',
							false,
							array(
								'class' => 'section-destination__img',
								'sizes' => '(min-width: 1280px) 47vw, 100vw',
							)
						);
						?>
					</figure>
				<?php endif; ?>

				<?php
				get_template_part(
					'template-parts/components/icon-list',
					null,
					array(
						'items' => $dest_items,
						'class' => 'section-destination__list',
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	get_template_part(
		'template-parts/components/gallery-slider',
		null,
		array(
			'images' => $dest_images,
			'class'  => 'section-destination__slider',
		)
	);
	?>
</section>
