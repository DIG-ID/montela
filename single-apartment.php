<?php
/**
 * Single Apartment (CPT apartment, below the Apartments page's URL): hero
 * (module), a "back to the overview" link, the facts bar, three photos, the
 * intro, the amenities ("Ausstattung:") and the gallery slider — then
 * "Inklusive Leistungen" (Box text, cream), the Services comparison and the
 * offer cards ("Für jede Auszeit das passende Angebot"). Each of those three
 * has a tab on the apartment; left empty, the Apartments page's is shown.
 *
 * Fields: acf-json/group_cpt_apartment.json; the shared sections in
 * acf-json/group_page_apartments.json. Figma: desktop 5426:7911, tablet
 * 5426:9736, mobile 5426:9146.
 *
 * @package montela
 * @subpackage Template
 * @since 1.32.0
 */

$apartment_overview = function_exists( 'montela_get_page_by_template' ) ? montela_get_page_by_template( 'page-templates/page-apartments.php' ) : null;
$apartment_fallback = $apartment_overview ? $apartment_overview->ID : null;

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part(
				'template-parts/components/back-link',
				null,
				array(
					'template' => 'page-templates/page-apartments.php',
					'class'    => 'mt-11 md:mt-[115px] xl:mt-[103px]',
				)
			);
			get_template_part( 'template-parts/apartment/facts' );
			get_template_part( 'template-parts/apartment/photos' );
			get_template_part( 'template-parts/apartment/intro' );
			get_template_part( 'template-parts/apartment/equipment' );
			get_template_part( 'template-parts/apartment/gallery' );

			// Each of these reads the apartment's own tab when it is filled
			// in, otherwise the Apartments page's.
			$apartment_sources = array(
				'included' => montela_section_source( get_the_ID(), array( 'section_box_text_title', 'section_box_text_text' ), $apartment_fallback ),
				'services' => montela_section_source( get_the_ID(), array( 'section_services_title', 'section_services_plans' ), $apartment_fallback ),
				'offers'   => montela_section_source( get_the_ID(), array( 'section_offer_cards_title', 'section_offer_cards_items' ), $apartment_fallback ),
			);

			if ( $apartment_sources['included'] ) :
				// Always the cream band. It has no space of its own above: on
				// mobile the gallery's arrows sit right over it.
				get_template_part(
					'template-parts/modules/box-text',
					null,
					array(
						'post_id' => $apartment_sources['included'],
						'style'   => 'cream',
						'class'   => 'mt-16 md:mt-0',
					)
				);
			endif;
			if ( $apartment_sources['services'] ) :
				get_template_part( 'template-parts/modules/services', null, array( 'post_id' => $apartment_sources['services'] ) );
			endif;
			if ( $apartment_sources['offers'] ) :
				get_template_part( 'template-parts/modules/offer-cards', null, array( 'post_id' => $apartment_sources['offers'] ) );
			endif;
			do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
