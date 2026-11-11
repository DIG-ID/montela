<?php
/**
 * Template Name: Services Template
 *
 * Leistungen / Services (a child of Aufenthalt): modules
 * (template-parts/modules/), whose fields are cloned into this page's field
 * group (acf-json/group_page_services.json): hero, intro and Space
 * ("Flexibel wohnen mit passenden Leistungen", columns layout) and Cards
 * with icon ("Lounge & Shop", with a button on each card); then "Frühstück"
 * (template-parts/pages/services/breakfast.php, this page only) and "Alles
 * direkt im Resort" (template-parts/pages/services/resort.php), then
 * Features ("Mehr Komfort nach Bedarf", centred layout) and Resort sections
 * (partner offers: Aqua Allalin, Magic Pass, outdoor clothing hire); last,
 * Box text ("Alles nah beieinander", cream style without a list).
 *
 * Figma: desktop 5426:6877, tablet 5426:9336, mobile 5426:8747.
 *
 * @package montela
 * @subpackage Template
 * @since 1.39.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			// No space above the title on this page (taken out on request).
			get_template_part( 'template-parts/modules/space', null, array( 'padding_top' => false ) );
			get_template_part( 'template-parts/modules/cards-icon' );
			get_template_part( 'template-parts/pages/services/breakfast' );
			get_template_part( 'template-parts/pages/services/resort' );
			get_template_part( 'template-parts/modules/features' );
			get_template_part( 'template-parts/modules/resort-sections' );
			get_template_part( 'template-parts/modules/box-text' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
