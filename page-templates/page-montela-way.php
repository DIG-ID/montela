<?php
/**
 * Template Name: Montela Way Template
 *
 * The Montela Way page ("Der Montela Weg"): modules (template-parts/modules/)
 * and the page's own sections (template-parts/pages/montela-way/). Fields:
 * acf-json/group_page_montela_way.json — the modules' groups cloned, the
 * page sections' fields in their own tabs.
 *
 * Figma: desktop 5426:15417, tablet 5426:17754, mobile 5426:16837.
 *
 * @package montela
 * @subpackage Template
 * @since 1.25.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/montela-way/values' );
			get_template_part( 'template-parts/pages/montela-way/history' );
			get_template_part( 'template-parts/pages/montela-way/timeline' );
			get_template_part( 'template-parts/pages/montela-way/future' );
			get_template_part( 'template-parts/pages/montela-way/closing' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
