<?php
/**
 * The template for displaying 404 (not found) pages.
 *
 * Figma "404": desktop 5426:5441, tablet 5617:25900, mobile 5617:25860. A
 * walnut frame on the cream page (no hero — the header is drawn dark, see
 * montela_header_dark()) with the big "404", a short text and a button to
 * the homepage, over the mountains line drawing.
 *
 * The text is edited in Theme Settings → 404 (error_404_*); empty fields
 * fall back to the theme strings below.
 *
 * @package montela
 * @subpackage Template
 * @since 1.0.0
 */

$error_has_acf = function_exists( 'get_field' );
$error_title   = $error_has_acf ? get_field( 'error_404_title', 'option' ) : '';
$error_text    = $error_has_acf ? get_field( 'error_404_text', 'option' ) : '';
$error_button  = $error_has_acf ? get_field( 'error_404_button', 'option' ) : '';

$error_title  = $error_title ? $error_title : __( 'Oops…', 'montela' );
$error_text   = $error_text ? $error_text : __( 'It looks like you missed a signpost. But don’t worry: all roads lead back to Montela.', 'montela' );
$error_button = $error_button ? $error_button : __( 'Back to the homepage', 'montela' );

get_header();
do_action( 'before_main_content' );
?>
<section class="section-404" aria-labelledby="section-404-title">
	<div class="theme-container">
		<div class="section-404__frame">
			<h1 id="section-404-title" class="section-404__code">
				404<span class="sr-only"> – <?php esc_html_e( 'Page not found', 'montela' ); ?></span>
			</h1>

			<div class="section-404__copy">
				<p class="section-404__title"><?php echo esc_html( $error_title ); ?></p>
				<p class="section-404__text"><?php echo esc_html( $error_text ); ?></p>
			</div>

			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'url'   => home_url( '/' ),
					'title' => $error_button,
					'style' => 'primary',
					'class' => 'section-404__btn',
				)
			);
			?>
		</div>
	</div>
</section>
<?php
do_action( 'after_main_content' );
get_footer();
