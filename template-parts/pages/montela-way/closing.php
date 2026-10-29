<?php
/**
 * Der Montela Weg — closing words ("Erleben Sie Montela selbst"): title and
 * text, side by side on desktop, stacked below.
 *
 * Fields: tab "Closing" of acf-json/group_page_montela_way.json. Figma:
 * desktop 5426:15547, tablet 5609:23601, mobile 5609:23609.
 *
 * @package montela
 * @subpackage Section
 * @since 1.25.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$closing_title = get_field( 'section_closing_title' );
$closing_text  = get_field( 'section_closing_text' );

if ( ! $closing_title && ! $closing_text ) {
	return;
}

$closing_title_id = wp_unique_id( 'section-closing-title-' );
?>
<section class="section-closing"<?php echo $closing_title ? ' aria-labelledby="' . esc_attr( $closing_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-closing__grid">
			<?php if ( $closing_title ) : ?>
				<h2 id="<?php echo esc_attr( $closing_title_id ); ?>" class="title-h3 section-closing__title"><?php echo wp_kses( $closing_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>
			<?php if ( $closing_text ) : ?>
				<p class="body-text section-closing__text"><?php echo esc_html( $closing_text ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
