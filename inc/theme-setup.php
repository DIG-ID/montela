<?php
/**
 * Theme setup: theme supports, nav menus, sidebars, and plugin integrations.
 *
 * @package montela
 * @subpackage Functionality
 * @since 1.0.0
 */

/**
 * Registers nav menus and theme supports.
 */
function montela_theme_setup() {

	register_nav_menus(
		array(
			'main-menu'   => __( 'Main Menu', 'montela' ),
			'mega-menu'   => __( 'Mega Menu', 'montela' ),
			'footer-menu' => __( 'Footer Menu', 'montela' ),
			'legal-menu'  => __( 'Legal Menu', 'montela' ),
		)
	);

	add_theme_support( 'menus' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
}

add_action( 'after_setup_theme', 'montela_theme_setup' );

/**
 * Registers widgetized areas.
 */
function montela_register_sidebars() {

	register_sidebar(
		array(
			'name'          => __( 'Footer', 'montela' ),
			'id'            => 'footer',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Header Language Switcher', 'montela' ),
			'id'            => 'header_ls',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
}

add_action( 'widgets_init', 'montela_register_sidebars' );

/**
 * Removes auto <p> wrapping from Contact Form 7 fields.
 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

if ( ! function_exists( 'montela_acf_google_maps_key' ) ) :
	/**
	 * Sets the Google Maps API key for ACF map fields.
	 * Requires MONTELA_GOOGLE_MAPS_API_KEY to be set in functions.php.
	 */
	function montela_acf_google_maps_key() {
		if ( defined( 'MONTELA_GOOGLE_MAPS_API_KEY' ) && MONTELA_GOOGLE_MAPS_API_KEY ) {
			acf_update_setting( 'google_api_key', MONTELA_GOOGLE_MAPS_API_KEY );
		}
	}
endif;

add_action( 'acf/init', 'montela_acf_google_maps_key' );

/**
 * Lowers Yoast SEO metabox priority so ACF fields appear above it.
 *
 * @return string
 */
function montela_lower_yoast_metabox_priority() {
	return 'core';
}

add_filter( 'wpseo_metabox_prio', 'montela_lower_yoast_metabox_priority' );

if ( ! function_exists( 'montela_header_dark' ) ) :
	/**
	 * Whether the header is drawn dark (walnut on cream) — for pages with no
	 * hero image under it. For now only the 404 page.
	 *
	 * @since 1.30.0
	 *
	 * @return bool
	 */
	function montela_header_dark() {
		return (bool) apply_filters( 'montela_header_dark', is_404() );
	}
endif;

/**
 * Adds the `header-dark` body class where the header is drawn dark
 * (montela_header_dark(); styles in _layout/_header.sass).
 *
 * @since 1.30.0
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function montela_header_dark_body_class( $classes ) {
	if ( montela_header_dark() ) {
		$classes[] = 'header-dark';
	}

	return $classes;
}

add_filter( 'body_class', 'montela_header_dark_body_class' );
