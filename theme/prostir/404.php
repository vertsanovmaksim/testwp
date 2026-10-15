<?php
/**
 * 404.
 *
 * @package Prostir
 */

get_header();
?>
<section class="section page-plain page-404">
	<div class="container">
		<p class="eyebrow">Помилка 404</p>
		<h1 class="section-title section-title--xl">Такої сторінки немає</h1>
		<p class="section-text">Можливо, посилання застаріло. Повернімося на головну або одразу до запису.</p>
		<div class="actions">
			<?php echo prostir_button( 'На головну', home_url( '/' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo prostir_button( 'Записатися', prostir_booking_url(), array( 'style' => 'outline' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
<?php
get_footer();
