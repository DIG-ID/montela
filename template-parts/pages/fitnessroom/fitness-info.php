<?php
/**
 * Fitnessroom — fitness info: the room at a glance. A small bold title
 * ("Auf einen Blick:"), a list of short lines with an icon (two columns on
 * desktop) and a photo beside them from tablet up, below them on mobile.
 *
 * Fields: "Fitness info" tab of acf-json/group_page_fitnessroom.json
 * (title, items repeater icon / text, image). The list is
 * components/icon-list.php. Figma: "Frame 1000006605" — desktop 5895:20037,
 * tablet 5895:20797, mobile 5895:20685. Layout as Tailwind classes here;
 * measurements and the list type in _pages/_fitnessroom/_fitness-info.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$fi_title = get_field( 'section_fitness_info_title' );
$fi_items = get_field( 'section_fitness_info_items' );
$fi_image = get_field( 'section_fitness_info_image' );

if ( ! $fi_title && ! $fi_items && ! $fi_image ) {
	return;
}

$fi_title_id = wp_unique_id( 'section-fitness-info-title-' );
?>
<section class="section-fitness-info pb-48 text-montela-walnut"<?php echo $fi_title ? ' aria-labelledby="' . esc_attr( $fi_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-6 md:grid-rows-[auto_1fr] md:gap-y-10 xl:gap-y-[68px]">
			<?php if ( $fi_title ) : ?>
				<h4 id="<?php echo esc_attr( $fi_title_id ); ?>" class="title-h4 section-fitness-info__title col-span-full md:col-span-3 xl:col-start-2 xl:col-span-6"><?php echo esc_html( $fi_title ); ?></h4>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/components/icon-list',
				null,
				array(
					'items'      => $fi_items,
					'class'      => 'section-fitness-info__list col-span-full self-start gap-[25px] md:col-span-3 md:gap-11 xl:col-start-2 xl:col-span-6 xl:grid xl:grid-cols-subgrid xl:gap-x-5 xl:gap-y-[57px]',
					'item_class' => array(
						'grid-cols-[37px_1fr] gap-x-[28px] items-center md:gap-x-[31px] xl:grid-cols-[52px_1fr] xl:gap-x-6 xl:col-start-1 xl:col-span-3',
						'grid-cols-[37px_1fr] gap-x-[28px] items-center md:gap-x-[31px] xl:grid-cols-[52px_1fr] xl:gap-x-6 xl:col-start-4 xl:col-span-3',
					),
				)
			);
			?>

			<?php if ( $fi_image ) : ?>
				<figure class="section-fitness-info__photo col-span-full m-0 overflow-hidden aspect-[329/246] md:col-start-4 md:col-span-3 md:row-start-1 md:row-span-2 md:self-start md:aspect-[340/512] xl:col-start-8 xl:col-span-4 xl:aspect-[660/556]">
					<?php
					echo wp_get_attachment_image(
						$fi_image,
						'large',
						false,
						array(
							'class' => 'section-fitness-info__img w-full h-full object-cover',
							'sizes' => '(min-width: 1280px) 31vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
