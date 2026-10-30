<?php
/**
 * List of items with an icon, a bold title and a short text.
 *
 * Used by the homepage "Direct booking" and "Gallery" sections, Bike Hotel
 * and Kids Playground. Typography and a default stacked layout live in
 * _components/_icon-list.sass (in the components layer, so Tailwind classes
 * passed here win); each section passes its own layout — grid columns, gaps —
 * as utility classes.
 *
 *     get_template_part(
 *         'template-parts/components/icon-list',
 *         null,
 *         array(
 *             'items' => get_field( 'section_foo_items' ),
 *             'class' => 'section-foo__list',
 *         )
 *     );
 *
 * Arguments ($args):
 * - items (array)  Required. Rows with 'icon' (attachment ID), 'title' and 'text'.
 * - class      (string)          Optional. Extra classes for the list.
 * - item_class (string|string[]) Optional. Extra classes for each item; an
 *                                array is cycled through, e.g. one entry per
 *                                grid column (odd / even items).
 * - text_class (string)          Optional. Extra classes for each item's text
 *                                box (e.g. a max-width).
 *
 * @package montela
 * @subpackage Component
 * @since 1.5.0
 */

$list_items = ! empty( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();

if ( ! $list_items ) {
	return;
}

$list_class        = 'icon-list' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
$list_text_class   = 'icon-list__text' . ( ! empty( $args['text_class'] ) ? ' ' . $args['text_class'] : '' );
$list_item_classes = ! empty( $args['item_class'] ) ? array_values( (array) $args['item_class'] ) : array( '' );
?>
<ul class="<?php echo esc_attr( $list_class ); ?>">
	<?php foreach ( $list_items as $list_index => $list_item ) : ?>
		<li class="<?php echo esc_attr( trim( 'icon-list__item ' . $list_item_classes[ $list_index % count( $list_item_classes ) ] ) ); ?>">
			<?php if ( ! empty( $list_item['icon'] ) ) : ?>
				<span class="icon-list__icon">
					<?php echo wp_get_attachment_image( $list_item['icon'], 'full', false, array( 'alt' => '' ) ); ?>
				</span>
			<?php endif; ?>
			<div class="<?php echo esc_attr( $list_text_class ); ?>">
				<?php if ( ! empty( $list_item['title'] ) ) : ?>
					<h3 class="icon-list__title"><?php echo esc_html( $list_item['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $list_item['text'] ) ) : ?>
					<p><?php echo esc_html( $list_item['text'] ); ?></p>
				<?php endif; ?>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
