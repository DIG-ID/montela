<?php
/**
 * Fitnessroom — fitness usage: the photo strip, then the notes on using the
 * room in cream boxes ("Rücksichtsvoll trainieren", "Nutzung auf eigene
 * Verantwortung", "Für Hotelgäste") and a button to the full rules.
 *
 * From tablet up the boxes sit in two columns: all but the last stacked on
 * the left, the last one on the right, as tall as the left column. On
 * mobile they stack in order. With a single box it stays on the left.
 *
 * Fields: "Fitness usage" tab of acf-json/group_page_fitnessroom.json
 * (slider gallery, items repeater title / text, button link). The strip is
 * components/photo-slider.php (variant photo-slider--subpage, as on Kids
 * Playground, Lounge & Bar, Shop and Meetings); the box style is .info-box
 * (_components/_info-box.sass, shared with Meetings' group details).
 * Figma: strip "Group 1000006320" — desktop 5895:20015, tablet 5895:20840,
 * mobile 5895:20679; boxes "Frame 1000006603" — 5895:20024 / 5895:20784 /
 * 5895:20668. Layout as Tailwind classes here; measurements and type in
 * _pages/_fitnessroom/_fitness-usage.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$fu_slider   = get_field( 'section_fitness_usage_slider' );
$fu_button   = get_field( 'section_fitness_usage_button' );
$fu_items    = array_values(
	array_filter(
		(array) get_field( 'section_fitness_usage_items' ),
		static function ( $fu_row ) {
			return is_array( $fu_row ) && ( ! empty( $fu_row['title'] ) || ! empty( $fu_row['text'] ) );
		}
	)
);
$fu_has_link = is_array( $fu_button ) && ! empty( $fu_button['url'] ) && ! empty( $fu_button['title'] );

if ( ! $fu_slider && ! $fu_items && ! $fu_has_link ) {
	return;
}

// Last box on the right (tablet up), the others stacked on the left.
$fu_right = count( $fu_items ) > 1 ? array_pop( $fu_items ) : null;

// Heights and title → text gaps as drawn, per box (mobile / tablet; desktop
// boxes hug their text, 38px gap). Heights are minimums, so longer text
// still fits. Tablet: 225 + 20 + 283 on the left = the right box's 528.
$fu_left_class  = array(
	'min-h-[166px] gap-6 md:min-h-[225px] xl:min-h-0 xl:gap-[38px]',
	'min-h-[153px] gap-9 md:min-h-[283px] md:gap-6 xl:min-h-0 xl:gap-[38px]',
);
$fu_right_class = 'min-h-[389px] gap-[52px] md:min-h-0 xl:gap-[38px]';

$fu_box_class = 'info-box section-fitness-usage__box flex flex-col pt-6 pb-6 pl-[21px] pr-8 md:pr-11 xl:gap-[38px] xl:pt-[22px] xl:pb-10 xl:pl-[22px] xl:pr-[22px]';
?>
<div class="section-fitness-usage pb-32 md:pb-48 text-montela-walnut">
	<?php
	if ( $fu_slider ) :
		get_template_part(
			'template-parts/components/photo-slider',
			null,
			array(
				'images' => $fu_slider,
				'class'  => 'photo-slider--subpage',
			)
		);
	endif;
	?>

	<?php if ( $fu_items || $fu_right || $fu_has_link ) : ?>
		<div class="theme-container<?php echo $fu_slider ? ' mt-32 md:mt-48' : ''; ?>">
			<?php if ( $fu_items ) : ?>
				<div class="theme-grid gap-y-5">
					<div class="section-fitness-usage__column col-span-full flex flex-col gap-5 md:col-span-3 xl:col-start-2 xl:col-span-5">
						<?php foreach ( $fu_items as $fu_i => $fu_item ) : ?>
							<article class="<?php echo esc_attr( $fu_box_class . ' ' . ( isset( $fu_left_class[ $fu_i ] ) ? $fu_left_class[ $fu_i ] : 'gap-6 xl:gap-[38px]' ) ); ?> md:grow">
								<?php if ( ! empty( $fu_item['title'] ) ) : ?>
									<h4 class="title-h4 section-fitness-usage__title max-w-[255px] md:max-w-[267px] xl:max-w-[425px]"><?php echo esc_html( $fu_item['title'] ); ?></h4>
								<?php endif; ?>
								<?php if ( ! empty( $fu_item['text'] ) ) : ?>
									<div class="section-fitness-usage__text xl:max-w-[570px]"><?php echo wp_kses_post( $fu_item['text'] ); ?></div>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>

					<?php if ( $fu_right ) : ?>
						<article class="<?php echo esc_attr( $fu_box_class . ' ' . $fu_right_class ); ?> col-span-full md:col-span-3 xl:col-span-5">
							<?php if ( ! empty( $fu_right['title'] ) ) : ?>
								<h4 class="title-h4 section-fitness-usage__title max-w-[255px] md:max-w-[267px] xl:max-w-[425px]"><?php echo esc_html( $fu_right['title'] ); ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $fu_right['text'] ) ) : ?>
								<div class="section-fitness-usage__text xl:max-w-[570px]"><?php echo wp_kses_post( $fu_right['text'] ); ?></div>
							<?php endif; ?>
						</article>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $fu_has_link ) : ?>
				<div class="section-fitness-usage__button flex justify-center<?php echo $fu_items ? ' mt-5 md:mt-11' : ''; ?>">
					<?php get_template_part( 'template-parts/components/button', null, array_merge( $fu_button, array( 'style' => 'secondary' ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
