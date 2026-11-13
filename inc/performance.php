<?php
/**
 * Performance optimizations: dequeue unused styles/scripts, clean head, disable unused features.
 *
 * @package montela
 * @subpackage Functionality
 * @since 1.0.0
 */

/**
 * Dequeues styles that are not needed on the frontend.
 * Runs at priority 100 to ensure it fires after all other enqueue hooks.
 */
function montela_dequeue_unused_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_enqueue_scripts', 'montela_dequeue_unused_styles', 100 );

/**
 * Dequeues the wp-embed script — embedding WP posts in external sites is rarely needed.
 */
function montela_dequeue_unused_scripts() {
	wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'montela_dequeue_unused_scripts' );

/**
 * Removes unnecessary links and meta tags from wp_head.
 */
function montela_clean_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}

add_action( 'init', 'montela_clean_head' );

/**
 * Disables the WordPress emoji detection script and styles.
 */
function montela_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'montela_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'montela_disable_emojis_dns_prefetch', 10, 2 );
}

add_action( 'init', 'montela_disable_emojis' );

/**
 * Removes the wpemoji TinyMCE plugin.
 *
 * @param array $plugins Active TinyMCE plugins.
 * @return array
 */
function montela_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	return array();
}

/**
 * Removes the emoji CDN URL from DNS prefetch hints.
 *
 * @param array  $urls          Resource hint URLs.
 * @param string $relation_type Hint type (dns-prefetch, preconnect, etc.).
 * @return array
 */
function montela_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls          = array_diff( $urls, array( $emoji_svg_url ) );
	}
	return $urls;
}

/**
 * Prevents WordPress from pinging its own URLs (self pingbacks).
 *
 * @param array $links List of URLs to ping.
 */
function montela_disable_self_pingbacks( &$links ) {
	foreach ( $links as $l => $link ) {
		if ( str_starts_with( $link, get_option( 'home' ) ) ) {
			unset( $links[ $l ] );
		}
	}
}

add_action( 'pre_ping', 'montela_disable_self_pingbacks' );


/**
 * Lazy-loads every image but the ones the theme marks as eager (the hero).
 *
 * WordPress leaves the first 3 images of a page without loading="lazy",
 * assuming they're above the fold. Here the hero fills the screen, so the
 * images after it are below the fold: on the homepage the two Text with
 * photos images were downloaded up front. The hero sets loading="eager" and
 * fetchpriority="high" itself (modules/hero.php), which this doesn't touch.
 *
 * @since 1.60.4
 *
 * @return int Number of images to leave without lazy-loading.
 */
function montela_omit_loading_attr_threshold() {
	return 0;
}

add_filter( 'wp_omit_loading_attr_threshold', 'montela_omit_loading_attr_threshold' );

/**
 * Whether the current page shows a Contact Form 7 form: the Info & Kontakt
 * template (pages/contact/form.php), or a page with the [contact-form-7]
 * shortcode in its content. Filterable: `montela_page_has_cf7`.
 *
 * @since 1.60.4
 *
 * @return bool
 */
function montela_page_has_cf7() {
	$has_form = is_page_template( 'page-templates/page-contact.php' );

	if ( ! $has_form && is_singular() ) {
		$post     = get_post();
		$has_form = $post && has_shortcode( $post->post_content, 'contact-form-7' );
	}

	return (bool) apply_filters( 'montela_page_has_cf7', $has_form );
}

/**
 * Loads Contact Form 7's CSS and JS — and reCAPTCHA's, when the integration
 * is on — only on pages with a form (montela_page_has_cf7()).
 *
 * CF7 loads them on every page by default; its reCAPTCHA module enqueues the
 * Google script on every page too, regardless of wpcf7_load_js, so its
 * action is removed separately. Runs on `wp`: the query is known, and
 * wp_enqueue_scripts hasn't fired yet.
 *
 * @since 1.60.4
 */
function montela_cf7_only_where_used() {
	if ( ! defined( 'WPCF7_VERSION' ) || is_admin() || montela_page_has_cf7() ) {
		return;
	}

	add_filter( 'wpcf7_load_js', '__return_false' );
	add_filter( 'wpcf7_load_css', '__return_false' );
	remove_action( 'wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20 );
}

add_action( 'wp', 'montela_cf7_only_where_used' );
