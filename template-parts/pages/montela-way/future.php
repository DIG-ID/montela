<?php
/**
 * Der Montela Weg — future ("Unser Blick in die Zukunft"): title, text and a
 * bulleted list of plans beside a photo (desktop); text and list side by
 * side over the photo (tablet); all stacked (mobile).
 *
 * Fields: tab "Future" of acf-json/group_page_montela_way.json — the list is
 * a textarea, one item per line. Figma: desktop 5767:16125, tablet
 * 5609:23488, mobile 5609:23502.
 *
 * @package montela
 * @subpackage Section
 * @since 1.25.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$future_title = get_field( 'section_future_title' );
$future_text  = get_field( 'section_future_text' );
$future_items = get_field( 'section_future_items' );
$future_image = get_field( 'section_future_image' );

$future_items = $future_items ? array_filter( array_map( 'trim', preg_split( '/\R/', $future_items ) ) ) : array();

if ( ! $future_title && ! $future_text && ! $future_items ) {
	return;
}

$future_title_id = wp_unique_id( 'section-future-title-' );
?>
<section class="section-future"<?php echo $future_title ? ' aria-labelledby="' . esc_attr( $future_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-future__grid">
			<div class="section-future__copy">
				<?php if ( $future_title ) : ?>
					<h2 id="<?php echo esc_attr( $future_title_id ); ?>" class="title-h3 section-future__title"><?php echo wp_kses( $future_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>
				<?php if ( $future_text ) : ?>
					<p class="body-text section-future__text"><?php echo esc_html( $future_text ); ?></p>
				<?php endif; ?>
				<?php if ( $future_items ) : ?>
					<ul class="body-text section-future__items">
						<?php foreach ( $future_items as $future_item ) : ?>
							<li><?php echo esc_html( $future_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $future_image ) : ?>
				<figure class="section-future__photo">
					<?php
					echo wp_get_attachment_image(
						$future_image,
						'full',
						false,
						array(
							'class' => 'section-future__img',
							'sizes' => '(min-width: 1280px) 52vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
