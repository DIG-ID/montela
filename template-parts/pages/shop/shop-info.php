<?php
/**
 * Shop — shop info: everything about the resort shop in one section.
 * Title, then the text beside a photo (desktop, tablet) — on mobile the
 * photo comes first; then the photo strip and the opening hours box.
 *
 * Fields: "Shop info" tab of acf-json/group_page_shop.json. The strip is
 * components/photo-slider.php (variant photo-slider--subpage, as on Kids
 * Playground and Lounge & Bar); the opening hours box is the Box text module
 * (modules/box-text.php), its fields cloned into the same tab.
 * Figma: "Praktisch, regional und direkt im Resort" — desktop 5895:19805,
 * tablet 5895:20731, mobile 5895:20616; strip 5895:19790 / 5895:20738 /
 * 5895:20622; box 5895:19813 / 5895:20744 / 5895:20631.
 * Layout as Tailwind classes here; measurements and the box type overrides
 * in _pages/_shop/_shop-info.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$si_title  = get_field( 'section_shop_info_title' );
$si_text   = get_field( 'section_shop_info_text' );
$si_image  = get_field( 'section_shop_info_image' );
$si_slider = get_field( 'section_shop_info_slider' );

if ( ! $si_title && ! $si_text && ! $si_image && ! $si_slider ) {
	return;
}

$si_title_id = wp_unique_id( 'section-shop-info-title-' );
?>
<section class="section-shop-info text-montela-walnut"<?php echo $si_title ? ' aria-labelledby="' . esc_attr( $si_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-shop-info__grid gap-y-8 pb-11 md:gap-y-11 xl:gap-y-16">
			<?php if ( $si_title ) : ?>
				<h2 id="<?php echo esc_attr( $si_title_id ); ?>" class="title-h3 section-shop-info__title col-span-full xl:col-start-2 xl:col-span-10">
					<?php echo wp_kses( $si_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>

			<?php if ( $si_text ) : ?>
				<p class="body-text section-shop-info__text col-span-full md:col-span-2 xl:col-start-2 xl:col-span-3"><?php echo esc_html( $si_text ); ?></p>
			<?php endif; ?>

			<?php if ( $si_image ) : ?>
				<figure class="section-shop-info__photo col-span-full order-first self-start m-0 md:self-stretch xl:self-start overflow-hidden aspect-[326/268] md:order-none md:col-start-3 md:col-span-4 md:aspect-[463/347] xl:col-start-5 xl:col-span-7 xl:aspect-[1053/600]">
					<?php
					echo wp_get_attachment_image(
						$si_image,
						'large',
						false,
						array(
							'class' => 'section-shop-info__img w-full h-full object-cover',
							'sizes' => '(min-width: 1280px) 55vw, (min-width: 768px) 56vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>

	<?php
	if ( $si_slider ) :
		get_template_part(
			'template-parts/components/photo-slider',
			null,
			array(
				'images' => $si_slider,
				'class'  => 'photo-slider--subpage',
			)
		);
	endif;

	// 44 / 96 / 128px around the box (measured on Shop, as on Kids and Lounge).
	get_template_part( 'template-parts/modules/box-text', null, array( 'class' => 'my-11 md:my-24 xl:my-32' ) );
	?>
</section>
