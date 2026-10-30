<?php
/**
 * The Section for the Header Template.
 *
 * Figma "main-navigation" (node 5567:15709): logo, main menu, booking
 * button, WPML language switcher and the menu toggle. The header sits over
 * the hero, so everything is drawn in cream.
 *
 * Content:
 * - Menu: Appearance → Menus, location "Main Menu" (one level, desktop).
 * - Booking button: Theme Settings → Header → "Booking button" (ACF Link).
 * - Languages: WPML — the current one, opening a list of the others.
 * - Toggle: opens template-parts/mega-menu.php and becomes its close button.
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

$header_booking = function_exists( 'get_field' ) ? get_field( 'header_booking_link', 'option' ) : null;
$header_book_ok = is_array( $header_booking ) && ! empty( $header_booking['url'] ) && ! empty( $header_booking['title'] );
$header_book_tg = ( $header_book_ok && ! empty( $header_booking['target'] ) ) ? $header_booking['target'] : '';

// WPML returns null when the plugin is inactive; the switcher is then skipped.
// Only languages this page is translated into are listed (skip_missing), so
// with no translations the switcher is hidden. Split into the current
// language (shown in the box) and the others (the dropdown). Codes can carry
// a region ("en-us"): only the language is shown.
$header_languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 1 ) );
$header_lang_now  = null;
$header_lang_alts = array();
if ( is_array( $header_languages ) ) {
	foreach ( $header_languages as $header_lang ) {
		$header_lang['short'] = strtoupper( strtok( $header_lang['language_code'], '-_' ) );
		if ( ! empty( $header_lang['active'] ) ) {
			$header_lang_now = $header_lang;
		} else {
			$header_lang_alts[] = $header_lang;
		}
	}
}
?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Zum Inhalt springen', 'montela' ); ?></a>

<header id="header-main" class="header-main">
	<div class="theme-container header-main__inner">

		<div class="header-main__logo">
			<?php do_action( 'theme_logo' ); ?>
		</div>

		<div class="header-main__actions">

			<?php if ( has_nav_menu( 'main-menu' ) ) : ?>
				<nav class="header-main__nav" aria-label="<?php esc_attr_e( 'Hauptnavigation', 'montela' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'main-menu',
							'container'      => false,
							'menu_class'     => 'header-main__menu',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( $header_book_ok ) : ?>
				<a class="header-main__cta" href="<?php echo esc_url( $header_booking['url'] ); ?>"<?php echo '_blank' === $header_book_tg ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<span class="header-main__cta-label">
						<?php echo esc_html( $header_booking['title'] ); ?>
						<?php if ( '_blank' === $header_book_tg ) : ?>
							<span class="sr-only"><?php esc_html_e( '(öffnet in einem neuen Tab)', 'montela' ); ?></span>
						<?php endif; ?>
					</span>
					<span class="header-main__cta-icon" aria-hidden="true"></span>
				</a>
			<?php endif; ?>

			<?php if ( $header_lang_now && $header_lang_alts ) : ?>
				<div class="header-main__lang-switcher">
					<button type="button" class="header-main__lang" aria-expanded="false" aria-controls="header-main-langs">
						<span aria-hidden="true"><?php echo esc_html( $header_lang_now['short'] ); ?></span>
						<span class="sr-only">
							<?php
							/* translators: %s: name of the current language, in that language. */
							echo esc_html( sprintf( __( 'Sprache: %s', 'montela' ), $header_lang_now['native_name'] ) );
							?>
						</span>
					</button>
					<ul id="header-main-langs" class="header-main__langs" style="--n: <?php echo esc_attr( count( $header_lang_alts ) ); ?>">
						<?php foreach ( array_values( $header_lang_alts ) as $header_lang_i => $header_lang ) : ?>
							<li style="--i: <?php echo esc_attr( $header_lang_i ); ?>">
								<a class="header-main__lang" href="<?php echo esc_url( $header_lang['url'] ); ?>" hreflang="<?php echo esc_attr( $header_lang['language_code'] ); ?>" lang="<?php echo esc_attr( $header_lang['language_code'] ); ?>">
									<span aria-hidden="true"><?php echo esc_html( $header_lang['short'] ); ?></span>
									<span class="sr-only"><?php echo esc_html( $header_lang['native_name'] ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<button type="button" class="header-main__toggle" aria-expanded="false"<?php echo has_nav_menu( 'mega-menu' ) ? ' aria-controls="mega-menu"' : ''; ?> aria-label="<?php esc_attr_e( 'Menü öffnen', 'montela' ); ?>" data-label-open="<?php esc_attr_e( 'Menü öffnen', 'montela' ); ?>" data-label-close="<?php esc_attr_e( 'Menü schliessen', 'montela' ); ?>">
				<span class="header-main__toggle-icon" aria-hidden="true">
					<span class="header-main__toggle-bar"></span>
					<span class="header-main__toggle-bar"></span>
					<span class="header-main__toggle-bar"></span>
				</span>
			</button>

		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/mega-menu' ); ?>
