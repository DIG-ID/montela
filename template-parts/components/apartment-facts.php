<?php
/**
 * An apartment's facts — size, bathrooms, bedrooms, occupancy — each with its
 * icon, in two columns. The icons and labels are the theme's; the values come
 * from the apartment ("Facts" tab, acf-json/group_cpt_apartment.json). An
 * empty value leaves its line out. Styles: _components/_apartment-facts.sass;
 * the icons take the text colour.
 *
 * Used by the apartment cards (template-parts/pages/apartments/cards.php)
 * and the apartment single.
 *
 * Arguments ($args):
 * - post_id (int)    Required. Apartment ID.
 * - class   (string) Optional. Extra classes for the list.
 *
 * @package montela
 * @subpackage Component
 * @since 1.32.0
 */

if ( empty( $args['post_id'] ) || ! function_exists( 'get_field' ) ) {
	return;
}

$facts_id = (int) $args['post_id'];

// Design order: size, bathrooms on the first row; bedrooms, occupancy below.
$facts_values = array(
	'size'      => get_field( 'section_apartment_size', $facts_id ),
	'bathrooms' => get_field( 'section_apartment_bathrooms', $facts_id ),
	'bedrooms'  => get_field( 'section_apartment_bedrooms', $facts_id ),
	'occupancy' => get_field( 'section_apartment_occupancy', $facts_id ),
);

$facts_items = array();
foreach ( $facts_values as $facts_key => $facts_value ) {
	if ( '' === (string) $facts_value || null === $facts_value ) {
		continue;
	}

	switch ( $facts_key ) {
		case 'size':
			/* translators: %s: apartment size in square metres. */
			$facts_items[ $facts_key ] = sprintf( __( 'Grösse: %s m²', 'montela' ), number_format_i18n( (float) $facts_value ) );
			break;
		case 'bathrooms':
			/* translators: %s: number of bathrooms. */
			$facts_items[ $facts_key ] = sprintf( __( 'Badezimmer: %s', 'montela' ), number_format_i18n( (float) $facts_value ) );
			break;
		case 'bedrooms':
			/* translators: %s: number of bedrooms. */
			$facts_items[ $facts_key ] = sprintf( __( 'Schlafzimmer: %s', 'montela' ), number_format_i18n( (float) $facts_value ) );
			break;
		default:
			/* translators: %s: occupancy, e.g. "2-3 Personen". */
			$facts_items[ $facts_key ] = sprintf( __( 'Belegung: %s', 'montela' ), $facts_value );
	}
}

if ( ! $facts_items ) {
	return;
}

$facts_class = 'apartment-facts' . ( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' );
?>
<ul class="<?php echo esc_attr( $facts_class ); ?>">
	<?php foreach ( $facts_items as $facts_key => $facts_text ) : ?>
		<li class="apartment-facts__item">
			<span class="apartment-facts__icon apartment-facts__icon--<?php echo esc_attr( $facts_key ); ?>" aria-hidden="true"></span>
			<span class="apartment-facts__text"><?php echo esc_html( $facts_text ); ?></span>
		</li>
	<?php endforeach; ?>
</ul>
