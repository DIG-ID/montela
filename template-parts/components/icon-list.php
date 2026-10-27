<?php
/**
 * List of items with an icon, a bold title and a short text.
 *
 * Used by the homepage "Direct booking" and "Gallery" sections. Typography
 * lives in _components/_icon-list.sass; each section sets its own layout
 * (icon size, gaps, columns) on top of it.
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
 * - class (string) Optional. Extra classes for the list.
 *
 * @package montela
 * @subpackage Component
 * @since 1.5.0
 */

$list_items = ! empty( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();

if ( ! $list_items ) {
	return;
}

$list_class = 'icon-list' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<ul class="<?php echo esc_attr( $list_class ); ?>">
	<?php foreach ( $list_items as $list_item ) : ?>
		<li class="icon-list__item">
			<?php if ( ! empty( $list_item['icon'] ) ) : ?>
				<span class="icon-list__icon">
					<?php echo wp_get_attachment_image( $list_item['icon'], 'full', false, array( 'alt' => '' ) ); ?>
				</span>
			<?php endif; ?>
			<div class="icon-list__text">
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
