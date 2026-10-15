<?php
/**
 * Як проходить фотосесія: п'ять кроків.
 *
 * @package Prostir
 */
?>
<section class="section steps" id="steps">
	<div class="container">
		<?php echo prostir_section_heading( prostir_opt( 'steps_title' ), array( 'eyebrow' => 'Процес', 'align' => 'center' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<ol class="steps__list">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<li class="step reveal">
					<span class="step__num">0<?php echo (int) $i; ?></span>
					<h3 class="step__title"><?php echo esc_html( prostir_opt( 'step_' . $i . '_title' ) ); ?></h3>
					<?php if ( prostir_opt( 'step_' . $i . '_text' ) ) : ?>
						<p class="step__text"><?php echo esc_html( prostir_opt( 'step_' . $i . '_text' ) ); ?></p>
					<?php endif; ?>
				</li>
			<?php endfor; ?>
		</ol>
	</div>
</section>
