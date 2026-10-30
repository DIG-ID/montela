<?php
/**
 * Template Name: Apartments Template
 *
 * Apartments overview ("Apartments", a child of Aufenthalt): modules
 * (template-parts/modules/), whose fields are cloned into this page's field
 * group (acf-json/group_page_apartments.json), and the apartment cards. The
 * apartment singles (CPT apartment) live below this page's URL.
 *
 * Figma: desktop 5426:7347, tablet 5426:9561, mobile 5426:8963.
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
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/apartments/cards' );
			get_template_part( 'template-parts/modules/services' );
			get_template_part( 'template-parts/modules/box-text' );
			get_template_part( 'template-parts/modules/testimonials' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
