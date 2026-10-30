<?php
/**
 * Shop — shop products: a centred title over a slider of product cards
 * (photo, then a walnut-framed box with a title and a text). Three cards in
 * view on desktop with the arrows at either side; one card below that, with
 * the arrows underneath. "Ein Stück Wallis für Ihren Genuss".
 *
 * Fields: "Products" tab of acf-json/group_page_shop.json. Figma: "Section
 * Card Shop" — desktop 5895:19814, tablet 5895:20745, mobile 5895:20633.
 * Layout as Tailwind classes here; measurements in
 * _pages/_shop/_shop-products.sass. Enqueues the Swiper module itself.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$sp_title = get_field( 'section_shop_products_title' );
$sp_items = array_filter(
	(array) get_field( 'section_shop_products_items' ),
	static function ( $sp_row ) {
		return is_array( $sp_row ) && ( ! empty( $sp_row['title'] ) || ! empty( $sp_row['text'] ) || ! empty( $sp_row['image'] ) );
	}
);

// No cards, no section.
if ( ! $sp_items ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$sp_title_id = wp_unique_id( 'section-shop-products-title-' );

// One card below desktop, 20px apart (the grid gutter); three on desktop,
// 21px apart as drawn. The arrows stay visible (disabled) when every card is
// in view, as drawn.
$sp_swiper = array(
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
<section class="section-shop-products mb-32 md:mb-48 text-montela-walnut"<?php echo $sp_title ? ' aria-labelledby="' . esc_attr( $sp_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-11" data-swiper-root>
			<?php if ( $sp_title ) : ?>
				<h3 id="<?php echo esc_attr( $sp_title_id ); ?>" class="title-h3 section-shop-products__title col-span-full text-center">
					<?php echo wp_kses( $sp_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h3>
			<?php endif; ?>

			<div class="section-shop-products__slider col-span-full md:col-start-2 md:col-span-4 md:px-3 xl:col-start-2 xl:col-span-10 xl:row-start-2 xl:px-[82px]">
				<div class="swiper section-shop-products__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $sp_swiper ) ); ?>">
					<div class="swiper-wrapper">
						<?php foreach ( $sp_items as $sp_item ) : ?>
							<div class="swiper-slide section-shop-products__slide">
								<article class="section-shop-products__card flex flex-col h-full">
									<?php if ( ! empty( $sp_item['image'] ) ) : ?>
										<figure class="section-shop-products__photo m-0 h-[164px] shrink-0 overflow-hidden md:h-[216px]">
											<?php
											echo wp_get_attachment_image(
												$sp_item['image'],
												'large',
												false,
												array(
													'class' => 'w-full h-full object-cover',
													'sizes' => '(min-width: 1280px) 26vw, (min-width: 768px) 56vw, 100vw',
												)
											);
											?>
										</figure>
									<?php endif; ?>

									<div class="section-shop-products__body flex flex-col flex-1 gap-3.5 border border-montela-walnut px-5 py-4 text-center md:gap-[18px]">
										<?php if ( ! empty( $sp_item['title'] ) ) : ?>
											<h4 class="title-h4 section-shop-products__card-title"><?php echo esc_html( $sp_item['title'] ); ?></h4>
										<?php endif; ?>
										<?php if ( ! empty( $sp_item['text'] ) ) : ?>
											<p class="body-text section-shop-products__card-text mx-auto max-w-[288px] md:max-w-[395px] xl:max-w-none"><?php echo esc_html( $sp_item['text'] ); ?></p>
										<?php endif; ?>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="slider-nav--walnut col-span-full flex justify-center gap-5 xl:contents">
				<button class="slider-nav__btn slider-nav__btn--prev xl:col-start-1 xl:row-start-2 xl:self-center xl:justify-self-end" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous product', 'montela' ); ?>"></button>
				<button class="slider-nav__btn slider-nav__btn--next xl:col-start-12 xl:row-start-2 xl:self-center xl:justify-self-start" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next product', 'montela' ); ?>"></button>
			</div>
		</div>
	</div>
</section>
