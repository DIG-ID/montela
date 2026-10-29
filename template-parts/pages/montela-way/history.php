<?php
/**
 * Der Montela Weg — history ("Unsere Geschichte"): a wide photo, then the
 * title beside the text and a highlighted paragraph (bold title + text).
 *
 * Fields: tab "History" of acf-json/group_page_montela_way.json. Figma:
 * "Horizontal section" desktop 5767:15946, tablet 5767:15957, mobile
 * 5767:16113.
 *
 * @package montela
 * @subpackage Section
 * @since 1.25.0
 */

if ( ! function_exists( 'get_field' ) ) {
	return;
}

$history_image    = get_field( 'section_history_image' );
$history_title    = get_field( 'section_history_title' );
$history_text     = get_field( 'section_history_text' );
$history_hl_title = get_field( 'section_history_highlight_title' );
$history_hl_text  = get_field( 'section_history_highlight_text' );

if ( ! $history_image && ! $history_title ) {
	return;
}

$history_title_id = wp_unique_id( 'section-history-title-' );
?>
<section class="section-history"<?php echo $history_title ? ' aria-labelledby="' . esc_attr( $history_title_id ) . '"' : ''; ?>>
	<div class="theme-container">
		<div class="theme-grid section-history__grid">
			<?php if ( $history_image ) : ?>
				<figure class="section-history__photo">
					<?php
					echo wp_get_attachment_image(
						$history_image,
						'full',
						false,
						array(
							'class' => 'section-history__img',
							'sizes' => '(min-width: 1920px) 1820px, 100vw',
						)
					);
					?>
				</figure>
			<?php endif; ?>

			<?php if ( $history_title ) : ?>
				<h2 id="<?php echo esc_attr( $history_title_id ); ?>" class="title-h3 section-history__title"><?php echo wp_kses( $history_title, array( 'br' => array( 'class' => array() ) ) ); ?></h2>
			<?php endif; ?>

			<?php if ( $history_text ) : ?>
				<p class="body-text section-history__text"><?php echo esc_html( $history_text ); ?></p>
			<?php endif; ?>

			<?php if ( $history_hl_title || $history_hl_text ) : ?>
				<div class="body-text section-history__highlight">
					<?php if ( $history_hl_title ) : ?>
						<h3 class="section-history__highlight-title"><?php echo esc_html( $history_hl_title ); ?></h3>
					<?php endif; ?>
					<?php if ( $history_hl_text ) : ?>
						<p><?php echo esc_html( $history_hl_text ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
