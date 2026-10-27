<?php
/**
 * Template Name: Home Template
 *
 * Homepage: assembled from modules (template-parts/modules/), whose fields
 * are cloned into this page's field group.
 *
 * @package montela
 * @subpackage Template
 * @since 1.0.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/home/find-apartment' );
			get_template_part( 'template-parts/pages/home/direct-book' );
			get_template_part( 'template-parts/pages/home/gallery' );
			// Map section: design not final yet.
			get_template_part( 'template-parts/modules/explore' );
			get_template_part( 'template-parts/modules/image-band' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
