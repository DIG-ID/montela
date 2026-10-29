<?php
/**
 * Impressum — the boxes ("Adresse", "Kontakt", "Konzeption, Umsetzung &
 * Grafik"): a sage-framed box each, with an icon on top and a bold title
 * over a few lines. Three in a row on desktop, stacked below.
 *
 * E-mail addresses, links and phone numbers in the text become clickable
 * (montela_make_clickable()). Fields: tab "Boxes" of
 * acf-json/group_page_imprint.json. Figma: desktop 5781:17553, tablet
 * 5623:26902, mobile 5623:26901.
 *
 * @package montela
 * @subpackage Section
 * @since 1.31.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$imprint_boxes = get_field( 'section_imprint_boxes' );

if ( ! $imprint_boxes ) {
	return;
}
?>
<section class="section-imprint" aria-label="<?php esc_attr_e( 'Imprint', 'montela' ); ?>">
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-imprint__boxes">
				<?php foreach ( $imprint_boxes as $imprint_box ) : ?>
					<div class="section-imprint__box">
						<?php if ( ! empty( $imprint_box['icon'] ) ) : ?>
							<span class="section-imprint__icon">
								<?php echo wp_get_attachment_image( $imprint_box['icon'], 'full', false, array( 'alt' => '' ) ); ?>
							</span>
						<?php endif; ?>
						<div class="section-imprint__copy">
							<?php if ( ! empty( $imprint_box['title'] ) ) : ?>
								<h2 class="section-imprint__title"><?php echo esc_html( $imprint_box['title'] ); ?></h2>
							<?php endif; ?>
							<?php if ( ! empty( $imprint_box['text'] ) ) : ?>
								<p class="section-imprint__text"><?php echo wp_kses_post( montela_make_clickable( $imprint_box['text'] ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
