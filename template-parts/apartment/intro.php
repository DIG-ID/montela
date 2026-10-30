<?php
/**
 * Single Apartment — intro: title, booking button and text, over a sage
 * hairline. Mobile: title, text, button. Tablet: title and button on
 * columns 1–3, the text on 4–6. Desktop: title and button on columns 2–6,
 * the text on 8–11.
 *
 * The booking button falls back to the header's "Jetzt buchen" link (Theme
 * Settings).
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

if ( ! $apt_title && ! $apt_text ) {
	return;
}

$apt_title_id = wp_unique_id( 'section-apartment-intro-title-' );
?>
<section class="theme-container section-apartment-intro mt-4 md:mt-11 xl:mt-[100px] text-montela-walnut"<?php echo $apt_title ? ' aria-labelledby="' . esc_attr( $apt_title_id ) . '"' : ''; ?>>
	<div class="theme-grid gap-y-4 md:gap-y-0 pb-11 md:pb-14 xl:pb-[77px] border-b border-montela-sage">
		<?php if ( $apt_title ) : ?>
			<h2 id="<?php echo esc_attr( $apt_title_id ); ?>" class="title-intro col-span-full md:col-span-3 md:row-start-1 xl:col-start-2 xl:col-span-5"><?php echo wp_kses( $apt_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
		<?php endif; ?>

		<?php if ( $apt_text ) : ?>
			<div class="body-text section-apartment-intro__text col-span-full md:col-start-4 md:col-span-3 md:row-start-1 md:row-span-2 xl:col-start-8 xl:col-span-4"><?php echo wp_kses_post( $apt_text ); ?></div>
		<?php endif; ?>

		<?php if ( is_array( $apt_booking ) && ! empty( $apt_booking['url'] ) ) : ?>
			<div class="col-span-full md:col-span-3 md:row-start-2 md:mt-[83px] xl:col-start-2 xl:col-span-5 xl:mt-[136px]">
				<?php get_template_part( 'template-parts/components/button', null, array_merge( $apt_booking, array( 'style' => 'secondary' ) ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
