<?php
/**
 * Bike Hotel — bike experiences: title and text beside a set of cards
 * (tours, trails, local partners), inside a thin sage frame. Used twice on
 * the page, with its own fields each time: "Bike-Erlebnisse rund um
 * Montela" (section_bike_experiences_*) and "Lokales Know-how für Ihre
 * Bikeferien" (section_bike_partners_*). Cards are filled in by hand and
 * drawn by components/card-preview.php.
 *
 * Two desktop layouts ($args['layout']), identical on tablet and mobile:
 * - 'right' Copy on the left, cards on the right half, arrows in the
 *           frame's top-right corner. Figma "Activities_with arrows":
 *           desktop 5895:19869, tablet 5895:20609, mobile 5895:20379.
 * - 'left'  Cards on the left half, copy on the right. Figma "Activities
 *           all_flip over": desktop 5895:19870, tablet 5895:20608, mobile
 *           5895:20380.
 *
 * Up to 4 cards sit in a 2 × 2 grid from tablet up; more than 4 turn the
 * grid into a two-row track that the arrows page through one column at a
 * time. On mobile it is always one card at a time. The track is a native
 * scroll-snap row driven by the carousel in assets/js/modules/activities.js
 * (shared with the Activities module) — no slider library.
 *
 * Fields: "Bike experiences" and "Bike partners" tabs of
 * acf-json/group_page_bike_hotel.json. Styles in
 * _pages/_bike-hotel/_bike-experiences.sass.
 *
 * Arguments ($args):
 * - fields (string) Field name stem: 'bike_experiences' (default) or 'bike_partners'.
 * - layout (string) 'right' (default) or 'left'.
 *
 * @package montela
 * @subpackage Section
 * @since 1.16.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$bx_stem   = ( isset( $args['fields'] ) && 'bike_partners' === $args['fields'] ) ? 'bike_partners' : 'bike_experiences';
$bx_layout = ( isset( $args['layout'] ) && 'left' === $args['layout'] ) ? 'left' : 'right';
$bx_title  = get_field( 'section_' . $bx_stem . '_title' );
$bx_text   = get_field( 'section_' . $bx_stem . '_text' );
$bx_label  = __( 'Mehr erfahren', 'montela' ); // Figma has the placeholder "Link to subpage"; same label as the other cards until the final wording is decided.

$bx_cards = array();
foreach ( (array) get_field( 'section_' . $bx_stem . '_cards' ) as $bx_row ) {
	if ( empty( $bx_row['title'] ) ) {
		continue;
	}
	$bx_link    = ( ! empty( $bx_row['link'] ) && is_array( $bx_row['link'] ) ) ? $bx_row['link'] : array();
	$bx_cards[] = array(
		'image'  => ! empty( $bx_row['image'] ) ? $bx_row['image'] : 0,
		'title'  => $bx_row['title'],
		'text'   => ! empty( $bx_row['text'] ) ? $bx_row['text'] : '',
		'url'    => ! empty( $bx_link['url'] ) ? $bx_link['url'] : '',
		'target' => ! empty( $bx_link['target'] ) ? $bx_link['target'] : '',
		'label'  => ! empty( $bx_link['title'] ) ? $bx_link['title'] : $bx_label,
	);
}

// No cards, no section.
if ( ! $bx_cards ) {
	return;
}

$bx_count = count( $bx_cards );

if ( $bx_count > 1 && function_exists( 'montela_enqueue_module' ) ) {
	montela_enqueue_module( 'activities' );
}

$bx_class = 'section-bike-experiences section-bike-experiences--' . $bx_layout;
if ( $bx_count > 4 ) {
	$bx_class .= ' section-bike-experiences--paged';
} elseif ( $bx_count <= 2 ) {
	$bx_class .= ' section-bike-experiences--one-row';
}

$bx_title_id = wp_unique_id( 'section-bike-experiences-title-' );
?>
<section class="<?php echo esc_attr( $bx_class ); ?>"<?php echo $bx_title ? ' aria-labelledby="' . esc_attr( $bx_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="section-bike-experiences__inner"<?php echo $bx_count > 1 ? ' data-activities-carousel' : ''; ?>>
			<div class="section-bike-experiences__frame" aria-hidden="true"></div>

			<?php if ( $bx_title || $bx_text ) : ?>
				<div class="section-bike-experiences__copy">
					<?php if ( $bx_title ) : ?>
						<h3 id="<?php echo esc_attr( $bx_title_id ); ?>" class="title-h3 section-bike-experiences__title"><?php echo esc_html( $bx_title ); ?></h3>
					<?php endif; ?>

					<?php if ( $bx_text ) : ?>
						<p class="body-text section-bike-experiences__text"><?php echo esc_html( $bx_text ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $bx_count > 1 ) : ?>
				<div class="slider-nav slider-nav--sage section-bike-experiences__nav">
					<button class="slider-nav__btn slider-nav__btn--prev" type="button" data-activities-prev aria-label="<?php esc_attr_e( 'Vorherige Aktivität', 'montela' ); ?>"></button>
					<button class="slider-nav__btn slider-nav__btn--next" type="button" data-activities-next aria-label="<?php esc_attr_e( 'Nächste Aktivität', 'montela' ); ?>"></button>
				</div>
			<?php endif; ?>

			<div class="section-bike-experiences__cards" data-activities-track>
				<?php
				foreach ( $bx_cards as $bx_card ) :
					get_template_part(
						'template-parts/components/card-preview',
						null,
						array_merge(
							$bx_card,
							array(
								'class' => 'section-bike-experiences__card',
								'sizes' => '(min-width: 1280px) 22vw, (min-width: 768px) 45vw, 100vw',
							)
						)
					);
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>
