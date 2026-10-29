<?php
/**
 * Design System page: typography classes from _typography.sass.
 *
 * Samples are <p>/<div> on purpose: the classes are independent of the
 * heading level, and real headings here would break the page outline.
 * Sizes are mobile / tablet / desktop, font size / line height in px.
 *
 * @package montela
 * @subpackage Section
 * @since 1.3.0
 */

$ds_styles = array(
	array(
		'class'  => 'title-h1',
		'figma'  => 'H1',
		'spec'   => 'FreightBig Pro 500 · 40/50 · 90/90 · 150/125',
		'sample' => 'H1 Title',
	),
	array(
		'class'  => 'title-h2',
		'figma'  => 'H2',
		'spec'   => 'FreightBig Pro 400 italic · 25/30 on every breakpoint',
		'sample' => 'H2 Title',
	),
	array(
		'class'  => 'title-h3',
		'figma'  => 'H3',
		'spec'   => 'FreightBig Pro 500 · 36/38 · 80/76 · 90/80',
		'sample' => 'H3 Title',
	),
	array(
		'class'  => 'title-h4',
		'figma'  => 'H4',
		'spec'   => 'Roboto 700 · 20/32 on every breakpoint',
		'sample' => 'H4 Title',
	),
	array(
		'class'  => 'title-intro',
		'figma'  => 'Intro title',
		'spec'   => 'FreightBig Pro 400 italic · 40/35 · 70/70 · 90/76',
		'sample' => 'Intro title',
	),
	array(
		'class'  => 'body-intro',
		'figma'  => 'Intro text',
		'spec'   => 'Roboto 300 · 20/30 · 30/40 · 24/40',
		'sample' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
	),
	array(
		'class'  => 'body-quote',
		'figma'  => 'Quote',
		'spec'   => 'Roboto 300 italic · 20/30 · 25/40 · 35/55',
		'sample' => 'Lorem ipsum dolor sit amet',
	),
	array(
		'class'  => 'body-small',
		'figma'  => 'Text small',
		'spec'   => 'Roboto 300 · 12/20 · 15/25 · 15/25',
		'sample' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
	),
);
?>
<section class="ds-section" aria-labelledby="ds-typography-title">
	<div class="theme-container">
		<h2 id="ds-typography-title" class="title-intro ds-section__title"><?php esc_html_e( 'Typography', 'montela' ); ?></h2>

		<?php foreach ( $ds_styles as $ds_style ) : ?>
			<div class="ds-type">
				<p class="ds-type__meta body-small">
					<code class="ds-code">.<?php echo esc_html( $ds_style['class'] ); ?></code>
					<span><?php echo esc_html( $ds_style['figma'] . ' — ' . $ds_style['spec'] ); ?></span>
				</p>
				<p class="<?php echo esc_attr( $ds_style['class'] ); ?>"><?php echo esc_html( $ds_style['sample'] ); ?></p>
			</div>
		<?php endforeach; ?>

		<div class="ds-type">
			<p class="ds-type__meta body-small">
				<code class="ds-code">.body-text</code>
				<span><?php echo esc_html( 'Text — Roboto 300 · 14/22 · 18/25 · 20/32 · editor content: paragraphs, lists, bold' ); ?></span>
			</p>
			<div class="body-text">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud <strong>exercitation ullamco</strong> laboris nisi ut aliquip ex ea commodo consequat.</p>
				<ul>
					<li>Duis aute irure dolor in reprehenderit</li>
					<li>In voluptate velit esse cillum dolore</li>
					<li>Excepteur sint occaecat cupidatat non proident</li>
				</ul>
				<ol>
					<li>Sunt in culpa qui officia</li>
					<li>Deserunt mollit anim id est laborum</li>
				</ol>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
			</div>
		</div>
	</div>
</section>
