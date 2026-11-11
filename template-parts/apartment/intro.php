<?php
/**
 * Single Apartment — intro: title, booking button and text, over a sage
 * hairline. Mobile: title, text, button. Tablet: title and button on
 * columns 1–3, the text on 4–6. Desktop: title and button on columns 2–6,
 * the text on 8–11.
 *
 * Text: basic WYSIWYG (since 1.51.0 — a textarea before; the old text keeps
 * its paragraphs and line breaks).
 *
 * Booking: button 1 falls back to the header's "Jetzt buchen" link (Theme
 * Settings); button 2 (a second building, since 1.51.0) only shows when
 * filled. Side by side on desktop (wrapping when the columns are too
 * narrow), one under the other below. Not in the Figma.
 *
 * Fields: tab "Intro" of acf-json/group_cpt_apartment.json. Figma: desktop
 * 5812:15629, tablet 5534:8383, mobile 5534:8218 (title from another page:
 * the desktop one is followed). Measurements: _posts/_apartment.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.34.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$apt_title   = get_field( 'section_apartment_intro_title' );
$apt_text    = get_field( 'section_apartment_intro_text' );
$apt_booking = get_field( 'section_apartment_booking' );
if ( ! $apt_booking ) {
	$apt_booking = get_field( 'header_booking_link', 'option' );
}
$apt_buttons = array_filter(
	array( $apt_booking, get_field( 'section_apartment_booking_2' ) ),
	function ( $apt_link ) {
		return is_array( $apt_link ) && ! empty( $apt_link['url'] );
	}
);

if ( ! $apt_title && ! $apt_text ) {
	return;
}

$apt_title_id = wp_unique_id( 'section-apartment-intro-title-' );
?>
<section class="theme-container section-apartment-intro mt-4 md:mt-11 xl:mt-[100px] text-montela-walnut"<?php echo $apt_title ? ' aria-labelledby="' . esc_attr( $apt_title_id ) . '"' : ''; ?>>
	<div class="theme-grid gap-y-4 md:gap-y-0 pb-11 md:pb-14 xl:pb-[77px] border-b border-montela-sage">
		<?php if ( $apt_title ) : ?>
			<h2 id="<?php echo esc_attr( $apt_title_id ); ?>" class="title-intro col-span-full md:col-span-3 md:row-start-1 xl:col-start-2 xl:col-span-5"><?php echo montela_text( $apt_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $apt_text ) : ?>
			<div class="body-text section-apartment-intro__text col-span-full md:col-start-4 md:col-span-3 md:row-start-1 md:row-span-2 xl:col-start-8 xl:col-span-4"><?php echo wp_kses_post( $apt_text ); ?></div>
		<?php endif; ?>

		<?php if ( $apt_buttons ) : ?>
			<div class="col-span-full flex flex-col items-start gap-5 md:col-span-3 md:row-start-2 md:mt-[83px] xl:col-start-2 xl:col-span-5 xl:mt-[136px] xl:flex-row xl:flex-wrap">
				<?php
				foreach ( $apt_buttons as $apt_button ) :
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge(
							$apt_button,
							array(
								'style' => 'mustard',
								// 237px label, as drawn on every breakpoint (5426:8212). The
								// underscores are escaped: Tailwind reads "_" as a space.
								'class' => '[&_.btn\_\_label]:min-w-[237px]',
							)
						)
					);
				endforeach;
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
