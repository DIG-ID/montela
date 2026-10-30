<?php
/**
 * Lounge & Bar — lounge info: everything about the lounge in one section.
 * Title, text and a button in a box with a hairline border, beside a wide
 * photo on desktop and above it below that; then the photo strip and the
 * opening hours box. The button can point to a file (the drinks menu PDF):
 * choose the down arrow for it in the "Button icon" field.
 *
 * Fields: "Lounge info" tab of acf-json/group_page_lounge_bar.json. The strip
 * is components/photo-slider.php (shared with the homepage Gallery); the
 * opening hours box is the Box text module (modules/box-text.php, also on
 * Kids Playground, Shop, Explore), its fields cloned into the same tab.
 * Figma: "Lounge & Bar" — desktop 5895:20872, tablet 5895:20476, mobile
 * 5895:20253; strip 5895:20885 / 5895:20492 / 5895:20269; box 5895:20901.
 * Layout as Tailwind classes here; measurements and the photo's ratio
 * spacer in _pages/_lounge-bar/_lounge-info.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$li_title       = get_field( 'section_lounge_info_title' );
$li_text        = get_field( 'section_lounge_info_text' );
$li_button      = get_field( 'section_lounge_info_button' );
$li_button_icon = get_field( 'section_lounge_info_button_icon' );
$li_image       = get_field( 'section_lounge_info_image' );
$li_slider      = get_field( 'section_lounge_info_slider' );

if ( ! $li_title && ! $li_text && ! $li_image && ! $li_slider ) {
	return;
}

$li_title_id = wp_unique_id( 'section-lounge-info-title-' );
?>
<section class="section-lounge-info text-montela-walnut"<?php echo $li_title ? ' aria-labelledby="' . esc_attr( $li_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-lounge-info__grid gap-y-6 pb-6 md:pb-10 xl:pb-5 xl:items-stretch">
			<div class="section-lounge-info__box col-span-full flex flex-col items-start border border-montela-walnut pt-[26px] pl-3 pr-3 pb-4 md:pt-[46px] md:pl-[22px] md:pr-[22px] md:pb-[26px] xl:col-span-5 xl:pt-[69px] xl:pl-[71px] xl:pr-[102px] xl:pb-[64px]">
				<?php if ( $li_title ) : ?>
					<h2 id="<?php echo esc_attr( $li_title_id ); ?>" class="title-h3 section-lounge-info__title">
						<?php echo wp_kses( $li_title, array( 'br' => array( 'class' => array() ) ) ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $li_text ) : ?>
					<p class="body-text section-lounge-info__text mt-4 md:mt-14 max-w-[574px]"><?php echo esc_html( $li_text ); ?></p>
				<?php endif; ?>

				<?php if ( is_array( $li_button ) ) : ?>
					<div class="section-lounge-info__button mt-4 md:mt-9 xl:mt-auto xl:pt-9">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array_merge(
								$li_button,
								array(
									'style' => 'secondary',
									'icon'  => 'arrow-down' === $li_button_icon ? 'arrow-down' : 'arrow-right',
								)
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $li_image ) : ?>
				<figure class="section-lounge-info__photo col-span-full m-0 overflow-hidden aspect-[329/204] md:aspect-[706/292] xl:relative xl:aspect-auto xl:col-start-6 xl:col-span-7">
					<?php
					echo wp_get_attachment_image(
						$li_image,
						'large',
						false,
						array(
							'class' => 'section-lounge-info__img w-full h-full object-cover xl:absolute xl:inset-0',
							'sizes' => '(min-width: 1280px) 55vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>

	<?php
	if ( $li_slider ) :
		get_template_part(
			'template-parts/components/photo-slider',
			null,
			array(
				'images' => $li_slider,
				'class'  => 'photo-slider--subpage',
			)
		);
	endif;

	// 44 / 96 / 128px around the box (measured on Kids and Lounge).
	get_template_part( 'template-parts/modules/box-text', null, array( 'class' => 'my-11 md:my-24 xl:my-32' ) );
	?>
</section>
