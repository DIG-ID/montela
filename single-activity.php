<?php
/**
 * Single Activity: assembled from modules (template-parts/modules/), whose
 * fields are cloned into the "Activity" field group
 * (acf-json/group_cpt_activity.json).
 *
 * The info cards are the post's own section (template-parts/activity/),
 * not a module.
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
			get_template_part( 'template-parts/modules/text-list', null, array( 'prefix' => 'activity_block_1_' ) );
			get_template_part( 'template-parts/modules/text-list', null, array( 'prefix' => 'activity_block_2_' ) );
			get_template_part( 'template-parts/modules/cards-icon' );
			get_template_part( 'template-parts/activity/info' );
			get_template_part(
				'template-parts/modules/activities',
				null,
				array(
					'seasons' => montela_get_activity_seasons( get_the_ID() ),
					'exclude' => get_the_ID(),
				)
			);
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
