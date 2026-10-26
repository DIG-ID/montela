<?php
/**
 * The template for displaying 404 (not found) pages.
 *
 * @package montela
 * @subpackage Template
 * @since 1.0.0
 */

get_header();
do_action( 'before_main_content' );
?>
<section class="page-header">
	<div class="theme-container">
		<h1><?php esc_html_e( '404', 'montela' ); ?></h1>
		<h2><?php esc_html_e( 'Seite nicht gefunden.', 'montela' ); ?></h2>
	</div>
</section>

<section class="section-404">
	<div class="theme-container">
		<p><?php esc_html_e( 'Die von Ihnen gesuchte Seite existiert nicht oder wurde verschoben.', 'montela' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn">
			<?php esc_html_e( 'Zurück zur Startseite', 'montela' ); ?>
		</a>
	</div>
</section>
<?php
do_action( 'after_main_content' );
get_footer();
