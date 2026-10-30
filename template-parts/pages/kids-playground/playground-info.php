<?php
/**
 * Kids Playground — playground info: everything about the indoor play area
 * in one section. A photo beside a large title and text, a list of features
 * with icons (two columns on desktop), then the photo strip and the opening
 * hours box.
 *
 * Fields: "Playground info" tab of acf-json/group_page_kids_playground.json.
 * The strip is components/photo-slider.php (shared with the homepage
 * Gallery); the opening hours box is the Box text module (modules/box-text.php,
 * also on Lounge & Bar, Shop, Explore), its fields cloned into the same tab.
 * Figma: "Indoor-Spielbereich für kleine Gäste" — desktop 5895:19892, tablet
 * 5895:20506, mobile 5895:20297; strip 5895:19913 / 5895:20532 / 5895:20322;
 * box 5895:19924 / 5895:20545 / 5895:20331. Layout as Tailwind classes here;
 * measurements and the remaining styles in
 * _pages/_kids-playground/_playground-info.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$pi_title  = get_field( 'section_playground_info_title' );
$pi_text   = get_field( 'section_playground_info_text' );
$pi_image  = get_field( 'section_playground_info_image' );
$pi_items  = get_field( 'section_playground_info_items' );
$pi_slider = get_field( 'section_playground_info_slider' );

if ( ! $pi_title && ! $pi_text && ! $pi_image && ! $pi_items && ! $pi_slider ) {
	return;
}

$pi_title_id = wp_unique_id( 'section-playground-info-title-' );
?>
<section class="section-playground-info text-montela-walnut"<?php echo $pi_title ? ' aria-labelledby="' . esc_attr( $pi_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-playground-info__grid gap-y-11 pb-11 md:gap-y-0 xl:gap-y-11">
			<?php if ( $pi_title || $pi_text ) : ?>
				<div class="section-playground-info__content col-span-full xl:col-start-8 xl:col-span-5 xl:row-start-1">
					<?php if ( $pi_title ) : ?>
						<h3 id="<?php echo esc_attr( $pi_title_id ); ?>" class="title-h3 section-playground-info__title xl:max-w-[660px]">
							<?php echo wp_kses( $pi_title, array( 'br' => array( 'class' => array() ) ) ); ?>
						</h3>
					<?php endif; ?>

					<?php if ( $pi_text ) : ?>
						<p class="body-text section-playground-info__text mt-3 md:mt-[52px] md:max-w-[578px] xl:mt-9 xl:max-w-[440px]"><?php echo esc_html( $pi_text ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $pi_image ) : ?>
				<figure class="section-playground-info__photo col-span-full m-0 md:mt-[112px] xl:mt-0 overflow-hidden aspect-[329/246] md:aspect-[706/452] xl:aspect-[747/470] xl:col-start-2 xl:col-span-5 xl:row-start-1 xl:self-start">
					<?php
					echo wp_get_attachment_image(
						$pi_image,
						'large',
						false,
						array(
							'class' => 'section-playground-info__img w-full h-full object-cover',
							'sizes' => '(min-width: 1280px) 39vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/components/icon-list',
				null,
				array(
					'items'      => $pi_items,
					'class'      => 'section-playground-info__list col-span-full gap-4 md:mt-11 md:gap-[72px] xl:mt-0 md:max-w-[467px] xl:max-w-none xl:col-start-2 xl:col-span-10 xl:grid xl:grid-cols-subgrid xl:gap-x-5 xl:gap-y-[126px] xl:pb-[14px]',
					'item_class' => array( 'xl:col-span-3 xl:col-start-1', 'xl:col-span-3 xl:col-start-6' ),
				)
			);
			?>
		</div>
	</div>

	<?php
	if ( $pi_slider ) :
		get_template_part(
			'template-parts/components/photo-slider',
			null,
			array(
				'images' => $pi_slider,
				'class'  => 'photo-slider--subpage',
			)
		);
	endif;

	// 44 / 96 / 128px around the box (measured on Kids and Lounge).
	get_template_part( 'template-parts/modules/box-text', null, array( 'class' => 'my-11 md:my-24 xl:my-32' ) );
	?>
</section>
