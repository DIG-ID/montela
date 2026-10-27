<?php
/**
 * Homepage: "Find your apartment" — title, text, button and two photos.
 *
 * Fields: "Apartments" tab of acf-json/group_page_home.json. Figma:
 * section-find-apartment-desktop 5588:16261, -tablet 5453:8586,
 * -mobile 5426:4201.
 *
 * @package montela
 * @subpackage Section
 * @since 1.5.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$apt_title  = get_field( 'section_find_apartment_title' );
$apt_text   = get_field( 'section_find_apartment_text' );
$apt_button = get_field( 'section_find_apartment_button' );
$apt_images = array_filter(
	array(
		get_field( 'section_find_apartment_image_1' ),
		get_field( 'section_find_apartment_image_2' ),
	)
);

if ( ! $apt_title && ! $apt_text && ! $apt_images ) {
	return;
}

$apt_title_id = wp_unique_id( 'section-find-apartment-title-' );
?>
<section class="section-find-apartment"<?php echo $apt_title ? ' aria-labelledby="' . esc_attr( $apt_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-find-apartment__grid">
			<?php if ( $apt_title ) : ?>
				<h2 id="<?php echo esc_attr( $apt_title_id ); ?>" class="title-h3 section-find-apartment__title">
					<?php echo wp_kses( $apt_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>

			<div class="section-find-apartment__content">
				<?php if ( $apt_text ) : ?>
					<p class="body-text section-find-apartment__text"><?php echo esc_html( $apt_text ); ?></p>
				<?php endif; ?>
				<?php
				if ( $apt_button ) :
					get_template_part( 'template-parts/components/button', null, array_merge( $apt_button, array( 'style' => 'secondary' ) ) );
				endif;
				?>
			</div>

			<?php if ( $apt_images ) : ?>
				<div class="section-find-apartment__photos">
					<?php foreach ( $apt_images as $apt_image ) : ?>
						<figure class="section-find-apartment__photo">
							<?php
							echo wp_get_attachment_image(
								$apt_image,
								'large',
								false,
								array(
									'class' => 'section-find-apartment__img',
									'sizes' => '(min-width: 1280px) 27vw, (min-width: 768px) 41vw, 100vw',
								)
							);
							?>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
