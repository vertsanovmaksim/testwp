<?php
/**
 * Template Name: Фотосесії
 * Description: Напрямки з фото, описом і відповідними пакетами.
 *
 * @package Prostir
 */

get_header();
the_post();

$prostir_sessions = prostir_get_sessions();

get_template_part(
	'template-parts/components/page-hero',
	null,
	array(
		'eyebrow' => 'Фотосесії під ключ',
		'lead'    => prostir_opt( 'photosession_lead' ),
		'extra'   => $prostir_sessions ? '<ul class="page-hero__links">' . implode( '', array_map( fn( $s ) => '<li><a href="#' . esc_attr( $s->post_name ) . '">' . esc_html( $s->post_title ) . '</a></li>', $prostir_sessions ) ) . '</ul>' : '',
	)
);

if ( get_the_content() ) {
	echo '<section class="section section--flush"><div class="container prose">';
	the_content();
	echo '</div></section>';
}

foreach ( $prostir_sessions as $prostir_i => $prostir_session ) :
	$prostir_gallery = array_filter( array_map( 'intval', (array) prostir_meta( $prostir_session->ID, 'gallery', array() ) ) );
	$prostir_thumb   = get_post_thumbnail_id( $prostir_session );
	$prostir_images  = array_values( array_unique( array_filter( array_merge( array( $prostir_thumb ), $prostir_gallery ) ) ) );
	$prostir_pkg_ids = array_filter( array_map( 'intval', (array) prostir_meta( $prostir_session->ID, 'packages', array() ) ) );
	$prostir_pkgs    = $prostir_pkg_ids ? get_posts( array( 'post_type' => 'prostir_package', 'post__in' => $prostir_pkg_ids, 'orderby' => 'menu_order', 'order' => 'ASC', 'posts_per_page' => -1 ) ) : array();
	$prostir_form    = prostir_meta( $prostir_session->ID, 'form_value' );
	if ( ! $prostir_form && $prostir_pkgs ) {
		$prostir_form = $prostir_pkgs[0]->post_name;
	}
	?>
	<section class="section session<?php echo $prostir_i % 2 ? ' session--reverse' : ''; ?>" id="<?php echo esc_attr( $prostir_session->post_name ); ?>">
		<div class="container session__inner">
			<div class="session__gallery reveal">
				<?php foreach ( array_slice( $prostir_images, 0, 4 ) as $prostir_k => $prostir_img ) : ?>
					<a class="session__photo js-lightbox" href="<?php echo esc_url( wp_get_attachment_image_url( $prostir_img, 'full' ) ); ?>" data-caption="<?php echo esc_attr( $prostir_session->post_title ); ?>">
						<?php echo prostir_image( $prostir_img, 'large', array( 'sizes' => 0 === $prostir_k ? '(min-width: 1024px) 40vw, 100vw' : '(min-width: 1024px) 20vw, 50vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				<?php endforeach; ?>
			</div>
			<div class="session__body reveal">
				<p class="eyebrow">0<?php echo (int) ( $prostir_i + 1 ); ?></p>
				<h2 class="section-title"><?php echo esc_html( $prostir_session->post_title ); ?></h2>
				<div class="prose prose--compact"><?php echo apply_filters( 'the_content', $prostir_session->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
				<?php if ( $prostir_pkgs ) : ?>
					<p class="session__label">Відповідні пакети</p>
					<ul class="session__packages">
						<?php foreach ( $prostir_pkgs as $prostir_pkg ) : ?>
							<li><a href="<?php echo esc_url( prostir_booking_url( $prostir_pkg->post_name ) ); ?>" data-package="<?php echo esc_attr( $prostir_pkg->post_name ); ?>"><strong><?php echo esc_html( $prostir_pkg->post_title ); ?></strong><span><?php echo esc_html( prostir_price( prostir_meta( $prostir_pkg->ID, 'price', 0 ) ) ); ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php echo prostir_button( 'Записатися', prostir_booking_url( $prostir_form ), array( 'icon' => 'arrow', 'attrs' => array( 'data-package' => $prostir_form ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</section>
<?php endforeach; ?>

<?php
get_template_part( 'template-parts/components/booking-form' );
get_footer();
