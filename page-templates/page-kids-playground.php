<?php
/**
 * Template Name: Kids Playground Template
 *
 * Kids Playground (child of Unser Resort): hero, intro, playground info
 * (photo, features, photo strip, opening hours) and the holiday section.
 * Fields: acf-json/group_page_kids_playground.json, one tab per section.
 * Figma: desktop 5895:19871, tablet 5895:20503, mobile 5895:20279.
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
			get_template_part( 'template-parts/pages/kids-playground/playground-info' );
			// Holiday: "Ferien mit Kindern im Saastal" — the Horizontal module.
			get_template_part( 'template-parts/modules/horizontal' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
