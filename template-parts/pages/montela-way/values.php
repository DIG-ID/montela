<?php
/**
 * Der Montela Weg — values ("Freiheit trifft persönliche Gastfreundschaft"):
 * title and text, then rows of a photo beside short texts (a bold title and
 * a paragraph each), the photo switching sides from row to row on tablet
 * and desktop. Mobile: the photo over its texts.
 *
 * Fields: tab "Values" of acf-json/group_page_montela_way.json. Figma:
 * desktop 5767:15933, tablet 5609:23696, mobile 5609:23697.
 *
 * @package montela
 * @subpackage Section
 * @since 1.25.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$values_title = get_field( 'section_values_title' );
$values_text  = get_field( 'section_values_text' );
$values_rows  = get_field( 'section_values_rows' );

if ( ! $values_title && ! $values_rows ) {
	return;
}

$values_title_id = wp_unique_id( 'section-values-title-' );
?>
<section class="section-values"<?php echo $values_title ? ' aria-labelledby="' . esc_attr( $values_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<?php if ( $values_title || $values_text ) : ?>
			<div class="theme-grid section-values__head">
				<?php if ( $values_title ) : ?>
					<h2 id="<?php echo esc_attr( $values_title_id ); ?>" class="title-h3 section-values__title"><?php echo wp_kses( $values_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
				<?php endif; ?>
				<?php if ( $values_text ) : ?>
					<p class="body-text section-values__text"><?php echo esc_html( $values_text ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php
		foreach ( (array) $values_rows as $values_index => $values_row ) :
			$values_texts = ! empty( $values_row['texts'] ) ? $values_row['texts'] : array();
			$values_class = 'theme-grid section-values__row' . ( 1 === $values_index % 2 ? ' section-values__row--reverse' : '' );
			?>
			<div class="<?php echo esc_attr( $values_class ); ?>">
				<?php if ( ! empty( $values_row['image'] ) ) : ?>
					<figure class="section-values__photo">
						<?php
						echo wp_get_attachment_image(
							$values_row['image'],
							'large',
							false,
							array(
								'class' => 'section-values__img',
								'sizes' => '(min-width: 1280px) 47vw, (min-width: 768px) 343px, 100vw',
							)
						);
						?>
					</figure>
				<?php endif; ?>

				<?php if ( $values_texts ) : ?>
					<div class="body-text section-values__texts">
						<?php foreach ( $values_texts as $values_item ) : ?>
							<div class="section-values__item">
								<?php if ( ! empty( $values_item['title'] ) ) : ?>
									<h3 class="section-values__item-title"><?php echo esc_html( $values_item['title'] ); ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $values_item['text'] ) ) : ?>
									<p><?php echo esc_html( $values_item['text'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
