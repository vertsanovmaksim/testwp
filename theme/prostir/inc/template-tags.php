<?php
/**
 * Хелпери для шаблонів: дані, кнопки, іконки, зображення.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ціна у форматі «1 900 грн».
 */
function prostir_price( $amount, $suffix = 'грн' ) {
	$amount = (int) $amount;
	if ( $amount <= 0 ) {
		return '';
	}
	return number_format( $amount, 0, ',', "\u{202F}" ) . "\u{00A0}" . $suffix;
}

/**
 * tel: посилання з телефону.
 */
function prostir_phone_href( $phone = '' ) {
	$phone = $phone ? $phone : prostir_opt( 'phone' );
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Посилання на форму запису. Якщо передано пакет, форма його підставить.
 */
function prostir_booking_url( $package = '' ) {
	$base = home_url( '/' );
	if ( $package ) {
		$base = add_query_arg( 'package', rawurlencode( $package ), $base );
	}
	return $base . '#booking';
}

/**
 * Рядки з textarea → масив непорожніх рядків.
 */
function prostir_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

/**
 * Зображення з медіатеки з лінивим завантаженням.
 */
function prostir_image( $attachment_id, $size = 'large', $attrs = array() ) {
	$attachment_id = (int) $attachment_id;
	if ( ! $attachment_id ) {
		return '';
	}
	$attrs = wp_parse_args( $attrs, array( 'loading' => 'lazy', 'decoding' => 'async' ) );
	if ( 'eager' === $attrs['loading'] ) {
		$attrs['fetchpriority'] = 'high';
	}
	return wp_get_attachment_image( $attachment_id, $size, false, $attrs );
}

/**
 * Запити до CPT.
 */
function prostir_get_posts( $type, $args = array() ) {
	return get_posts(
		wp_parse_args(
			$args,
			array(
				'post_type'        => $type,
				'posts_per_page'   => -1,
				'orderby'          => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
				'post_status'      => 'publish',
				'suppress_filters' => false,
			)
		)
	);
}

function prostir_get_packages() {
	return prostir_get_posts( 'prostir_package' );
}

function prostir_get_sessions( $home_only = false ) {
	$args = array();
	if ( $home_only ) {
		$args['meta_key']   = '_prostir_on_home'; // phpcs:ignore WordPress.DB.SlowDBQuery
		$args['meta_value'] = '1'; // phpcs:ignore WordPress.DB.SlowDBQuery
		$args['posts_per_page'] = 4;
	}
	return prostir_get_posts( 'prostir_session', $args );
}

function prostir_get_reviews() {
	return prostir_get_posts( 'prostir_review' );
}

function prostir_get_works( $count = 16 ) {
	return prostir_get_posts( 'prostir_work', array( 'posts_per_page' => (int) $count ) );
}

function prostir_get_features() {
	return prostir_get_posts( 'prostir_feature' );
}

/**
 * Варіанти для поля «Яка фотосесія вас цікавить?».
 */
function prostir_form_options() {
	$options = array();
	foreach ( prostir_get_packages() as $package ) {
		$options[ $package->post_name ] = $package->post_title;
	}
	$options['business']  = 'Контент для бізнесу';
	$options['rent']      = 'Оренда студії';
	$options['undecided'] = 'Ще не визначився / визначилась';
	return $options;
}

/**
 * Назва пакета за значенням з форми.
 */
function prostir_form_option_label( $value ) {
	$options = prostir_form_options();
	return $options[ $value ] ?? $value;
}

/**
 * Інлайнові SVG-іконки (без іконочних шрифтів).
 */
function prostir_icon( $name, $class = '' ) {
	$icons = array(
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-down' => '<path d="M12 5v14M6 13l6 6 6-6"/>',
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
		'check'     => '<path d="M5 12l5 5L20 7"/>',
		'pin'       => '<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
		'route'     => '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M8.5 18H14a3 3 0 0 0 0-6h-4a3 3 0 0 1 0-6h5.5"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'star'      => '<path d="M12 3l2.8 6 6.2.7-4.6 4.3 1.3 6.3L12 17l-5.7 3.3 1.3-6.3L3 9.7 9.2 9z"/>',
		'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
		'camera'    => '<path d="M4 8h3l2-3h6l2 3h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.5"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'image'     => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 16l5-5 4 4 3-3 6 6"/><circle cx="16" cy="9" r="1.5"/>',
		'chevron-left'  => '<path d="M15 5l-7 7 7 7"/>',
		'chevron-right' => '<path d="M9 5l7 7-7 7"/>',
	);
	if ( empty( $icons[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon--' . esc_attr( $name ) . ( $class ? ' ' . esc_attr( $class ) : '' ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Кнопка.
 *
 * @param string $label Текст.
 * @param string $url   Посилання.
 * @param array  $args  style: primary|light|outline|outline-light|ghost; size: lg; icon; attrs.
 */
function prostir_button( $label, $url, $args = array() ) {
	$args    = wp_parse_args( $args, array( 'style' => 'primary', 'size' => '', 'icon' => '', 'attrs' => array(), 'class' => '' ) );
	$classes = array( 'btn', 'btn--' . $args['style'] );
	if ( $args['size'] ) {
		$classes[] = 'btn--' . $args['size'];
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}
	$attr_html = '';
	foreach ( $args['attrs'] as $k => $v ) {
		$attr_html .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
	}
	return sprintf(
		'<a class="%s" href="%s"%s><span>%s</span>%s</a>',
		esc_attr( implode( ' ', $classes ) ),
		esc_url( $url ),
		$attr_html,
		esc_html( $label ),
		$args['icon'] ? prostir_icon( $args['icon'] ) : ''
	);
}

/**
 * Заголовок секції: надзаголовок, заголовок, текст.
 */
function prostir_section_heading( $title, $args = array() ) {
	$args = wp_parse_args( $args, array( 'eyebrow' => '', 'text' => '', 'align' => 'left', 'tag' => 'h2', 'size' => '' ) );
	$html = '<div class="section-heading section-heading--' . esc_attr( $args['align'] ) . ( $args['size'] ? ' section-heading--' . esc_attr( $args['size'] ) : '' ) . '">';
	if ( $args['eyebrow'] ) {
		$html .= '<p class="eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>';
	}
	$html .= '<' . $args['tag'] . ' class="section-title">' . esc_html( $title ) . '</' . $args['tag'] . '>';
	if ( $args['text'] ) {
		$html .= '<p class="section-text">' . esc_html( $args['text'] ) . '</p>';
	}
	return $html . '</div>';
}

/**
 * Головне меню (fallback на сторінки, якщо меню ще не створене).
 */
function prostir_nav( $location, $class ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => $class,
				'depth'          => 1,
				'fallback_cb'    => false,
			)
		);
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	wp_list_pages( array( 'title_li' => '', 'exclude' => get_option( 'page_on_front' ), 'depth' => 1 ) );
	echo '</ul>';
}

/**
 * Вкладене фото: hero сторінки або fallback.
 */
function prostir_page_hero_image_id( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$id      = get_post_thumbnail_id( $post_id );
	return $id ? (int) $id : (int) prostir_opt( 'hero_image' );
}
