<?php
/**
 * Cards with icon module: a centred title (and optional text) over a slider
 * of framed cards — icon, title, a short line and a text in each. Four
 * cards in view on desktop, two on tablet, one on mobile; arrows below.
 *
 * Two card styles (field "Card style"): "frame" — cream with a sage hairline
 * (Unsere Region) — and "filled" — sage with cream text (Fitnessroom); they
 * differ in colour and in the space above and below the icon. Fewer cards
 * than fit are centred.
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

// The two styles differ in colour and in the space around the icon.
if ( 'filled' === $cards_style ) {
	$cards_card_class = 'bg-montela-sage text-montela-cream pt-10 md:pt-[43px] xl:pt-[44px]';
	$cards_body_class = 'mt-[59px] md:mt-[95px] xl:mt-[74px]';
	$cards_nav_gap    = 'gap-9 md:gap-11 xl:gap-9';
	$cards_head_gap   = 'gap-6';
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

// The filled style (Fitnessroom) keeps its arrows, disabled, when every card
// is in view, as drawn; the frame style hides them.
if ( 'filled' === $cards_style ) {
	$cards_swiper['watchOverflow'] = false;
}
?>
<section class="section-cards-icon section-cards-icon--<?php echo esc_attr( $cards_style . ' ' . $cards_class ); ?> text-montela-walnut"<?php echo $cards_title ? ' aria-labelledby="' . esc_attr( $cards_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-cards-icon__inner col-span-full flex flex-col items-center gap-9 md:gap-11 xl:col-start-2 xl:col-span-10 xl:gap-24" data-swiper-root>
				<?php if ( $cards_title || $cards_text ) : ?>
					<div class="section-cards-icon__head flex flex-col items-center text-center xl:max-w-[1217px] <?php echo esc_attr( $cards_head_gap ); ?>">
						<?php if ( $cards_title ) : ?>
							<h3 id="<?php echo esc_attr( $cards_title_id ); ?>" class="title-h3 section-cards-icon__title"><?php echo esc_html( $cards_title ); ?></h3>
						<?php endif; ?>
						<?php if ( $cards_text ) : ?>
							<p class="body-text section-cards-icon__text xl:max-w-[957px]"><?php echo esc_html( $cards_text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<div class="section-cards-icon__slider flex w-full flex-col items-center <?php echo esc_attr( $cards_nav_gap ); ?>">
					<div class="swiper section-cards-icon__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $cards_swiper ) ); ?>">
						<div class="swiper-wrapper">
							<?php foreach ( $cards_items as $cards_item ) : ?>
								<article class="swiper-slide section-cards-icon__card px-[35px] pb-10 xl:px-5 2xl:px-[35px] <?php echo esc_attr( $cards_card_class ); ?>">
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
	</div>
</section>
