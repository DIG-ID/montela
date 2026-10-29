<?php
/**
 * Template Name: Arrival Template
 *
 * Getting here page ("Anreise & Lage"): modules (template-parts/modules/)
 * and the page's own sections (template-parts/pages/arrival/). Fields:
 * acf-json/group_page_arrival.json — the modules' groups cloned (Features
 * twice, with prefixed field names), the page sections' fields in their
 * own tabs.
 *
 * Figma: desktop 5781:17203, tablet 5426:17808, mobile 5426:16896.
 *
 * @package montela
 * @subpackage Template
 * @since 1.26.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/modules/cards-icon' );
			get_template_part( 'template-parts/modules/features', null, array( 'prefix' => 'arrival_car_' ) );
			get_template_part( 'template-parts/modules/features', null, array( 'prefix' => 'arrival_train_' ) );
			get_template_part( 'template-parts/pages/arrival/luggage' );
			get_template_part( 'template-parts/pages/arrival/location' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
