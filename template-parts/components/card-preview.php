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
 * Used by the Unsere Region day trips (template-parts/pages/region/trips.php)
 * and, with manual content, the Bike Hotel cards
 * (template-parts/pages/bike-hotel/bike-experiences.php).
 *
 *     get_template_part(
 *         'template-parts/components/card-preview',
 *         null,
 *         array( 'post_id' => $foo_id )
 *     );
 *
 * Arguments ($args):
 * - post_id (int)    The post to show. Without it, the card is built from
 *                    the manual arguments below (e.g. an ACF repeater row).
 * - image   (int)    Manual: attachment ID.
 * - title   (string) Manual: title. Required without post_id.
 * - text    (string) Manual: the text shown when the card opens.
 * - url     (string) Manual: the button's link; no URL, no button.
 * - target  (string) Manual: the link's target ('_blank' for a new tab).
 * - label   (string) Optional. Button label. Default: "Learn more".
 * - sizes   (string) Optional. The image's sizes attribute, for the slot the
 *                    card sits in. Default: a quarter of the screen on desktop.
 * - class   (string) Optional. Extra classes for the card.
 *
 * @package montela
 * @subpackage Component
 * @since 1.23.2
 */

$card_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( $card_id ) {
	if ( 'publish' !== get_post_status( $card_id ) ) {
		return;
	}
	$card_title   = get_the_title( $card_id );
	$card_excerpt = has_excerpt( $card_id ) ? get_the_excerpt( $card_id ) : '';
	$card_image   = get_post_thumbnail_id( $card_id );
	$card_url     = get_permalink( $card_id );
	$card_target  = '';
} else {
	$card_title   = ! empty( $args['title'] ) ? $args['title'] : '';
	$card_excerpt = ! empty( $args['text'] ) ? $args['text'] : '';
	$card_image   = ! empty( $args['image'] ) ? (int) $args['image'] : 0;
	$card_url     = ! empty( $args['url'] ) ? $args['url'] : '';
	$card_target  = ! empty( $args['target'] ) ? $args['target'] : '';
	if ( ! $card_title ) {
		return;
	}
}

$card_label = ! empty( $args['label'] ) ? $args['label'] : __( 'Learn more', 'montela' );
$card_sizes = ! empty( $args['sizes'] ) ? $args['sizes'] : '(min-width: 1280px) 19vw, (min-width: 768px) 468px, 100vw';
$card_class = 'card-preview' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
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
				'url'     => $card_url,
				'target'  => $card_target,
				'title'   => $card_label,
				'context' => $card_title,
				'style'   => 'ghost',
				'class'   => 'card-preview__btn',
			)
		);
		?>
	</div>
</article>
