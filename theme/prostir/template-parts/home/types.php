<?php
/**
 * Види фотосесій: чотири великі картки.
 *
 * @package Prostir
 */

$prostir_sessions = prostir_get_sessions( true );
if ( ! $prostir_sessions ) {
	return;
}
$prostir_page = get_page_by_path( 'photosession' );
$prostir_base = $prostir_page ? get_permalink( $prostir_page ) : home_url( '/photosession/' );
?>
<section class="section types" id="types">
	<div class="container">
		<div class="section-row reveal">
			<?php echo prostir_section_heading( prostir_opt( 'types_title' ), array( 'eyebrow' => 'Напрямки' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<a class="link-arrow" href="<?php echo esc_url( $prostir_base ); ?>"><span>Усі напрямки</span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
		<div class="types__grid">
			<?php foreach ( $prostir_sessions as $prostir_session ) : ?>
				<article class="type-card reveal">
					<a class="type-card__link" href="<?php echo esc_url( $prostir_base . '#' . $prostir_session->post_name ); ?>">
						<div class="type-card__media">
							<?php echo prostir_image( get_post_thumbnail_id( $prostir_session ), 'large', array( 'sizes' => '(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
						<div class="type-card__body">
							<h3 class="type-card__title"><?php echo esc_html( $prostir_session->post_title ); ?></h3>
							<p class="type-card__text"><?php echo esc_html( prostir_meta( $prostir_session->ID, 'short' ) ); ?></p>
							<span class="btn btn--light btn--sm"><span><?php echo esc_html( prostir_opt( 'types_btn' ) ); ?></span></span>
						</div>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
