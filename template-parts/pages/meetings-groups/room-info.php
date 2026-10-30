<?php
/**
 * Meetings & Groups — room info: everything about the multi-purpose room in
 * one section. Title, then a photo beside the text, a bullet list and a
 * button (desktop, tablet) — on mobile the photo comes first and there is
 * no button, as drawn; then the photo strip. "Mehrzweckraum im Haus C".
 *
 * Fields: "Room info" tab of acf-json/group_page_meetings_groups.json. The
 * strip is components/photo-slider.php (variant photo-slider--subpage, as on
 * Kids Playground, Lounge & Bar and Shop — same slide sizes and arrows).
 * Figma: "section-find-apartment" — desktop 5895:19981, tablet 5895:20756,
 * mobile 5895:20640; strip 5895:19972 / 5895:20766 / 5895:20648.
 * Layout as Tailwind classes here; measurements and the list style in
 * _pages/_meetings-groups/_room-info.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$ri_title  = get_field( 'section_room_info_title' );
$ri_text   = get_field( 'section_room_info_text' );
$ri_list   = get_field( 'section_room_info_list' );
$ri_button = get_field( 'section_room_info_button' );
$ri_image  = get_field( 'section_room_info_image' );
$ri_slider = get_field( 'section_room_info_slider' );

// One list item per line of the textarea; blank lines dropped.
$ri_items = $ri_list ? array_filter( array_map( 'trim', preg_split( '/\R/', (string) $ri_list ) ) ) : array();

if ( ! $ri_title && ! $ri_text && ! $ri_items && ! $ri_image && ! $ri_slider ) {
	return;
}

$ri_title_id = wp_unique_id( 'section-room-info-title-' );
$ri_has_link = is_array( $ri_button ) && ! empty( $ri_button['url'] );
?>
<section class="section-room-info pb-32 md:pb-48 text-montela-walnut"<?php echo $ri_title ? ' aria-labelledby="' . esc_attr( $ri_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-8 pb-11 md:gap-y-9 xl:gap-y-16">
			<?php if ( $ri_title ) : ?>
				<h3 id="<?php echo esc_attr( $ri_title_id ); ?>" class="title-h3 section-room-info__title col-span-full xl:col-start-2 xl:col-span-10">
					<?php echo wp_kses( $ri_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h3>
			<?php endif; ?>

			<?php if ( $ri_image ) : ?>
				<figure class="section-room-info__photo col-span-full order-first self-start m-0 overflow-hidden aspect-[326/268] md:order-none md:col-span-3 md:aspect-[343/536] xl:col-start-2 xl:col-span-7 xl:aspect-[1053/600]">
					<?php
					echo wp_get_attachment_image(
						$ri_image,
						'large',
						false,
						array(
							'class' => 'section-room-info__img w-full h-full object-cover',
							'sizes' => '(min-width: 1280px) 55vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $ri_text || $ri_items || $ri_has_link ) : ?>
				<div class="section-room-info__content col-span-full flex flex-col items-start gap-5 md:col-start-4 md:col-span-3 md:gap-11 xl:col-start-9 xl:col-span-3 xl:gap-16">
					<?php if ( $ri_text ) : ?>
						<p class="body-text section-room-info__text md:max-w-[340px] xl:max-w-none"><?php echo esc_html( $ri_text ); ?></p>
					<?php endif; ?>

					<?php if ( $ri_items ) : ?>
						<ul class="body-text section-room-info__list">
							<?php foreach ( $ri_items as $ri_item ) : ?>
								<li><?php echo esc_html( $ri_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $ri_has_link ) : ?>
						<div class="section-room-info__button hidden md:block">
							<?php get_template_part( 'template-parts/components/button', null, array_merge( $ri_button, array( 'style' => 'secondary' ) ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	if ( $ri_slider ) :
		get_template_part(
			'template-parts/components/photo-slider',
			null,
			array(
				'images' => $ri_slider,
				'class'  => 'photo-slider--subpage',
			)
		);
	endif;
	?>
</section>
