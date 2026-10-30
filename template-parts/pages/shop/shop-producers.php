<?php
/**
 * Shop — shop producers: title and text (side by side on desktop, centred
 * and stacked below), then a slider of text cards on the region's producers
 * — a sage-framed card with a title and a text. Three cards in view on
 * desktop with the arrows at either side; one card below that, with the
 * arrows underneath. "Aus der Region. Mit Leidenschaft."
 *
 * Fields: "Producers" tab of acf-json/group_page_shop.json. Figma: "Section
 * Card Shop_region" — desktop 5895:19815, tablet 5895:20746, mobile
 * 5895:20632. Layout as Tailwind classes here; measurements in
 * _pages/_shop/_shop-producers.sass. Enqueues the Swiper module itself.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$spr_title = get_field( 'section_shop_producers_title' );
$spr_text  = get_field( 'section_shop_producers_text' );
$spr_items = array_filter(
	(array) get_field( 'section_shop_producers_items' ),
	static function ( $spr_row ) {
		return is_array( $spr_row ) && ( ! empty( $spr_row['title'] ) || ! empty( $spr_row['text'] ) );
	}
);

// No cards, no section.
if ( ! $spr_items ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$spr_title_id = wp_unique_id( 'section-shop-producers-title-' );

// One card below desktop, 20px apart (the grid gutter); three on desktop,
// 21px apart as drawn. The arrows stay visible (disabled) when every card is
// in view, as drawn.
$spr_swiper = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 20,
	'watchOverflow' => false,
	'breakpoints'   => array(
		1280 => array(
			'slidesPerView' => 3,
			'spaceBetween'  => 21,
		),
	),
);
?>
<section class="section-shop-producers mb-48 text-montela-walnut"<?php echo $spr_title ? ' aria-labelledby="' . esc_attr( $spr_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-11 xl:gap-y-[123px]" data-swiper-root>
			<?php if ( $spr_title || $spr_text ) : ?>
				<div class="section-shop-producers__head col-span-full grid grid-cols-subgrid gap-y-4 text-center md:gap-y-9 xl:col-start-2 xl:col-span-10 xl:items-end xl:text-left">
					<?php if ( $spr_title ) : ?>
						<h2 id="<?php echo esc_attr( $spr_title_id ); ?>" class="title-h3 section-shop-producers__title col-span-full xl:col-span-6">
							<?php echo wp_kses( $spr_title, array( 'br' => array( 'class' => array() ) ) ); ?>
						</h2>
					<?php endif; ?>
					<?php if ( $spr_text ) : ?>
						<p class="body-text section-shop-producers__text col-span-full justify-self-center max-w-[329px] md:max-w-[580px] xl:col-start-7 xl:col-span-4 xl:justify-self-stretch xl:max-w-[555px]"><?php echo esc_html( $spr_text ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="section-shop-producers__slider col-span-full md:col-start-2 md:col-span-4 md:px-3 xl:col-start-2 xl:col-span-10 xl:row-start-2 xl:px-[82px]">
				<div class="swiper section-shop-producers__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $spr_swiper ) ); ?>">
					<div class="swiper-wrapper">
						<?php foreach ( $spr_items as $spr_item ) : ?>
							<div class="swiper-slide section-shop-producers__slide">
								<article class="section-shop-producers__card flex flex-col h-full gap-2.5 border border-montela-sage shadow-[0_4px_4px_rgba(0,0,0,0.25)] pt-8 pb-4 px-[31px] md:gap-4 md:py-8 md:px-11 xl:min-h-[410px] xl:gap-2.5 xl:pt-[47px] xl:pb-[26px] xl:pl-[42px] xl:pr-[41px]">
									<?php if ( ! empty( $spr_item['title'] ) ) : ?>
										<h3 class="title-h4 section-shop-producers__card-title"><?php echo esc_html( $spr_item['title'] ); ?></h3>
									<?php endif; ?>
									<?php if ( ! empty( $spr_item['text'] ) ) : ?>
										<p class="section-shop-producers__card-text max-w-[260px] md:max-w-none"><?php echo esc_html( $spr_item['text'] ); ?></p>
									<?php endif; ?>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="slider-nav--walnut col-span-full flex justify-center gap-5 xl:contents">
				<button class="slider-nav__btn slider-nav__btn--prev xl:col-start-1 xl:row-start-2 xl:self-center xl:justify-self-end" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Vorheriger Produzent', 'montela' ); ?>"></button>
				<button class="slider-nav__btn slider-nav__btn--next xl:col-start-12 xl:row-start-2 xl:self-center xl:justify-self-start" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Nächster Produzent', 'montela' ); ?>"></button>
			</div>
		</div>
	</div>
</section>
