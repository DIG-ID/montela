<?php
/**
 * Structured data (JSON-LD), added to Yoast SEO's schema graph through its
 * Schema API — never printed by hand in wp_head.
 *
 * FAQ page (page-templates/page-faq.php): the WebPage piece also becomes an
 * FAQPage, with every question of the page's groups as a Question and its
 * Answer (mainEntity). Only that page: the FAQ teaser on Info & Kontakt
 * repeats some of the same questions, and the full list is the one to mark
 * up.
 *
 * @link https://developer.yoast.com/features/schema/api/
 *
 * @package montela
 * @subpackage Functionality
 * @since 1.29.0
 */

if ( ! function_exists( 'montela_get_faq_page_questions' ) ) :
	/**
	 * Returns the questions of the FAQ page, from all its groups, as
	 * schema.org Question objects.
	 *
	 * @since 1.29.0
	 *
	 * @param int $post_id Page ID.
	 * @return array[] Question objects; empty when it isn't the FAQ page or has no questions.
	 */
	function montela_get_faq_page_questions( $post_id ) {
		if ( ! function_exists( 'get_field' ) || 'page-templates/page-faq.php' !== get_page_template_slug( $post_id ) ) {
			return array();
		}

		$questions = array();

		foreach ( (array) get_field( 'section_faq_groups', $post_id ) as $group ) {
			foreach ( (array) ( isset( $group['items'] ) ? $group['items'] : array() ) as $item ) {
				$name   = isset( $item['question'] ) ? trim( wp_strip_all_tags( $item['question'] ) ) : '';
				$answer = isset( $item['answer'] ) ? trim( wp_strip_all_tags( $item['answer'] ) ) : '';

				if ( ! $name || ! $answer ) {
					continue;
				}

				$questions[] = array(
					'@type'          => 'Question',
					'name'           => $name,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $answer,
					),
				);
			}
		}

		return $questions;
	}
endif;

/**
 * Adds FAQPage to the WebPage piece's types on the FAQ page.
 *
 * @since 1.29.0
 *
 * @param string|string[] $type The WebPage piece's type(s).
 * @return string|string[]
 */
function montela_schema_faq_page_type( $type ) {
	if ( ! is_singular( 'page' ) || ! montela_get_faq_page_questions( get_queried_object_id() ) ) {
		return $type;
	}

	$types = (array) $type;
	if ( ! in_array( 'FAQPage', $types, true ) ) {
		$types[] = 'FAQPage';
	}

	return $types;
}
add_filter( 'wpseo_schema_webpage_type', 'montela_schema_faq_page_type' );

/**
 * Adds the FAQ page's questions to the WebPage piece (mainEntity).
 *
 * @since 1.29.0
 *
 * @param array $data The WebPage piece.
 * @return array
 */
function montela_schema_faq_page_questions( $data ) {
	if ( ! is_singular( 'page' ) ) {
		return $data;
	}

	$questions = montela_get_faq_page_questions( get_queried_object_id() );
	if ( $questions ) {
		$data['mainEntity'] = $questions;
	}

	return $data;
}
add_filter( 'wpseo_schema_webpage', 'montela_schema_faq_page_questions' );
