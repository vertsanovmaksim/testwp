<?php
/**
 * Template Name: Контакти
 * Description: Адреса, телефон, Instagram, режим роботи, карта.
 *
 * @package Prostir
 */

get_header();
the_post();
?>
<section class="page-hero page-hero--plain">
	<div class="container page-hero__content">
		<p class="eyebrow"><?php echo esc_html( prostir_opt( 'studio_name' ) ); ?></p>
		<h1 class="page-hero__title"><?php the_title(); ?></h1>
		<p class="page-hero__lead"><?php echo esc_html( prostir_opt( 'contacts_lead' ) ); ?></p>
	</div>
</section>

<section class="section section--flush contacts">
	<div class="container contacts__grid">
		<div class="contact-card reveal">
			<?php echo prostir_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<p class="contact-card__label">Адреса</p>
			<p class="contact-card__value"><?php echo esc_html( prostir_opt( 'address' ) ); ?></p>
			<a class="link-arrow" href="<?php echo esc_url( prostir_opt( 'map_route' ) ); ?>" target="_blank" rel="noopener"><span>Прокласти маршрут</span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
		<div class="contact-card reveal">
			<?php echo prostir_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<p class="contact-card__label">Телефон</p>
			<p class="contact-card__value"><a href="<?php echo esc_attr( prostir_phone_href() ); ?>"><?php echo esc_html( prostir_opt( 'phone' ) ); ?></a></p>
			<a class="link-arrow" href="<?php echo esc_attr( prostir_phone_href() ); ?>"><span>Зателефонувати</span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
		<div class="contact-card reveal">
			<?php echo prostir_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<p class="contact-card__label">Instagram</p>
			<p class="contact-card__value"><a href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( prostir_opt( 'instagram_handle' ) ); ?></a></p>
			<a class="link-arrow" href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener"><span>Написати в Instagram</span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
		<div class="contact-card reveal">
			<?php echo prostir_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<p class="contact-card__label">Режим роботи</p>
			<?php foreach ( prostir_lines( prostir_opt( 'hours' ) ) as $prostir_line ) : ?>
				<p class="contact-card__value"><?php echo esc_html( $prostir_line ); ?></p>
			<?php endforeach; ?>
			<a class="link-arrow" href="#booking"><span>Записатися</span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		</div>
	</div>
	<?php if ( get_the_content() ) : ?>
		<div class="container prose"><?php the_content(); ?></div>
	<?php endif; ?>
</section>

<?php if ( prostir_opt( 'map_embed' ) ) : ?>
<section class="map reveal">
	<iframe class="map__frame" src="<?php echo esc_url( prostir_opt( 'map_embed' ) ); ?>" title="Карта: <?php echo esc_attr( prostir_opt( 'address' ) ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</section>
<?php endif; ?>

<?php
get_template_part( 'template-parts/components/booking-form' );
get_footer();
