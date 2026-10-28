<?php
/**
 * Template Name: Explore Template
 *
 * Explore page ("Erkunden"): assembled from modules (template-parts/modules/),
 * whose fields are cloned into this page's field group
 * (acf-json/group_page_explore.json).
 *
 * Figma: desktop 5426:16171, tablet 5426:17252, mobile 5426:16296.
 *
 * @package montela
 * @subpackage Template
 * @since 1.15.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
