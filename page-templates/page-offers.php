<?php
/**
 * Template Name: Offers Template
 *
 * Offers & Packages page ("Angebote & Packages"): assembled from modules
 * (template-parts/modules/), whose fields are cloned into this page's field
 * group (acf-json/group_page_offers.json).
 *
 * Figma: desktop 5426:14165, tablet 5426:18068, mobile 5426:17172.
 *
 * @package montela
 * @subpackage Template
 * @since 1.20.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/offers/cards' );
			get_template_part( 'template-parts/modules/services' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
