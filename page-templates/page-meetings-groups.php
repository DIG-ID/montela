<?php
/**
 * Template Name: Meetings & Groups Template
 *
 * Meetings & Groups (child of Unser Resort): hero, intro, room info (photo,
 * text, list, button, photo strip), group details (two boxes) and the
 * Horizontal module. Fields: acf-json/group_page_meetings_groups.json, one
 * tab per section. Figma: desktop 5895:19941, tablet 5895:20752, mobile
 * 5895:20634.
 *
 * @package montela
 * @subpackage Template
 * @since 1.24.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			// Default (900px) child-page hero; the editor's "Full screen" toggle can enlarge it.
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/meetings-groups/room-info' );
			get_template_part( 'template-parts/pages/meetings-groups/group-details' );
			// "Apartments für Gruppen im Saastal" — the Horizontal module.
			get_template_part( 'template-parts/modules/horizontal' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
