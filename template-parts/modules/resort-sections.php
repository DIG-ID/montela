<?php
/**
 * Resort sections module: a stack of blocks — title, text, button and two
 * photos — with text and photos swapping sides on every other block.
 *
 * Fields: acf-json/group_module_resort_sections.json, cloned into each
 * page's field group. Figma: "Sections Resort" — Our Resort desktop
 * 5895:20070, tablet 5895:20388, mobile 5895:20164 (also on Services).
 *
 * Arguments ($args), read by montela_module_field():
 * - row     (bool)       Optional. True inside a Flexible Content loop (fields read with get_sub_field()).
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names".
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'montela_module_field' ) ) {
	return;
}

$rs_items = montela_module_field( 'section_resort_sections_items', isset( $args ) ? $args : array() );

if ( ! $rs_items ) {
	return;
}
?>
<div class="section-resort-sections pb-32 xl:pb-[192px] text-montela-walnut">
	<div class="theme-container">
		<?php $rs_shown = 0; ?>
		<?php foreach ( array_values( $rs_items ) as $rs_i => $rs_item ) : ?>
			<?php
			if ( empty( $rs_item['title'] ) ) {
				continue;
			}
			$rs_title_id = wp_unique_id( 'resort-block-title-' );
			$rs_reverse  = 1 === $rs_i % 2;
			?>
			<section class="theme-grid resort-block<?php echo $rs_reverse ? ' resort-block--reverse' : ''; ?> items-start gap-y-7 md:gap-y-11 xl:gap-y-[52px]<?php echo $rs_shown++ ? ' mt-32' : ''; ?>" aria-labelledby="<?php echo esc_attr( $rs_title_id ); ?>">
				<h3 id="<?php echo esc_attr( $rs_title_id ); ?>" class="title-h3 resort-block__title col-span-full xl:col-start-2 xl:col-span-11">
					<?php echo wp_kses( $rs_item['title'], array( 'br' => array( 'class' => array() ) ) ); ?>
				</h3>

				<div class="resort-block__body col-span-full flex flex-col items-start gap-7 md:col-span-2 md:row-start-2 md:gap-11 <?php echo $rs_reverse ? 'md:col-start-5 xl:col-start-9 xl:col-span-4 xl:gap-[49px]' : 'xl:col-start-2 xl:col-span-3 xl:gap-16'; ?>">
					<?php if ( ! empty( $rs_item['text'] ) ) : ?>
						<p class="body-text resort-block__text<?php echo $rs_reverse ? ' xl:max-w-[518px]' : ''; ?>"><?php echo esc_html( $rs_item['text'] ); ?></p>
					<?php endif; ?>
					<?php
					if ( ! empty( $rs_item['link']['url'] ) ) {
						get_template_part( 'template-parts/components/button', null, array_merge( $rs_item['link'], array( 'style' => 'secondary' ) ) );
					}
					?>
				</div>

				<div class="resort-block__media col-span-full flex mt-4 aspect-[326/268] md:mt-0 md:col-span-4 md:row-start-2 md:gap-5 md:aspect-auto md:h-[385px] xl:col-span-8 xl:gap-[18px] xl:h-auto xl:aspect-[1206/600] <?php echo $rs_reverse ? 'md:col-start-1 md:flex-row-reverse xl:col-start-1' : 'xl:col-start-5'; ?>">
					<?php foreach ( array( 'image_inner', 'image_outer' ) as $rs_key ) : ?>
						<?php if ( ! empty( $rs_item[ $rs_key ] ) ) : ?>
							<div class="resort-block__photo min-w-0 h-full overflow-hidden flex-1 <?php echo 'image_inner' === $rs_key ? 'xl:flex-[522_1_0%]' : 'hidden md:block xl:flex-[666_1_0%]'; ?>">
								<?php
								echo wp_get_attachment_image(
									$rs_item[ $rs_key ],
									'large',
									false,
									array(
										'class' => 'resort-block__img w-full h-full object-cover',
										'sizes' => '(min-width: 1280px) 35vw, (min-width: 768px) 30vw, 100vw',
									)
								);
								?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>
	</div>
</div>
