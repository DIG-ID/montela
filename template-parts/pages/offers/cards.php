<?php
/**
 * Angebote & Packages — the offers: one full-bleed row per offer, a sage
 * panel (title, validity, duration, excerpt, "learn more") beside its card
 * image — the sides alternate on desktop; stacked below.
 *
 * Content: the Offers post type. Shows the published offers that are still
 * valid ("Valid until" today or later, or no end date), sorted by their
 * start date (offers without one last) — montela_get_active_offers(),
 * inc/helpers.php. Card fields: title, card image
 * (featured image), excerpt, "Duration" and the validity dates
 * (acf-json/group_cpt_offer.json).
 *
 * Figma: Cards_Offers desktop 5679:16726, tablet 5607:22922, mobile
 * 5607:22885.
 *
 * @package montela
 * @subpackage Section
 * @since 1.21.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$offers = function_exists( 'montela_get_active_offers' ) ? montela_get_active_offers() : array();

if ( ! $offers ) {
	return;
}
?>
<div class="section-offers">
	<?php
	foreach ( $offers as $offers_id ) :
		$offers_validity = function_exists( 'montela_get_offer_validity' ) ? montela_get_offer_validity( $offers_id, 'd.m.y' ) : '';
		$offers_duration = get_field( 'section_offer_duration', $offers_id );
		$offers_excerpt  = has_excerpt( $offers_id ) ? get_the_excerpt( $offers_id ) : '';
		$offers_image    = get_post_thumbnail_id( $offers_id );
		$offers_title    = get_the_title( $offers_id );
		?>
		<article class="offer-card">
			<div class="offer-card__panel">
				<h2 class="title-h3 offer-card__title"><?php echo esc_html( $offers_title ); ?></h2>

				<?php if ( $offers_validity ) : ?>
					<p class="offer-card__dates"><?php echo esc_html( $offers_validity ); ?></p>
				<?php endif; ?>

				<?php if ( $offers_duration ) : ?>
					<p class="offer-card__duration"><?php echo esc_html( $offers_duration ); ?></p>
				<?php endif; ?>

				<?php if ( $offers_excerpt ) : ?>
					<p class="offer-card__excerpt"><?php echo esc_html( $offers_excerpt ); ?></p>
				<?php endif; ?>

				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'url'     => get_permalink( $offers_id ),
						'title'   => __( 'Mehr erfahren', 'montela' ),
						'context' => $offers_title,
						'style'   => 'outline',
						'class'   => 'offer-card__btn',
					)
				);
				?>
			</div>

			<?php if ( $offers_image ) : ?>
				<figure class="offer-card__photo">
					<?php
					echo wp_get_attachment_image(
						$offers_image,
						'full',
						false,
						array(
							'class' => 'offer-card__img',
							'alt'   => '',
							'sizes' => '(min-width: 1280px) 50vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</article>
	<?php endforeach; ?>
</div>
