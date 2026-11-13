<?php
/**
 * Cards with icon module: a centred title (and optional text) over a slider
 * of framed cards — icon, title, a short line and a text in each. Four
 * cards in view on desktop, two on tablet, one on mobile; arrows below.
 *
 * Two card styles (field "Card style"): "frame" — cream with a sage hairline
 * (Unsere Region) — and "filled" — sage with cream text (Fitnessroom); they
 * differ in colour and in the space above and below the icon. Fewer cards
 * than fit are centred. A card can end with a button (item "Button",
 * Leistungen): then the cards take the filled style's spacing, the button
 * as far below the text as the text is below the icon.
 *
 * Fields: acf-json/group_module_cards_icon.json, cloned into each page's
 * field group. Figma: "Cards with icon section" — desktop 5753:15123,
 * tablet 5753:15154, mobile 5753:15179; filled on Fitnessroom 5895:20061 /
 * 5895:20837 / 5895:20722. Enqueues the Swiper module itself. Layout as
 * Tailwind classes here; measurements and the card frame in
 * _modules/_cards-icon.sass.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'cards_' for a clone named 'cards'.
 * - class   (string)     Optional. Margins around the section, replacing the
 *                        default (128px below on mobile, 192 from tablet up).
 * - nav     (string)     Optional. 'auto' (default) hides the arrows when every
 *                        card is in view; 'always' keeps them, disabled, as
 *                        drawn on the Fitnessroom.
 * - width   (string)     Optional. 'fluid' (default) shares the row between
 *                        the cards in view; 'fixed' gives each card the
 *                        width drawn on the Fitnessroom — 340px on tablet,
 *                        369 from 1536px up (fewer than four cards — four at
 *                        369 need ~1940px).
 * - spacing (string)     Optional. 'frame' gives filled cards the frame
 *                        style's spacing — the text further down, near the
 *                        middle of the card (Fitnessroom, Bruno 2026-11-10).
 *                        Default: each style's own.
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
$cards_text  = get_field( $cards_prefix . 'section_cards_icon_text', $cards_ctx );
$cards_items = get_field( $cards_prefix . 'section_cards_icon_items', $cards_ctx );
$cards_style = 'filled' === get_field( $cards_prefix . 'section_cards_icon_style', $cards_ctx ) ? 'filled' : 'frame';

if ( ! $cards_items ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$cards_title_id = wp_unique_id( 'section-cards-icon-title-' );
$cards_class    = ! empty( $args['class'] ) ? $args['class'] : 'mb-[128px] md:mb-[192px]';

// Cards with a button (Leistungen) are spaced as the filled ones, in either
// style.
$cards_has_button = (bool) array_filter(
	$cards_items,
	function ( $cards_item ) {
		return ! empty( $cards_item['link']['url'] );
	}
);

// The two styles differ in colour and in the space around the icon; a page
// can give filled cards the frame spacing ('spacing' => 'frame').
$cards_btn_class     = 'mt-[59px] md:mt-[95px] xl:mt-[74px]';
$cards_frame_spacing = 'filled' === $cards_style && ! empty( $args['spacing'] ) && 'frame' === $args['spacing'] && ! $cards_has_button;
if ( $cards_frame_spacing ) {
	$cards_card_class = 'bg-montela-sage text-montela-cream pt-10 md:pt-[60px] xl:pt-[76px]';
	$cards_body_class = 'mt-[59px] md:mt-[75px] xl:mt-[120px]';
	$cards_nav_gap    = 'gap-9 md:gap-11 xl:gap-9';
	$cards_head_gap   = 'gap-6';
} elseif ( 'filled' === $cards_style || $cards_has_button ) {
	$cards_card_class = ( 'filled' === $cards_style ? 'bg-montela-sage text-montela-cream ' : '' ) . 'pt-10 md:pt-[43px] xl:pt-[44px]';
	$cards_body_class = 'mt-[59px] md:mt-[95px] xl:mt-[74px]';
	$cards_nav_gap    = 'filled' === $cards_style ? 'gap-9 md:gap-11 xl:gap-9' : 'gap-9 md:gap-11 xl:gap-24';
	$cards_head_gap   = 'filled' === $cards_style ? 'gap-6' : 'gap-4 xl:gap-6';
} else {
	$cards_card_class = 'pt-10 md:pt-[60px] xl:pt-[76px]';
	$cards_body_class = 'mt-[59px] md:mt-[75px] xl:mt-[120px]';
	$cards_nav_gap    = 'gap-9 md:gap-11 xl:gap-24';
	$cards_head_gap   = 'gap-4 xl:gap-6';
}

$cards_swiper = array(
	'slidesPerView'            => 1,
	'spaceBetween'             => 20,
	'centerInsufficientSlides' => true,
	'breakpoints'              => array(
		768  => array( 'slidesPerView' => 2 ),
		1280 => array( 'slidesPerView' => 4 ),
	),
);

// Arrows hide when every card is in view, unless the page keeps them
// (Fitnessroom: disabled, as drawn).
$cards_nav_always = ! empty( $args['nav'] ) && 'always' === $args['nav'];
if ( $cards_nav_always ) {
	$cards_swiper['watchOverflow'] = false;
}

// Fixed-width cards (Fitnessroom): on tablet and from 1536px the card width
// comes from the SASS, not from the number of cards in view.
$cards_fixed = ! empty( $args['width'] ) && 'fixed' === $args['width'];
if ( $cards_fixed ) {
	$cards_swiper['breakpoints'][768]  = array( 'slidesPerView' => 'auto' );
	$cards_swiper['breakpoints'][1536] = array( 'slidesPerView' => 'auto' );
	ksort( $cards_swiper['breakpoints'] );
}
?>
<section class="section-cards-icon section-cards-icon--<?php echo esc_attr( $cards_style . ( $cards_has_button ? ' section-cards-icon--button' : '' ) . ( $cards_fixed ? ' section-cards-icon--fixed' : '' ) . ' ' . $cards_class ); ?> text-montela-walnut"<?php echo $cards_title ? ' aria-labelledby="' . esc_attr( $cards_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-cards-icon__inner col-span-full flex flex-col items-center gap-9 md:gap-11 xl:col-start-2 xl:col-span-10 xl:gap-24" data-swiper-root>
				<?php if ( $cards_title || $cards_text ) : ?>
					<div class="section-cards-icon__head flex flex-col items-center text-center xl:max-w-[1217px] <?php echo esc_attr( $cards_head_gap ); ?>">
						<?php if ( $cards_title ) : ?>
							<h2 id="<?php echo esc_attr( $cards_title_id ); ?>" class="title-h3 section-cards-icon__title anim-fade-up"><?php echo montela_text( $cards_title ); ?></h2>
						<?php endif; ?>
						<?php if ( $cards_text ) : ?>
							<p class="body-text section-cards-icon__text anim-fade-up xl:max-w-[957px]"><?php echo montela_text( $cards_text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="section-cards-icon__slider anim-fade-up flex w-full flex-col items-center <?php echo esc_attr( $cards_nav_gap ); ?>">
					<div class="swiper section-cards-icon__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $cards_swiper ) ); ?>">
						<div class="swiper-wrapper">
							<?php foreach ( $cards_items as $cards_item ) : ?>
								<article class="swiper-slide section-cards-icon__card px-[35px] pb-10 xl:px-5 2xl:px-[35px] <?php echo esc_attr( $cards_card_class . ( $cards_has_button ? ' md:pb-5 xl:pb-10' : '' ) ); ?>">
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

									<div class="section-cards-icon__body <?php echo esc_attr( $cards_body_class ); ?>">
										<?php if ( ! empty( $cards_item['title'] ) ) : ?>
											<h3 class="section-cards-icon__card-title"><?php echo montela_text( $cards_item['title'] ); ?></h3>
										<?php endif; ?>
										<?php if ( ! empty( $cards_item['subtitle'] ) ) : ?>
											<p class="section-cards-icon__subtitle"><?php echo montela_text( $cards_item['subtitle'] ); ?></p>
										<?php endif; ?>
										<?php if ( ! empty( $cards_item['text'] ) ) : ?>
											<p><?php echo montela_text( $cards_item['text'] ); ?></p>
										<?php endif; ?>
									</div>

									<?php if ( ! empty( $cards_item['link']['url'] ) ) : ?>
										<div class="<?php echo esc_attr( $cards_btn_class ); ?>">
											<?php get_template_part( 'template-parts/components/button', null, array_merge( $cards_item['link'], array( 'style' => 'filled' === $cards_style ? 'ghost' : 'outline' ) ) ); ?>
										</div>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="slider-nav slider-nav--walnut section-cards-icon__nav">
						<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Vorherige Karte', 'montela' ); ?>"></button>
						<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Nächste Karte', 'montela' ); ?>"></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
