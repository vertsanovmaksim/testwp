<?php
/**
 * Оренда студії для фотографів.
 *
 * @package Prostir
 */

$prostir_rent_page = get_page_by_path( 'rent' );
?>
<section class="section section--dark rent" id="rent">
	<div class="container rent__inner">
		<div class="rent__body reveal">
			<p class="eyebrow">Оренда студії</p>
			<h2 class="section-title"><?php echo esc_html( prostir_opt( 'rent_title' ) ); ?></h2>
			<p class="section-text"><?php echo esc_html( prostir_opt( 'rent_text' ) ); ?></p>
			<p class="rent__price">
				<span class="rent__price-label"><?php echo esc_html( prostir_opt( 'rent_price_label' ) ); ?></span>
				<strong class="rent__price-value"><?php echo esc_html( prostir_price( prostir_opt( 'rent_price' ) ) ); ?> / година</strong>
			</p>
			<?php echo prostir_button( prostir_opt( 'rent_btn' ), $prostir_rent_page ? get_permalink( $prostir_rent_page ) : home_url( '/rent/' ), array( 'style' => 'light', 'icon' => 'arrow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="rent__media reveal">
			<?php echo prostir_image( (int) prostir_opt( 'rent_image' ), 'large', array( 'sizes' => '(min-width: 1024px) 50vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
