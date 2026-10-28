<?php
/**
 * Single Offer — "Noch ein bisschen mehr Genuss?": centred title and text,
 * then the conditions — one sage box per stay — and the offer's validity.
 *
 * Fields: tab "Conditions" of acf-json/group_cpt_offer.json. The validity
 * comes from the "Valid from" / "Valid until" dates
 * (montela_get_offer_validity(), inc/helpers.php). Figma: desktop
 * 5778:17192, tablet 5609:23487, mobile 5609:23486.
 *
 * @package montela
 * @subpackage Section
 * @since 1.21.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$cond_title    = get_field( 'section_offer_extra_title' );
$cond_text     = get_field( 'section_offer_extra_text' );
$cond_items    = get_field( 'section_offer_conditions' );
$cond_validity = function_exists( 'montela_get_offer_validity' ) ? montela_get_offer_validity( get_the_ID() ) : '';

if ( ! $cond_title && ! $cond_text && ! $cond_items && ! $cond_validity ) {
	return;
}

$cond_title_id = wp_unique_id( 'section-offer-conditions-title-' );
?>
<section class="section-offer-conditions"<?php echo $cond_title ? ' aria-labelledby="' . esc_attr( $cond_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-offer-conditions__inner">
			<?php if ( $cond_title ) : ?>
				<h2 id="<?php echo esc_attr( $cond_title_id ); ?>" class="title-h3 section-offer-conditions__title"><?php echo esc_html( $cond_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $cond_text ) : ?>
				<p class="body-text section-offer-conditions__text"><?php echo esc_html( $cond_text ); ?></p>
			<?php endif; ?>

			<?php if ( $cond_items ) : ?>
				<h3 class="section-offer-conditions__label"><?php esc_html_e( 'Conditions', 'montela' ); ?></h3>

				<ul class="section-offer-conditions__boxes">
					<?php foreach ( $cond_items as $cond_item ) : ?>
						<li class="section-offer-conditions__box">
							<?php if ( ! empty( $cond_item['title'] ) ) : ?>
								<p class="section-offer-conditions__box-title"><?php echo esc_html( $cond_item['title'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $cond_item['text'] ) ) : ?>
								<div class="section-offer-conditions__box-text"><?php echo wp_kses_post( $cond_item['text'] ); ?></div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $cond_validity ) : ?>
				<p class="section-offer-conditions__validity">
					<strong><?php esc_html_e( 'Offer valid:', 'montela' ); ?></strong>
					<span><?php echo esc_html( $cond_validity ); ?></span>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
