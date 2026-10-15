<?php
/**
 * Фінальний заклик.
 *
 * @package Prostir
 */
?>
<section class="cta" id="cta">
	<div class="cta__media">
		<?php echo prostir_image( (int) prostir_opt( 'cta_image' ), 'full', array( 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<div class="container cta__content reveal">
		<h2 class="cta__title"><?php echo esc_html( prostir_opt( 'cta_title' ) ); ?></h2>
		<p class="cta__text"><?php echo esc_html( prostir_opt( 'cta_text' ) ); ?></p>
		<div class="actions actions--center">
			<?php echo prostir_button( prostir_opt( 'cta_btn_primary' ), '#booking', array( 'style' => 'light', 'size' => 'lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo prostir_button( prostir_opt( 'cta_btn_secondary' ), prostir_opt( 'instagram' ), array( 'style' => 'outline-light', 'size' => 'lg', 'icon' => 'instagram', 'attrs' => array( 'target' => '_blank', 'rel' => 'noopener' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
