<?php
/**
 * Cards with icon module: a centred title over a slider of framed cards —
 * icon, title, a short line and a text in each. Four cards in view on
 * desktop, two on tablet, one on mobile; arrows below.
 *
 * Fields: acf-json/group_module_cards_icon.json, cloned into each page's
 * field group. Figma: "Cards with icon section" — desktop 5753:15123,
 * tablet 5753:15154, mobile 5753:15179. Enqueues the Swiper module itself.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'cards_' for a clone named 'cards'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.17.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$cards_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$cards_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$cards_title = get_field( $cards_prefix . 'section_cards_icon_title', $cards_ctx );
$cards_items = get_field( $cards_prefix . 'section_cards_icon_items', $cards_ctx );

if ( ! $cards_items ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$cards_title_id = wp_unique_id( 'section-cards-icon-title-' );
$cards_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 20,
	'breakpoints'   => array(
		768  => array( 'slidesPerView' => 2 ),
		1280 => array( 'slidesPerView' => 4 ),
	),
);
?>
<section class="section-cards-icon"<?php echo $cards_title ? ' aria-labelledby="' . esc_attr( $cards_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-cards-icon__inner" data-swiper-root>
				<?php if ( $cards_title ) : ?>
					<h2 id="<?php echo esc_attr( $cards_title_id ); ?>" class="title-h3 section-cards-icon__title"><?php echo esc_html( $cards_title ); ?></h2>
				<?php endif; ?>

				<div class="swiper section-cards-icon__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $cards_swiper ) ); ?>">
					<div class="swiper-wrapper">
						<?php foreach ( $cards_items as $cards_item ) : ?>
							<article class="swiper-slide section-cards-icon__card">
								<?php if ( ! empty( $cards_item['icon'] ) ) : ?>
									<?php
									echo wp_get_attachment_image(
										$cards_item['icon'],
										'full',
										false,
										array(
											'class' => 'section-cards-icon__icon',
											'alt'   => '',
										)
									);
									?>
								<?php endif; ?>

								<div class="section-cards-icon__body">
									<?php if ( ! empty( $cards_item['title'] ) ) : ?>
										<h3 class="section-cards-icon__card-title"><?php echo esc_html( $cards_item['title'] ); ?></h3>
									<?php endif; ?>
									<?php if ( ! empty( $cards_item['subtitle'] ) ) : ?>
										<p class="section-cards-icon__subtitle"><?php echo esc_html( $cards_item['subtitle'] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $cards_item['text'] ) ) : ?>
										<p><?php echo esc_html( $cards_item['text'] ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="slider-nav slider-nav--walnut section-cards-icon__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous card', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next card', 'montela' ); ?>"></button>
				</div>
			</div>
		</div>
	</div>
</section>
