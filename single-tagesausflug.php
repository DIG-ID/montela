<?php
/**
 * Single Tagesausflug (day trip, post type "tagesausflug"): hero and intro
 * (modules), "Über das Reiseziel" (photo, text and highlights) with a photo
 * slider, how to get there (Cards with icon module) and what to experience
 * (Explore module).
 *
 * Fields: acf-json/group_cpt_day_trip.json. Figma: desktop 5426:15059,
 * tablet 5426:17664, mobile 5426:16736.
 *
 * @package montela
 * @subpackage Template
 * @since 1.22.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/day-trip/destination' );
			get_template_part( 'template-parts/modules/cards-icon' );
			get_template_part( 'template-parts/modules/explore' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
