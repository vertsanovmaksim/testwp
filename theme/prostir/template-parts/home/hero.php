<?php
/**
 * Перший екран.
 *
 * @package Prostir
 */

$prostir_hero_image = (int) prostir_opt( 'hero_image' );
$prostir_tags       = array_filter( array_map( 'trim', explode( '•', prostir_opt( 'hero_tags' ) ) ) );
?>
<section class="hero" id="top">
	<div class="hero__media">
		<?php
		if ( $prostir_hero_image ) {
			echo prostir_image( $prostir_hero_image, 'full', array( 'class' => 'hero__img', 'loading' => 'eager', 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		}
		?>
	</div>
	<div class="container hero__content">
		<h1 class="hero__title">
			<span class="hero__kicker"><?php echo esc_html( prostir_opt( 'hero_title' ) ); ?> <span class="hero__dot">·</span> <?php echo esc_html( prostir_opt( 'hero_city' ) ); ?></span>
			<span class="hero__headline"><?php echo esc_html( prostir_opt( 'hero_subtitle' ) ); ?></span>
		</h1>
		<p class="hero__lead"><?php echo esc_html( prostir_opt( 'hero_lead' ) ); ?></p>
		<div class="hero__actions">
			<?php echo prostir_button( prostir_opt( 'hero_btn_primary' ), '#booking', array( 'style' => 'light', 'size' => 'lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo prostir_button( prostir_opt( 'hero_btn_secondary' ), '#packages', array( 'style' => 'outline-light', 'size' => 'lg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<?php if ( $prostir_tags ) : ?>
			<ul class="hero__tags">
				<?php foreach ( $prostir_tags as $prostir_tag ) : ?>
					<li><?php echo esc_html( $prostir_tag ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<a class="hero__scroll" href="#benefits" aria-label="Прокрутити вниз"><?php echo prostir_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
</section>
