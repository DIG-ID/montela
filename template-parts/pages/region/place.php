<?php
/**
 * Unsere Region — "Saas-Grund" section: title, text, two photos and a list
 * of short lines with icons.
 *
 * Desktop: photo 1 beside the title and text, then the list beside photo 2.
 * Tablet: title, text, photo 1, list, photo 2. Mobile: photo 1 comes before
 * the text (CSS order).
 *
 * Fields: tab "Saas-Grund" of acf-json/group_page_region.json. Figma:
 * desktop 5780:17201, tablet 5585:5978, mobile 5585:6173.
 *
 * @package montela
 * @subpackage Section
 * @since 1.17.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$place_title   = get_field( 'section_place_title' );
$place_text    = get_field( 'section_place_text' );
$place_image_1 = get_field( 'section_place_image_1' );
$place_image_2 = get_field( 'section_place_image_2' );
$place_items   = get_field( 'section_place_items' );

if ( ! $place_title && ! $place_text ) {
	return;
}

$place_title_id = wp_unique_id( 'section-place-title-' );
?>
<section class="section-place"<?php echo $place_title ? ' aria-labelledby="' . esc_attr( $place_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-place__grid">
			<?php if ( $place_title ) : ?>
				<h2 id="<?php echo esc_attr( $place_title_id ); ?>" class="title-h3 section-place__title"><?php echo esc_html( $place_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $place_text ) : ?>
				<p class="body-text section-place__text"><?php echo esc_html( $place_text ); ?></p>
			<?php endif; ?>

			<?php if ( $place_image_1 ) : ?>
				<figure class="section-place__photo section-place__photo--1">
					<?php
					echo wp_get_attachment_image(
						$place_image_1,
						'large',
						false,
						array(
							'class' => 'section-place__img',
							'sizes' => '(min-width: 1280px) 39vw, 100vw',
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
					'items' => $place_items,
					'class' => 'section-place__list',
				)
			);
			?>

			<?php if ( $place_image_2 ) : ?>
				<figure class="section-place__photo section-place__photo--2">
					<?php
					echo wp_get_attachment_image(
						$place_image_2,
						'large',
						false,
						array(
							'class' => 'section-place__img',
							'sizes' => '(min-width: 1280px) 35vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
