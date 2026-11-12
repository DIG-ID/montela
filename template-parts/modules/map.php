<?php
/**
 * Map module: intro (title, button, text), the interactive illustrated map
 * and a bar of features below it.
 *
 * The map can be zoomed and panned; each season (summer / winter) is a layer
 * of artwork + pins on the same canvas, and the switch cross-fades between
 * them keeping the zoom and position. The pins are printed in the artwork:
 * each one is a transparent button over its marker, positioned in % of the
 * image, that opens a popup (label + title, text, button). Behaviour in
 * assets/js/modules/map.js, styles in _modules/_map.sass.
 *
 * Fields: the intro and the features are per page (acf-json/
 * group_module_map.json, cloned into the page's field group); the map —
 * the two illustrations and their pins — is the same everywhere, set once
 * in Theme Settings, tab Map (map_image_* / map_pins_*).
 *
 * The intro's desktop layout follows what is filled in (Figma):
 * - with a button: title on columns 2–8, below it the button (column 2)
 *   and the text (columns 8–11) — homepage;
 * - title and text, no button: side by side, on their bottom line — Unser
 *   Resort;
 * - title only: centred — Aufenthalt, Unsere Region.
 *
 * Figma "section-map": desktop 5588:16242 (in Home_desktop 5895:18239),
 * tablet 5895:18193, mobile 5895:18042; also Our Resort 5895:20078, Stay
 * 5895:19043, Our Region 5895:21050. Behaviour from an earlier, validated
 * prototype (kitchen-sink theme).
 *
 * Arguments ($args):
 * - post_id     (int|string) Optional. Where to read the page fields from. Default: current post.
 * - prefix      (string)     Optional. Clone field prefix ("Prefix field names").
 * - padding_top (bool)       Optional. False drops the space above the section,
 *                            when it follows the page intro (Unser Resort). Default true.
 *
 * @package montela
 * @subpackage Module
 * @since 1.53.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$map_title    = montela_module_field( 'section_map_title', $args );
$map_text     = montela_module_field( 'section_map_text', $args );
$map_button   = montela_module_field( 'section_map_button', $args );
$map_features = montela_module_field( 'section_map_features', $args );

// Season => image field, pins field, Season term slug and fallback label.
$map_seasons = array(
	'summer' => array(
		'term'  => 'sommer',
		'label' => __( 'Sommer', 'montela' ),
	),
	'winter' => array(
		'term'  => 'winter',
		'label' => __( 'Winter', 'montela' ),
	),
);

foreach ( $map_seasons as $map_key => $map_season ) {
	$map_image = (int) get_field( 'map_image_' . $map_key, 'option' );

	// A season without its map is dropped (no winter map = no switch).
	if ( ! $map_image ) {
		unset( $map_seasons[ $map_key ] );
		continue;
	}

	$map_seasons[ $map_key ]['image'] = $map_image;
	$map_seasons[ $map_key ]['pins']  = array_values(
		array_filter(
			(array) get_field( 'map_pins_' . $map_key, 'option' ),
			static function ( $map_pin ) {
				return is_array( $map_pin ) && '' !== (string) $map_pin['x'] && '' !== (string) $map_pin['y'];
			}
		)
	);

	// Switch label: the Season term's name, as in the Activities module, so
	// it follows the site language; the theme string is the fallback.
	$map_term = get_term_by( 'slug', $map_season['term'], 'season' );
	if ( $map_term ) {
		$map_term = get_term( apply_filters( 'wpml_object_id', $map_term->term_id, 'season', true ), 'season' );
	}
	if ( $map_term && ! is_wp_error( $map_term ) ) {
		$map_seasons[ $map_key ]['label'] = $map_term->name;
	}
}

if ( ! $map_title && ! $map_seasons ) {
	return;
}

// The season open on page load: winter from December to April, as in the
// Activities module.
$map_current = in_array( (int) wp_date( 'n' ), array( 12, 1, 2, 3, 4 ), true ) ? 'winter' : 'summer';
if ( ! isset( $map_seasons[ $map_current ] ) ) {
	$map_current = array_key_first( $map_seasons );
}

if ( $map_seasons && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'map' );
}

$map_uid      = wp_unique_id( 'section-map-' );
$map_title_id = $map_uid . '-title';
$map_popup_id = $map_uid . '-popup';
$map_icons    = get_theme_file_uri( 'assets/svg/icons/' );

/**
 * Image attributes for a season layer. WordPress scales big uploads down to
 * 2560px (the "-scaled" file); the original is added as the largest srcset
 * candidate so deep zoom stays sharp — map.js widens `sizes` on the first
 * zoom so the browser fetches it.
 *
 * @param int $image_id Attachment ID.
 * @return array
 */
$map_image_attr = static function ( $image_id ) {
	$attr = array(
		'class'     => 'section-map__image',
		'loading'   => 'lazy',
		'draggable' => 'false',
		'sizes'     => '(min-width: 1280px) 1820px, 100vw',
	);

	$map_original_url  = wp_get_original_image_url( $image_id );
	$map_original_path = wp_get_original_image_path( $image_id );
	$map_srcset        = wp_get_attachment_image_srcset( $image_id, 'full' );
	if ( $map_original_url && $map_original_path && $map_srcset && wp_get_attachment_image_url( $image_id, 'full' ) !== $map_original_url ) {
		$map_size = wp_getimagesize( $map_original_path );
		if ( $map_size ) {
			$attr['srcset'] = $map_srcset . ', ' . esc_url( $map_original_url ) . ' ' . (int) $map_size[0] . 'w';
		}
	}

	return $attr;
};
?>
<?php
$map_has_button = is_array( $map_button ) && ! empty( $map_button['url'] );
$map_has_intro  = $map_title || $map_text || $map_has_button;

// Desktop intro layout, from the content (see the file docblock).
if ( $map_has_button ) {
	$map_title_class = 'xl:col-start-2 xl:col-span-7 xl:max-w-[934px]';
	$map_text_class  = 'xl:col-start-8 xl:col-span-4 xl:row-start-2 xl:mt-5';
	$map_gap_class   = 'xl:mt-[165px]';
} elseif ( $map_text ) {
	$map_title_class = 'xl:col-start-2 xl:col-span-6';
	$map_text_class  = 'xl:col-start-8 xl:col-span-4 xl:row-start-1 xl:self-end xl:mt-0';
	$map_gap_class   = 'xl:mt-20'; // 81px in Figma.
} else {
	$map_title_class = 'xl:col-start-2 xl:col-span-10 xl:mx-auto xl:max-w-[934px] xl:text-center';
	$map_text_class  = '';
	$map_gap_class   = 'xl:mt-[72px]';
}

// Unser Resort: right after the page intro, which already ends with its own space.
$map_padding = ( isset( $args['padding_top'] ) && false === $args['padding_top'] ) ? 'pb-32 md:pb-48 xl:pb-56' : 'py-32 md:py-48 xl:py-56';
?>
<section class="section-map <?php echo esc_attr( $map_padding ); ?> text-montela-walnut"<?php echo $map_title ? ' aria-labelledby="' . esc_attr( $map_title_id ) . '"' : ''; ?>>
	<?php if ( $map_has_intro ) : ?>
		<div class="theme-container">
			<div class="theme-grid section-map__intro">
				<?php if ( $map_title ) : ?>
					<h2 id="<?php echo esc_attr( $map_title_id ); ?>" class="title-h3 section-map__title col-span-full <?php echo esc_attr( $map_title_class ); ?>"><?php echo montela_text( $map_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $map_text ) : ?>
					<p class="body-text section-map__text col-span-full mt-10 md:col-span-5 md:mt-[72px] <?php echo esc_attr( $map_text_class ); ?>"><?php echo montela_text( $map_text ); ?></p>
				<?php endif; ?>

				<?php if ( $map_has_button ) : ?>
					<div class="section-map__button col-span-full mt-8 xl:col-start-2 xl:col-span-6 xl:row-start-2 xl:self-end xl:mt-5">
						<?php get_template_part( 'template-parts/components/button', null, array_merge( $map_button, array( 'style' => 'secondary' ) ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $map_seasons ) : ?>
		<?php // Below desktop the map takes the screen width, in a 12px cream border. ?>
		<div class="section-map__frame mx-auto max-w-[1920px] px-3 xl:px-[50px]<?php echo $map_has_intro ? ' mt-10 md:mt-16 ' . esc_attr( $map_gap_class ) : ''; ?>">
			<div class="section-map__map" data-map>
				<div class="section-map__viewport" data-map-viewport role="group" tabindex="0" aria-label="<?php esc_attr_e( 'Interaktive Karte. Mit den Zoom-Tasten vergrössern und zum Erkunden ziehen oder scrollen.', 'montela' ); ?>">
					<div class="section-map__canvas" data-map-canvas>
						<?php foreach ( $map_seasons as $map_key => $map_season ) : ?>
							<?php $map_is_current = $map_key === $map_current; ?>
							<div class="section-map__layer<?php echo $map_is_current ? ' is-active' : ''; ?>" data-map-layer="<?php echo esc_attr( $map_key ); ?>"<?php echo $map_is_current ? '' : ' inert'; ?>>
								<?php echo wp_get_attachment_image( $map_season['image'], 'full', false, $map_image_attr( $map_season['image'] ) ); ?>

								<?php foreach ( $map_season['pins'] as $map_index => $map_pin ) : ?>
									<?php $map_pin_name = trim( wp_strip_all_tags( $map_pin['label'] . '. ' . $map_pin['title'] ), '. ' ); ?>
									<button class="section-map__pin" type="button" data-map-pin data-content="<?php echo esc_attr( $map_uid . '-' . $map_key . '-' . $map_index ); ?>" style="<?php echo esc_attr( '--x: ' . (float) $map_pin['x'] . '%; --y: ' . (float) $map_pin['y'] . '%;' ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $map_popup_id ); ?>">
										<span class="sr-only"><?php echo esc_html( $map_pin_name ); ?></span>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="section-map__hint" data-map-hint aria-hidden="true"><?php esc_html_e( 'Klicken Sie auf die Karte, um mit dem Mausrad zu zoomen', 'montela' ); ?></div>

				<div class="section-map__controls" role="group" aria-label="<?php esc_attr_e( 'Kartensteuerung', 'montela' ); ?>">
					<button class="section-map__control" type="button" data-map-zoom-in aria-label="<?php esc_attr_e( 'Vergrössern', 'montela' ); ?>">
						<img src="<?php echo esc_url( $map_icons . 'map-zoom-in.svg' ); ?>" alt="" width="25" height="25">
					</button>
					<button class="section-map__control" type="button" data-map-zoom-out aria-label="<?php esc_attr_e( 'Verkleinern', 'montela' ); ?>">
						<img src="<?php echo esc_url( $map_icons . 'map-zoom-out.svg' ); ?>" alt="" width="24" height="2">
					</button>
					<button class="section-map__control" type="button" data-map-reset aria-label="<?php esc_attr_e( 'Karte zurücksetzen', 'montela' ); ?>">
						<img src="<?php echo esc_url( $map_icons . 'map-reset.svg' ); ?>" alt="" width="42" height="42">
					</button>
					<button class="section-map__control" type="button" data-map-fullscreen aria-label="<?php esc_attr_e( 'Vollbild', 'montela' ); ?>" aria-pressed="false" hidden>
						<img src="<?php echo esc_url( $map_icons . 'map-fullscreen.svg' ); ?>" alt="" width="42" height="42">
					</button>
				</div>

				<?php if ( count( $map_seasons ) > 1 ) : ?>
					<div class="section-map__seasons" role="group" data-map-seasons aria-label="<?php esc_attr_e( 'Saison', 'montela' ); ?>">
						<?php foreach ( $map_seasons as $map_key => $map_season ) : ?>
							<?php $map_is_current = $map_key === $map_current; ?>
							<button class="section-map__season section-map__season--<?php echo esc_attr( $map_key ); ?><?php echo $map_is_current ? ' is-active' : ''; ?>" type="button" data-map-season="<?php echo esc_attr( $map_key ); ?>" aria-pressed="<?php echo $map_is_current ? 'true' : 'false'; ?>"><?php echo esc_html( $map_season['label'] ); ?></button>
						<?php endforeach; ?>
						<span class="section-map__indicator" aria-hidden="true"></span>
					</div>
				<?php endif; ?>

				<div class="section-map__popup" id="<?php echo esc_attr( $map_popup_id ); ?>" data-map-popup role="dialog" aria-modal="false" aria-label="<?php esc_attr_e( 'Details zum Ort', 'montela' ); ?>" hidden>
					<button class="section-map__popup-close" type="button" data-map-popup-close aria-label="<?php esc_attr_e( 'Schliessen', 'montela' ); ?>"></button>
					<div class="section-map__popup-body" data-map-popup-body></div>
				</div>

				<?php // Each popup's content as real HTML (hidden): translatable and crawlable; map.js copies it into the popup. ?>
				<div hidden>
					<?php foreach ( $map_seasons as $map_key => $map_season ) : ?>
						<?php foreach ( $map_season['pins'] as $map_index => $map_pin ) : ?>
							<?php
							$map_pin_link   = ( is_array( $map_pin['link'] ) && ! empty( $map_pin['link']['url'] ) ) ? $map_pin['link'] : null;
							$map_pin_target = ( $map_pin_link && ! empty( $map_pin_link['target'] ) ) ? $map_pin_link['target'] : '';
							?>
							<div id="<?php echo esc_attr( $map_uid . '-' . $map_key . '-' . $map_index ); ?>">
								<h3 class="section-map__popup-title"><?php echo montela_text( trim( $map_pin['label'] . '. ' . $map_pin['title'], '. ' ) ); ?></h3>
								<?php if ( ! empty( $map_pin['text'] ) ) : ?>
									<p class="section-map__popup-text"><?php echo montela_text( $map_pin['text'] ); ?></p>
								<?php endif; ?>
								<?php if ( $map_pin_link ) : ?>
									<a class="section-map__popup-button" href="<?php echo esc_url( $map_pin_link['url'] ); ?>"<?php echo '_blank' === $map_pin_target ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $map_pin_link['title'] ? $map_pin_link['title'] : __( 'Mehr erfahren', 'montela' ) ); ?></a>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $map_features ) : ?>
		<?php // Tablet: 24px from the screen edges, wider than the container (Figma). ?>
		<div class="section-map__features-wrap mx-auto max-w-[1920px] px-8 md:px-6 xl:px-[50px]<?php echo ( $map_has_intro || $map_seasons ) ? ' mt-10 md:mt-16 xl:mt-[30px]' : ''; ?>">
			<ul class="section-map__features grid grid-cols-2 gap-6 p-6 md:grid-cols-6 md:gap-8 xl:flex xl:justify-between xl:gap-5 xl:px-10 xl:py-8 border border-solid border-montela-sage">
				<?php foreach ( $map_features as $map_feature ) : ?>
					<?php
					if ( empty( $map_feature['text'] ) ) {
						continue;
					}
					?>
					<li class="section-map__feature flex flex-col items-start gap-3 xl:flex-1 xl:flex-row xl:items-center">
						<?php
						if ( ! empty( $map_feature['icon'] ) ) :
							echo wp_get_attachment_image(
								$map_feature['icon'],
								'full',
								false,
								array(
									'class'   => 'section-map__feature-icon',
									'alt'     => '',
									'loading' => 'lazy',
								)
							);
						endif;
						?>
						<span class="section-map__feature-text"><?php echo montela_text( $map_feature['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</section>
