<?php
/**
 * Template Name: Про студію
 * Description: Приміщення, команда, обладнання, довіра.
 *
 * @package Prostir
 */

get_header();
the_post();

get_template_part(
	'template-parts/components/page-hero',
	null,
	array(
		'eyebrow' => prostir_opt( 'city' ),
		'lead'    => prostir_opt( 'studio_page_lead' ),
	)
);
?>
<section class="section section--flush">
	<div class="container prose">
		<?php the_content(); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/home/features' );
get_template_part( 'template-parts/home/reviews' );
get_template_part( 'template-parts/components/booking-form' );
get_footer();
