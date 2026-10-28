<?php
/**
 * Activities module: title, text and button beside up to four activity
 * cards, with a Summer / Winter switch that swaps the cards.
 *
 * Fields: acf-json/group_module_activities.json, cloned into each page's
 * field group. Each season picks its cards in a Relationship field (max 4,
 * filtered by the Season taxonomy); left empty, the first 4 activities of
 * that season are shown. Figma: "Activities_with slider SW" — desktop
 * 5679:16639, tablet 5679:15677, mobile 5679:16603.
 *
 * The season open on load follows the date (winter from December to
 * April). Page caching (WP Rocket) keeps whatever was rendered until the
 * cache is rebuilt, which is fine for a switch that changes twice a year.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'activities_' for a clone named 'activities'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.11.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$act_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$act_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$act_title  = get_field( $act_prefix . 'section_activities_title', $act_ctx );
$act_text   = get_field( $act_prefix . 'section_activities_text', $act_ctx );
$act_button = get_field( $act_prefix . 'section_activities_button', $act_ctx );

// Season => Relationship field, Season term slug and fallback label.
$act_seasons = array(
	'summer' => array(
		'field' => 'section_activities_summer',
		'term'  => 'sommer',
		'label' => __( 'Summer', 'montela' ),
	),
	'winter' => array(
		'field' => 'section_activities_winter',
		'term'  => 'winter',
		'label' => __( 'Winter', 'montela' ),
	),
);

foreach ( $act_seasons as $act_key => $act_season ) {
	// Switch label: the Season term's name ("Sommer"), so it follows the
	// site language and is translated with the taxonomy in WPML. The theme
	// string above is only the fallback when the term is missing.
	$act_term = get_term_by( 'slug', $act_season['term'], 'season' );
	if ( $act_term ) {
		$act_term = get_term( apply_filters( 'wpml_object_id', $act_term->term_id, 'season', true ), 'season' );
	}
	if ( $act_term && ! is_wp_error( $act_term ) ) {
		$act_seasons[ $act_key ]['label'] = $act_term->name;
	}

	$act_seasons[ $act_key ]['ids'] = montela_get_season_activities(
		get_field( $act_prefix . $act_season['field'], $act_ctx ),
		$act_season['term'],
		4
	);
}

// Drop the seasons without cards; no cards at all, no section.
$act_seasons = array_filter(
	$act_seasons,
	function ( $act_season ) {
		return ! empty( $act_season['ids'] );
	}
);

if ( ! $act_seasons ) {
	return;
}

// Winter months (1–12) — the season open on page load.
$act_winter_months = array( 12, 1, 2, 3, 4 );
$act_current       = in_array( (int) wp_date( 'n' ), $act_winter_months, true ) ? 'winter' : 'summer';
if ( ! isset( $act_seasons[ $act_current ] ) ) {
	$act_current = array_key_first( $act_seasons );
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'activities' );
}

$act_uid      = wp_unique_id( 'section-activities-' );
$act_title_id = $act_uid . '-title';
?>
<section class="section-activities"<?php echo $act_title ? ' aria-labelledby="' . esc_attr( $act_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-activities__inner">
			<div class="section-activities__frame" aria-hidden="true"></div>

			<div class="section-activities__intro">
				<?php if ( count( $act_seasons ) > 1 ) : ?>
					<div class="section-activities__switch" role="tablist" aria-label="<?php esc_attr_e( 'Season', 'montela' ); ?>" data-activities-tabs>
						<?php foreach ( $act_seasons as $act_key => $act_season ) : ?>
							<?php $act_is_current = $act_key === $act_current; ?>
							<button
								class="section-activities__tab section-activities__tab--<?php echo esc_attr( $act_key ); ?>"
								type="button"
								role="tab"
								id="<?php echo esc_attr( $act_uid . '-tab-' . $act_key ); ?>"
								aria-controls="<?php echo esc_attr( $act_uid . '-panel-' . $act_key ); ?>"
								aria-selected="<?php echo $act_is_current ? 'true' : 'false'; ?>"
								tabindex="<?php echo $act_is_current ? '0' : '-1'; ?>"
							><?php echo esc_html( $act_season['label'] ); ?></button>
						<?php endforeach; ?>
						<span class="section-activities__indicator" aria-hidden="true"></span>
					</div>
				<?php endif; ?>

				<div class="section-activities__copy">
					<?php if ( $act_title ) : ?>
						<h2 id="<?php echo esc_attr( $act_title_id ); ?>" class="title-h3 section-activities__title"><?php echo esc_html( $act_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $act_text ) : ?>
						<p class="body-text section-activities__text"><?php echo esc_html( $act_text ); ?></p>
					<?php endif; ?>

					<?php
					if ( $act_button ) :
						get_template_part( 'template-parts/components/button', null, array_merge( $act_button, array( 'style' => 'secondary' ) ) );
					endif;
					?>
				</div>
			</div>

			<?php foreach ( $act_seasons as $act_key => $act_season ) : ?>
				<div
					class="section-activities__panel"
					id="<?php echo esc_attr( $act_uid . '-panel-' . $act_key ); ?>"
					<?php if ( count( $act_seasons ) > 1 ) : ?>
						role="tabpanel"
						aria-labelledby="<?php echo esc_attr( $act_uid . '-tab-' . $act_key ); ?>"
					<?php endif; ?>
					<?php echo $act_key !== $act_current ? 'hidden' : ''; ?>
					data-activities-carousel
				>
					<div class="section-activities__cards" data-activities-track>
						<?php
						foreach ( $act_season['ids'] as $act_id ) :
							get_template_part( 'template-parts/components/card-activity', null, array( 'post_id' => $act_id ) );
						endforeach;
						?>
					</div>

					<?php if ( count( $act_season['ids'] ) > 1 ) : ?>
						<div class="slider-nav section-activities__nav">
							<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-activities-prev aria-label="<?php esc_attr_e( 'Previous activity', 'montela' ); ?>"></button>
							<button class="slider-nav__btn slider-nav__btn--next" type="button" data-activities-next aria-label="<?php esc_attr_e( 'Next activity', 'montela' ); ?>"></button>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
