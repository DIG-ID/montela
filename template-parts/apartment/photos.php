<?php
/**
 * Single Apartment — three photos: a large one on the left (columns 1–8 on
 * desktop, 1–4 on tablet) and two stacked on the right, filling its height
 * (the top one taller). Mobile shows the large photo only. Without the two
 * right photos, the large one takes the whole width.
 *
 * Fields: tab "Photos" of acf-json/group_cpt_apartment.json. Figma: desktop
 * 5426:7958, tablet 5534:8388, mobile 5534:8220. Measurements:
 * _posts/_apartment.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$photos_main = get_field( 'section_apartment_photo_1' );
$photos_side = array_values( array_filter( array( get_field( 'section_apartment_photo_2' ), get_field( 'section_apartment_photo_3' ) ) ) );

if ( ! $photos_main ) {
	return;
}

$photos_main_class = $photos_side ? 'col-span-full md:col-span-4 xl:col-span-8' : 'col-span-full';
?>
<div class="theme-container apartment-photos mt-11 xl:mt-[47px]">
	<div class="theme-grid">
		<figure class="apartment-photos__main <?php echo esc_attr( $photos_main_class ); ?>">
			<?php
			echo wp_get_attachment_image(
				$photos_main,
				'full',
				false,
				array(
					'class' => 'apartment-photos__img',
					'sizes' => $photos_side ? '(min-width: 1280px) 63vw, (min-width: 768px) 56vw, 100vw' : '100vw',
				)
			);
			?>
		</figure>

		<?php if ( $photos_side ) : ?>
			<div class="hidden md:flex md:flex-col md:col-span-2 xl:col-span-4 gap-y-[5px] xl:gap-y-3">
				<?php foreach ( $photos_side as $photos_index => $photos_id ) : ?>
					<figure class="apartment-photos__side <?php echo 0 === $photos_index ? 'flex-[516_1_0%]' : 'flex-[304_1_0%]'; ?>">
						<?php
						echo wp_get_attachment_image(
							$photos_id,
							'large',
							false,
							array(
								'class' => 'apartment-photos__img',
								'sizes' => '(min-width: 1280px) 31vw, 27vw',
							)
						);
						?>
					</figure>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
