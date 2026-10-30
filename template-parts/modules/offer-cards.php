<?php
/**
 * Offer cards module ("Für jede Auszeit das passende Angebot"): title and
 * button, the text beside them on desktop, then a slider of offer cards
 * (components/card-preview.php) — four in view on desktop, two on tablet,
 * one on mobile; walnut arrows centred below, hidden when every card is in
 * view.
 *
 * Content: the offers picked in the relationship, in that order; empty, the
 * offers still valid (montela_get_active_offers(), inc/helpers.php). Fields:
 * acf-json/group_module_offer_cards.json, cloned into the Apartments page,
 * whose apartment singles show it. Figma: "Section Card activities" desktop
 * 5823:15953, tablet 5830:16101, mobile 5833:15845. Enqueues the Swiper
 * module itself. Measurements: _modules/_offer-cards.sass.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'offers_' for a clone named 'offers'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$ofc_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$ofc_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$ofc_title  = get_field( $ofc_prefix . 'section_offer_cards_title', $ofc_ctx );
$ofc_text   = get_field( $ofc_prefix . 'section_offer_cards_text', $ofc_ctx );
$ofc_button = get_field( $ofc_prefix . 'section_offer_cards_button', $ofc_ctx );
$ofc_ids    = get_field( $ofc_prefix . 'section_offer_cards_items', $ofc_ctx );

if ( ! $ofc_ids && function_exists( 'montela_get_active_offers' ) ) {
	$ofc_ids = montela_get_active_offers();
}

$ofc_ids = array_filter(
	(array) $ofc_ids,
	function ( $ofc_id ) {
		return 'publish' === get_post_status( $ofc_id );
	}
);

if ( ! $ofc_ids ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$ofc_title_id = wp_unique_id( 'section-offer-cards-title-' );
$ofc_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 20,
	'breakpoints'   => array(
		768  => array( 'slidesPerView' => 2 ),
		1280 => array( 'slidesPerView' => 4 ),
	),
);
?>
<section class="section-offer-cards mb-[192px] text-montela-walnut"<?php echo $ofc_title ? ' aria-labelledby="' . esc_attr( $ofc_title_id ) . '"' : ''; ?>>
	<div class="theme-container" data-swiper-root>
		<?php if ( $ofc_title || $ofc_text || $ofc_button ) : ?>
			<div class="theme-grid gap-y-4 md:gap-y-6 xl:gap-y-[38px]">
				<?php if ( $ofc_title ) : ?>
					<h2 id="<?php echo esc_attr( $ofc_title_id ); ?>" class="title-h3 col-span-full xl:col-start-2 xl:col-span-5 xl:row-start-1"><?php echo wp_kses( $ofc_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $ofc_text ) : ?>
					<p class="body-text col-span-full md:col-span-4 xl:col-start-8 xl:col-span-4 xl:row-start-1 xl:row-span-2"><?php echo esc_html( $ofc_text ); ?></p>
				<?php endif; ?>

				<?php if ( is_array( $ofc_button ) && ! empty( $ofc_button['url'] ) ) : ?>
					<div class="col-span-full xl:col-start-2 xl:col-span-5 xl:row-start-2">
						<?php get_template_part( 'template-parts/components/button', null, array_merge( $ofc_button, array( 'style' => 'secondary' ) ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="swiper section-offer-cards__swiper mt-11 xl:mt-[65px]" data-swiper="<?php echo esc_attr( wp_json_encode( $ofc_swiper ) ); ?>">
			<div class="swiper-wrapper">
				<?php foreach ( $ofc_ids as $ofc_id ) : ?>
					<div class="swiper-slide section-offer-cards__slide">
						<?php
						get_template_part(
							'template-parts/components/card-preview',
							null,
							array(
								'post_id' => $ofc_id,
								'sizes'   => '(min-width: 1280px) 24vw, (min-width: 768px) 42vw, 100vw',
							)
						);
						?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="slider-nav slider-nav--walnut section-offer-cards__nav justify-center mt-11">
			<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Vorheriges Angebot', 'montela' ); ?>"></button>
			<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Nächstes Angebot', 'montela' ); ?>"></button>
		</div>
	</div>
</section>
