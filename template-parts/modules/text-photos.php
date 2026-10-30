<?php
/**
 * Text with photos module: title, then text and button beside one or two
 * photos. The layout follows the number of photos and, with two, the
 * editor's desktop layout: side by side (default) or staggered — a small
 * photo above the title, the large one beside title and text (Premium
 * Stay). Measurements: _text-photos.sass; layout classes here.
 *
 * Fields: acf-json/group_module_text_photos.json, cloned into each page's
 * field group. The fields keep their original names and keys
 * (section_find_apartment_*, field_page_home_find_apartment_*) from when
 * this was a homepage-only section, so existing content keeps loading.
 * Figma: two photos — homepage section-text-photos desktop 5588:16261,
 * tablet 5453:8586, mobile 5426:4201; one photo — Aktivitäten desktop
 * 5778:17124, tablet 5561:11940, mobile 5561:11955; staggered — Premium
 * Stay desktop 5812:15507 (tablet and mobile as two photos).
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
$apt_count    = count( $apt_images );
$apt_layout   = 2 === $apt_count && 'staggered' === get_field( $apt_prefix . 'section_find_apartment_layout', $apt_ctx ) ? 'staggered' : 'side';

// Layout per variant: one photo, two side by side, two staggered (desktop).
if ( 1 === $apt_count ) {
	$apt_cls = array(
		'grid'    => 'gap-y-11 xl:gap-y-16',
		'title'   => 'col-span-full xl:col-start-2 xl:col-span-10',
		'content' => 'col-span-full md:col-span-2 xl:col-start-2 xl:col-span-3 flex flex-col items-start gap-5 md:gap-11 xl:gap-16',
		'text'    => '',
		'button'  => '',
		'photos'  => 'col-span-full -mt-6 md:mt-0 md:col-start-3 md:col-span-4 xl:col-start-5 xl:col-span-7 flex flex-col',
		'photo'   => array( 'aspect-[326/268] md:aspect-[463/314] xl:aspect-auto xl:h-[600px]' ),
		'sizes'   => array( '(min-width: 1280px) 55vw, (min-width: 768px) 56vw, 100vw' ),
	);
} elseif ( 'staggered' === $apt_layout ) {
	// Desktop rows: small photo · title (grows, so title and text end
	// level with the large photo) · text · button. The content and photo
	// wrappers dissolve into the grid (xl:contents).
	$apt_cls = array(
		'grid'    => 'gap-y-16 md:gap-y-10 xl:gap-y-0 xl:grid-rows-[auto_1fr_auto_auto]',
		'title'   => 'col-span-full xl:col-start-2 xl:col-span-4 xl:row-start-2 xl:self-end',
		'content' => 'col-span-full md:col-span-3 flex flex-col items-start gap-6 md:gap-10 xl:contents',
		'text'    => 'xl:col-start-2 xl:col-span-3 xl:row-start-3 xl:mt-9',
		'button'  => 'xl:col-start-2 xl:col-span-4 xl:row-start-4 xl:justify-self-start xl:mt-11',
		'photos'  => 'col-span-full md:col-start-4 md:col-span-3 flex flex-col gap-16 md:gap-5 xl:contents',
		'photo'   => array(
			'aspect-[699/600] md:aspect-[360/246] xl:col-start-2 xl:col-span-4 xl:row-start-1 xl:aspect-[593/455]',
			'aspect-[699/600] md:aspect-[360/246] xl:col-start-7 xl:col-span-6 xl:row-start-2 xl:row-span-2 xl:aspect-[895/700]',
		),
		'sizes'   => array( '(min-width: 1280px) 31vw, (min-width: 768px) 41vw, 100vw', '(min-width: 1280px) 47vw, (min-width: 768px) 41vw, 100vw' ),
	);
} else {
	$apt_cls = array(
		'grid'    => 'gap-y-16 md:gap-y-10 xl:gap-y-16',
		'title'   => 'col-span-full xl:col-start-2 xl:col-span-10',
		'content' => 'col-span-full md:col-span-3 xl:col-start-2 xl:col-span-3 flex flex-col items-start gap-6 md:gap-10 xl:gap-16',
		'text'    => '',
		'button'  => '',
		'photos'  => 'col-span-full md:col-start-4 md:col-span-3 xl:col-start-5 xl:col-span-7 flex flex-col gap-16 md:gap-5 xl:flex-row xl:gap-5',
		'photo'   => array( 'aspect-[699/600] md:aspect-[360/246] xl:aspect-auto xl:h-[600px] xl:flex-1' ),
		'sizes'   => array( '(min-width: 1280px) 27vw, (min-width: 768px) 41vw, 100vw' ),
	);
}

$apt_class = 'section-text-photos section-text-photos--' . ( 1 === $apt_count ? 'single' : $apt_layout ) . ' pb-[128px] md:pb-[192px] xl:pb-[224px] text-montela-walnut';
?>
<section class="<?php echo esc_attr( $apt_class ); ?>"<?php echo $apt_title ? ' aria-labelledby="' . esc_attr( $apt_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-text-photos__grid <?php echo esc_attr( $apt_cls['grid'] ); ?>">
			<?php if ( $apt_title ) : ?>
				<h2 id="<?php echo esc_attr( $apt_title_id ); ?>" class="title-h3 section-text-photos__title <?php echo esc_attr( $apt_cls['title'] ); ?>">
					<?php echo wp_kses( $apt_title, array( 'br' => array( 'class' => array() ) ) ); ?>
				</h2>
			<?php endif; ?>

			<div class="section-text-photos__content <?php echo esc_attr( $apt_cls['content'] ); ?>">
				<?php if ( $apt_text ) : ?>
					<p class="<?php echo esc_attr( trim( 'body-text section-text-photos__text ' . $apt_cls['text'] ) ); ?>"><?php echo esc_html( $apt_text ); ?></p>
				<?php endif; ?>
				<?php
				if ( $apt_button ) :
					get_template_part(
						'template-parts/components/button',
						null,
						array_merge(
							$apt_button,
							array(
								'style' => 'secondary',
								'class' => $apt_cls['button'],
							)
						)
					);
				endif;
				?>
			</div>

			<?php if ( $apt_images ) : ?>
				<div class="section-text-photos__photos <?php echo esc_attr( $apt_cls['photos'] ); ?>">
					<?php foreach ( array_values( $apt_images ) as $apt_index => $apt_image ) : ?>
						<figure class="section-text-photos__photo <?php echo esc_attr( $apt_cls['photo'][ $apt_index % count( $apt_cls['photo'] ) ] ); ?>">
							<?php
							echo wp_get_attachment_image(
								$apt_image,
								'large',
								false,
								array(
									'class' => 'section-text-photos__img',
									'sizes' => $apt_cls['sizes'][ $apt_index % count( $apt_cls['sizes'] ) ],
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
