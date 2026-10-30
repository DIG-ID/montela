<?php
/**
 * Meetings & Groups — group details: cream boxes with a soft shadow, each
 * with a small bold title and a text ("Was inklusive ist und was organisiert
 * werden kann.", "Für welche Anlässe geeignet?"). Two side by side from
 * tablet up, with the texts starting on the same line; stacked on mobile.
 *
 * Fields: "Group details" tab of acf-json/group_page_meetings_groups.json
 * (repeater: title, text — a blank line starts a new paragraph). Figma:
 * "Group 1000005748" — desktop 5895:19961, tablet 5895:20771, mobile
 * 5895:20653. Layout as Tailwind classes here; box style .info-box
 * (_components/_info-box.sass); measurements and type in
 * _pages/_meetings-groups/_group-details.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$gd_items = array_filter(
	(array) get_field( 'section_group_details_items' ),
	static function ( $gd_row ) {
		return is_array( $gd_row ) && ( ! empty( $gd_row['title'] ) || ! empty( $gd_row['text'] ) );
	}
);

// No boxes, no section.
if ( ! $gd_items ) {
	return;
}
?>
<div class="section-group-details pb-32 md:pb-48 text-montela-walnut">
	<div class="theme-container">
		<div class="theme-grid gap-y-5">
			<?php foreach ( $gd_items as $gd_item ) : ?>
				<article class="info-box section-group-details__box col-span-full flex flex-col gap-6 pt-6 pb-6 pl-[21px] pr-4 md:col-span-3 md:row-span-2 md:grid md:grid-rows-subgrid md:pr-11 xl:col-span-5 xl:odd:col-start-2 xl:even:col-start-7 xl:gap-y-[26px] xl:pt-[22px] xl:pb-[46px] xl:pl-[22px] xl:pr-[22px]">
					<?php if ( ! empty( $gd_item['title'] ) ) : ?>
						<h4 class="title-h4 section-group-details__title max-w-[255px] md:max-w-[267px] xl:max-w-[425px]"><?php echo esc_html( $gd_item['title'] ); ?></h4>
					<?php endif; ?>
					<?php if ( ! empty( $gd_item['text'] ) ) : ?>
						<div class="section-group-details__text md:row-start-2 xl:max-w-[570px]"><?php echo wp_kses_post( $gd_item['text'] ); ?></div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</div>
