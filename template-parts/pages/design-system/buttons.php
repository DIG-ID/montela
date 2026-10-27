<?php
/**
 * Design System page: buttons, rendered through the real components
 * (template-parts/components/button.php and button-icon.php).
 *
 * @package montela
 * @subpackage Section
 * @since 1.3.0
 */

$ds_buttons = array( 'primary', 'secondary', 'outline' );
$ds_icons   = array(
	'arrow-right' => __( 'Next', 'montela' ),
	'arrow-down'  => __( 'Scroll down', 'montela' ),
	'home'        => __( 'Apartments', 'montela' ),
);
?>
<section class="ds-section" aria-labelledby="ds-buttons-title">
	<div class="theme-container">
		<h2 id="ds-buttons-title" class="title-intro ds-section__title"><?php esc_html_e( 'Buttons', 'montela' ); ?></h2>

		<p class="ds-type__meta body-small"><?php esc_html_e( 'Text buttons — hover to see the hover state, Tab to see the focus state.', 'montela' ); ?></p>
		<div class="ds-buttons">
			<?php foreach ( $ds_buttons as $ds_button ) : ?>
				<div class="ds-buttons__item">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'url'   => '#',
							'title' => 'Button name',
							'style' => $ds_button,
						)
					);
					?>
					<code class="body-small ds-code">.btn.btn-<?php echo esc_html( $ds_button ); ?></code>
				</div>
			<?php endforeach; ?>
			<div class="ds-buttons__item">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'url'    => '#',
						'title'  => 'Apartments entdecken',
						'target' => '_blank',
						'style'  => 'secondary',
					)
				);
				?>
				<code class="body-small ds-code">target="_blank"</code>
			</div>
		</div>

		<p class="ds-type__meta body-small"><?php esc_html_e( 'Icon buttons', 'montela' ); ?></p>
		<div class="ds-buttons">
			<?php foreach ( $ds_icons as $ds_icon => $ds_label ) : ?>
				<div class="ds-buttons__item">
					<?php
					get_template_part(
						'template-parts/components/button-icon',
						null,
						array(
							'icon'  => $ds_icon,
							'label' => $ds_label,
						)
					);
					?>
					<code class="body-small ds-code">.btn-icon--<?php echo esc_html( $ds_icon ); ?></code>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
