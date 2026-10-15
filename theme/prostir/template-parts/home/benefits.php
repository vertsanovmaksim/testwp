<?php
/**
 * Три переваги.
 *
 * @package Prostir
 */
?>
<section class="section benefits" id="benefits">
	<div class="container">
		<h2 class="section-title section-title--xl benefits__title reveal"><?php echo esc_html( prostir_opt( 'benefits_title' ) ); ?></h2>
		<div class="benefits__grid">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<div class="benefit reveal">
					<span class="benefit__num">0<?php echo (int) $i; ?></span>
					<h3 class="benefit__title"><?php echo esc_html( prostir_opt( 'benefit_' . $i . '_title' ) ); ?></h3>
					<p class="benefit__text"><?php echo esc_html( prostir_opt( 'benefit_' . $i . '_text' ) ); ?></p>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
