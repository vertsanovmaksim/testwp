<?php
/**
 * Запасний шаблон.
 *
 * @package Prostir
 */

get_header();
?>
<section class="section page-plain">
	<div class="container prose">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				the_title( '<h1 class="section-title">', '</h1>' );
				the_content();
			}
		} else {
			echo '<h1 class="section-title">Нічого не знайдено</h1>';
		}
		?>
	</div>
</section>
<?php
get_footer();
