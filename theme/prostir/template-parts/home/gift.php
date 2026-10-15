<?php
/**
 * Подарункові сертифікати.
 *
 * @package Prostir
 */

$prostir_gift_page = get_page_by_path( 'gift' );
?>
<section class="section split gift" id="gift">
	<div class="container split__inner">
		<div class="split__media reveal">
			<?php echo prostir_image( (int) prostir_opt( 'gift_image' ), 'large', array( 'sizes' => '(min-width: 1024px) 50vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="split__body reveal">
			<?php echo prostir_section_heading( prostir_opt( 'gift_title' ), array( 'eyebrow' => 'Подарункові сертифікати', 'text' => prostir_opt( 'gift_text' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo prostir_button( prostir_opt( 'gift_btn' ), $prostir_gift_page ? get_permalink( $prostir_gift_page ) : home_url( '/gift/' ), array( 'icon' => 'arrow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
