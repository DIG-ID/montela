<?php
/**
 * Anreise & Lage — location ("Direkt zu Montela"): a cream panel with the
 * title, the address and a Google Maps button beside an embedded Google
 * map (desktop); panel over the map below. Last section of the page: on
 * tablet and desktop it runs on behind the footer's booking box
 * (_arrival.sass).
 *
 * The design leaves the right half as a placeholder; it held a photo slider
 * until 1.48.0, now the map. With an API key (MONTELA_GOOGLE_MAPS_KEY in
 * wp-config.php) and the page's Latitude / Longitude, a Maps JavaScript API map
 * without the places of interest and with a Montela pin (since 1.54.0,
 * assets/js/modules/google-map.js; hovering, focusing or tapping the pin
 * shows the name and address); otherwise Google's embed iframe (no
 * key, no JS), lazy-loaded: the "Map" field takes the "Embed a map" code or
 * its src URL; empty, the map searches for the address. The iframe is also
 * the JS map's fallback when the key fails. Fields: tab "Location"
 * of acf-json/group_page_arrival.json. Figma "Next to footer_explore":
 * desktop 5769:16301, tablet 5769:16291, mobile 5769:16282.
 *
 * @package montela
 * @subpackage Section
 * @since 1.26.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$location_title   = get_field( 'section_location_title' );
$location_name    = get_field( 'section_location_name' );
$location_address = get_field( 'section_location_address' );
$location_button  = get_field( 'section_location_button' );
$location_map     = trim( (string) get_field( 'section_location_map' ) );

// The embed code pasted whole: keep only its src.
if ( preg_match( '/<iframe[^>]+src=["\']([^"\']+)["\']/i', $location_map, $location_match ) ) {
	$location_map = html_entity_decode( $location_match[1] );
}

// Only Google's embeds; anything else falls back to the address search.
if ( $location_map && ! preg_match( '#^https://(www\.)?google\.[a-z.]+/maps/#i', $location_map ) ) {
	$location_map = '';
}

// The address on one line, its line breaks as commas: the map searches for
// it (Google finds the street more reliably than the hotel's name), and the
// iframe's title names the place.
$location_lines = preg_split( '/<br\s*\/?>|\R/i', (string) $location_address );
$location_query = implode( ', ', array_filter( array_map( 'trim', array_map( 'wp_strip_all_tags', $location_lines ) ) ) );
$location_place = trim( wp_strip_all_tags( (string) $location_name ) );
// The same lines for the JS map's pin popup.
$location_address_lines = array_values( array_filter( array_map( 'trim', array_map( 'wp_strip_all_tags', $location_lines ) ) ) );
if ( ! $location_map && $location_query ) {
	$location_map = add_query_arg(
		array(
			'q'      => rawurlencode( $location_query ),
			'output' => 'embed',
		),
		'https://www.google.com/maps'
	);
}

if ( ! $location_title && ! $location_address && ! $location_map && ! is_numeric( get_field( 'section_location_lat' ) ) ) {
	return;
}

// The JS map: a key and both coordinates. The key is the
// MONTELA_GOOGLE_MAPS_KEY constant (wp-config.php). It still ends up in the
// page source — a browser key is public; what protects it is the
// HTTP-referrer and API restriction in Google Cloud.
$location_key = defined( 'MONTELA_GOOGLE_MAPS_KEY' ) ? trim( (string) MONTELA_GOOGLE_MAPS_KEY ) : '';
$location_lat = get_field( 'section_location_lat' );
$location_lng = get_field( 'section_location_lng' );
$location_js  = $location_key && is_numeric( $location_lat ) && is_numeric( $location_lng );
// Either map loads from Google only after consent (google-map.js), so the
// module runs for the iframe too.
if ( $location_js || $location_map ) {
	montela_enqueue_module( 'google-map' );
}

// Consent overlay: link to the privacy policy when one is set
// (Settings → Privacy).
$location_privacy = get_privacy_policy_url();

$location_title_id = wp_unique_id( 'section-location-title-' );
/* translators: %s: place name or address shown on the map. */
$location_map_label = sprintf( __( 'Karte: %s', 'montela' ), $location_place ? $location_place : ( $location_query ? $location_query : 'Montela' ) );
?>
<section class="section-location"<?php echo $location_title ? ' aria-labelledby="' . esc_attr( $location_title_id ) . '"' : ''; ?>>
	<div class="section-location__panel anim-fade-up">
		<?php if ( $location_title ) : ?>
			<h2 id="<?php echo esc_attr( $location_title_id ); ?>" class="title-h3 section-location__title"><?php echo montela_text( $location_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $location_name || $location_address ) : ?>
			<address class="section-location__address">
				<?php if ( $location_name ) : ?>
					<span class="section-location__name"><?php echo montela_text( $location_name ); ?></span>
				<?php endif; ?>
				<?php if ( $location_address ) : ?>
					<span class="section-location__lines"><?php echo montela_text( $location_address ); ?></span>
				<?php endif; ?>
			</address>
		<?php endif; ?>

		<?php
		if ( is_array( $location_button ) ) :
			get_template_part( 'template-parts/components/button', null, array_merge( $location_button, array( 'style' => 'outline' ) ) );
		endif;
		?>
	</div>

	<?php if ( $location_js || $location_map ) : ?>
		<div class="section-location__map anim-fade">
			<?php
			// Nothing from Google until consent: the JS map (key + coordinates)
			// or, without them, the embed iframe — google-map.js builds it
			// from data-fallback (no data-key = straight to the iframe).
			?>
			<?php
			$location_data = array( 'data-fallback' => esc_url( $location_map ) );
			if ( $location_js ) {
				$location_data += array(
					'data-key'      => $location_key,
					'data-lat'      => $location_lat,
					'data-lng'      => $location_lng,
					'data-language' => substr( determine_locale(), 0, 2 ),
					'data-name'     => $location_place,
					'data-address'  => wp_json_encode( $location_address_lines ),
				);
			}
			$location_attrs = '';
			foreach ( $location_data as $location_attr => $location_value ) {
				$location_attrs .= ' ' . $location_attr . '="' . esc_attr( $location_value ) . '"';
			}
			?>
			<div class="section-location__canvas" role="region" aria-label="<?php echo esc_attr( $location_map_label ); ?>" data-google-map<?php echo $location_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_attr() above. ?>></div>

			<div class="section-location__consent" data-map-consent>
				<p class="section-location__consent-text">
					<?php esc_html_e( 'Die Karte wird von Google Maps geladen. Dabei werden Daten an Google übertragen.', 'montela' ); ?>
					<?php if ( $location_privacy ) : ?>
						<a href="<?php echo esc_url( $location_privacy ); ?>"><?php esc_html_e( 'Mehr in der Datenschutzerklärung', 'montela' ); ?></a>
					<?php endif; ?>
				</p>
				<button class="btn btn-secondary section-location__consent-button" type="button" data-map-consent-accept>
					<span class="btn__label"><?php esc_html_e( 'Karte laden', 'montela' ); ?></span>
					<span class="btn__icon" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	<?php endif; ?>
</section>
