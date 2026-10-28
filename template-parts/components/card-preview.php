<?php
/**
 * Preview card: a post's card image with a sage panel — title, excerpt and
 * a "learn more" link. Mobile and tablet: the photo whole on top, the panel
 * below, always open. Desktop: the panel over the photo's bottom, closed
 * (title and link) until hover or keyboard focus opens it to show the
 * excerpt (_card-preview.sass).
 *
 * Content comes from the post: featured image ("Card image" / "Kartenbild"),
 * title, excerpt and permalink. Figma: "Card Offers and Packages" 5470:17071.
 * Used by the Unsere Region day trips (template-parts/pages/region/trips.php).
 *
 *     get_template_part(
 *         'template-parts/components/card-preview',
 *         null,
 *         array( 'post_id' => $foo_id )
 *     );
 *
 * Arguments ($args):
 * - post_id (int)    Required. Post ID.
 * - sizes   (string) Optional. The image's sizes attribute, for the slot the
 *                    card sits in. Default: a quarter of the screen on desktop.
 * - class   (string) Optional. Extra classes for the card.
 *
 * @package montela
 * @subpackage Component
 * @since 1.23.2
 */

$card_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( ! $card_id || 'publish' !== get_post_status( $card_id ) ) {
	return;
}

$card_title   = get_the_title( $card_id );
$card_excerpt = has_excerpt( $card_id ) ? get_the_excerpt( $card_id ) : '';
$card_image   = get_post_thumbnail_id( $card_id );
$card_sizes   = ! empty( $args['sizes'] ) ? $args['sizes'] : '(min-width: 1280px) 19vw, (min-width: 768px) 468px, 100vw';
$card_class   = 'card-preview' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<article class="<?php echo esc_attr( $card_class ); ?>">
	<?php if ( $card_image ) : ?>
		<figure class="card-preview__photo">
			<?php
			echo wp_get_attachment_image(
				$card_image,
				'large',
				false,
				array(
					'class' => 'card-preview__img',
					'alt'   => '',
					'sizes' => $card_sizes,
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="card-preview__panel">
		<h3 class="card-preview__title"><?php echo esc_html( $card_title ); ?></h3>
		<?php if ( $card_excerpt ) : ?>
			<div class="card-preview__more">
				<div class="card-preview__more-inner">
					<p class="card-preview__text"><?php echo esc_html( $card_excerpt ); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'url'     => get_permalink( $card_id ),
				'title'   => __( 'Learn more', 'montela' ),
				'context' => $card_title,
				'style'   => 'ghost',
				'class'   => 'card-preview__btn',
			)
		);
		?>
	</div>
</article>
