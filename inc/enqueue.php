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
			// The kit CSS @imports a tracking stylesheet from this host.
			$urls[] = 'https://p.typekit.net';
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
			array( 'jquery' ),
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

if ( ! function_exists( 'montela_enqueue_module' ) ) :
	/**
	 * Enqueues an optional front-end module (JS plus its CSS), built as a
	 * separate Vite entry so it only loads where it is used and can be
	 * delayed by WP Rocket independently of the critical bundle.
	 *
	 * For modules rendered above the fold, call it on wp_enqueue_scripts
	 * (see montela_enqueue_assets()) so the CSS is printed in <head>. Calling
	 * it from a template part also works, but the CSS is then printed in the
	 * footer, which may cause a flash of unstyled content.
	 *
	 * @since 1.1.0
	 *
	 * @param string $module Module name: 'swiper', 'fancybox', 'isotope' or 'activities'.
	 * @return bool True if the module was enqueued.
	 */
	function montela_enqueue_module( $module ) {
		$modules = array(
			'swiper'     => 'assets/js/modules/swiper.js',
			'fancybox'   => 'assets/js/modules/fancybox.js',
			'isotope'    => 'assets/js/modules/isotope.js',
			'activities' => 'assets/js/modules/activities.js',
		);

		if ( ! isset( $modules[ $module ] ) ) {
			_doing_it_wrong(
				__FUNCTION__,
				/* translators: %s: module name. */
				sprintf( esc_html__( 'Unknown theme module "%s".', 'montela' ), esc_html( $module ) ),
				'1.1.0'
			);

			return false;
		}

		return montela_vite_enqueue_script( 'montela-' . $module, $modules[ $module ] );
	}
endif;

/**
 * Registers and enqueues the theme's main CSS and JS.
 *
 * Assets are resolved by inc/vite.php: hashed files from the build
 * manifest in production, or the Vite dev server (HMR) locally.
 */
function montela_enqueue_assets() {
	montela_vite_enqueue_style( 'theme-styles', 'assets/sass/main.sass' );

	// Critical bundle — keep it small and library-free. The only theme JS
	// that may be excluded from WP Rocket's "Delay JavaScript execution".
	montela_vite_enqueue_script( 'theme-scripts', 'assets/js/main.js' );

	// Non-critical: GSAP, ScrollTrigger and Lenis. Safe to delay with WP Rocket.
	montela_vite_enqueue_script( 'theme-animations', 'assets/js/animations.js' );

	// Optional modules — enqueue only where they are used.
	if ( is_page_template( 'page-templates/page-home.php' ) ) {
		montela_enqueue_module( 'swiper' ); // Gallery, Explore and Testimonials sliders.
	}
	if ( is_page_template( 'page-templates/page-region.php' ) ) {
		montela_enqueue_module( 'swiper' ); // Cards with icon and day trips sliders.
	}
	if ( is_singular( array( 'offer', 'tagesausflug' ) ) ) {
		montela_enqueue_module( 'swiper' ); // Gallery slider (+ Cards with icon, Explore on day trips).
	}
	if ( is_page_template( 'page-templates/page-montela-way.php' ) ) {
		montela_enqueue_module( 'swiper' ); // Timeline slider.
	}
	if ( is_singular( 'activity' ) ) {
		montela_enqueue_module( 'swiper' ); // Cards with icon slider.
	}
	if ( is_page_template( array( 'page-templates/page-offers.php', 'page-templates/page-stay.php' ) ) ) {
		montela_enqueue_module( 'swiper' ); // Services comparison (+ Testimonials on Stay) sliders.
	}
}

add_action( 'wp_enqueue_scripts', 'montela_enqueue_assets' );
