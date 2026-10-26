<?php
/**
 * Enqueue scripts and styles.
 *
 * @package montela
 * @subpackage Functionality
 * @since 1.0.0
 */

if ( ! function_exists( 'montela_enqueue_fonts' ) ) :
	/**
	 * Enqueues web fonts based on the provider configured in functions.php.
	 * Supports 'google', 'adobe' (Typekit), or 'none'.
	 */
	function montela_enqueue_fonts() {
		$provider = defined( 'MONTELA_FONT_PROVIDER' ) ? MONTELA_FONT_PROVIDER : 'none';

		if ( 'google' === $provider && defined( 'MONTELA_GOOGLE_FONTS_URL' ) && MONTELA_GOOGLE_FONTS_URL ) {
			wp_enqueue_style(
				'montela-fonts',
				MONTELA_GOOGLE_FONTS_URL,
				array(),
				null
			);
		}

		if ( 'adobe' === $provider && defined( 'MONTELA_ADOBE_FONTS_ID' ) && MONTELA_ADOBE_FONTS_ID ) {
			wp_enqueue_style(
				'montela-fonts',
				'https://use.typekit.net/' . MONTELA_ADOBE_FONTS_ID . '.css',
				array(),
				null
			);
		}
	}
endif;

add_action( 'wp_enqueue_scripts', 'montela_enqueue_fonts' );

if ( ! function_exists( 'montela_font_resource_hints' ) ) :
	/**
	 * Adds preconnect resource hints for the configured font provider
	 * via the native WordPress resource hints API.
	 *
	 * @param array  $urls          Resource hint URLs.
	 * @param string $relation_type Hint type (preconnect, dns-prefetch, etc.).
	 * @return array
	 */
	function montela_font_resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' !== $relation_type ) {
			return $urls;
		}

		$provider = defined( 'MONTELA_FONT_PROVIDER' ) ? MONTELA_FONT_PROVIDER : 'none';

		if ( 'google' === $provider ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array(
				'href'        => 'https://fonts.gstatic.com',
				'crossorigin' => 'crossorigin',
			);
		}

		if ( 'adobe' === $provider ) {
			$urls[] = array(
				'href'        => 'https://use.typekit.net',
				'crossorigin' => 'crossorigin',
			);
		}

		return $urls;
	}
endif;

add_filter( 'wp_resource_hints', 'montela_font_resource_hints', 10, 2 );

if ( ! function_exists( 'montela_enqueue_google_maps' ) ) :
	/**
	 * Enqueues Google Maps API and init script on specific page templates.
	 * Requires MONTELA_GOOGLE_MAPS_API_KEY to be set in functions.php.
	 * Add page templates to $templates as needed per project.
	 */
	function montela_enqueue_google_maps() {
		if ( ! defined( 'MONTELA_GOOGLE_MAPS_API_KEY' ) || ! MONTELA_GOOGLE_MAPS_API_KEY ) {
			return;
		}

		$templates = array(
			// 'page-templates/page-contact.php',
		);

		if ( empty( $templates ) || ! is_page_template( $templates ) ) {
			return;
		}

		$theme_version = wp_get_theme()->get( 'Version' );

		wp_enqueue_script(
			'montela-google-maps-init',
			get_theme_file_uri( '/assets/js/google-maps.js' ),
			array(),
			$theme_version,
			true
		);

		wp_enqueue_script(
			'montela-google-maps-api',
			add_query_arg(
				array(
					'key'      => MONTELA_GOOGLE_MAPS_API_KEY,
					'callback' => 'initMap',
					'loading'  => 'async',
				),
				'https://maps.googleapis.com/maps/api/js'
			),
			array( 'montela-google-maps-init' ),
			null,
			true
		);
	}
endif;

add_action( 'wp_enqueue_scripts', 'montela_enqueue_google_maps' );

/**
 * Registers and enqueues the theme's main CSS and JS.
 *
 * Assets are resolved by inc/vite.php: hashed files from the build
 * manifest in production, or the Vite dev server (HMR) locally.
 */
function montela_enqueue_assets() {
	montela_vite_enqueue_style( 'theme-styles', 'assets/sass/main.sass' );
	montela_vite_enqueue_script( 'theme-scripts', 'assets/js/main.js', array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'montela_enqueue_assets' );
