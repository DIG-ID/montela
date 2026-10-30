<?php
/**
 * Services comparison module: title, text and button, then one column per
 * plan (photo, title, subtitle and a checklist) — two side by side on
 * desktop, a slider with arrows on tablet and mobile.
 *
 * Fields: acf-json/group_module_services.json, cloned into each page's field
 * group. The checklist is a textarea, one feature per line. Figma: "Services
 * comparison" desktop 5679:16769, tablet 5679:16905, mobile 5679:16983.
 * Enqueues the Swiper module itself.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'services_' for a clone named 'services'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.21.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$srv_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
$srv_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

$srv_title  = get_field( $srv_prefix . 'section_services_title', $srv_ctx );
$srv_text   = get_field( $srv_prefix . 'section_services_text', $srv_ctx );
$srv_button = get_field( $srv_prefix . 'section_services_button', $srv_ctx );
$srv_plans  = get_field( $srv_prefix . 'section_services_plans', $srv_ctx );

if ( ! $srv_plans ) {
	return;
}

if ( function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'swiper' );
}

$srv_title_id = wp_unique_id( 'section-services-title-' );
$srv_swiper   = array(
	'slidesPerView' => 1,
	'spaceBetween'  => 20,
	'breakpoints'   => array(
		1280 => array( 'slidesPerView' => 2 ),
	),
);
?>
<section class="section-services"<?php echo $srv_title ? ' aria-labelledby="' . esc_attr( $srv_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-services__inner" data-swiper-root>
				<div class="section-services__head">
					<?php if ( $srv_title ) : ?>
						<h2 id="<?php echo esc_attr( $srv_title_id ); ?>" class="title-h3 section-services__title"><?php echo wp_kses( $srv_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
					<?php endif; ?>

					<?php if ( $srv_text || $srv_button ) : ?>
						<div class="section-services__intro">
							<?php if ( $srv_text ) : ?>
								<p class="body-text section-services__text"><?php echo esc_html( $srv_text ); ?></p>
							<?php endif; ?>
							<?php
							if ( $srv_button ) :
								get_template_part( 'template-parts/components/button', null, array_merge( $srv_button, array( 'style' => 'secondary' ) ) );
							endif;
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="swiper section-services__swiper" data-swiper="<?php echo esc_attr( wp_json_encode( $srv_swiper ) ); ?>">
					<div class="swiper-wrapper">
						<?php
						foreach ( $srv_plans as $srv_plan ) :
							$srv_features = ! empty( $srv_plan['features'] ) ? array_filter( array_map( 'trim', preg_split( '/\R/', $srv_plan['features'] ) ) ) : array();
							?>
							<article class="swiper-slide section-services__plan">
								<?php if ( ! empty( $srv_plan['image'] ) ) : ?>
									<figure class="section-services__photo">
										<?php
										echo wp_get_attachment_image(
											$srv_plan['image'],
											'large',
											false,
											array(
												'class' => 'section-services__img',
												'sizes' => '(min-width: 1280px) 39vw, 100vw',
											)
										);
										?>
									</figure>
								<?php endif; ?>

								<div class="section-services__box">
									<?php if ( ! empty( $srv_plan['title'] ) ) : ?>
										<h3 class="section-services__plan-title"><?php echo esc_html( $srv_plan['title'] ); ?></h3>
									<?php endif; ?>
									<?php if ( ! empty( $srv_plan['subtitle'] ) ) : ?>
										<p class="section-services__subtitle"><?php echo esc_html( $srv_plan['subtitle'] ); ?></p>
									<?php endif; ?>
									<?php if ( $srv_features ) : ?>
										<ul class="section-services__features">
											<?php foreach ( $srv_features as $srv_feature ) : ?>
												<li><?php echo esc_html( $srv_feature ); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="slider-nav slider-nav--walnut section-services__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-swiper-prev aria-label="<?php esc_attr_e( 'Previous plan', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-swiper-next aria-label="<?php esc_attr_e( 'Next plan', 'montela' ); ?>"></button>
				</div>
			</div>
		</div>
	</div>
</section>
