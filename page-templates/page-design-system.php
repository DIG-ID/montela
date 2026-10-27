<?php
/**
 * Template Name: Design System
 *
 * Living style guide: colours, typography classes and buttons, rendered
 * with the theme's real CSS and components. Meant for the team — publish
 * the page as Private (or noindex it in Yoast) so it never reaches search.
 *
 * @package montela
 * @subpackage Template
 * @since 1.3.0
 */

get_header();
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		do_action( 'before_main_content' );
		?>
		<div class="page-design-system">
			<div class="theme-container">
				<h1 class="title-h3 page-design-system__title"><?php the_title(); ?></h1>
			</div>
			<?php
			get_template_part( 'template-parts/pages/design-system/colors' );
			get_template_part( 'template-parts/pages/design-system/typography' );
			get_template_part( 'template-parts/pages/design-system/buttons' );
			?>
		</div>
		<?php
		do_action( 'after_main_content' );
	endwhile;
endif;
get_footer();
