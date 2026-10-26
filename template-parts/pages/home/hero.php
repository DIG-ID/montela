<?php
/**
 * Hero Section in the Home Page Template.
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

?>
<section class="section-hero">
	<div class="theme-container">
		<?php
		// Image display example (ACF image field returning an ID).
		// Guarded so the page still renders while ACF is not active.
		$img_id = function_exists( 'get_field' ) ? get_field( 'hero_image' ) : null;
		if ( $img_id ) :
			echo wp_get_attachment_image(
				$img_id,
				'full',
				false,
				array(
					'class'         => 'w-full h-auto object-cover',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
		endif;
		?>
	</div>
</section>
