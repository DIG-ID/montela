<?php
/**
 * Single Activity: assembled from modules (template-parts/modules/), whose
 * fields are cloned into the "Activity" field group
 * (acf-json/group_cpt_activity.json).
 *
 * Figma: desktop 5426:15217, tablet 5426:17534, mobile 5426:16593.
 *
 * @package montela
 * @subpackage Template
 * @since 1.9.0
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
