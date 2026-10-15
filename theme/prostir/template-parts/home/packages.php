<?php
/**
 * Пакети та ціни.
 *
 * @package Prostir
 */

$prostir_packages = prostir_get_packages();
if ( ! $prostir_packages ) {
	return;
}
?>
<section class="section packages" id="packages">
	<div class="container">
		<?php echo prostir_section_heading( prostir_opt( 'packages_title' ), array( 'eyebrow' => 'Ціни', 'text' => prostir_opt( 'packages_text' ), 'align' => 'center' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="packages__grid">
			<?php foreach ( $prostir_packages as $prostir_package ) : ?>
				<?php
				$prostir_popular = (bool) prostir_meta( $prostir_package->ID, 'popular' );
				$prostir_items   = prostir_lines( prostir_meta( $prostir_package->ID, 'items' ) );
				$prostir_button  = prostir_meta( $prostir_package->ID, 'button', 'Обрати ' . $prostir_package->post_title );
				?>
				<article class="package<?php echo $prostir_popular ? ' package--popular' : ''; ?> reveal">
					<?php if ( $prostir_popular ) : ?>
						<span class="package__badge"><?php echo esc_html( prostir_opt( 'popular_label' ) ); ?></span>
					<?php endif; ?>
					<header class="package__head">
						<h3 class="package__name"><?php echo esc_html( $prostir_package->post_title ); ?></h3>
						<p class="package__price"><?php echo esc_html( prostir_price( prostir_meta( $prostir_package->ID, 'price', 0 ) ) ); ?></p>
						<?php if ( $prostir_package->post_content ) : ?>
							<p class="package__desc"><?php echo esc_html( wp_strip_all_tags( $prostir_package->post_content ) ); ?></p>
						<?php endif; ?>
					</header>
					<ul class="package__meta">
						<?php
						$prostir_meta_map = array( 'clock' => 'duration', 'user' => 'participants', 'image' => 'looks' );
						foreach ( $prostir_meta_map as $prostir_icon => $prostir_key ) :
							$prostir_val = prostir_meta( $prostir_package->ID, $prostir_key );
							if ( ! $prostir_val ) {
								continue;
							}
							?>
							<li><?php echo prostir_icon( $prostir_icon ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $prostir_val ); ?></span></li>
						<?php endforeach; ?>
					</ul>
					<?php if ( $prostir_items ) : ?>
						<ul class="package__items">
							<?php foreach ( $prostir_items as $prostir_item ) : ?>
								<li><?php echo prostir_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $prostir_item ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php
					echo prostir_button( // phpcs:ignore WordPress.Security.EscapeOutput
						$prostir_button,
						prostir_booking_url( $prostir_package->post_name ),
						array(
							'style' => $prostir_popular ? 'light' : 'primary',
							'class' => 'package__btn',
							'attrs' => array( 'data-package' => $prostir_package->post_name ),
						)
					);
					?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
