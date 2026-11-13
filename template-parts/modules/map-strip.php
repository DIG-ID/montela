<?php
/**
 * Map strip module: a full-width crop of the illustrated map, with its pins
 * and popups and the summer / winter switch — no zoom, no pan.
 *
 * The same for every page: the illustrations and the pins are set once in
 * Theme Settings, tab Map strip (map_strip_image_* / map_strip_pins_* —
 * each one empty = the Map tab's, map_image_* / map_pins_*). The whole
 * illustration is laid out in a square "plane" and the strip shows a crop
 * of it, chosen per breakpoint in _map-strip.sass. The pins keep their %
 * coordinates on the plane, so any pin inside the crop shows up;
 * map-strip.js hides the ones outside it (or under the footer's box). No
 * page fields: a template adds it with
 * get_template_part( 'template-parts/modules/map-strip' ).
 *
 * Each season is a layer (artwork + pins); the switch — the Map module's,
 * top right here — cross-fades them. Without a winter map, no switch.
 *
 * Hover (mouse) or click / tap / keyboard turns the pin mustard and opens a
 * sage card with its title, text and button, with its corner on the pin.
 *
 * Figma: Kids Playground desktop 5895:19876 (in 5895:19871) — summer only,
 * no switch. Tablet and mobile are not drawn: the crop narrows around the
 * resort.
 *
 * @package montela
 * @subpackage Module
 * @since 1.56.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

// Season => Season term slug and fallback label (as in the Map module).
$strip_seasons = array(
	'summer' => array(
		'term'  => 'sommer',
		'label' => __( 'Sommer', 'montela' ),
	),
	'winter' => array(
		'term'  => 'winter',
		'label' => __( 'Winter', 'montela' ),
	),
);

foreach ( $strip_seasons as $strip_key => $strip_season ) {
	// Its own illustration and pins; each one left empty falls back to the Map tab's.
	$strip_image = (int) get_field( 'map_strip_image_' . $strip_key, 'option' );
	$strip_image = $strip_image ? $strip_image : (int) get_field( 'map_image_' . $strip_key, 'option' );

	if ( ! $strip_image ) {
		unset( $strip_seasons[ $strip_key ] );
		continue;
	}

	$strip_rows = get_field( 'map_strip_pins_' . $strip_key, 'option' );
	$strip_rows = $strip_rows ? $strip_rows : get_field( 'map_pins_' . $strip_key, 'option' );

	$strip_seasons[ $strip_key ]['image'] = $strip_image;
	$strip_seasons[ $strip_key ]['pins']  = array_values(
		array_filter(
			(array) $strip_rows,
			static function ( $strip_pin ) {
				return is_array( $strip_pin ) && '' !== (string) $strip_pin['x'] && '' !== (string) $strip_pin['y'];
			}
		)
	);

	// Switch label: the Season term's name, so it follows the site language.
	$strip_term = get_term_by( 'slug', $strip_season['term'], 'season' );
	if ( $strip_term ) {
		$strip_term = get_term( apply_filters( 'wpml_object_id', $strip_term->term_id, 'season', true ), 'season' );
	}
	if ( $strip_term && ! is_wp_error( $strip_term ) ) {
		$strip_seasons[ $strip_key ]['label'] = $strip_term->name;
	}
}

if ( ! $strip_seasons ) {
	return;
}

// The season open on page load: winter from December to April, as in the
// Map and Activities modules.
$strip_current = in_array( (int) wp_date( 'n' ), array( 12, 1, 2, 3, 4 ), true ) ? 'winter' : 'summer';
if ( ! isset( $strip_seasons[ $strip_current ] ) ) {
	$strip_current = array_key_first( $strip_seasons );
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'map-strip' );
}

$strip_uid      = wp_unique_id( 'section-map-strip-' );
$strip_title_id = $strip_uid . '-title';
$strip_popup_id = $strip_uid . '-popup';

// The plane is about one screen wide on desktop, three on mobile.
$strip_image_attr = array(
	'class'   => 'section-map-strip__image',
	'alt'     => '',
	'loading' => 'lazy',
	'sizes'   => '(min-width: 1280px) 103vw, (min-width: 768px) 180vw, 300vw',
);
?>
<section class="section-map-strip" aria-labelledby="<?php echo esc_attr( $strip_title_id ); ?>" data-map-strip>
	<h2 id="<?php echo esc_attr( $strip_title_id ); ?>" class="sr-only"><?php esc_html_e( 'Lageplan', 'montela' ); ?></h2>

	<div class="section-map-strip__frame anim-fade">
		<?php foreach ( $strip_seasons as $strip_key => $strip_season ) : ?>
			<?php $strip_is_current = $strip_key === $strip_current; ?>
			<div class="section-map-strip__plane<?php echo $strip_is_current ? ' is-active' : ''; ?>" data-map-strip-layer="<?php echo esc_attr( $strip_key ); ?>"<?php echo $strip_is_current ? '' : ' inert'; ?>>
				<?php echo wp_get_attachment_image( $strip_season['image'], 'full', false, $strip_image_attr ); ?>

				<?php foreach ( $strip_season['pins'] as $strip_index => $strip_pin ) : ?>
					<button class="section-map-strip__pin" type="button" data-map-strip-pin data-content="<?php echo esc_attr( $strip_uid . '-' . $strip_key . '-' . $strip_index ); ?>" style="<?php echo esc_attr( '--x: ' . (float) $strip_pin['x'] . '%; --y: ' . (float) $strip_pin['y'] . '%;' ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $strip_popup_id ); ?>">
						<span class="section-map-strip__label" aria-hidden="true"><?php echo esc_html( $strip_pin['label'] ); ?></span>
						<span class="sr-only"><?php echo esc_html( trim( wp_strip_all_tags( $strip_pin['label'] . '. ' . $strip_pin['title'] ), '. ' ) ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( count( $strip_seasons ) > 1 ) : ?>
		<?php // The Map module's switch (_map.sass); placed top right in _map-strip.sass. ?>
		<div class="section-map__seasons section-map-strip__seasons" role="group" data-map-strip-seasons aria-label="<?php esc_attr_e( 'Saison', 'montela' ); ?>">
			<?php foreach ( $strip_seasons as $strip_key => $strip_season ) : ?>
				<?php $strip_is_current = $strip_key === $strip_current; ?>
				<button class="section-map__season section-map__season--<?php echo esc_attr( $strip_key ); ?><?php echo $strip_is_current ? ' is-active' : ''; ?>" type="button" data-map-strip-season="<?php echo esc_attr( $strip_key ); ?>" aria-pressed="<?php echo $strip_is_current ? 'true' : 'false'; ?>"><?php echo esc_html( $strip_season['label'] ); ?></button>
			<?php endforeach; ?>
			<span class="section-map__indicator" aria-hidden="true"></span>
		</div>
	<?php endif; ?>

	<div class="section-map-strip__popup" id="<?php echo esc_attr( $strip_popup_id ); ?>" data-map-strip-popup role="dialog" aria-modal="false" aria-label="<?php esc_attr_e( 'Details zum Ort', 'montela' ); ?>" hidden>
		<button class="section-map-strip__popup-close" type="button" data-map-strip-close aria-label="<?php esc_attr_e( 'Schliessen', 'montela' ); ?>"></button>
		<div data-map-strip-body></div>
	</div>

	<?php // Each popup's content as real HTML (hidden): translatable and crawlable; map-strip.js copies it into the popup. ?>
	<div hidden>
		<?php foreach ( $strip_seasons as $strip_key => $strip_season ) : ?>
			<?php foreach ( $strip_season['pins'] as $strip_index => $strip_pin ) : ?>
				<?php
				$strip_link   = ( is_array( $strip_pin['link'] ) && ! empty( $strip_pin['link']['url'] ) ) ? $strip_pin['link'] : null;
				$strip_target = ( $strip_link && ! empty( $strip_link['target'] ) ) ? $strip_link['target'] : '';
				?>
				<div id="<?php echo esc_attr( $strip_uid . '-' . $strip_key . '-' . $strip_index ); ?>">
					<h3 class="section-map-strip__popup-title"><?php echo montela_text( $strip_pin['title'] ? $strip_pin['title'] : $strip_pin['label'] ); ?></h3>
					<?php if ( ! empty( $strip_pin['text'] ) ) : ?>
						<p class="section-map-strip__popup-text"><?php echo montela_text( $strip_pin['text'] ); ?></p>
					<?php endif; ?>
					<?php if ( $strip_link ) : ?>
						<a class="section-map-strip__popup-button" href="<?php echo esc_url( $strip_link['url'] ); ?>"<?php echo '_blank' === $strip_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $strip_link['title'] ? $strip_link['title'] : __( 'Mehr erfahren', 'montela' ) ); ?></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</div>
</section>
