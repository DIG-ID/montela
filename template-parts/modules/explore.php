<?php
/**
 * Explore module: a sage text panel (title, small photo, text, button)
 * beside a photo slider — side by side on desktop, slider on top below that.
 *
 * Fields: acf-json/group_module_explore.json, cloned into each page's field
 * group. Figma: "Explore with photo" desktop 5685:17337, tablet 5685:17362,
 * mobile 5685:17389. Enqueues the Swiper module itself when there are slides;
 * for the CSS to land in <head>, also enqueue it on wp_enqueue_scripts for the
 * templates that use this module (see inc/enqueue.php).
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'explore_' for a clone named 'explore'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.6.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$exp_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$exp_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$exp_title  = get_field( $exp_prefix . 'section_explore_title', $exp_ctx );
$exp_image  = get_field( $exp_prefix . 'section_explore_image', $exp_ctx );
$exp_text   = get_field( $exp_prefix . 'section_explore_text', $exp_ctx );
$exp_button = get_field( $exp_prefix . 'section_explore_button', $exp_ctx );
$exp_slides = get_field( $exp_prefix . 'section_explore_slides', $exp_ctx );

if ( ! $exp_title && ! $exp_text && ! $exp_slides ) {
	return;
}

// The module can land on any page: make sure the slider script is there. On
// templates that already enqueue Swiper on wp_enqueue_scripts (homepage) this
// is a no-op; elsewhere its CSS is printed late, in the footer.
if ( $exp_slides && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$exp_title_id = wp_unique_id( 'section-explore-title-' );
$exp_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 0,
);
?>
<section class="section-explore"<?php echo $exp_title ? ' aria-labelledby="' . esc_attr( $exp_title_id ) . '"' : ''; ?>>
	<?php if ( $exp_slides ) : ?>
		<div class="section-explore__slider" data-swiper-root>
			<div class="swiper section-explore__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $exp_swiper ) ); ?>">
				<div class="swiper-wrapper">
					<?php foreach ( $exp_slides as $exp_slide ) : ?>
						<div class="swiper-slide section-explore__slide">
							<?php
							echo wp_get_attachment_image(
								$exp_slide,
								'full',
								false,
								array(
									'class' => 'section-explore__img',
									'sizes' => '(min-width: 1280px) 50vw, 100vw',
								)
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( count( $exp_slides ) > 1 ) : ?>
				<div class="slider-nav slider-nav--sage section-explore__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="section-explore__panel">
		<?php if ( $exp_title ) : ?>
			<h2 id="<?php echo esc_attr( $exp_title_id ); ?>" class="title-h3 section-explore__title"><?php echo esc_html( $exp_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $exp_image ) : ?>
			<figure class="section-explore__photo">
				<?php
				echo wp_get_attachment_image(
					$exp_image,
					'large',
					false,
					array(
						'class' => 'section-explore__img',
						'sizes' => '(min-width: 1280px) 506px, (min-width: 768px) 460px, 100vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>

		<?php if ( $exp_text ) : ?>
			<p class="body-text section-explore__text"><?php echo esc_html( $exp_text ); ?></p>
		<?php endif; ?>

		<?php
		if ( $exp_button ) :
			get_template_part( 'template-parts/components/button', null, array_merge( $exp_button, array( 'style' => 'outline' ) ) );
		endif;
		?>
	</div>
</section>
