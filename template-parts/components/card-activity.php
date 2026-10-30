<?php
/**
 * Activity card: the activity's card image with its title in a bar at the
 * bottom. The bar opens into a sage panel with the excerpt and a link to the
 * activity — on desktop with a mouse, on hover or keyboard focus; always
 * open on tablet, mobile and touch screens (see _card-activity.sass).
 *
 * Content comes either from an Activity post — featured image (labelled
 * "Card image" in the admin), title, excerpt and a "Learn more" link to it —
 * or straight from the arguments, for cards typed into a page (the info
 * cards of an activity single). Those show a button only when they have a
 * link. Figma: the cards of "Activities_with slider SW" — desktop
 * 5679:16639, tablet 5679:15677, mobile 5679:16603.
 *
 * Arguments ($args):
 * - post_id (int)    Activity post ID. Leave it out to pass the content below.
 * - title   (string) Without post_id: required. Card title.
 * - text    (string) Without post_id: optional. Text in the open panel.
 * - image   (int)    Without post_id: optional. Attachment ID.
 * - link    (array)  Without post_id: optional. ACF Link array (url, title,
 *                    target) — the button; no URL, no button.
 * - class   (string) Optional. Extra classes for the card.
 * - title_tag (string) Optional. Heading level of the title, h2–h6 (default
 *                    h3), so the card fits the heading outline of the
 *                    section it sits in.
 *
 * @package montela
 * @subpackage Component
 * @since 1.11.0
 */

$card_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : 0;

if ( $card_id ) {
	if ( 'publish' !== get_post_status( $card_id ) ) {
		return;
	}

	$card_title   = get_the_title( $card_id );
	$card_excerpt = get_the_excerpt( $card_id );
	$card_image   = get_post_thumbnail_id( $card_id );
	$card_link    = array(
		'url'   => get_permalink( $card_id ),
		'title' => __( 'Learn more', 'montela' ),
	);
} else {
	$card_title   = ! empty( $args['title'] ) ? $args['title'] : '';
	$card_excerpt = ! empty( $args['text'] ) ? $args['text'] : '';
	$card_image   = ! empty( $args['image'] ) ? (int) $args['image'] : 0;
	$card_link    = ( ! empty( $args['link'] ) && is_array( $args['link'] ) && ! empty( $args['link']['url'] ) ) ? $args['link'] : null;

	if ( $card_link && empty( $card_link['title'] ) ) {
		$card_link['title'] = __( 'Learn more', 'montela' );
	}

	if ( ! $card_title ) {
		return;
	}
}

$card_class     = 'card-activity' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
$card_title_tag = ( ! empty( $args['title_tag'] ) && in_array( $args['title_tag'], array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) ? $args['title_tag'] : 'h3';
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
		<<?php echo esc_attr( $card_title_tag ); ?> class="title-h4 card-activity__title"><?php echo esc_html( $card_title ); ?></<?php echo esc_attr( $card_title_tag ); ?>>

		<div class="card-activity__more">
			<div class="card-activity__more-inner">
				<?php if ( $card_excerpt ) : ?>
					<p class="card-activity__excerpt"><?php echo esc_html( $card_excerpt ); ?></p>
				<?php endif; ?>

				<?php
				if ( $card_link ) {
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge(
							$card_link,
							array(
								'context' => $card_title,
								'style'   => 'ghost',
								'class'   => 'card-activity__link',
							)
						)
					);
				}
				?>
			</div>
		</div>
	</div>
</article>
