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
 * The business: Yoast's Organization piece also becomes a Hotel, with the
 * address, phone, e-mail and social profiles of the Theme Settings footer.
 * Yoast only outputs that piece when Settings → Site representation has an
 * organization name and logo — without them there is no Hotel either.
 *
 * Singles, one node each, read from the post's own fields: an offer is an
 * Offer (price, validity), an apartment an Apartment (size, rooms,
 * occupancy, amenities), an activity or a day trip a TouristAttraction.
 * Offers and apartments point at the Hotel when Yoast outputs it.
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

if ( ! function_exists( 'montela_schema_text' ) ) :
	/**
	 * Plain text for a schema property: tags and entities out, whitespace
	 * collapsed, at most 60 words.
	 *
	 * @since 1.40.0
	 *
	 * @param string $text Text or HTML.
	 * @return string
	 */
	function montela_schema_text( $text ) {
		$text = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES, 'UTF-8' );
		$text = trim( preg_replace( '/\s+/u', ' ', $text ) );

		return '' === $text ? '' : wp_trim_words( $text, 60, '…' );
	}
endif;

if ( ! function_exists( 'montela_get_schema_address' ) ) :
	/**
	 * Returns the business address as a schema.org PostalAddress, read from
	 * the footer address of the Theme Settings: the line with the postcode
	 * ("3910 Saas-Grund") gives postcode and town, the line above it the
	 * street; lines before that (the business name) are skipped.
	 *
	 * @since 1.40.0
	 *
	 * @return array PostalAddress; empty when no line has a Swiss postcode.
	 */
	function montela_get_schema_address() {
		$raw   = function_exists( 'get_field' ) ? (string) get_field( 'footer_address', 'option' ) : '';
		$raw   = wp_strip_all_tags( preg_replace( '/<br\s*\/?>/i', "\n", $raw ) );
		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) );

		foreach ( $lines as $index => $line ) {
			if ( ! preg_match( '/^(?:CH-)?(\d{4})\s+(.+)$/u', $line, $match ) ) {
				continue;
			}

			$address = array(
				'@type'           => 'PostalAddress',
				'postalCode'      => $match[1],
				'addressLocality' => $match[2],
				'addressCountry'  => 'CH',
			);
			if ( $index > 0 ) {
				$address['streetAddress'] = $lines[ $index - 1 ];
			}

			return $address;
		}

		return array();
	}
endif;

/**
 * Makes Yoast's Organization piece a Hotel too, with the contact details of
 * the Theme Settings footer.
 *
 * @since 1.40.0
 *
 * @param array $data The Organization piece.
 * @return array
 */
function montela_schema_organization( $data ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $data;
	}

	$data['@type'] = array_values( array_unique( array_merge( (array) $data['@type'], array( 'Hotel' ) ) ) );

	$address = montela_get_schema_address();
	if ( $address ) {
		$data['address'] = $address;
	}

	// "+41 (0) 27 957 70 00": the trunk zero doesn't belong in an international number.
	$phone = trim( (string) get_field( 'footer_phone', 'option' ) );
	if ( $phone ) {
		$data['telephone'] = trim( preg_replace( '/\s*\(0\)\s*/', ' ', $phone ) );
	}

	$email = trim( (string) get_field( 'footer_email', 'option' ) );
	if ( is_email( $email ) ) {
		$data['email'] = $email;
	}

	$profiles = array_filter( array( get_field( 'socials_facebook', 'option' ), get_field( 'socials_instagram', 'option' ) ) );
	if ( $profiles ) {
		$same_as        = isset( $data['sameAs'] ) ? (array) $data['sameAs'] : array();
		$data['sameAs'] = array_values( array_unique( array_merge( $same_as, $profiles ) ) );
	}

	return $data;
}
add_filter( 'wpseo_schema_organization', 'montela_schema_organization' );

if ( ! function_exists( 'montela_schema_price' ) ) :
	/**
	 * Splits a price as typed in the offer ("CHF 799", "CHF 1'290.–",
	 * "CHF 89.50") into a schema.org number and a currency code.
	 *
	 * @since 1.40.0
	 *
	 * @param string $text Price text.
	 * @return array {
	 *     @type string $price    Number, "." as decimal separator.
	 *     @type string $currency ISO 4217 code; CHF when the text has none.
	 * } Empty when the text has no number.
	 */
	function montela_schema_price( $text ) {
		$text   = (string) $text;
		$digits = str_replace( array( "'", '’', ' ', "\xc2\xa0" ), '', $text );
		if ( ! preg_match( '/\d[\d.,]*/', $digits, $match ) ) {
			return array();
		}

		$number = rtrim( $match[0], '.,' );
		if ( preg_match( '/^(.*)[.,](\d{1,2})$/', $number, $parts ) ) {
			$number = str_replace( array( '.', ',' ), '', $parts[1] ) . '.' . $parts[2];
		} else {
			$number = str_replace( array( '.', ',' ), '', $number );
		}

		return array(
			'price'    => $number,
			'currency' => preg_match( '/\b([A-Z]{3})\b/', $text, $code ) ? $code[1] : 'CHF',
		);
	}
endif;

if ( ! function_exists( 'montela_schema_single' ) ) :
	/**
	 * The properties every single's node shares: type, @id, name, URL,
	 * description, image and the page it is the main entity of.
	 *
	 * Description: the excerpt, else Yoast's meta description, else the
	 * intro text of the page. Image: the featured image, else the hero's.
	 *
	 * @since 1.40.0
	 *
	 * @param string $type        schema.org type.
	 * @param int    $post_id     Post ID.
	 * @param object $context     Yoast's Meta_Tags_Context.
	 * @param string $intro_field Field with the post's intro text.
	 * @return array
	 */
	function montela_schema_single( $type, $post_id, $context, $intro_field ) {
		$url  = $context->canonical ? $context->canonical : get_permalink( $post_id );
		$data = array(
			'@type'            => $type,
			'@id'              => $url . '#' . strtolower( $type ),
			'name'             => montela_schema_text( get_the_title( $post_id ) ),
			'url'              => $url,
			'mainEntityOfPage' => array( '@id' => $context->main_schema_id ),
		);

		$descriptions = array(
			has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : '',
			$context->description,
			get_field( $intro_field, $post_id ),
		);
		foreach ( $descriptions as $description ) {
			$description = montela_schema_text( $description );
			if ( $description ) {
				$data['description'] = $description;
				break;
			}
		}

		$image_id  = get_post_thumbnail_id( $post_id );
		$image_id  = $image_id ? $image_id : get_field( 'section_hero_image', $post_id );
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : '';
		if ( $image_url ) {
			$data['image'] = $image_url;
		}

		return $data;
	}
endif;

if ( ! function_exists( 'montela_schema_offer' ) ) :
	/**
	 * An offer as a schema.org Offer: "Ab" price per unit and validity.
	 *
	 * @since 1.40.0
	 *
	 * @param int    $post_id Offer ID.
	 * @param object $context Yoast's Meta_Tags_Context.
	 * @return array
	 */
	function montela_schema_offer( $post_id, $context ) {
		$data  = montela_schema_single( 'Offer', $post_id, $context, 'section_offer_intro_text' );
		$price = montela_schema_price( get_field( 'section_offer_price', $post_id ) );

		if ( $price ) {
			$data['price']         = $price['price'];
			$data['priceCurrency'] = $price['currency'];

			// "Ab CHF 799 / Person": a starting price, per the unit typed in the offer.
			$data['priceSpecification'] = array(
				'@type'         => 'UnitPriceSpecification',
				'minPrice'      => $price['price'],
				'priceCurrency' => $price['currency'],
			);
			$unit                       = trim( (string) get_field( 'section_offer_price_unit', $post_id ), " /\t\n\r" );
			if ( $unit ) {
				$data['priceSpecification']['unitText'] = $unit;
			}
		}

		$dates = array(
			'validFrom'    => 'section_offer_valid_from',
			'validThrough' => 'section_offer_valid_to',
		);
		foreach ( $dates as $property => $field ) {
			$raw  = get_field( $field, $post_id );
			$date = $raw ? DateTime::createFromFormat( 'Ymd', $raw ) : false;
			if ( $date ) {
				$data[ $property ] = $date->format( 'Y-m-d' );
			}
		}

		if ( 'company' === $context->site_represents ) {
			$data['offeredBy'] = $context->site_represents_reference;
		}

		return $data;
	}
endif;

if ( ! function_exists( 'montela_schema_apartment' ) ) :
	/**
	 * An apartment as a schema.org Apartment: size, rooms, occupancy and
	 * amenities, inside the Hotel.
	 *
	 * @since 1.40.0
	 *
	 * @param int    $post_id Apartment ID.
	 * @param object $context Yoast's Meta_Tags_Context.
	 * @return array
	 */
	function montela_schema_apartment( $post_id, $context ) {
		$data = montela_schema_single( 'Apartment', $post_id, $context, 'section_apartment_intro_text' );

		$size = get_field( 'section_apartment_size', $post_id );
		if ( is_numeric( $size ) && $size > 0 ) {
			$data['floorSize'] = array(
				'@type'    => 'QuantitativeValue',
				'value'    => 0 + $size,
				'unitCode' => 'MTK',
				'unitText' => 'm²',
			);
		}

		$rooms = array(
			'numberOfBedrooms'       => 'section_apartment_bedrooms',
			'numberOfBathroomsTotal' => 'section_apartment_bathrooms',
		);
		foreach ( $rooms as $property => $field ) {
			$count = get_field( $field, $post_id );
			if ( is_numeric( $count ) && $count > 0 ) {
				$data[ $property ] = (int) $count;
			}
		}

		// "4-5 Personen" → 4 to 5 guests; "4 Personen" → up to 4.
		if ( preg_match_all( '/\d+/', (string) get_field( 'section_apartment_occupancy', $post_id ), $guests ) && $guests[0] ) {
			$guests            = array_map( 'intval', $guests[0] );
			$data['occupancy'] = array( '@type' => 'QuantitativeValue' );
			if ( count( $guests ) > 1 ) {
				$data['occupancy']['minValue'] = min( $guests );
			}
			$data['occupancy']['maxValue'] = max( $guests );
		}

		$amenities = function_exists( 'montela_get_apartment_amenities' ) ? montela_get_apartment_amenities( $post_id ) : array();
		foreach ( $amenities as $amenity ) {
			$data['amenityFeature'][] = array(
				'@type' => 'LocationFeatureSpecification',
				'name'  => montela_schema_text( $amenity->name ),
				'value' => true,
			);
		}

		if ( 'company' === $context->site_represents ) {
			$data['containedInPlace'] = $context->site_represents_reference;
		}

		return $data;
	}
endif;

/**
 * Adds the node of an offer, apartment, activity or day trip single to
 * Yoast's graph.
 *
 * @since 1.40.0
 *
 * @param array  $graph   Schema graph.
 * @param object $context Yoast's Meta_Tags_Context.
 * @return array
 */
function montela_schema_singles( $graph, $context ) {
	if ( ! function_exists( 'get_field' ) || empty( $context->indexable ) || 'post' !== $context->indexable->object_type ) {
		return $graph;
	}

	$post_id = (int) $context->indexable->object_id;

	switch ( $context->indexable->object_sub_type ) {
		case 'offer':
			$graph[] = montela_schema_offer( $post_id, $context );
			break;

		case 'apartment':
			$graph[] = montela_schema_apartment( $post_id, $context );
			break;

		case 'activity':
		case 'tagesausflug':
			$graph[] = montela_schema_single( 'TouristAttraction', $post_id, $context, 'section_intro_text' );
			break;
	}

	return $graph;
}
add_filter( 'wpseo_schema_graph', 'montela_schema_singles', 10, 2 );
