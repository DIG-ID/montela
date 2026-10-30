<?php
/**
 * "Zurück zur Übersicht": the sage back button (components/button.php,
 * style 'back') to the overview page of a single — found by its page
 * template, so slugs and WPML translations don't matter. No page with that
 * template, no link.
 *
 * Used by the offer single (template-parts/offer/intro.php) and the
 * apartment single. Figma: 5426:14464 (offer), 5426:7949 (apartment).
 *
 *     get_template_part(
 *         'template-parts/components/back-link',
 *         null,
 *         array(
 *             'template' => 'page-templates/page-offers.php',
 *             'class'    => 'mt-11 xl:mt-[103px]',
 *         )
 *     );
 *
 * Arguments ($args):
 * - template (string) Required. Page template of the overview page.
 * - class    (string) Optional. Extra classes for the wrapper (its margins).
 *
 * @package montela
 * @subpackage Component
 * @since 1.34.0
 */

if ( empty( $args['template'] ) || ! function_exists( 'montela_get_page_by_template' ) ) {
	return;
}

$back_page = montela_get_page_by_template( $args['template'] );

if ( ! $back_page ) {
	return;
}

$back_class = 'theme-container back-link' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<div class="<?php echo esc_attr( $back_class ); ?>">
	<?php
	get_template_part(
		'template-parts/components/button',
		null,
		array(
			'url'   => get_permalink( $back_page ),
			'title' => __( 'Zurück zur Übersicht', 'montela' ),
			'style' => 'back',
		)
	);
	?>
</div>
