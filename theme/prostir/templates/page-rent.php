<?php
/**
 * Template Name: Оренда студії
 * Description: Приміщення, зони, обладнання, ціна за годину.
 *
 * @package Prostir
 */

get_header();
the_post();

get_template_part(
	'template-parts/components/page-hero',
	null,
	array(
		'eyebrow' => 'Для фотографів і відеографів',
		'lead'    => prostir_opt( 'rent_page_lead' ),
		'extra'   => '<p class="page-hero__price"><strong>' . esc_html( prostir_price( prostir_opt( 'rent_price' ) ) ) . ' / година</strong><span>світло та обладнання включено</span></p>',
		'actions' => prostir_button( prostir_opt( 'rent_page_btn' ), prostir_booking_url( 'rent' ), array( 'style' => 'light', 'size' => 'lg', 'attrs' => array( 'data-package' => 'rent' ) ) ) . prostir_button( 'Зателефонувати', prostir_phone_href(), array( 'style' => 'outline-light', 'size' => 'lg', 'icon' => 'phone' ) ),
	)
);
?>
<section class="section section--flush">
	<div class="container prose">
		<?php the_content(); ?>
	</div>
</section>
<section class="section section--dark band">
	<div class="container band__inner">
		<div>
			<p class="eyebrow">Оренда</p>
			<h2 class="section-title"><?php echo esc_html( prostir_price( prostir_opt( 'rent_price' ) ) ); ?> / година</h2>
			<p class="section-text">Бронюйте час телефоном або через форму нижче. Підтверджуємо дзвінком.</p>
		</div>
		<div class="actions">
			<?php echo prostir_button( prostir_opt( 'rent_page_btn' ), prostir_booking_url( 'rent' ), array( 'style' => 'light', 'size' => 'lg', 'attrs' => array( 'data-package' => 'rent' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
<?php
get_template_part( 'template-parts/components/booking-form' );
get_footer();
