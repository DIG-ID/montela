<?php
/**
 * Day trip card: the post's card image on top and a sage panel below it,
 * always open — title, excerpt and a "Mehr erfahren" link pinned to the
 * bottom, so cards in the same row line up (_card-trip.sass).
 *
 * Content comes from the post: featured image ("Kartenbild"), title, excerpt
 * and permalink. Figma "Cards with image": desktop 5753:14853, tablet
 * 5753:14837, mobile 5753:14852. Used by the Unsere Region day trips
 * (template-parts/pages/region/trips.php).
 *
 *     get_template_part(
 *         'template-parts/components/card-trip',
 *         null,
 *         array( 'post_id' => $foo_id )
 *     );
 *
 * Arguments ($args):
 * - post_id (int)    Required. The post to show.
 * - sizes   (string) Optional. The image's sizes attribute, for the slot the
 *                    card sits in. Default: a quarter of the screen on desktop.
 * - class   (string) Optional. Extra classes for the card.
 *
 * @package montela
 * @subpackage Component
 * @since 1.37.0
 */

$card_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( ! $card_id || 'publish' !== get_post_status( $card_id ) ) {
	return;
}

$card_title   = get_the_title( $card_id );
$card_excerpt = has_excerpt( $card_id ) ? get_the_excerpt( $card_id ) : '';
$card_image   = get_post_thumbnail_id( $card_id );
$card_sizes   = ! empty( $args['sizes'] ) ? $args['sizes'] : '(min-width: 1280px) 19vw, (min-width: 768px) 468px, 100vw';
$card_class   = 'card-trip' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<article class="<?php echo esc_attr( $card_class ); ?>">
	<?php if ( $card_image ) : ?>
		<figure class="card-trip__photo">
			<?php
			echo wp_get_attachment_image(
				$card_image,
				'large',
				false,
				array(
					'class' => 'card-trip__img',
					'alt'   => '',
					'sizes' => $card_sizes,
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="card-trip__panel">
		<div class="card-trip__body">
			<h3 class="card-trip__title"><?php echo montela_text( $card_title ); ?></h3>
			<?php if ( $card_excerpt ) : ?>
				<p class="card-trip__text"><?php echo montela_text( $card_excerpt ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		get_template_part(
			'template-parts/components/button',
			null,
			array(
				'url'     => get_permalink( $card_id ),
				'title'   => __( 'Mehr erfahren', 'montela' ),
				'context' => $card_title,
				'style'   => 'ghost',
				'class'   => 'card-trip__btn',
			)
		);
		?>
	</div>
</article>
