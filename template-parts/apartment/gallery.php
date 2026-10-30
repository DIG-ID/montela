<?php
/**
 * Single Apartment — the apartment's gallery as a full-bleed slider
 * (components/gallery-slider.php). The same photos slide on the apartment's
 * card on the Apartments page.
 *
 * Fields: tab "Gallery" of acf-json/group_cpt_apartment.json. Figma: desktop
 * 5812:15622, tablet 5534:8371, mobile 5534:8241. Space above: 45px
 * (mobile), 56 (tablet), 101 (desktop).
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

get_template_part(
	'template-parts/components/gallery-slider',
	null,
	array(
		'images' => get_field( 'section_apartment_gallery' ),
		'class'  => 'mt-[45px] md:mt-14 xl:mt-[101px]',
	)
);
