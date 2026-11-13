<?php
/**
 * Leistungen — "Frühstück": a framed box with the title beside a photo strip
 * (components/photo-slider.php) that runs off the right edge of the screen,
 * then a full-width photo with a title, a text and a list (the Horizontal
 * module — dogs welcome). Desktop: the box on columns 1–4, the strip from
 * column 5. Tablet and mobile: the box across, the strip below it.
 *
 * Fields: tab "Breakfast" of acf-json/group_page_services.json (with the
 * Horizontal module's group cloned in). Figma: the whole section desktop
 * 5787:19075, tablet 5787:19085, mobile 5787:19081 — box and strip 5426:6929
 * / 5528:6641 / 5532:7276, Horizontal 5787:19029 / 5787:19030 / 5787:19031.
 * Measurements: _pages/_services.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.39.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$breakfast_title  = get_field( 'section_breakfast_title' );
$breakfast_text   = get_field( 'section_breakfast_text' );
$breakfast_images = get_field( 'section_breakfast_images' );

if ( ! $breakfast_title && ! $breakfast_images && ! get_field( 'section_horizontal_image' ) ) {
	return;
}

$breakfast_title_id = wp_unique_id( 'section-breakfast-title-' );
?>
<section class="section-breakfast text-montela-walnut"<?php echo $breakfast_title ? ' aria-labelledby="' . esc_attr( $breakfast_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-3 md:gap-y-8 mb-5 md:mb-3 xl:mb-5">
			<?php if ( $breakfast_title || $breakfast_text ) : ?>
				<div class="section-breakfast__box anim-fade-up col-span-full flex flex-col gap-6 border border-montela-walnut px-5 pt-6 pb-5 min-h-[92px] md:min-h-[304px] xl:col-span-4 xl:min-h-0 xl:px-5 xl:pt-[69px] xl:pb-10 2xl:pl-[71px] 2xl:pr-10">
					<?php if ( $breakfast_title ) : ?>
						<h2 id="<?php echo esc_attr( $breakfast_title_id ); ?>" class="title-h3 section-breakfast__title"><?php echo montela_text( $breakfast_title ); ?></h2>
					<?php endif; ?>
					<?php if ( $breakfast_text ) : ?>
						<div class="body-text"><?php echo wp_kses_post( $breakfast_text ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			if ( $breakfast_images ) :
				get_template_part(
					'template-parts/components/photo-slider',
					null,
					array(
						'images'     => $breakfast_images,
						'class'      => 'photo-slider--subpage photo-slider--side anim-fade-right col-span-full -mr-8 md:-mr-16 xl:col-start-5 xl:col-span-8 xl:-mr-[50px] min-w-0',
						'mobile_gap' => 20,
					)
				);
			endif;
			?>
		</div>
	</div>

	<?php
	// The dogs block: the photo crop is moved up on desktop, as in Figma.
	get_template_part(
		'template-parts/modules/horizontal',
		null,
		array(
			'title_tag'   => 'h3',
			'image_class' => 'xl:object-[50%_22%]',
		)
	);
	?>
</section>
