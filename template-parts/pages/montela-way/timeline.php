<?php
/**
 * Der Montela Weg — timeline: a full-width slider, one year per slide — a
 * sage panel (year, title, text) beside a photo on desktop, under it on
 * tablet and mobile. Cream arrows on the panel; the slides cross-fade.
 *
 * The design shows no heading, so the section is named by a screen-reader
 * one. Fields: tab "Timeline" of acf-json/group_page_montela_way.json.
 * Figma: desktop 5745:13795, tablet 5745:13774, mobile 5745:13756.
 * Enqueues the Swiper module itself.
 *
 * @package montela
 * @subpackage Section
 * @since 1.25.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$timeline_items = get_field( 'section_timeline_items' );

$timeline_items = array_values(
	array_filter(
		(array) $timeline_items,
		function ( $timeline_item ) {
			return ! empty( $timeline_item['year'] );
		}
	)
);

if ( ! $timeline_items ) {
	return;
}

$timeline_many = count( $timeline_items ) > 1;

if ( $timeline_many && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$timeline_title_id = wp_unique_id( 'section-timeline-title-' );
$timeline_swiper   = array(
	'effect'     => 'fade',
	'fadeEffect' => array( 'crossFade' => true ),
	'speed'      => 600,
);
?>
<section class="section-timeline" aria-labelledby="<?php echo esc_attr( $timeline_title_id ); ?>">
	<h2 id="<?php echo esc_attr( $timeline_title_id ); ?>" class="sr-only"><?php esc_html_e( 'Our story, year by year', 'montela' ); ?></h2>

	<div class="section-timeline__inner" data-swiper-root>
		<div class="swiper section-timeline__swiper"<?php echo $timeline_many ? ' data-swiper="' . esc_attr( wp_json_encode( $timeline_swiper ) ) . '"' : ''; ?>>
			<div class="swiper-wrapper">
				<?php foreach ( $timeline_items as $timeline_item ) : ?>
					<article class="swiper-slide section-timeline__slide">
						<div class="section-timeline__panel">
							<h3 class="title-h3 section-timeline__year"><?php echo esc_html( $timeline_item['year'] ); ?></h3>
							<?php if ( ! empty( $timeline_item['title'] ) ) : ?>
								<p class="title-h2 section-timeline__title"><?php echo esc_html( $timeline_item['title'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $timeline_item['text'] ) ) : ?>
								<p class="section-timeline__text"><?php echo esc_html( $timeline_item['text'] ); ?></p>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $timeline_item['image'] ) ) : ?>
							<figure class="section-timeline__photo">
								<?php
								echo wp_get_attachment_image(
									$timeline_item['image'],
									'full',
									false,
									array(
										'class' => 'section-timeline__img',
										'sizes' => '(min-width: 1280px) 50vw, 100vw',
									)
								);
								?>
							</figure>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( $timeline_many ) : ?>
			<div class="slider-nav section-timeline__nav">
				<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous year', 'montela' ); ?>"></button>
				<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next year', 'montela' ); ?>"></button>
			</div>
		<?php endif; ?>
	</div>
</section>
