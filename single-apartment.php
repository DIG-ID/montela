<?php
/**
 * Single Apartment (CPT apartment, below the Apartments page's URL): hero
 * (module), a "back to the overview" link, the facts bar, three photos, the
 * intro, the amenities ("Ausstattung:") and the gallery slider — then three
 * sections shared by every apartment, read from the Apartments page:
 * "Inklusive Leistungen" (Box text), the Services comparison and the offer
 * cards ("Für jede Auszeit das passende Angebot").
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
		if ( $apartment_overview ) :
			// The cream band has no space of its own above: on mobile the
			// gallery's arrows sit right over it.
			get_template_part(
				'template-parts/modules/box-text',
				null,
				array(
					'post_id' => $apartment_overview->ID,
					'class'   => 'mt-16 md:mt-0',
				)
			);
			get_template_part( 'template-parts/modules/services', null, array( 'post_id' => $apartment_overview->ID ) );
			get_template_part( 'template-parts/modules/offer-cards', null, array( 'post_id' => $apartment_overview->ID ) );
		endif;
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
