<?php
/**
 * Single Offer — intro: a "back to the overview" link, then the title, a
 * price box ("Ab CHF 799 / Person" + booking button) and the text.
 *
 * Desktop: title and price box on the left, text on the right. Tablet and
 * mobile: price box first, then title and text (CSS order).
 *
 * The back link goes to the page using the Offers template (found by
 * template, so slugs and WPML translations don't matter). The booking
 * button falls back to the header's "Jetzt buchen" link (Theme Settings).
 *
 * Fields: tab "Intro" of acf-json/group_cpt_offer.json. Figma: desktop
 * 5778:17177 (back link 5426:14464), tablet 5607:23300, mobile 5607:23443.
 *
 * @package montela
 * @subpackage Section
 * @since 1.20.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$offer_title   = get_field( 'section_offer_intro_title' );
$offer_text    = get_field( 'section_offer_intro_text' );
$offer_price   = get_field( 'section_offer_price' );
$offer_unit    = get_field( 'section_offer_price_unit' );
$offer_booking = get_field( 'section_offer_booking' );
if ( ! $offer_booking ) {
	$offer_booking = get_field( 'header_booking_link', 'option' );
}

$offer_overview = function_exists( 'montela_get_page_by_template' ) ? montela_get_page_by_template( 'page-templates/page-offers.php' ) : null;
$offer_title_id = wp_unique_id( 'section-offer-intro-title-' );
?>
<?php if ( $offer_overview ) : ?>
	<div class="theme-container offer-back">
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'url'   => get_permalink( $offer_overview ),
				'title' => __( 'Back to overview', 'montela' ),
				'style' => 'back',
			)
		);
		?>
	</div>
<?php endif; ?>

<?php if ( $offer_title || $offer_text || $offer_price ) : ?>
	<section class="section-offer-intro"<?php echo $offer_title ? ' aria-labelledby="' . esc_attr( $offer_title_id ) . '"' : ''; ?>>
		<div class="theme-container">
			<div class="theme-grid section-offer-intro__grid">
				<?php if ( $offer_title ) : ?>
					<h2 id="<?php echo esc_attr( $offer_title_id ); ?>" class="title-intro section-offer-intro__title"><?php echo wp_kses( $offer_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>

				<?php if ( $offer_price || $offer_booking ) : ?>
					<div class="section-offer-intro__price">
						<?php if ( $offer_price ) : ?>
							<p class="section-offer-intro__amount">
								<span class="section-offer-intro__from"><?php esc_html_e( 'From', 'montela' ); ?></span>
								<span class="section-offer-intro__value"><?php echo esc_html( $offer_price ); ?></span><?php echo $offer_unit ? '<span class="section-offer-intro__unit">' . esc_html( $offer_unit ) . '</span>' : ''; ?>
							</p>
						<?php endif; ?>

						<?php
						if ( $offer_booking ) :
							get_template_part( 'template-parts/components/button', null, array_merge( $offer_booking, array( 'style' => 'secondary' ) ) );
						endif;
						?>
					</div>
				<?php endif; ?>

				<?php if ( $offer_text ) : ?>
					<div class="body-text section-offer-intro__text"><?php echo wp_kses_post( $offer_text ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
