<?php
/**
 * Text with photos module: title, then text and button beside one or two
 * photos. The layout follows the number of photos (see _text-photos.sass).
 *
 * Fields: acf-json/group_module_text_photos.json, cloned into each page's
 * field group. The fields keep their original names and keys
 * (section_find_apartment_*, field_page_home_find_apartment_*) from when
 * this was a homepage-only section, so existing content keeps loading.
 * Figma: two photos — homepage section-text-photos desktop 5588:16261,
 * tablet 5453:8586, mobile 5426:4201; one photo — Aktivitäten desktop
 * 5778:17124, tablet 5561:11940, mobile 5561:11955.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'photos_' for a clone named 'photos'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.5.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$apt_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$apt_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$apt_title  = get_field( $apt_prefix . 'section_find_apartment_title', $apt_ctx );
$apt_text   = get_field( $apt_prefix . 'section_find_apartment_text', $apt_ctx );
$apt_button = get_field( $apt_prefix . 'section_find_apartment_button', $apt_ctx );
$apt_images = array_filter(
	array(
		get_field( $apt_prefix . 'section_find_apartment_image_1', $apt_ctx ),
		get_field( $apt_prefix . 'section_find_apartment_image_2', $apt_ctx ),
	)
);

if ( ! $apt_title && ! $apt_text && ! $apt_images ) {
	return;
}

$apt_title_id = wp_unique_id( 'section-text-photos-title-' );
$apt_class    = 'section-text-photos' . ( 1 === count( $apt_images ) ? ' section-text-photos--single' : '' );
?>
<section class="<?php echo esc_attr( $apt_class ); ?>"<?php echo $apt_title ? ' aria-labelledby="' . esc_attr( $apt_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-text-photos__grid">
			<?php if ( $apt_title ) : ?>
				<h2 id="<?php echo esc_attr( $apt_title_id ); ?>" class="title-h3 section-text-photos__title">
					<?php echo wp_kses( $apt_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>

			<div class="section-text-photos__content">
				<?php if ( $apt_text ) : ?>
					<p class="body-text section-text-photos__text"><?php echo esc_html( $apt_text ); ?></p>
				<?php endif; ?>
				<?php
				if ( $apt_button ) :
					get_template_part( 'template-parts/components/button', null, array_merge( $apt_button, array( 'style' => 'secondary' ) ) );
				endif;
				?>
			</div>

			<?php if ( $apt_images ) : ?>
				<div class="section-text-photos__photos">
					<?php foreach ( $apt_images as $apt_image ) : ?>
						<figure class="section-text-photos__photo">
							<?php
							echo wp_get_attachment_image(
								$apt_image,
								'large',
								false,
								array(
									'class' => 'section-text-photos__img',
									'sizes' => 1 === count( $apt_images ) ? '(min-width: 1280px) 55vw, (min-width: 768px) 56vw, 100vw' : '(min-width: 1280px) 27vw, (min-width: 768px) 41vw, 100vw',
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
