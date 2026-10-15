<?php
/**
 * Шапка внутрішньої сторінки з фото.
 *
 * @package Prostir
 */

$prostir_args  = wp_parse_args( $args ?? array(), array( 'title' => get_the_title(), 'lead' => '', 'image' => get_post_thumbnail_id(), 'eyebrow' => '', 'actions' => '', 'extra' => '' ) );
$prostir_image = (int) $prostir_args['image'];
?>
<section class="page-hero<?php echo $prostir_image ? ' page-hero--image' : ' page-hero--plain'; ?>">
	<?php if ( $prostir_image ) : ?>
		<div class="page-hero__media"><?php echo prostir_image( $prostir_image, 'full', array( 'loading' => 'eager', 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php endif; ?>
	<div class="container page-hero__content">
		<?php if ( $prostir_args['eyebrow'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $prostir_args['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $prostir_args['title'] ); ?></h1>
		<?php if ( $prostir_args['lead'] ) : ?>
			<p class="page-hero__lead"><?php echo esc_html( $prostir_args['lead'] ); ?></p>
		<?php endif; ?>
		<?php echo $prostir_args['extra']; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php if ( $prostir_args['actions'] ) : ?>
			<div class="actions"><?php echo $prostir_args['actions']; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
	</div>
</section>
