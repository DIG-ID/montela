<?php
/**
 * Template Name: Bike Hotel Template
 *
 * Bike Hotel (child of Unser Resort): hero, intro, bike services, green
 * band, bike experiences and local partners (the same section twice), then
 * the explore section. Fields: acf-json/group_page_bike_hotel.json, one tab
 * per section. Figma: desktop 5895:19816, tablet 5895:20546, mobile
 * 5895:20332.
 *
 * @package montela
 * @subpackage Template
 * @since 1.16.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			// Default (900px) child-page hero; the editor's "Full screen" toggle can enlarge it.
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/bike-hotel/bike-services' );
			get_template_part( 'template-parts/modules/green-text' );
			get_template_part( 'template-parts/pages/bike-hotel/bike-experiences', null, array( 'layout' => 'right' ) );
			get_template_part(
				'template-parts/pages/bike-hotel/bike-experiences',
				null,
				array(
					'fields' => 'bike_partners',
					'layout' => 'left',
				)
			);
			get_template_part( 'template-parts/modules/explore' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
