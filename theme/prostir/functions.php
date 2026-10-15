<?php
/**
 * Тема «Простір» — точка входу.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

define( 'PROSTIR_VERSION', '0.1.0' );
define( 'PROSTIR_DIR', get_template_directory() );
define( 'PROSTIR_URI', get_template_directory_uri() );

require PROSTIR_DIR . '/inc/setup.php';
require PROSTIR_DIR . '/inc/post-types.php';
require PROSTIR_DIR . '/inc/meta-boxes.php';
require PROSTIR_DIR . '/inc/customizer.php';
require PROSTIR_DIR . '/inc/template-tags.php';
require PROSTIR_DIR . '/inc/form.php';
require PROSTIR_DIR . '/inc/admin.php';
require PROSTIR_DIR . '/inc/demo-content.php';
