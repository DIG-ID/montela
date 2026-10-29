<?php
/**
 * Template Name: Stay Template
 *
 * Stay page ("Aufenthalt"): assembled from modules (template-parts/modules/),
 * whose fields are cloned into this page's field group
 * (acf-json/group_page_stay.json).
 *
 * Figma: desktop 5426:8346, tablet 5426:9261, mobile 5426:8673.
 *
 * @package montela
 * @subpackage Template
 * @since 1.24.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/stay/space' );
			get_template_part( 'template-parts/modules/services' );
			get_template_part( 'template-parts/modules/box-text' );
			get_template_part( 'template-parts/modules/testimonials' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
