<?php
/**
 * Apartments — the apartment cards: one full-bleed row per apartment, a sage
 * panel (title, facts, excerpt, "learn more" and booking buttons) beside a
 * photo slider — the sides alternate on desktop; stacked below.
 *
 * Content: the Apartments post type. The page picks the apartments and their
 * order in "Apartments" (acf-json/group_page_apartments.json); left empty,
 * every published apartment, in its "Order" (Page Attributes). Card fields:
 * title, excerpt, the facts (components/apartment-facts.php), the booking
 * link (falls back to the header's "Jetzt buchen" link) and the photos — the
 * card image (featured image) followed by the gallery.
 *
 * Figma: Cards_Appartment desktop 5679:14338, tablet 5679:13437, mobile
 * 5679:12702.
 *
 * @package montela
 * @subpackage Section
 * @since 1.32.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$apts_ids = get_field( 'section_apartments_items' );

// Nothing picked: every apartment, in admin order.
if ( ! $apts_ids ) {
	$apts_ids = get_posts(
		array(
			'post_type'        => 'apartment',
			'posts_per_page'   => -1,
			'fields'           => 'ids',
			'orderby'          => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'suppress_filters' => false, // Let WPML keep the current language.
		)
	);
}

$apts_ids = array_filter(
	(array) $apts_ids,
	static function ( $apts_id ) {
		return 'publish' === get_post_status( $apts_id );
	}
);

if ( ! $apts_ids ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$apts_header_booking = get_field( 'header_booking_link', 'option' );

// One photo per view, sliding.
$apts_swiper = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 0,
);
?>
<div class="section-apartments">
	<?php
	foreach ( $apts_ids as $apts_id ) :
		$apts_title   = get_the_title( $apts_id );
		$apts_excerpt = has_excerpt( $apts_id ) ? get_the_excerpt( $apts_id ) : '';
		$apts_booking = get_field( 'section_apartment_booking', $apts_id );
		if ( ! $apts_booking ) {
			$apts_booking = $apts_header_booking;
		}

		// Card image first, then the gallery.
		$apts_photos = array_values(
			array_unique(
				array_filter(
					array_merge(
						array( get_post_thumbnail_id( $apts_id ) ),
						(array) get_field( 'section_apartment_gallery', $apts_id )
					)
				)
			)
		);
		?>
		<article class="apartment-card">
			<div class="apartment-card__panel">
				<h2 class="title-h3 apartment-card__title"><?php echo esc_html( $apts_title ); ?></h2>

				<div class="apartment-card__body">
					<?php
					get_template_part(
						'template-parts/components/apartment-facts',
						null,
						array(
							'post_id' => $apts_id,
							'class'   => 'apartment-card__facts',
						)
					);
					?>

					<?php if ( $apts_excerpt ) : ?>
						<p class="apartment-card__excerpt"><?php echo esc_html( $apts_excerpt ); ?></p>
					<?php endif; ?>

					<div class="apartment-card__buttons">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'url'     => get_permalink( $apts_id ),
								'title'   => __( 'Mehr erfahren', 'montela' ),
								'context' => $apts_title,
								'style'   => 'ghost',
							)
						);

						if ( is_array( $apts_booking ) ) :
							get_template_part(
								'template-parts/components/button',
								null,
								array_merge(
									$apts_booking,
									array(
										'context' => $apts_title,
										'style'   => 'outline',
									)
								)
							);
						endif;
						?>
					</div>
				</div>
			</div>

			<?php if ( $apts_photos ) : ?>
				<div class="apartment-card__photos" data-swiper-root>
					<div class="swiper apartment-card__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $apts_swiper ) ); ?>">
						<div class="swiper-wrapper">
							<?php foreach ( $apts_photos as $apts_photo ) : ?>
								<div class="swiper-slide apartment-card__slide">
									<?php
									echo wp_get_attachment_image(
										$apts_photo,
										'full',
										false,
										array(
											'class' => 'apartment-card__img',
											'sizes' => '(min-width: 1280px) 50vw, 100vw',
										)
									);
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<?php if ( count( $apts_photos ) > 1 ) : ?>
						<div class="slider-nav slider-nav--sage apartment-card__nav">
							<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Vorheriges Foto', 'montela' ); ?>"></button>
							<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Nächstes Foto', 'montela' ); ?>"></button>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	<?php endforeach; ?>
</div>
