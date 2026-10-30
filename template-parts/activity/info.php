<?php
/**
 * Single Activity — info cards ("Sicher und gut informiert"): title and
 * text beside two to four cards typed into the post (photo, title, text and
 * an optional button — a page, an outside site or a PDF).
 *
 * The Activities module mirrored: cards on the left half, copy on the
 * right, on desktop; stacked on tablet; one card at a time on mobile, with
 * arrows under the text. Cards: components/card-activity.php (content from
 * the arguments); carousel: assets/js/modules/activities.js.
 *
 * Fields: tab "Info cards" of acf-json/group_cpt_activity.json. Figma:
 * "Activities all_flip over" desktop 5787:18947, tablet 5601:21318, mobile
 * 5601:21423.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$info_title = get_field( 'section_activity_info_title' );
$info_text  = get_field( 'section_activity_info_text' );
$info_items = get_field( 'section_activity_info_items' );

// Rows without a title draw no card.
$info_items = array_values(
	array_filter(
		(array) $info_items,
		function ( $info_item ) {
			return ! empty( $info_item['title'] );
		}
	)
);

if ( ! $info_items ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'activities' );
}

$info_title_id = wp_unique_id( 'section-activity-info-title-' );
?>
<section class="section-activity-info"<?php echo $info_title ? ' aria-labelledby="' . esc_attr( $info_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-activity-info__inner" data-activities-carousel>
			<div class="section-activity-info__frame" aria-hidden="true"></div>

			<div class="section-activity-info__copy">
				<?php if ( $info_title ) : ?>
					<h2 id="<?php echo esc_attr( $info_title_id ); ?>" class="title-h3 section-activity-info__title"><?php echo wp_kses( $info_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $info_text ) : ?>
					<p class="body-text section-activity-info__text"><?php echo esc_html( $info_text ); ?></p>
				<?php endif; ?>

				<?php if ( count( $info_items ) > 1 ) : ?>
					<div class="slider-nav slider-nav--sage section-activity-info__nav">
						<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-activities-prev aria-label="<?php esc_attr_e( 'Previous card', 'montela' ); ?>"></button>
						<button class="slider-nav__btn slider-nav__btn--next" type="button" data-activities-next aria-label="<?php esc_attr_e( 'Next card', 'montela' ); ?>"></button>
					</div>
				<?php endif; ?>
			</div>

			<div class="section-activity-info__cards" data-activities-track>
				<?php
				foreach ( $info_items as $info_item ) :
					get_template_part(
						'template-parts/components/card-activity',
						null,
						array(
							'title'     => $info_item['title'],
							'text'      => ! empty( $info_item['text'] ) ? $info_item['text'] : '',
							'image'     => ! empty( $info_item['image'] ) ? $info_item['image'] : 0,
							'link'      => ! empty( $info_item['link'] ) ? $info_item['link'] : null,
							// Straight under the section's h2.
							'title_tag' => 'h3',
						)
					);
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>
