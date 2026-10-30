<?php
/**
 * Explore module: a sage text panel (overline, title, small photo, text,
 * button) beside a photo slider — side by side on desktop, slider on top
 * below that. The overline and the small photo are optional; without the
 * photo the panel spaces its text as in the Bike Hotel frames.
 *
 * Fields: acf-json/group_module_explore.json — cloned into a page's field
 * group, or used as a Flexible Content layout. Figma: "Explore with photo"
 * desktop 5685:17337, tablet 5685:17362, mobile 5685:17389; without the
 * photo, with an overline ("Next to footer_explore", Bike Hotel): desktop
 * 5895:19865, tablet 5895:20547, mobile 5895:20377. Enqueues the Swiper
 * module itself when there are slides; for the CSS to land in <head>, also
 * enqueue it on wp_enqueue_scripts for the templates that use this module
 * (see inc/enqueue.php).
 *
 * Arguments ($args):
 * - row     (bool)       Optional. True inside a Flexible Content loop (fields
 *                        read with get_sub_field()). Since 1.13.0.
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'explore_' for a clone named 'explore'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.6.0
 */

if ( ! function_exists( 'montela_module_field' ) ) {
	return;
}

$exp_args = isset( $args ) && is_array( $args ) ? $args : array();

$exp_overline = montela_module_field( 'section_explore_overline', $exp_args );
$exp_title    = montela_module_field( 'section_explore_title', $exp_args );
$exp_image    = montela_module_field( 'section_explore_image', $exp_args );
$exp_text     = montela_module_field( 'section_explore_text', $exp_args );
$exp_button   = montela_module_field( 'section_explore_button', $exp_args );
$exp_slides   = montela_module_field( 'section_explore_slides', $exp_args );

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
// The arrows always show, as drawn — even with one photo (disabled then;
// Swiper would hide them when locked, hence watchOverflow off).
$exp_swiper = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 0,
	'watchOverflow' => false,
);
?>
<section class="section-explore<?php echo $exp_image ? '' : ' section-explore--no-photo'; ?>"<?php echo $exp_title ? ' aria-labelledby="' . esc_attr( $exp_title_id ) . '"' : ''; ?>>
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

			<div class="slider-nav slider-nav--sage section-explore__nav">
				<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous photo', 'montela' ); ?>"></button>
				<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next photo', 'montela' ); ?>"></button>
			</div>
		</div>
	<?php endif; ?>

	<div class="section-explore__panel">
		<?php if ( $exp_overline ) : ?>
			<h2 class="title-h2 section-explore__overline"><?php echo esc_html( $exp_overline ); ?></h2>
		<?php endif; ?>

		<?php if ( $exp_title ) : ?>
			<h3 id="<?php echo esc_attr( $exp_title_id ); ?>" class="title-h3 section-explore__title"><?php echo esc_html( $exp_title ); ?></h3>
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
