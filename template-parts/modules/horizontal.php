<?php
/**
 * Horizontal module: a full-width photo, with a large title and a button
 * below it on the left and a short text on the right (stacked below desktop).
 *
 * Fields: acf-json/group_module_horizontal.json. Figma "Horizontal
 * section": Kids Playground desktop 5895:19923, tablet 5895:20544, mobile
 * 5895:20330 (also Services, Meetings 5895:19971, Montela Way).
 *
 * Arguments ($args):
 * - row     (bool)       Optional. True inside a Flexible Content loop (fields read with get_sub_field()).
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names".
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

$hz_image  = montela_module_field( 'section_horizontal_image', $args );
$hz_title  = montela_module_field( 'section_horizontal_title', $args );
$hz_button = montela_module_field( 'section_horizontal_button', $args );
$hz_text   = montela_module_field( 'section_horizontal_text', $args );

if ( ! $hz_image && ! $hz_title && ! $hz_text ) {
	return;
}

$hz_title_id = wp_unique_id( 'section-horizontal-title-' );
$hz_has_link = is_array( $hz_button ) && ! empty( $hz_button['url'] );
?>
<section class="section-horizontal"<?php echo $hz_title ? ' aria-labelledby="' . esc_attr( $hz_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<?php if ( $hz_image ) : ?>
				<figure class="section-horizontal__photo">
					<?php
					echo wp_get_attachment_image(
						$hz_image,
						'full',
						false,
						array(
							'class' => 'section-horizontal__img',
							'sizes' => '(min-width: 1920px) 1820px, (min-width: 1280px) calc(100vw - 100px), (min-width: 768px) calc(100vw - 128px), calc(100vw - 64px)',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $hz_title || $hz_has_link ) : ?>
				<div class="section-horizontal__head">
					<?php if ( $hz_title ) : ?>
						<h3 id="<?php echo esc_attr( $hz_title_id ); ?>" class="title-h3 section-horizontal__title">
							<?php echo wp_kses( $hz_title, array( 'br' => array( 'class' => array() ) ) ); ?>
						</h3>
					<?php endif; ?>
					<?php
					if ( $hz_has_link ) {
						get_template_part( 'template-parts/components/button', null, array_merge( $hz_button, array( 'style' => 'secondary' ) ) );
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $hz_text ) : ?>
				<div class="body-text section-horizontal__text"><?php echo wp_kses_post( $hz_text ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
