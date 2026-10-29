<?php
/**
 * Template Name: Impressum Template
 *
 * Impressum page: the title on the sage band (template-parts/pages/
 * page-header.php), then boxes with an icon, a title and a few lines
 * (template-parts/pages/imprint/boxes.php). No editor content: the fields
 * are in acf-json/group_page_imprint.json.
 *
 * Figma: desktop 5426:5833, tablet 5617:26635, mobile 5617:26598.
 *
 * @package montela
 * @subpackage Template
 * @since 1.31.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/pages/page-header' );
			get_template_part( 'template-parts/pages/imprint/boxes' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
