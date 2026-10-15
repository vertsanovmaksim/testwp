<?php
/**
 * Портфоліо: 12–16 найкращих фото.
 *
 * @package Prostir
 */

$prostir_works = prostir_get_works( (int) prostir_opt( 'portfolio_count' ) );
if ( ! $prostir_works ) {
	return;
}
$prostir_url = prostir_opt( 'portfolio_btn_url' );
$prostir_url = str_starts_with( $prostir_url, '/' ) ? home_url( $prostir_url ) : $prostir_url;
?>
<section class="section portfolio" id="portfolio">
	<div class="container">
		<div class="section-row reveal">
			<?php echo prostir_section_heading( prostir_opt( 'portfolio_title' ), array( 'eyebrow' => 'Роботи' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
	<div class="container container--wide">
		<div class="portfolio__grid">
			<?php foreach ( $prostir_works as $prostir_i => $prostir_work ) : ?>
				<?php
				$prostir_thumb = get_post_thumbnail_id( $prostir_work );
				if ( ! $prostir_thumb ) {
					continue;
				}
				$prostir_terms = get_the_terms( $prostir_work, 'prostir_work_cat' );
				$prostir_cat   = $prostir_terms && ! is_wp_error( $prostir_terms ) ? $prostir_terms[0]->name : '';
				?>
				<a class="portfolio__item js-lightbox reveal" href="<?php echo esc_url( wp_get_attachment_image_url( $prostir_thumb, 'full' ) ); ?>" data-caption="<?php echo esc_attr( $prostir_work->post_title . ( $prostir_cat ? ' · ' . $prostir_cat : '' ) ); ?>" aria-label="<?php echo esc_attr( 'Відкрити фото: ' . $prostir_work->post_title ); ?>">
					<?php echo prostir_image( $prostir_thumb, 'large', array( 'sizes' => '(min-width: 1024px) 25vw, 50vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span class="portfolio__label"><?php echo esc_html( $prostir_cat ? $prostir_cat : $prostir_work->post_title ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="container portfolio__more reveal">
		<?php echo prostir_button( prostir_opt( 'portfolio_btn' ), $prostir_url, array( 'style' => 'outline', 'icon' => 'arrow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
