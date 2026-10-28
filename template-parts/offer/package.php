<?php
/**
 * Single Offer — "Im Package enthalten": title and the list of what the
 * package includes (icon, bold first line, text), a photo beside it on
 * desktop, then the full-bleed photo slider.
 *
 * Desktop: photo on columns 1–4, title and a two-column list on 6–12.
 * Tablet and mobile: title and a one-column list; no photo.
 *
 * Fields: tabs "Included" and "Gallery" of acf-json/group_cpt_offer.json.
 * List: components/icon-list.php. Slider: components/gallery-slider.php.
 * Figma: desktop 5833:15950, tablet 5607:23359, mobile 5607:23136.
 *
 * @package montela
 * @subpackage Section
 * @since 1.21.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$pkg_title  = get_field( 'section_offer_package_title' );
$pkg_image  = get_field( 'section_offer_package_image' );
$pkg_items  = get_field( 'section_offer_package_items' );
$pkg_images = get_field( 'section_offer_gallery' );

if ( ! $pkg_items && ! $pkg_images ) {
	return;
}

$pkg_title_id = wp_unique_id( 'section-offer-package-title-' );
?>
<section class="section-offer-package"<?php echo $pkg_title ? ' aria-labelledby="' . esc_attr( $pkg_title_id ) . '"' : ''; ?>>
	<?php if ( $pkg_items ) : ?>
		<div class="theme-container">
			<div class="theme-grid section-offer-package__grid">
				<?php if ( $pkg_image ) : ?>
					<figure class="section-offer-package__photo">
						<?php
						// Desktop only: display: none below, so lazy loading never fetches it there.
						echo wp_get_attachment_image(
							$pkg_image,
							'large',
							false,
							array(
								'class' => 'section-offer-package__img',
								'sizes' => '(min-width: 1280px) 32vw, 1px',
							)
						);
						?>
					</figure>
				<?php endif; ?>

				<div class="section-offer-package__content">
					<?php if ( $pkg_title ) : ?>
						<h2 id="<?php echo esc_attr( $pkg_title_id ); ?>" class="title-h3 section-offer-package__title"><?php echo esc_html( $pkg_title ); ?></h2>
					<?php endif; ?>

					<?php
					get_template_part(
						'template-parts/components/icon-list',
						null,
						array(
							'items' => $pkg_items,
							'class' => 'section-offer-package__list',
						)
					);
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php
	get_template_part(
		'template-parts/components/gallery-slider',
		null,
		array(
			'images' => $pkg_images,
			'class'  => 'section-offer-package__slider',
		)
	);
	?>
</section>
