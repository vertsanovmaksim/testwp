<?php
/**
 * Головна сторінка: лендинг з усіма секціями ТЗ.
 *
 * @package Prostir
 */

get_header();

$prostir_sections = array( 'hero', 'benefits', 'types', 'packages', 'portfolio', 'features', 'steps', 'gift', 'print', 'rent', 'reviews', 'cta' );
foreach ( $prostir_sections as $prostir_section ) {
	get_template_part( 'template-parts/home/' . $prostir_section );
}
get_template_part( 'template-parts/components/booking-form' );

get_footer();
