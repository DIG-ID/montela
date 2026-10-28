<?php
/**
 * Single Offer ("Angebote & Packages"): hero (module), a "back to the
 * overview" link and the offer's intro (title, price box, text).
 *
 * Fields: acf-json/group_cpt_offer.json. Figma: desktop 5426:14430, tablet
 * 5426:17370, mobile 5426:16423.
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
			get_template_part( 'template-parts/offer/intro' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
