<?php
/**
 * Single Apartment — the facts bar: size, bathrooms, bedrooms and occupancy
 * (components/apartment-facts.php) in sage, over a sage hairline. One
 * column on mobile, two on tablet (columns 1 and 4), one row on desktop
 * from column 2.
 *
 * Fields: tab "Facts" of acf-json/group_cpt_apartment.json. Figma: desktop
 * 5812:15633, tablet 5812:15644, mobile 5539:4763. Measurements:
 * _posts/_apartment.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

// No facts at all: no bar (the hairline would stand alone).
$bar_has_facts = false;
foreach ( array( 'size', 'bathrooms', 'bedrooms', 'occupancy' ) as $bar_fact ) {
	if ( '' !== (string) get_field( 'section_apartment_' . $bar_fact ) ) {
		$bar_has_facts = true;
		break;
	}
}

if ( ! $bar_has_facts ) {
	return;
}
?>
<div class="theme-container apartment-bar mt-11 md:mt-[37px] xl:mt-[57px]">
	<div class="theme-grid pb-11 md:pb-[18px] border-b border-montela-sage text-montela-sage">
		<?php
		get_template_part(
			'template-parts/components/apartment-facts',
			null,
			array(
				'post_id' => get_the_ID(),
				'class'   => 'col-span-full flex flex-col gap-y-3 md:grid md:grid-cols-2 md:grid-rows-2 md:grid-flow-col md:gap-x-5 md:gap-y-[15px] xl:flex xl:flex-row xl:gap-x-[76px] xl:col-start-2 xl:col-span-10',
			)
		);
		?>
	</div>
</div>
