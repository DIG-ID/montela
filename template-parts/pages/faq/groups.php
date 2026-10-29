<?php
/**
 * FAQ page — the question groups ("Unterkunft & Aufenthalt", "Lage &
 * Anreise"…), one after the other. Each group is drawn by the FAQ module
 * (template-parts/modules/faq.php), with its content passed in.
 *
 * Fields: tab "Questions" of acf-json/group_page_faq.json — a repeater of
 * groups, each a title and its questions. Figma: desktop 5769:16763 (and
 * the five frames below it), tablet 5426:17907, mobile 5426:17003.
 *
 * @package montela
 * @subpackage Section
 * @since 1.29.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$faq_groups = get_field( 'section_faq_groups' );

foreach ( (array) $faq_groups as $faq_group ) {
	get_template_part(
		'template-parts/modules/faq',
		null,
		array(
			'data' => array(
				'title' => ! empty( $faq_group['title'] ) ? $faq_group['title'] : '',
				'items' => ! empty( $faq_group['items'] ) ? $faq_group['items'] : array(),
			),
		)
	);
}
