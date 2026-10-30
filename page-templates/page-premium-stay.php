<?php
/**
 * Template Name: Premium Stay Template
 *
 * Premium Stay ("Montela Premium Hotel Stay", a child of Aufenthalt):
 * modules (template-parts/modules/), whose fields are cloned into this
 * page's field group (acf-json/group_page_premium_stay.json): hero, intro,
 * services comparison, Text with photos (staggered on desktop) and Cards
 * with icon ("Lage & Resort", filled style).
 *
 * Figma: desktop 5426:7103, tablet 5426:9487, mobile 5426:8886.
 *
 * @package montela
 * @subpackage Template
 * @since 1.36.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/modules/services' );
			get_template_part( 'template-parts/modules/text-photos' );
			get_template_part( 'template-parts/modules/cards-icon' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
