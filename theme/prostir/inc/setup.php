<?php
/**
 * Базове налаштування теми: підтримка функцій, меню, розміри зображень, скрипти.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

function prostir_setup() {
	load_theme_textdomain( 'prostir', PROSTIR_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'script', 'style' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-width' => true, 'flex-height' => true ) );

	register_nav_menus(
		array(
			'primary' => __( 'Головне меню', 'prostir' ),
			'footer'  => __( 'Меню у футері', 'prostir' ),
		)
	);

	// Розміри: картки (портрет), широкі банери, маленькі прев'ю.
	add_image_size( 'prostir-card', 900, 1125, true );
	add_image_size( 'prostir-wide', 1600, 900, true );
	add_image_size( 'prostir-thumb', 600, 750, true );
	add_image_size( 'prostir-hero', 2000, 1250, false );
}
add_action( 'after_setup_theme', 'prostir_setup' );

/**
 * Стилі та скрипти фронтенду.
 */
function prostir_assets() {
	$css = PROSTIR_DIR . '/assets/css/main.css';
	$js  = PROSTIR_DIR . '/assets/js/main.js';

	wp_enqueue_style( 'prostir-main', PROSTIR_URI . '/assets/css/main.css', array(), file_exists( $css ) ? filemtime( $css ) : PROSTIR_VERSION );

	wp_enqueue_script( 'prostir-main', PROSTIR_URI . '/assets/js/main.js', array(), file_exists( $js ) ? filemtime( $js ) : PROSTIR_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script(
		'prostir-main',
		'prostirConfig',
		array(
			'leadEndpoint' => esc_url_raw( rest_url( 'prostir/v1/lead' ) ),
			'nonce'        => wp_create_nonce( 'wp_rest' ),
		)
	);

	// Стилі блоків потрібні лише на сторінках з контентом редактора.
	if ( is_front_page() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
		wp_dequeue_style( 'classic-theme-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'prostir_assets', 20 );

/**
 * Preload шрифтів для швидшого першого рендеру.
 */
function prostir_preload_fonts() {
	$fonts = array( 'manrope-latin', 'manrope-cyrillic', 'inter-latin', 'inter-cyrillic' );
	foreach ( $fonts as $font ) {
		printf( '<link rel="preload" href="%s/assets/fonts/%s.woff2" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( PROSTIR_URI ), esc_attr( $font ) );
	}
}
add_action( 'wp_head', 'prostir_preload_fonts', 1 );

/**
 * Прибираємо зайве з <head> для швидкості.
 */
function prostir_cleanup_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'prostir_cleanup_head' );

/**
 * Класи body: позначаємо сторінки з темним хедером поверх фото.
 */
function prostir_body_class( $classes ) {
	if ( is_front_page() || prostir_page_has_hero_image() ) {
		$classes[] = 'has-hero';
	}
	return $classes;
}
add_filter( 'body_class', 'prostir_body_class' );

/**
 * Чи є на поточній сторінці hero з фото (хедер прозорий).
 */
function prostir_page_has_hero_image() {
	if ( ! is_page() ) {
		return false;
	}
	$template = get_page_template_slug();
	return in_array( $template, array( 'templates/page-rent.php', 'templates/page-studio.php', 'templates/page-photosession.php' ), true ) && has_post_thumbnail();
}

/**
 * Ширина контенту для вбудованих медіа.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}

/**
 * Вимикаємо коментарі: сайт не блог.
 */
function prostir_disable_comments() {
	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'post', 'comments' );
}
add_action( 'init', 'prostir_disable_comments', 20 );
