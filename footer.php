<?php
/**
 * The footer for the theme: closes the page and prints wp_footer.
 *
 * @package montela
 * @subpackage Core
 * @since 1.0.0
 */

get_template_part( 'template-parts/footer', 'main' );

/*
 * Sticky CTA hidden for now (Bruno, 2026-11-24). To bring it back, move this
 * call out of the comment; its fields stay in Theme Settings → Sticky CTA,
 * and main.js skips it while it is not on the page.
 *
 * get_template_part( 'template-parts/sticky-cta' );
 */
?>
		<?php wp_footer(); ?>
	</body>

</html>