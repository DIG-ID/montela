<?php
/**
 * Template Name: Lounge & Bar Template
 *
 * Lounge & Bar (child of Unser Resort): hero, intro and lounge info (box
 * with photo, photo strip, opening hours). Fields:
 * acf-json/group_page_lounge_bar.json, one tab per section. Figma: desktop
 * 5895:20851, tablet 5895:20470, mobile 5895:20247.
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
			get_template_part( 'template-parts/pages/lounge-bar/lounge-info' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
