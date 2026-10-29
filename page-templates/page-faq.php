<?php
/**
 * Template Name: FAQ Template
 *
 * FAQ page: hero and intro (modules, cloned into acf-json/group_page_faq.json),
 * then the question groups (template-parts/pages/faq/groups.php — each one
 * drawn by the FAQ module, from a repeater on the page).
 *
 * Figma: desktop 5426:15899, tablet 5426:17907, mobile 5426:17003.
 *
 * @package montela
 * @subpackage Template
 * @since 1.29.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/faq/groups' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
