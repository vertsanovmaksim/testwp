<?php
/**
 * Відгуки.
 *
 * @package Prostir
 */

$prostir_reviews = prostir_get_reviews();
if ( ! $prostir_reviews ) {
	return;
}
?>
<section class="section reviews" id="reviews">
	<div class="container">
		<?php echo prostir_section_heading( prostir_opt( 'reviews_title' ), array( 'eyebrow' => 'Відгуки', 'text' => prostir_opt( 'reviews_text' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="reviews__grid">
			<?php foreach ( $prostir_reviews as $prostir_review ) : ?>
				<blockquote class="review reveal">
					<p class="review__text"><?php echo esc_html( wp_strip_all_tags( $prostir_review->post_content ) ); ?></p>
					<footer class="review__footer">
						<strong class="review__name"><?php echo esc_html( $prostir_review->post_title ); ?></strong>
						<?php if ( prostir_meta( $prostir_review->ID, 'tag' ) ) : ?>
							<span class="review__tag"><?php echo esc_html( prostir_meta( $prostir_review->ID, 'tag' ) ); ?></span>
						<?php endif; ?>
					</footer>
				</blockquote>
			<?php endforeach; ?>
		</div>
	</div>
</section>
