<?php
/**
 * Space module ("Viel Raum für gemeinsame Ferien", "Flexibel wohnen mit
 * passenden Leistungen"): title, text and button, two photos and a list
 * (icon, bold line, text — the icon is optional).
 *
 * Desktop: title across the top; text and button beside photo 1; then photo
 * 2 beside the list. Tablet: title, text and button beside photo 1, photo 2
 * below them, the list centred underneath. Mobile: all stacked.
 *
 * Two desktop layouts (field "Desktop layout"): "list" — the list in one
 * column, 75px between the rows (Aufenthalt) — and "columns" — the list in
 * two columns, 36px between the rows and 192px above the title (Leistungen).
 * The columns layout also sets the list text in the body sizes on tablet
 * (18/25) and makes photo 2 lower on mobile, as drawn.
 *
 * Fields: acf-json/group_module_space.json, cloned into each page's field
 * group. The field names and keys (field_page_stay_space_*) come from when
 * this was an Aufenthalt-only section: keep them, the content depends on
 * them. List: components/icon-list.php. Figma: Aufenthalt desktop
 * 5767:15879, tablet 5466:16170, mobile 5466:16083; Leistungen desktop
 * 5787:19062, tablet 5496:4521, mobile 5496:4825. Measurements:
 * _modules/_space.sass.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'space_' for a clone named 'space'.
 * - padding_top (bool)   Optional. false drops the columns layout's 192px
 *                        above the title (Services template). Default true.
 *
 * @package montela
 * @subpackage Section
 * @since 1.24.0
 */

if ( ! function_exists( 'montela_module_field' ) ) {
	return;
}

$space_title   = montela_module_field( 'section_space_title', $args );
$space_text    = montela_module_field( 'section_space_text', $args );
$space_button  = montela_module_field( 'section_space_button', $args );
$space_image_1 = montela_module_field( 'section_space_image_1', $args );
$space_image_2 = montela_module_field( 'section_space_image_2', $args );
$space_items   = montela_module_field( 'section_space_items', $args );
$space_layout  = 'columns' === montela_module_field( 'section_space_layout', $args ) ? 'columns' : 'list';

if ( ! $space_title && ! $space_text && ! $space_items ) {
	return;
}

// Layout per desktop variant; tablet and mobile are shared, but for photo
// 2's mobile ratio. Measurements in _modules/_space.sass.
if ( 'columns' === $space_layout ) {
	$space_cls = array(
		'section' => 'xl:pt-[192px]',
		'content' => 'xl:mt-9',
		'photo_1' => 'xl:mt-9',
		'photo_2' => 'aspect-[320/223] xl:mt-9',
		'list'    => 'md:gap-y-[72px] xl:grid xl:self-start xl:col-start-7 xl:col-span-6 xl:grid-cols-2 xl:gap-x-5 xl:gap-y-[130px] xl:mt-[81px]',
	);
} else {
	$space_cls = array(
		'section' => '',
		'content' => 'xl:gap-[75px] xl:mt-[75px]',
		'photo_1' => 'xl:mt-[75px]',
		'photo_2' => 'aspect-[660/556] xl:mt-[75px]',
		'list'    => 'md:gap-y-[72px] xl:col-start-7 xl:col-span-4 xl:max-w-[496px] xl:mt-[75px]',
	);
}

// A page can drop the space above the title (Services template).
if ( isset( $args['padding_top'] ) && false === $args['padding_top'] ) {
	$space_cls['section'] = '';
}

// Items without icons drop the icon column, or the text lands in it.
$space_has_icons = $space_items && array_filter( wp_list_pluck( $space_items, 'icon' ) );

$space_title_id = wp_unique_id( 'section-space-title-' );
?>
<section class="section-space section-space--<?php echo esc_attr( $space_layout ); ?> pb-[128px] md:pb-[192px] text-montela-walnut <?php echo esc_attr( $space_cls['section'] ); ?>"<?php echo $space_title ? ' aria-labelledby="' . esc_attr( $space_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<?php if ( $space_title ) : ?>
				<h2 id="<?php echo esc_attr( $space_title_id ); ?>" class="title-h3 col-span-full xl:col-start-2 xl:col-span-11"><?php echo montela_text( $space_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $space_text || $space_button ) : ?>
				<div class="col-span-full flex flex-col items-start gap-8 mt-4 md:col-span-3 md:row-start-2 md:gap-10 md:mt-10 xl:col-start-2 <?php echo esc_attr( $space_cls['content'] ); ?>">
					<?php if ( $space_text ) : ?>
						<p class="body-text"><?php echo montela_text( $space_text ); ?></p>
					<?php endif; ?>
					<?php
					if ( $space_button ) :
						get_template_part( 'template-parts/components/button', null, array_merge( $space_button, array( 'style' => 'secondary' ) ) );
					endif;
					?>
				</div>
			<?php endif; ?>

			<?php if ( $space_image_1 ) : ?>
				<figure class="section-space__photo col-span-full mt-8 aspect-[320/246] md:col-start-4 md:col-span-3 md:row-start-2 md:mt-10 md:aspect-[360/246] xl:col-start-6 xl:col-span-7 xl:aspect-[1050/632] <?php echo esc_attr( $space_cls['photo_1'] ); ?>">
					<?php
					echo wp_get_attachment_image(
						$space_image_1,
						'large',
						false,
						array(
							'class' => 'section-space__img',
							'sizes' => '(min-width: 1280px) 55vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $space_image_2 ) : ?>
				<figure class="section-space__photo col-span-full mt-8 md:col-start-1 md:col-span-3 md:row-start-3 md:mt-5 md:aspect-[360/246] xl:col-start-2 xl:col-span-4 xl:w-[91.06%] xl:aspect-[540/455] <?php echo esc_attr( $space_cls['photo_2'] ); ?>">
					<?php
					echo wp_get_attachment_image(
						$space_image_2,
						'large',
						false,
						array(
							'class' => 'section-space__img',
							'sizes' => '(min-width: 1280px) 28vw, (min-width: 768px) 41vw, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php
			if ( $space_items ) :
				get_template_part(
					'template-parts/components/icon-list',
					null,
					array(
						'items'      => $space_items,
						'item_class' => $space_has_icons ? '' : 'grid-cols-1',
						'class'      => 'section-space__list col-span-full mt-4 gap-y-4 md:row-start-4 md:justify-self-center md:w-[464px] md:mt-9 xl:row-start-3 xl:justify-self-stretch xl:w-auto ' . $space_cls['list'],
					)
				);
			endif;
			?>
		</div>
	</div>
</section>
