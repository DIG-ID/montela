<?php
/**
 * Anreise & Lage — luggage service ("SBB Gepäcktransport"): title and button
 * beside the text on desktop; title, text and button stacked below.
 *
 * Fields: tab "Luggage" of acf-json/group_page_arrival.json — the text is a
 * basic WYSIWYG (paragraphs, links, emphasis). Figma: desktop 5767:16127,
 * tablet 5611:23996, mobile 5611:24002.
 *
 * @package montela
 * @subpackage Section
 * @since 1.26.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$luggage_title  = get_field( 'section_luggage_title' );
$luggage_text   = get_field( 'section_luggage_text' );
$luggage_button = get_field( 'section_luggage_button' );

if ( ! $luggage_title && ! $luggage_text ) {
	return;
}

$luggage_title_id = wp_unique_id( 'section-luggage-title-' );
?>
<section class="section-luggage"<?php echo $luggage_title ? ' aria-labelledby="' . esc_attr( $luggage_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-luggage__grid">
			<?php if ( $luggage_title ) : ?>
				<h2 id="<?php echo esc_attr( $luggage_title_id ); ?>" class="title-h3 section-luggage__title"><?php echo wp_kses( $luggage_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $luggage_text ) : ?>
				<div class="body-text section-luggage__text"><?php echo wp_kses_post( $luggage_text ); ?></div>
			<?php endif; ?>

			<?php if ( is_array( $luggage_button ) ) : ?>
				<div class="section-luggage__button">
					<?php get_template_part( 'template-parts/components/button', null, array_merge( $luggage_button, array( 'style' => 'secondary' ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
