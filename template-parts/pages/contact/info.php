<?php
/**
 * Info & Kontakt — contact boxes: framed boxes with a bold title and a few
 * lines ("Adresse", "Kontakt", "Persönlich vor Ort"). Three in a row on
 * desktop; two, then the third full width, on tablet; stacked on mobile.
 *
 * E-mail addresses, links and phone numbers in the text become clickable
 * (montela_link_phone_numbers(): tel:, or a WhatsApp chat after the word
 * "WhatsApp"). Fields: tab "Contact boxes" of
 * acf-json/group_page_contact.json. Figma: desktop 5426:15850, tablet
 * 5613:24222, mobile 5613:24223.
 *
 * @package montela
 * @subpackage Section
 * @since 1.28.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$info_boxes = get_field( 'section_contact_boxes' );

if ( ! $info_boxes ) {
	return;
}
?>
<section class="section-contact-info" aria-label="<?php esc_attr_e( 'Contact details', 'montela' ); ?>">
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-contact-info__boxes">
				<?php foreach ( $info_boxes as $info_box ) : ?>
					<div class="section-contact-info__box">
						<?php if ( ! empty( $info_box['title'] ) ) : ?>
							<h2 class="section-contact-info__title"><?php echo esc_html( $info_box['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $info_box['text'] ) ) : ?>
							<p class="section-contact-info__text"><?php echo wp_kses_post( montela_link_phone_numbers( make_clickable( $info_box['text'] ) ) ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
