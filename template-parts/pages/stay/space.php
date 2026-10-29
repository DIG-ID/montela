<?php
/**
 * Aufenthalt — "Viel Raum für gemeinsame Ferien": title, text and button,
 * two photos and a list of what the apartments offer (icon, bold line, text).
 *
 * Desktop: title across the top; text and button beside photo 1; then photo
 * 2 beside the list. Tablet: title, text and button beside photo 1, photo 2
 * below them, the list centred underneath. Mobile: all stacked.
 *
 * Fields: tab "Space" of acf-json/group_page_stay.json. List:
 * components/icon-list.php. Figma: desktop 5767:15879, tablet 5466:16170,
 * mobile 5466:16083.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$space_title   = get_field( 'section_space_title' );
$space_text    = get_field( 'section_space_text' );
$space_button  = get_field( 'section_space_button' );
$space_image_1 = get_field( 'section_space_image_1' );
$space_image_2 = get_field( 'section_space_image_2' );
$space_items   = get_field( 'section_space_items' );

if ( ! $space_title && ! $space_text && ! $space_items ) {
	return;
}

$space_title_id = wp_unique_id( 'section-space-title-' );
?>
<section class="section-space"<?php echo $space_title ? ' aria-labelledby="' . esc_attr( $space_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-space__grid">
			<?php if ( $space_title ) : ?>
				<h2 id="<?php echo esc_attr( $space_title_id ); ?>" class="title-h3 section-space__title"><?php echo wp_kses( $space_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $space_text || $space_button ) : ?>
				<div class="section-space__content">
					<?php if ( $space_text ) : ?>
						<p class="body-text section-space__text"><?php echo esc_html( $space_text ); ?></p>
					<?php endif; ?>
					<?php
					if ( $space_button ) :
						get_template_part( 'template-parts/components/button', null, array_merge( $space_button, array( 'style' => 'secondary' ) ) );
					endif;
					?>
				</div>
			<?php endif; ?>

			<?php if ( $space_image_1 ) : ?>
				<figure class="section-space__photo section-space__photo--1">
					<?php
					echo wp_get_attachment_image(
						$space_image_1,
						'large',
						false,
						array(
							'class' => 'section-space__img',
							'sizes' => '(min-width: 1280px) 55vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $space_image_2 ) : ?>
				<figure class="section-space__photo section-space__photo--2">
					<?php
					echo wp_get_attachment_image(
						$space_image_2,
						'large',
						false,
						array(
							'class' => 'section-space__img',
							'sizes' => '(min-width: 1280px) 28vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/components/icon-list',
				null,
				array(
					'items' => $space_items,
					'class' => 'section-space__list',
				)
			);
			?>
		</div>
	</div>
</section>
