<?php
/**
 * Template Name: Activities Template
 *
 * Activities page ("Aktivitäten", a child of Erkunden): assembled from
 * modules (template-parts/modules/), whose fields are cloned into this
 * page's field group (acf-json/group_page_activities.json). Not to be
 * confused with the Activities post type and its single template.
 *
 * Figma: desktop 5426:14590, tablet 5426:17294, mobile 5426:16343.
 *
 * @package montela
 * @subpackage Template
 * @since 1.18.0
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
