<?php
/**
 * Звичайна сторінка.
 *
 * @package Prostir
 */

get_header();
the_post();
?>
<section class="page-hero page-hero--plain">
	<div class="container">
		<h1 class="page-hero__title"><?php the_title(); ?></h1>
	</div>
</section>
<section class="section section--flush">
	<div class="container prose">
		<?php the_content(); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/components/booking-form' );
get_footer();
