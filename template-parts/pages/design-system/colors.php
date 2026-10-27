<?php
/**
 * Design System page: colour swatches.
 *
 * Mirrors the tokens in tailwind.config.js and _vars.sass — add a colour
 * there first, then list it here. Class names must stay literal strings so
 * Tailwind finds them when scanning this file.
 *
 * @package montela
 * @subpackage Section
 * @since 1.3.0
 */

$ds_colors = array(
	array(
		'name'  => 'Cream',
		'hex'   => '#F8F8F2',
		'token' => 'montela-cream',
		'class' => 'bg-montela-cream',
	),
	array(
		'name'  => 'Sage',
		'hex'   => '#8F9B87',
		'token' => 'montela-sage',
		'class' => 'bg-montela-sage',
	),
	array(
		'name'  => 'Mustard',
		'hex'   => '#E2B529',
		'token' => 'montela-mustard',
		'class' => 'bg-montela-mustard',
	),
	array(
		'name'  => 'Walnut',
		'hex'   => '#4E3C2A',
		'token' => 'montela-walnut',
		'class' => 'bg-montela-walnut',
	),
);
?>
<section class="ds-section" aria-labelledby="ds-colors-title">
	<div class="theme-container">
		<h2 id="ds-colors-title" class="title-intro ds-section__title"><?php esc_html_e( 'Colors', 'montela' ); ?></h2>
		<ul class="ds-colors">
			<?php foreach ( $ds_colors as $ds_color ) : ?>
				<li class="ds-colors__item">
					<span class="ds-colors__swatch <?php echo esc_attr( $ds_color['class'] ); ?>" aria-hidden="true"></span>
					<span class="ds-colors__meta">
						<span class="title-h4"><?php echo esc_html( $ds_color['name'] ); ?></span>
						<span class="body-small"><?php echo esc_html( $ds_color['hex'] ); ?></span>
						<code class="body-small ds-code"><?php echo esc_html( $ds_color['token'] ); ?></code>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
