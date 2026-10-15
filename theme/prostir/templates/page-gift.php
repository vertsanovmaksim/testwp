<?php
/**
 * Template Name: Подарунковий сертифікат
 * Description: Готові сертифікати за пакетами.
 *
 * @package Prostir
 */

get_header();
the_post();

$prostir_packages = array_filter( prostir_get_packages(), fn( $p ) => (bool) prostir_meta( $p->ID, 'gift' ) );
?>
<section class="page-hero page-hero--split">
	<div class="container page-hero__split">
		<div class="page-hero__content">
			<p class="eyebrow">Подарунок, який запам’ятають</p>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<p class="page-hero__lead"><?php echo esc_html( prostir_opt( 'gift_page_lead' ) ); ?></p>
			<div class="actions">
				<?php echo prostir_button( 'Обрати сертифікат', '#certificates', array( 'size' => 'lg', 'icon' => 'arrow-down' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
		<div class="page-hero__aside">
			<?php echo prostir_image( get_post_thumbnail_id(), 'large', array( 'loading' => 'eager', 'sizes' => '(min-width: 1024px) 45vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<?php if ( $prostir_packages ) : ?>
<section class="section certificates" id="certificates">
	<div class="container">
		<?php echo prostir_section_heading( 'Сертифікати за пакетами', array( 'eyebrow' => 'Готові варіанти', 'text' => 'Усе як у звичайній фотосесії: студія, фотограф, світло, обробка та друк. Людина обирає лише дату.', 'align' => 'center' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="certificates__grid">
			<?php foreach ( $prostir_packages as $prostir_package ) : ?>
				<?php $prostir_items = array_slice( prostir_lines( prostir_meta( $prostir_package->ID, 'items' ) ), 0, 4 ); ?>
				<article class="certificate reveal">
					<p class="certificate__label">Сертифікат</p>
					<h3 class="certificate__name"><?php echo esc_html( $prostir_package->post_title ); ?></h3>
					<p class="certificate__price"><?php echo esc_html( prostir_price( prostir_meta( $prostir_package->ID, 'price', 0 ) ) ); ?></p>
					<p class="certificate__meta"><?php echo esc_html( implode( ' · ', array_filter( array( prostir_meta( $prostir_package->ID, 'duration' ), prostir_meta( $prostir_package->ID, 'participants' ), prostir_meta( $prostir_package->ID, 'looks' ) ) ) ) ); ?></p>
					<ul class="certificate__items">
						<?php foreach ( $prostir_items as $prostir_item ) : ?>
							<li><?php echo esc_html( $prostir_item ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php echo prostir_button( prostir_opt( 'gift_page_btn' ) . ' ' . $prostir_package->post_title, prostir_booking_url( $prostir_package->post_name ), array( 'style' => 'outline', 'class' => 'certificate__btn', 'attrs' => array( 'data-package' => $prostir_package->post_name, 'data-comment' => 'Подарунковий сертифікат' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section section--flush">
	<div class="container prose">
		<?php the_content(); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/components/booking-form' );
get_footer();
