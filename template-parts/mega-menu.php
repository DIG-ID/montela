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
 * - Images (desktop only): the default one in Theme Settings → Header →
 *   "Menu image", shown when the menu opens and while no link is hovered,
 *   and one per item in Appearance → Menus (ACF "menu_item_image",
 *   group_menu_item; montela_get_menu_item_image() in inc/helpers.php).
 *   Hovering or focusing an item crossfades to its image (main.js); a
 *   sub-item without one shows its parent's, a top-level item without one
 *   the default. No default image = the first item image is the default.
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

// Default image (Theme Settings), then the image of each item (attachment
// ID, keyed by item ID): children fall back to their parent's, items without
// one to the default. $mega_images lists each attachment once, the default
// first.
$mega_default     = function_exists( 'get_field' ) ? (int) get_field( 'header_menu_image', 'option' ) : 0;
$mega_item_images = array();
foreach ( $mega_top as $mega_item ) {
	$mega_top_image                     = montela_get_menu_item_image( $mega_item );
	$mega_item_images[ $mega_item->ID ] = $mega_top_image ? $mega_top_image : $mega_default;
}
foreach ( $mega_children as $mega_parent_id => $mega_kids ) {
	foreach ( $mega_kids as $mega_child ) {
		$mega_child_image                    = montela_get_menu_item_image( $mega_child );
		$mega_item_images[ $mega_child->ID ] = $mega_child_image ? $mega_child_image : ( $mega_item_images[ $mega_parent_id ] ?? 0 );
	}
}
$mega_images = array_values( array_unique( array_filter( array_merge( array( $mega_default ), $mega_item_images ) ) ) );

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

/**
 * Prints the data attribute that ties a menu item to its image.
 *
 * @param WP_Post $item Menu item.
 * @return void
 */
$mega_image_attr = static function ( $item ) use ( $mega_item_images ) {
	if ( ! empty( $mega_item_images[ $item->ID ] ) ) {
		echo ' data-menu-image="' . esc_attr( $mega_item_images[ $item->ID ] ) . '"';
	}
};
?>
<div id="mega-menu" class="mega-menu" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menü', 'montela' ); ?>" data-lenis-prevent>
	<div class="theme-container mega-menu__inner">

		<?php if ( $mega_images ) : ?>
			<div class="mega-menu__media" data-menu-default="<?php echo esc_attr( $mega_images[0] ); ?>">
				<?php
				foreach ( $mega_images as $mega_img_i => $mega_img ) {
					echo wp_get_attachment_image(
						$mega_img,
						'full',
						false,
						array(
							'class'           => 'mega-menu__img' . ( 0 === $mega_img_i ? ' is-active' : '' ),
							'loading'         => 'lazy',
							// 832 of 1920px; hidden below desktop, so no download there.
							'sizes'           => '(min-width: 1280px) 44vw, 1px',
							'data-menu-image' => $mega_img,
						)
					);
				}
				?>
			</div>
		<?php endif; ?>

		<nav class="mega-menu__nav" aria-label="<?php esc_attr_e( 'Website-Menü', 'montela' ); ?>">

			<ul class="mega-menu__grid" style="--n: <?php echo esc_attr( count( $mega_top ) ); ?>">
				<?php foreach ( $mega_top as $mega_i => $mega_item ) : ?>
					<li style="--i: <?php echo esc_attr( $mega_i ); ?>">
						<?php if ( ! empty( $mega_children[ $mega_item->ID ] ) ) : ?>
							<button type="button" class="mega-menu__item" aria-expanded="false" aria-controls="mega-menu-sub-<?php echo esc_attr( $mega_item->ID ); ?>"<?php $mega_image_attr( $mega_item ); ?>>
								<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
								<span class="mega-menu__plus" aria-hidden="true">+</span>
							</button>
						<?php else : ?>
							<a class="mega-menu__item" href="<?php echo esc_url( $mega_item->url ); ?>"<?php $mega_target( $mega_item ); ?><?php $mega_image_attr( $mega_item ); ?>>
								<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
								<span class="mega-menu__sub-icon" aria-hidden="true"></span>
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
					<a class="mega-menu__item" href="<?php echo esc_url( $mega_item->url ); ?>"<?php $mega_target( $mega_item ); ?><?php $mega_image_attr( $mega_item ); ?>>
						<span><?php echo esc_html( $mega_label( $mega_item ) ); ?></span>
						<span class="mega-menu__sub-icon" aria-hidden="true"></span>
					</a>
					<ul class="mega-menu__sub-list">
						<?php foreach ( $mega_children[ $mega_item->ID ] as $mega_child ) : ?>
							<li>
								<a class="mega-menu__sub-link" href="<?php echo esc_url( $mega_child->url ); ?>"<?php $mega_target( $mega_child ); ?><?php $mega_image_attr( $mega_child ); ?>>
									<span><?php echo esc_html( $mega_label( $mega_child ) ); ?></span>
									<span class="mega-menu__sub-icon" aria-hidden="true"></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<button type="button" class="mega-menu__item mega-menu__back">
						<span class="mega-menu__back-icon" aria-hidden="true"></span>
						<span><?php esc_html_e( 'Zurück', 'montela' ); ?></span>
					</button>
				</div>
			<?php endforeach; ?>

		</nav>
	</div>
</div>
