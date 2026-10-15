<?php
/**
 * Можливості студії.
 *
 * @package Prostir
 */

$prostir_features = prostir_get_features();
if ( ! $prostir_features ) {
	return;
}
?>
<section class="section section--dark features" id="features">
	<div class="container">
		<?php echo prostir_section_heading( prostir_opt( 'features_title' ), array( 'eyebrow' => 'Можливості студії', 'text' => prostir_opt( 'features_text' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="features__grid">
			<?php foreach ( $prostir_features as $prostir_feature ) : ?>
				<figure class="feature reveal">
					<?php echo prostir_image( get_post_thumbnail_id( $prostir_feature ), 'large', array( 'sizes' => '(min-width: 1024px) 33vw, 50vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<figcaption class="feature__caption">
						<strong><?php echo esc_html( $prostir_feature->post_title ); ?></strong>
						<?php if ( prostir_meta( $prostir_feature->ID, 'subtitle' ) ) : ?>
							<span><?php echo esc_html( prostir_meta( $prostir_feature->ID, 'subtitle' ) ); ?></span>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
