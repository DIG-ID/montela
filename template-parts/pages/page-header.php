<?php
/**
 * Page header: breadcrumbs and page title.
 *
 * @package montela
 * @subpackage Section
 * @since 1.0.0
 */

?>
<div class="page-header">
	<div class="theme-container">
		<?php do_action( 'breadcrumbs' ); ?>
		<h1 class="page-header__title"><?php the_title(); ?></h1>
	</div>
</div>
