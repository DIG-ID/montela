<?php
/**
 * Template Name: Shop Template
 *
 * Shop (child of Unser Resort): hero, intro, shop info (text + photo, photo
 * strip, opening hours box), products slider and producers slider. The
 * resort map that ends the Figma page is postponed. Fields:
 * acf-json/group_page_shop.json, one tab per section. Figma: desktop
 * 5895:19770, tablet 5895:20724, mobile 5895:20610.
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
			get_template_part( 'template-parts/pages/shop/shop-info' );
			get_template_part( 'template-parts/pages/shop/shop-products' );
			get_template_part( 'template-parts/pages/shop/shop-producers' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
