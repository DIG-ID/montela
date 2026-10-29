<?php
/**
 * Default page content: the WP editor content, styled as running text —
 * headings, paragraphs, lists and links (_layout/_page.sass). Used by the
 * legal pages (Datenschutz, AGB) and any other text page.
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

?>
<div class="page-content">
	<div class="theme-container">
		<div class="theme-grid">
			<div class="page-content__body">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</div>
