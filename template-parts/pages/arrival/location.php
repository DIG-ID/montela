<?php
/**
 * Anreise & Lage — location ("Direkt zu Montela"): a cream panel with the
 * title, the address and a Google Maps button beside an embedded Google
 * map (desktop); panel over the map below. Last section of the page: on
 * tablet and desktop it runs on behind the footer's booking box
 * (_arrival.sass).
 *
 * The design leaves the right half as a placeholder; it held a photo slider
 * until 1.48.0, now the map. Google's embed iframe (no API key, no JS),
 * lazy-loaded: the "Map" field takes the "Embed a map" code or its src URL;
 * empty, the map searches for the address. Fields: tab "Location"
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
if ( ! $location_map && $location_query ) {
	$location_map = add_query_arg(
		array(
			'q'      => rawurlencode( $location_query ),
			'output' => 'embed',
		),
		'https://www.google.com/maps'
	);
}

if ( ! $location_title && ! $location_address && ! $location_map ) {
	return;
}

$location_title_id = wp_unique_id( 'section-location-title-' );
/* translators: %s: place name or address shown on the map. */
$location_map_label = sprintf( __( 'Karte: %s', 'montela' ), $location_place ? $location_place : ( $location_query ? $location_query : 'Montela' ) );
?>
<section class="section-location"<?php echo $location_title ? ' aria-labelledby="' . esc_attr( $location_title_id ) . '"' : ''; ?>>
	<div class="section-location__panel">
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

	<?php if ( $location_map ) : ?>
		<div class="section-location__map">
			<iframe class="section-location__iframe" src="<?php echo esc_url( $location_map ); ?>" title="<?php echo esc_attr( $location_map_label ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
		</div>
	<?php endif; ?>
</section>
