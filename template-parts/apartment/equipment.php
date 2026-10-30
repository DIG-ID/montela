<?php
/**
 * Single Apartment — "Ausstattung:": the apartment's amenities (Amenity
 * terms, each with its icon) as an icon list (components/icon-list.php),
 * beside a photo on desktop.
 *
 * The list is filled column by column, in the design's order
 * (montela_get_apartment_amenities(), inc/helpers.php): one column on
 * mobile; two on tablet (columns 1–3 and 4–6) and desktop (2–4 and 5–7,
 * the photo on 8–11). No photo below desktop, as in the design.
 *
 * Fields: tab "Equipment" of acf-json/group_cpt_apartment.json; the
 * amenities are ticked in the sidebar, their icons set on each term
 * (acf-json/group_taxonomy_amenity.json). Figma: desktop 5812:15627, tablet
 * 5534:8386, mobile 5539:4733. Measurements: _posts/_apartment.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) || ! function_exists( 'montela_get_apartment_amenities' ) ) {
	return;
}

$equip_items = array();
foreach ( montela_get_apartment_amenities( get_the_ID() ) as $equip_term ) {
	$equip_items[] = array(
		'icon' => get_field( 'amenity_icon', $equip_term ),
		'text' => $equip_term->name,
	);
}

if ( ! $equip_items ) {
	return;
}

$equip_title = get_field( 'section_apartment_equipment_title' );
if ( ! $equip_title ) {
	$equip_title = __( 'Ausstattung:', 'montela' );
}
$equip_image = get_field( 'section_apartment_equipment_image' );

// Rows per column from tablet up: the list fills the first column first.
$equip_rows     = (int) ceil( count( $equip_items ) / 2 );
$equip_title_id = wp_unique_id( 'section-apartment-equipment-title-' );
?>
<section class="theme-container section-apartment-equipment mt-11 md:mt-14 xl:mt-[77px] text-montela-walnut" aria-labelledby="<?php echo esc_attr( $equip_title_id ); ?>" style="--equipment-rows: <?php echo esc_attr( $equip_rows ); ?>">
	<div class="theme-grid gap-y-6 md:gap-y-11 xl:gap-y-[50px]">
		<h2 id="<?php echo esc_attr( $equip_title_id ); ?>" class="title-h4 col-span-full xl:col-start-2 xl:col-span-6 xl:row-start-1"><?php echo esc_html( $equip_title ); ?></h2>

		<?php
		get_template_part(
			'template-parts/components/icon-list',
			null,
			array(
				'items'      => $equip_items,
				'class'      => 'col-span-full grid grid-cols-subgrid gap-y-4 md:grid-flow-col md:grid-rows-[repeat(var(--equipment-rows),auto)] md:gap-y-[30px] xl:gap-y-4 xl:col-start-2 xl:col-span-6 xl:row-start-2 xl:self-start',
				'item_class' => 'col-span-full md:col-span-3 grid-cols-[50px_1fr] gap-x-4 items-center xl:grid-cols-[68px_1fr] xl:gap-x-[17px]',
			)
		);
		?>

		<?php if ( $equip_image ) : ?>
			<figure class="section-apartment-equipment__photo hidden xl:block xl:col-start-8 xl:col-span-4 xl:row-start-1 xl:row-span-2">
				<?php
				echo wp_get_attachment_image(
					$equip_image,
					'large',
					false,
					array(
						'class' => 'section-apartment-equipment__img',
						'sizes' => '31vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>
	</div>
</section>
