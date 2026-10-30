<?php
/**
 * The Section for the Footer Default Template.
 *
 * Figma "full-footer-desktop" (5581:5814), "full-footer-tablet" (5475:24966)
 * and "full-footer-mobile" (5895:18039): a cream call-to-action box
 * overlapping the top of the sage footer, then the logo, four columns
 * (newsletter, contact, navigation, partner logos) and the copyright bar.
 *
 * Content:
 * - Theme Settings, "Footer: …" tabs (acf-json/group_options_theme_settings.json):
 *   call to action, newsletter text, address, phone, e-mail, socials,
 *   partner logos.
 * - Newsletter form: Mailchimp for WP (default form), when the plugin is active.
 * - Menus: "Footer Menu" (one level, four per column) and "Legal Menu".
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

$footer_has_acf = function_exists( 'get_field' );

$footer_cta_title = $footer_has_acf ? get_field( 'footer_cta_title', 'option' ) : '';
$footer_cta_text  = $footer_has_acf ? get_field( 'footer_cta_text', 'option' ) : '';
$footer_cta_link  = $footer_has_acf ? get_field( 'footer_cta_link', 'option' ) : null;
$footer_news_text = $footer_has_acf ? get_field( 'footer_newsletter_text', 'option' ) : '';
$footer_address   = $footer_has_acf ? get_field( 'footer_address', 'option' ) : '';
$footer_phone     = $footer_has_acf ? get_field( 'footer_phone', 'option' ) : '';
$footer_email     = $footer_has_acf ? get_field( 'footer_email', 'option' ) : '';
$footer_partners  = $footer_has_acf ? get_field( 'footer_partners', 'option' ) : array();

$footer_socials = array(
	'Facebook'  => $footer_has_acf ? get_field( 'socials_facebook', 'option' ) : '',
	'Instagram' => $footer_has_acf ? get_field( 'socials_instagram', 'option' ) : '',
);
$footer_socials = array_filter( $footer_socials );

$footer_has_form = function_exists( 'mc4wp_show_form' );
?>

<footer class="footer-main<?php echo $footer_cta_title ? ' footer-main--has-cta' : ''; ?>">

	<?php if ( $footer_cta_title ) : ?>
		<div class="theme-container footer-main__cta-wrap">
			<div class="footer-main__cta">
				<?php // An h2 with the H3 look: the box belongs to no section of the page, so it must not nest under the last one (and a 404 has no h2 before it). ?>
				<h2 class="title-h3 footer-main__cta-title">
					<?php echo wp_kses( $footer_cta_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
				<div class="footer-main__cta-row">
					<?php if ( $footer_cta_text ) : ?>
						<p class="footer-main__cta-text"><?php echo esc_html( $footer_cta_text ); ?></p>
					<?php endif; ?>
					<?php
					if ( is_array( $footer_cta_link ) ) {
						get_template_part(
							'template-parts/components/button',
							null,
							array_merge(
								$footer_cta_link,
								array(
									'style' => 'primary',
									'class' => 'footer-main__cta-btn',
								)
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="footer-main__body">

		<a class="footer-main__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/svg/logo-montela.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="187" height="85" loading="lazy">
		</a>

		<div class="theme-container footer-main__content">

			<div class="footer-main__info">

			<?php if ( $footer_news_text || $footer_has_form ) : ?>
				<div class="footer-main__newsletter">
					<?php if ( $footer_news_text ) : ?>
						<p><?php echo esc_html( $footer_news_text ); ?></p>
					<?php endif; ?>
					<?php
					if ( $footer_has_form ) {
						mc4wp_show_form();
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $footer_address || $footer_phone || $footer_email || $footer_socials ) : ?>
			<address class="footer-main__contact">
				<?php if ( $footer_address ) : ?>
					<p><?php echo wp_kses( $footer_address, array( 'br' => array() ) ); ?></p>
				<?php endif; ?>
				<?php if ( $footer_phone ) : ?>
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', str_replace( '(0)', '', $footer_phone ) ) ); ?>"><?php echo esc_html( $footer_phone ); ?></a></p>
				<?php endif; ?>
				<?php if ( $footer_email ) : ?>
					<p><a href="mailto:<?php echo esc_attr( antispambot( $footer_email ) ); ?>"><?php echo esc_html( antispambot( $footer_email ) ); ?></a></p>
				<?php endif; ?>
				<?php if ( $footer_socials ) : ?>
					<ul class="footer-main__socials">
						<?php foreach ( $footer_socials as $footer_social_name => $footer_social_url ) : ?>
							<li>
								<a href="<?php echo esc_url( $footer_social_url ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo esc_html( $footer_social_name ); ?>
									<span class="sr-only"><?php esc_html_e( '(opens in a new tab)', 'montela' ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</address>
			<?php endif; ?>

			</div>

			<?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
				<nav class="footer-main__nav" aria-label="<?php esc_attr_e( 'Footer navigation', 'montela' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu',
							'container'      => false,
							'menu_class'     => 'footer-main__menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( $footer_partners ) : ?>
				<ul class="footer-main__partners">
					<?php foreach ( $footer_partners as $footer_partner ) : ?>
						<?php
						if ( empty( $footer_partner['image'] ) ) {
							continue;
						}
						$footer_partner_link = ( ! empty( $footer_partner['link'] ) && is_array( $footer_partner['link'] ) ) ? $footer_partner['link'] : null;
						$footer_partner_img  = wp_get_attachment_image(
							$footer_partner['image'],
							'medium',
							false,
							array(
								'class'   => 'footer-main__partner-img',
								'loading' => 'lazy',
							)
						);
						?>
						<li>
							<?php if ( $footer_partner_link && ! empty( $footer_partner_link['url'] ) ) : ?>
								<a class="footer-main__partner" href="<?php echo esc_url( $footer_partner_link['url'] ); ?>"<?php echo ( ! empty( $footer_partner_link['target'] ) && '_blank' === $footer_partner_link['target'] ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
									<?php echo $footer_partner_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() is escaped. ?>
								</a>
							<?php else : ?>
								<span class="footer-main__partner"><?php echo $footer_partner_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() is escaped. ?></span>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>

		<div class="footer-main__bottom">
			<div class="theme-container footer-main__bottom-inner">
				<p>
					<?php
					/* translators: 1: current year, 2: site name. */
					echo esc_html( sprintf( __( '© %1$s %2$s. All rights reserved', 'montela' ), wp_date( 'Y' ), get_bloginfo( 'name' ) ) );
					?>
				</p>
				<?php if ( has_nav_menu( 'legal-menu' ) ) : ?>
					<nav aria-label="<?php esc_attr_e( 'Legal', 'montela' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'legal-menu',
								'container'      => false,
								'menu_class'     => 'footer-main__legal',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>
		</div>

	</div>
</footer>
