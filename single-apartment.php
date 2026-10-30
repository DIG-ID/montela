<?php
/**
 * Single Apartment (CPT apartment, below the Apartments page's URL): hero
 * (module). The other sections are still to be built.
 *
 * Fields: acf-json/group_cpt_apartment.json. Figma: desktop 5426:7911, tablet
 * 5426:9736, mobile 5426:9146.
 *
 * @package montela
 * @subpackage Template
 * @since 1.32.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
