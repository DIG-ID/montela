<?php
/**
 * Template Name: Fitnessroom Template
 *
 * Fitnessroom (child of Unser Resort): hero, intro, fitness info (list with
 * icons and a photo), the Cards with icon module (filled style) and fitness
 * usage (photo strip, usage boxes, button). Fields:
 * acf-json/group_page_fitnessroom.json, one tab per section. Figma: desktop
 * 5895:19995, tablet 5895:20780, mobile 5895:20662.
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
			get_template_part( 'template-parts/modules/hero' );
			get_template_part( 'template-parts/modules/intro' );
			get_template_part( 'template-parts/pages/fitnessroom/fitness-info' );
			// "Trainieren während Ihrer Ferien": 128px below on desktop and 369px
			// cards, as drawn. The arrows hide when every card is in view (Bruno,
			// 2026-11-10 — the design shows them disabled). The text sits lower,
			// as in the frame-style cards (Bruno, 2026-11-10).
			get_template_part(
				'template-parts/modules/cards-icon',
				null,
				array(
					'class'   => 'mb-[128px] md:mb-[192px] xl:mb-[128px]',
					'width'   => 'fixed',
					'spacing' => 'frame',
				)
			);
			get_template_part( 'template-parts/pages/fitnessroom/fitness-usage' );
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
