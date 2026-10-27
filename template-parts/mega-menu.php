<?php
/**
 * The Section for the Mega Menu, opened by the header's menu toggle.
 *
 * Figma "menu_desktop": closed state 5895:18352, a sub-menu open 5895:18398.
 * The header stays on top of it (logo, booking button, languages and the
 * toggle, which turns into the close button).
 *
 * Content:
 * - Links: Appearance → Menus, location "Mega Menu". Top-level items fill
 *   the two-column grid row by row, in menu order. An item with children
 *   gets a "+" and opens a view with its own link, its children and "Back".
 *   Deeper levels are ignored.
 * - Image: Theme Settings → Header → "Menu image" (ACF, attachment ID).
 *
 * @package montela
 * @subpackage Section
 * @since 1.5.0
 */

$mega_locations = get_nav_menu_locations();
$mega_items     = ! empty( $mega_locations['mega-menu'] ) ? wp_get_nav_menu_items( $mega_locations['mega-menu'] ) : array();

// No menu assigned: nothing to open.
if ( ! $mega_items ) {
	return;
}

// Two levels: top-level items, and their children keyed by parent ID.
$mega_top      = array();
$mega_children = array();
foreach ( $mega_items as $mega_item ) {
	if ( empty( $mega_item->menu_item_parent ) ) {
		$mega_top[] = $mega_item;
	} else {
		$mega_children[ (int) $mega_item->menu_item_parent ][] = $mega_item;
	}
}

$mega_image = function_exists( 'get_field' ) ? get_field( 'header_menu_image', 'option' ) : null;

/**
 * Returns a menu item's label with a lone "&" glued to the next word, so a
 * two-line label breaks as in the tablet frame ("Angebote / & Packages").
 *
 * @param WP_Post $item Menu item.
 * @return string Unescaped label.
 */
$mega_label = static function ( $item ) {
	return preg_replace( '/ (&amp;|&) /', ' $1' . "\u{00A0}", $item->title );
};

/**
 * Prints the target/rel attributes of a menu item that opens in a new tab.
 *
 * @param WP_Post $item Menu item.
 * @return void
 */
$mega_target = static function ( $item ) {
	if ( '_blank' === $item->target ) {
		echo ' target="_blank" rel="noopener noreferrer"';
	}
};
?>
<div id="mega-menu" class="mega-menu" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'montela' ); ?>" data-lenis-prevent>
	<div class="theme-container mega-menu__inner">

		<?php if ( $mega_image ) : ?>
			<div class="mega-menu__media">
				<?php
				echo wp_get_attachment_image(
					$mega_image,
					'full',
					false,
					array(
						'class'   => 'mega-menu__img',
						'loading' => 'lazy',
					)
				);
				?>
			</div>
		<?php endif; ?>

		<nav class="mega-menu__nav" aria-label="<?php esc_attr_e( 'Site menu', 'montela' ); ?>">

			<ul class="mega-menu__grid" style="--n: <?php echo esc_attr( count( $mega_top ) ); ?>">
				<?php foreach ( $mega_top as $mega_i => $mega_item ) : ?>
					<li style="--i: <?php echo esc_attr( $mega_i ); ?>">
						<?php if ( ! empty( $mega_children[ $mega_item->ID ] ) ) : ?>
							<button type="button" class="mega-menu__item" aria-expanded="false" aria-controls="mega-menu-sub-<?php echo esc_attr( $mega_item->ID ); ?>">
								<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
								<span class="mega-menu__plus" aria-hidden="true">+</span>
							</button>
						<?php else : ?>
							<a class="mega-menu__item" href="<?php echo esc_url( $mega_item->url ); ?>"<?php $mega_target( $mega_item ); ?>>
								<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
							</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php foreach ( $mega_top as $mega_item ) : ?>
				<?php
				if ( empty( $mega_children[ $mega_item->ID ] ) ) {
					continue;
				}
				?>
				<div id="mega-menu-sub-<?php echo esc_attr( $mega_item->ID ); ?>" class="mega-menu__sub">
					<a class="mega-menu__item" href="<?php echo esc_url( $mega_item->url ); ?>"<?php $mega_target( $mega_item ); ?>>
						<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
					</a>
					<ul class="mega-menu__sub-list">
						<?php foreach ( $mega_children[ $mega_item->ID ] as $mega_child ) : ?>
							<li>
								<a class="mega-menu__sub-link" href="<?php echo esc_url( $mega_child->url ); ?>"<?php $mega_target( $mega_child ); ?>>
									<span><?php echo esc_html( $mega_label( $mega_child ) ); ?></span>
									<span class="mega-menu__sub-icon" aria-hidden="true"></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<button type="button" class="mega-menu__item mega-menu__back">
						<span class="mega-menu__back-icon" aria-hidden="true"></span>
						<?php // German source string by request; other languages via WPML String Translation. ?>
						<span><?php esc_html_e( 'Zurück', 'montela' ); ?></span>
					</button>
				</div>
			<?php endforeach; ?>

		</nav>
	</div>
</div>
