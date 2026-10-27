<?php
/**
 * Activity card: the activity's card image with its title in a bar at the
 * bottom. The bar opens into a sage panel with the excerpt and a link to the
 * activity — on hover or keyboard focus on devices with a mouse, always open
 * on touch screens (see _card-activity.sass).
 *
 * Content comes from the Activity post: featured image (labelled "Card
 * image" in the admin), title, excerpt and permalink. Figma: the cards of
 * "Activities_with slider SW" — desktop 5679:16639, tablet 5679:15677,
 * mobile 5679:16603.
 *
 * Arguments ($args):
 * - post_id (int)    Required. Activity post ID.
 * - class   (string) Optional. Extra classes for the card.
 *
 * @package montela
 * @subpackage Component
 * @since 1.11.0
 */

$card_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( ! $card_id || 'publish' !== get_post_status( $card_id ) ) {
	return;
}

$card_title   = get_the_title( $card_id );
$card_excerpt = get_the_excerpt( $card_id );
$card_image   = get_post_thumbnail_id( $card_id );
$card_class   = 'card-activity' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<article class="<?php echo esc_attr( $card_class ); ?>">
	<?php if ( $card_image ) : ?>
		<figure class="card-activity__media">
			<?php
			echo wp_get_attachment_image(
				$card_image,
				'large',
				false,
				array(
					'class' => 'card-activity__img',
					'alt'   => '',
					'sizes' => '(min-width: 1280px) 403px, (min-width: 768px) 340px, 100vw',
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="card-activity__panel">
		<h3 class="title-h4 card-activity__title"><?php echo esc_html( $card_title ); ?></h3>

		<div class="card-activity__more">
			<div class="card-activity__more-inner">
				<?php if ( $card_excerpt ) : ?>
					<p class="card-activity__excerpt"><?php echo esc_html( $card_excerpt ); ?></p>
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
						'class'   => 'card-activity__link',
					)
				);
				?>
			</div>
		</div>
	</div>
</article>
