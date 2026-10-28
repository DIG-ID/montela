<?php
/**
 * Template Name: Our Region Template
 *
 * Our Region page ("Unsere Region", a child of Erkunden): assembled from
 * modules (template-parts/modules/), whose fields are cloned into this
 * page's field group (acf-json/group_page_region.json).
 *
 * Figma: desktop 5426:14763, tablet 5426:17416, mobile 5426:16474.
 *
 * @package montela
 * @subpackage Template
 * @since 1.17.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/region/place' );
			get_template_part( 'template-parts/modules/cards-icon' );
			// Map section and "Ausflüge ab Saas-Grund" (image cards from a post type
			// still to be created): skipped for now.
			get_template_part( 'template-parts/modules/box-text' );
			get_template_part( 'template-parts/modules/activities' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
