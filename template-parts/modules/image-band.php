<?php
/**
 * Image band module: a single full-width photo between sections.
 *
 * Fields: acf-json/group_module_image_band.json, cloned into each page's
 * field group. Figma: "Rectangle 71" — desktop 5426:5003, tablet 5426:4501,
 * mobile 5458:15111.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'band_' for a clone named 'band'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.7.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$band_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$band_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';
$band_image  = get_field( $band_prefix . 'section_image_band_image', $band_ctx );

if ( ! $band_image ) {
	return;
}
?>
<figure class="section-image-band">
	<?php
	echo wp_get_attachment_image(
		$band_image,
		'full',
		false,
		array(
			'class' => 'section-image-band__img',
			'sizes' => '100vw',
		)
	);
	?>
</figure>
