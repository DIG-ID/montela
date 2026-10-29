<?php
/**
 * Page header: the page title on a sage band, under the header — for pages
 * without a hero (the default page template — Datenschutz, AGB — and the
 * Impressum template).
 *
 * Figma: Impressum desktop 5426:5833, tablet 5617:26635, mobile 5617:26598;
 * Datenschutz 5426:5647; AGB 5846:19127.
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

?>
<header class="page-header">
	<div class="theme-container">
		<div class="theme-grid">
			<h1 class="title-h1 page-header__title"><?php the_title(); ?></h1>
		</div>
	</div>
</header>
