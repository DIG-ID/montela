<?php
/**
 * Theme functions and definitions.
 *
 * @package montela
 * @subpackage Core
 * @since 1.0.0
 */

// Theme constants: safe defaults only. Each can be overridden per
// environment in wp-config.php — real API keys belong there (outside the
// repo), never committed to the theme. See SETUP.md.

// Font provider configuration — set per project.
// Options: 'google' | 'adobe' | 'none'.
if ( ! defined( 'MONTELA_FONT_PROVIDER' ) ) {
	define( 'MONTELA_FONT_PROVIDER', 'adobe' );
}
if ( ! defined( 'MONTELA_GOOGLE_FONTS_URL' ) ) {
	define( 'MONTELA_GOOGLE_FONTS_URL', '' ); // Unused: fonts come from Adobe Fonts.
}
if ( ! defined( 'MONTELA_ADOBE_FONTS_ID' ) ) {
	define( 'MONTELA_ADOBE_FONTS_ID', 'eju2iqq' ); // FreightBig Pro + Roboto.
}

// Google Maps API key — define the real key in wp-config.php. Empty = disabled.
if ( ! defined( 'MONTELA_GOOGLE_MAPS_API_KEY' ) ) {
	define( 'MONTELA_GOOGLE_MAPS_API_KEY', '' );
}

// Theme setup: supports, nav menus, sidebars, plugin filters.
require get_template_directory() . '/inc/theme-setup.php';

// Vite asset loader (manifest in production, dev server with HMR locally).
require get_template_directory() . '/inc/vite.php';

// Scripts and styles.
require get_template_directory() . '/inc/enqueue.php';

// Admin and login customisations.
require get_template_directory() . '/inc/theme-admin-settings.php';

// Template tags and reusable output functions.
require get_template_directory() . '/inc/theme-template-tags.php';

// Helper/utility functions.
require get_template_directory() . '/inc/helpers.php';

// Performance optimizations.
require get_template_directory() . '/inc/performance.php';

// Security hardening.
require get_template_directory() . '/inc/security.php';
