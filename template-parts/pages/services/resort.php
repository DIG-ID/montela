<?php
/**
 * Leistungen — "Alles direkt im Resort": a title over photo cards, each with
 * a framed panel below the photo (bold title, text). Desktop: three in a row
 * on columns 2–11, the title centred. Tablet and mobile: stacked, 12px apart,
 * the title to the left.
 *
 * Fields: tab "Resort" of acf-json/group_page_services.json. Figma: desktop
 * 5787:19130, tablet 5787:19170, mobile 5787:19201. Measurements:
 * _pages/_services.sass.
 *
 * @package montela
 * @subpackage Section
 * @since 1.39.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$resort_title = get_field( 'section_resort_title' );
$resort_items = get_field( 'section_resort_items' );

if ( ! $resort_items ) {
	return;
}

$resort_title_id = wp_unique_id( 'section-resort-title-' );
?>
<section class="section-resort mb-32 md:mb-[192px] text-montela-walnut"<?php echo $resort_title ? ' aria-labelledby="' . esc_attr( $resort_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid gap-y-11">
			<?php if ( $resort_title ) : ?>
				<h2 id="<?php echo esc_attr( $resort_title_id ); ?>" class="title-h3 col-span-full xl:text-center"><?php echo montela_text( $resort_title ); ?></h2>
			<?php endif; ?>

			<ul class="col-span-full grid gap-y-3 xl:col-start-2 xl:col-span-10 xl:grid-cols-3 xl:gap-x-5">
				<?php foreach ( $resort_items as $resort_item ) : ?>
					<li class="flex flex-col">
						<?php if ( ! empty( $resort_item['image'] ) ) : ?>
							<figure class="section-resort__photo m-0 overflow-hidden aspect-[700/330] xl:aspect-auto xl:h-[570px]">
								<?php
								echo wp_get_attachment_image(
									$resort_item['image'],
									'large',
									false,
									array(
										'class' => 'section-resort__img',
										'sizes' => '(min-width: 1280px) 26vw, (min-width: 768px) 85vw, 100vw',
									)
								);
								?>
							</figure>
						<?php endif; ?>
						<div class="section-resort__panel flex flex-1 flex-col gap-[10px] border border-montela-sage px-[27px] pt-[19px] pb-3 min-h-[129px] md:pl-[37px] md:pr-[17px] md:pt-6 md:pb-6 md:min-h-[160px] xl:px-6 xl:min-h-[200px]">
							<?php if ( ! empty( $resort_item['title'] ) ) : ?>
								<h3 class="section-resort__title"><?php echo montela_text( $resort_item['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $resort_item['text'] ) ) : ?>
								<p class="section-resort__text"><?php echo montela_text( $resort_item['text'] ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
