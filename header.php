<?php
/**
 * The header for the theme: outputs <head> and opens <body>.
 *
 * @package montela
 * @subpackage Core
 * @since 1.0.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<?php if ( in_array( wp_parse_url( home_url(), PHP_URL_HOST ), array( 'montela.ch', 'www.montela.ch' ), true ) ) : ?>
			<script async src="https://www.googletagmanager.com/gtm.js?id=GTM-NJ8PKLBK"></script><?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Google Tag Manager: Google asks for it right after <head>, before wp_head(); live domain only. ?>
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
			</script>
		<?php endif; ?>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php do_action( 'wp_body_open' ); ?>
		<?php get_template_part( 'template-parts/header', 'main' ); ?>
