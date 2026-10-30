<?php
/**
 * Homepage: "Book direct" — title and a list of benefits with icons.
 *
 * Fields: "Direct booking" tab of acf-json/group_page_home.json. Figma:
 * section-direct-book 5588:16243, -tablet 5426:4361, -mobile 5594:17021.
 *
 * @package montela
 * @subpackage Section
 * @since 1.5.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$book_title = get_field( 'section_direct_book_title' );
$book_items = get_field( 'section_direct_book_items' );

if ( ! $book_title && ! $book_items ) {
	return;
}

$book_title_id = wp_unique_id( 'section-direct-book-title-' );
?>
<section class="section-direct-book"<?php echo $book_title ? ' aria-labelledby="' . esc_attr( $book_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<?php if ( $book_title ) : ?>
			<h3 id="<?php echo esc_attr( $book_title_id ); ?>" class="title-h3 section-direct-book__title"><?php echo esc_html( $book_title ); ?></h3>
		<?php endif; ?>

		<?php
		get_template_part(
			'template-parts/components/icon-list',
			null,
			array(
				'items' => $book_items,
				'class' => 'section-direct-book__list',
			)
		);
		?>
	</div>
</section>
