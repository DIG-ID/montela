<?php
/**
 * Info & Kontakt — contact form: a title over a Contact Form 7 form.
 *
 * The form's markup lives in the plugin (Contact → Contact Forms), so its
 * classes are styled in _pages/_contact.sass, which keeps a copy of the
 * template. Fields: tab "Form" of acf-json/group_page_contact.json — the
 * form is picked in a Post Object field. Figma: desktop 5426:15826, tablet
 * 5613:24183, mobile 5613:24221.
 *
 * @package montela
 * @subpackage Section
 * @since 1.28.0
 */

if ( ! function_exists( 'get_field' ) || ! function_exists( 'wpcf7_contact_form' ) ) {
	return;
}

$form_title = get_field( 'section_form_title' );
$form_id    = (int) get_field( 'section_form_form' );

if ( ! $form_id || 'publish' !== get_post_status( $form_id ) ) {
	return;
}

$form_title_id = wp_unique_id( 'section-contact-form-title-' );
?>
<section class="section-contact-form"<?php echo $form_title ? ' aria-labelledby="' . esc_attr( $form_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<?php if ( $form_title ) : ?>
				<h2 id="<?php echo esc_attr( $form_title_id ); ?>" class="title-h3 section-contact-form__title"><?php echo wp_kses( $form_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<div class="section-contact-form__form">
				<?php echo do_shortcode( '[contact-form-7 id="' . $form_id . '"]' ); ?>
			</div>
		</div>
	</div>
</section>
