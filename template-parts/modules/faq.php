<?php
/**
 * FAQ module: title, text and a button to the full FAQ, over an accordion
 * of questions. Native <details> elements: no script, keyboard and screen
 * readers work out of the box; opening one closes the others (shared name).
 *
 * Fields: acf-json/group_module_faq.json, cloned into each page's field
 * group. Figma (Info & Kontakt): desktop 5769:16438, tablet 5613:24350,
 * mobile 5613:24441.
 *
 * Arguments ($args):
 * - post_id (int|string) Optional. Where to read the fields from. Default: current post.
 * - prefix  (string)     Optional. Clone field prefix, when the clone uses
 *                        "Prefix field names" — e.g. 'faq_' for a clone named 'faq'.
 * - data    (array)      Optional. The content itself, instead of the fields:
 *                        'title', 'text', 'button' (ACF link array) and 'items'
 *                        (rows with 'question' and 'answer'). For pages that
 *                        list several FAQ groups from a repeater (FAQ page).
 *
 * @package montela
 * @subpackage Section
 * @since 1.28.0
 */

$faq_data = ( ! empty( $args['data'] ) && is_array( $args['data'] ) ) ? $args['data'] : null;

if ( $faq_data ) {
	$faq_title  = ! empty( $faq_data['title'] ) ? $faq_data['title'] : '';
	$faq_text   = ! empty( $faq_data['text'] ) ? $faq_data['text'] : '';
	$faq_button = ! empty( $faq_data['button'] ) ? $faq_data['button'] : null;
	$faq_items  = ! empty( $faq_data['items'] ) ? $faq_data['items'] : array();
} else {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$faq_ctx    = ! empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
	$faq_prefix = ! empty( $args['prefix'] ) ? $args['prefix'] : '';

	$faq_title  = get_field( $faq_prefix . 'section_faq_title', $faq_ctx );
	$faq_text   = get_field( $faq_prefix . 'section_faq_text', $faq_ctx );
	$faq_button = get_field( $faq_prefix . 'section_faq_button', $faq_ctx );
	$faq_items  = get_field( $faq_prefix . 'section_faq_items', $faq_ctx );
}

$faq_items = array_values(
	array_filter(
		(array) $faq_items,
		function ( $faq_item ) {
			return ! empty( $faq_item['question'] );
		}
	)
);

if ( ! $faq_items ) {
	return;
}

$faq_uid      = wp_unique_id( 'section-faq-' );
$faq_title_id = $faq_uid . '-title';
?>
<section class="section-faq"<?php echo $faq_title ? ' aria-labelledby="' . esc_attr( $faq_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid">
			<div class="section-faq__head">
				<div class="section-faq__copy">
					<?php if ( $faq_title ) : ?>
						<h2 id="<?php echo esc_attr( $faq_title_id ); ?>" class="title-h3 section-faq__title"><?php echo wp_kses( $faq_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
					<?php endif; ?>
					<?php if ( $faq_text ) : ?>
						<p class="body-text section-faq__text"><?php echo esc_html( $faq_text ); ?></p>
					<?php endif; ?>
				</div>
				<?php
				if ( is_array( $faq_button ) ) :
					get_template_part( 'template-parts/components/button', null, array_merge( $faq_button, array( 'style' => 'secondary' ) ) );
				endif;
				?>
			</div>

			<div class="section-faq__list">
				<?php foreach ( $faq_items as $faq_item ) : ?>
					<details class="section-faq__item" name="<?php echo esc_attr( $faq_uid ); ?>">
						<summary class="section-faq__question">
							<span class="section-faq__question-text"><?php echo esc_html( $faq_item['question'] ); ?></span>
							<span class="section-faq__icon" aria-hidden="true"></span>
						</summary>
						<?php if ( ! empty( $faq_item['answer'] ) ) : ?>
							<div class="section-faq__answer"><?php echo wp_kses_post( $faq_item['answer'] ); ?></div>
						<?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
