<?php
/**
 * Bike Hotel — bike services: a big title, a short text, a portrait photo,
 * a wide photo and a two-column icon list ("Gut vorbereitet auf jeden
 * Trail": bike room, wash area, tools, charging…). Both photos are static.
 *
 *   Desktop  title from column 2; left (columns 1–4): text on columns 2–4
 *            over the portrait photo; right (columns 6–12): wide photo over the list
 *   Tablet   text beside the wide photo, portrait photo below the text,
 *            then the list across the full width
 *   Mobile   title, text, wide photo, list, portrait photo
 *
 * Fields: "Bike services" tab of acf-json/group_page_bike_hotel.json. Figma:
 * desktop 5895:19819, tablet 5895:20551, mobile 5895:20337. Layout as
 * Tailwind classes here; measurements and the remaining styles in
 * _pages/_bike-hotel/_bike-services.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$bs_title      = get_field( 'section_bike_services_title' );
$bs_text       = get_field( 'section_bike_services_text' );
$bs_image_tall = get_field( 'section_bike_services_image_tall' );
$bs_image_wide = get_field( 'section_bike_services_image_wide' );
$bs_items      = get_field( 'section_bike_services_items' );

if ( ! $bs_title && ! $bs_text && ! $bs_image_tall && ! $bs_image_wide && ! $bs_items ) {
	return;
}

$bs_title_id = wp_unique_id( 'section-bike-services-title-' );
?>
<section class="section-bike-services mb-32 md:mb-48 text-montela-walnut"<?php echo $bs_title ? ' aria-labelledby="' . esc_attr( $bs_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<?php if ( $bs_title ) : ?>
				<h2 id="<?php echo esc_attr( $bs_title_id ); ?>" class="title-h3 section-bike-services__title col-span-full max-w-[300px] md:max-w-none mb-9 md:mb-10 xl:mb-11 xl:col-start-2 xl:col-span-11"><?php echo esc_html( $bs_title ); ?></h2>
			<?php endif; ?>

			<div class="section-bike-services__body col-span-full grid grid-cols-1 gap-y-11 md:grid-cols-6 md:gap-x-5 md:gap-y-0 xl:grid-cols-12 xl:items-start">
				<div class="section-bike-services__col section-bike-services__col--left contents xl:grid xl:grid-cols-subgrid xl:col-span-4 xl:gap-y-[138px]">
					<?php if ( $bs_text ) : ?>
						<p class="body-text section-bike-services__text order-1 md:col-span-3 md:row-start-1 md:max-w-[343px] xl:col-start-2 xl:col-span-3 xl:max-w-none"><?php echo esc_html( $bs_text ); ?></p>
					<?php endif; ?>

					<?php if ( $bs_image_tall ) : ?>
						<figure class="section-bike-services__photo section-bike-services__photo--tall order-4 m-0 overflow-hidden aspect-[660/556] md:col-span-3 md:row-start-2 md:mt-5 md:aspect-auto md:h-[246px] xl:col-span-4 xl:mt-0 xl:h-auto xl:aspect-[3/4]">
							<?php
							echo wp_get_attachment_image(
								$bs_image_tall,
								'large',
								false,
								array(
									'class' => 'section-bike-services__img w-full h-full object-cover',
									'sizes' => '(min-width: 1280px) 31vw, (min-width: 768px) 45vw, 100vw',
								)
							);
							?>
						</figure>
					<?php endif; ?>
				</div>

				<div class="section-bike-services__col section-bike-services__col--right contents xl:grid xl:grid-cols-subgrid xl:col-start-6 xl:col-span-7 xl:gap-y-[101px]">
					<?php if ( $bs_image_wide ) : ?>
						<figure class="section-bike-services__photo section-bike-services__photo--wide order-2 m-0 overflow-hidden h-[246px] md:col-start-4 md:col-span-3 md:row-start-1 xl:col-span-7 xl:h-auto xl:aspect-[1002/632]">
							<?php
							echo wp_get_attachment_image(
								$bs_image_wide,
								'full',
								false,
								array(
									'class' => 'section-bike-services__img w-full h-full object-cover',
									'sizes' => '(min-width: 1280px) 55vw, (min-width: 768px) 45vw, 100vw',
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
							'items'      => $bs_items,
							'class'      => 'section-bike-services__list order-3 gap-9 md:col-span-6 md:row-start-3 md:mt-[88px] md:grid md:grid-cols-subgrid md:gap-x-5 md:gap-y-10 xl:col-span-7 xl:row-start-2 xl:mt-0 xl:gap-y-[72px]',
							'item_class' => array( 'md:col-span-3 xl:col-start-1', 'md:col-span-3 xl:col-start-5' ),
							'text_class' => 'max-w-[245px] md:max-w-none',
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
