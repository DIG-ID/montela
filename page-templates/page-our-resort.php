<?php
/**
 * Template Name: Our Resort Template
 *
 * Our Resort page: assembled from modules (template-parts/modules/), whose
 * fields are cloned into this page's field group (group_page_our_resort).
 * Figma: Our Resort desktop 5895:20065, tablet 5895:20381, mobile 5895:20157.
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
			// Full-screen hero, same as the homepage's.
			get_template_part( 'template-parts/modules/hero', null, array( 'variant' => 'home' ) );
			get_template_part( 'template-parts/modules/intro' );
			// Resort map section: postponed.
			get_template_part( 'template-parts/modules/resort-sections' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
