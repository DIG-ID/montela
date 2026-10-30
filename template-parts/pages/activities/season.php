<?php
/**
 * Aktivitäten — season section: small title, title, text and the season's
 * activity cards ("Sommeraktivitäten" / "Winteraktivitäten").
 *
 * Cards in pages of four (2×2) on tablet and desktop, one at a time on
 * mobile, scrolled with the arrows. The arrows show on tablet and desktop
 * only with more than four activities. Cards: components/card-activity.php;
 * carousel: assets/js/modules/activities.js.
 *
 * Fields: tabs "Summer" and "Winter" of acf-json/group_page_activities.json.
 * Figma: "Activities_with arrows" desktop 5679:16044, tablet 5679:16089,
 * mobile 5679:16157.
 *
 * Arguments ($args):
 * - season (string) Required. 'summer' or 'winter'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.19.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$season_key = ( ! empty( $args['season'] ) && 'winter' === $args['season'] ) ? 'winter' : 'summer';
$season_ids = montela_get_season_activities(
	get_field( 'section_' . $season_key . '_activities' ),
	'winter' === $season_key ? 'winter' : 'sommer'
);

if ( ! $season_ids ) {
	return;
}

$season_eyebrow = get_field( 'section_' . $season_key . '_eyebrow' );
$season_title   = get_field( 'section_' . $season_key . '_title' );
$season_text    = get_field( 'section_' . $season_key . '_text' );

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'activities' );
}

$season_title_id = wp_unique_id( 'section-season-title-' );
$season_class    = 'section-season' . ( count( $season_ids ) > 4 ? ' section-season--paged' : '' );
?>
<section class="<?php echo esc_attr( $season_class ); ?>"<?php echo $season_title ? ' aria-labelledby="' . esc_attr( $season_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-season__inner" data-activities-carousel>
			<div class="section-season__frame" aria-hidden="true"></div>

			<div class="section-season__copy">
				<?php if ( $season_eyebrow ) : ?>
					<p class="title-h2 section-season__eyebrow"><?php echo esc_html( $season_eyebrow ); ?></p>
				<?php endif; ?>

				<?php if ( $season_title ) : ?>
					<h2 id="<?php echo esc_attr( $season_title_id ); ?>" class="title-h3 section-season__title"><?php echo esc_html( $season_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $season_text ) : ?>
					<p class="body-text section-season__text"><?php echo esc_html( $season_text ); ?></p>
				<?php endif; ?>

				<?php if ( count( $season_ids ) > 1 ) : ?>
					<div class="slider-nav slider-nav--sage section-season__nav">
						<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-activities-prev aria-label="<?php esc_attr_e( 'Vorherige Aktivitäten', 'montela' ); ?>"></button>
						<button class="slider-nav__btn slider-nav__btn--next" type="button" data-activities-next aria-label="<?php esc_attr_e( 'Nächste Aktivitäten', 'montela' ); ?>"></button>
					</div>
				<?php endif; ?>
			</div>

			<div class="section-season__track" data-activities-track>
				<?php foreach ( array_chunk( $season_ids, 4 ) as $season_page ) : ?>
					<div class="section-season__page">
						<?php
						foreach ( $season_page as $season_id ) :
							get_template_part( 'template-parts/components/card-activity', null, array( 'post_id' => $season_id ) );
						endforeach;
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
