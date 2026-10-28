<?php
/**
 * General-purpose helper functions.
 *
 * @package montela
 * @subpackage Helpers
 * @since 1.0.0
 */

/**
 * Outputs one or more values to the browser console.
 *
 * Development/debugging helper. No-op unless WP_DEBUG is enabled, so stray
 * calls can never leak data (queries, user info, API responses) to visitors
 * in production.
 *
 * @param mixed ...$data Values to log.
 */
function montela_console_log( ...$data ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	$json = wp_json_encode( $data );
	add_action(
		'shutdown',
		function () use ( $json ) {
			wp_print_inline_script_tag( "console.log({$json});" );
		}
	);
}

if ( ! function_exists( 'montela_get_season_activities' ) ) :
	/**
	 * Returns the published activities to show for a season.
	 *
	 * Takes the IDs an editor picked in a Relationship field; when none were
	 * picked, falls back to the season's activities (Season taxonomy term),
	 * in admin order. Used by the Activities module and the Aktivitäten page.
	 *
	 * @since 1.19.0
	 *
	 * @param array|false|null $picked Activity IDs from the Relationship field.
	 * @param string           $term   Season term slug: 'sommer' or 'winter'.
	 * @param int              $limit  Maximum number of activities; -1 for all.
	 * @return int[] Activity IDs.
	 */
	function montela_get_season_activities( $picked, $term, $limit = -1 ) {
		$ids = $picked ? (array) $picked : get_posts(
			array(
				'post_type'        => 'activity',
				'posts_per_page'   => $limit,
				'fields'           => 'ids',
				'orderby'          => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'suppress_filters' => false, // Let WPML keep the current language.
				'tax_query'        => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'season',
						'field'    => 'slug',
						'terms'    => $term,
					),
				),
			)
		);

		$ids = array_values(
			array_filter(
				array_map( 'intval', $ids ),
				function ( $id ) {
					return 'publish' === get_post_status( $id );
				}
			)
		);

		return $limit > 0 ? array_slice( $ids, 0, $limit ) : $ids;
	}
endif;

if ( ! function_exists( 'montela_get_page_by_template' ) ) :
	/**
	 * Returns the first published page that uses a page template, in the
	 * current language — e.g. the offers overview, for the "back" link of
	 * an offer. Lets the link survive slug changes and translations.
	 *
	 * @since 1.20.0
	 *
	 * @param string $template Template file, relative to the theme: 'page-templates/page-offers.php'.
	 * @return WP_Post|null The page, or null when none uses the template.
	 */
	function montela_get_page_by_template( $template ) {
		$pages = get_posts(
			array(
				'post_type'        => 'page',
				'post_status'      => 'publish',
				'posts_per_page'   => 1,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => false, // Let WPML keep the current language.
				'meta_key'         => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'       => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);

		return $pages ? $pages[0] : null;
	}
endif;
