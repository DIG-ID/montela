<?php
/**
 * Template Name: Contact Template
 *
 * Info & contact page ("Info & Kontakt"): modules (template-parts/modules/)
 * and the page's own sections (template-parts/pages/contact/). Fields:
 * acf-json/group_page_contact.json — the modules' groups cloned, the page
 * sections' fields in their own tabs.
 *
 * Figma: desktop 5426:15732, tablet 5426:18017, mobile 5426:17117.
 *
 * @package montela
 * @subpackage Template
 * @since 1.28.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/contact/info' );
			get_template_part( 'template-parts/pages/contact/form' );
			get_template_part( 'template-parts/modules/faq' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
