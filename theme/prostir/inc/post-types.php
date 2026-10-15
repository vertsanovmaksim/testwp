<?php
/**
 * Типи записів: пакети, види фотосесій, портфоліо, можливості студії, відгуки, заявки.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Підписи для CPT українською.
 */
function prostir_labels( $singular, $plural, $menu = '' ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $menu ? $menu : $plural,
		'all_items'          => $plural,
		'add_new'            => 'Додати',
		'add_new_item'       => 'Додати: ' . mb_strtolower( $singular ),
		'edit_item'          => 'Редагувати: ' . mb_strtolower( $singular ),
		'new_item'           => $singular,
		'view_item'          => 'Переглянути',
		'search_items'       => 'Шукати',
		'not_found'          => 'Нічого не знайдено',
		'not_found_in_trash' => 'У кошику порожньо',
		'featured_image'     => 'Фото',
		'set_featured_image' => 'Обрати фото',
		'remove_featured_image' => 'Прибрати фото',
		'use_featured_image' => 'Використати як фото',
	);
}

function prostir_register_post_types() {
	$common = array(
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'prostir',
		'show_in_rest'        => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'has_archive'         => false,
		'rewrite'             => false,
		'map_meta_cap'        => true,
	);

	register_post_type(
		'prostir_package',
		array_merge(
			$common,
			array(
				'labels'   => prostir_labels( 'Пакет', 'Пакети', 'Пакети та ціни' ),
				'supports' => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'prostir_session',
		array_merge(
			$common,
			array(
				'labels'   => prostir_labels( 'Вид фотосесії', 'Види фотосесій' ),
				'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'prostir_work',
		array_merge(
			$common,
			array(
				'labels'   => prostir_labels( 'Робота', 'Портфоліо' ),
				'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_taxonomy(
		'prostir_work_cat',
		'prostir_work',
		array(
			'labels'       => array(
				'name'          => 'Напрямки',
				'singular_name' => 'Напрямок',
				'menu_name'     => 'Напрямки',
				'add_new_item'  => 'Додати напрямок',
				'search_items'  => 'Шукати',
			),
			'hierarchical' => true,
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_admin_column' => true,
			'rewrite'      => false,
		)
	);

	register_post_type(
		'prostir_feature',
		array_merge(
			$common,
			array(
				'labels'   => prostir_labels( 'Можливість', 'Можливості студії' ),
				'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'prostir_review',
		array_merge(
			$common,
			array(
				'labels'   => prostir_labels( 'Відгук', 'Відгуки' ),
				'supports' => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'prostir_lead',
		array_merge(
			$common,
			array(
				'labels'       => prostir_labels( 'Заявка', 'Заявки' ),
				'supports'     => array( 'title' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
			)
		)
	);
}
add_action( 'init', 'prostir_register_post_types' );

/**
 * У CPT з page-attributes прибираємо вибір батьківського запису, лишаємо лише порядок.
 */
function prostir_order_label( $translation, $text, $domain ) {
	if ( 'default' === $domain && 'Order' === $text ) {
		return 'Порядок (менше число — вище)';
	}
	return $translation;
}
add_filter( 'gettext', 'prostir_order_label', 10, 3 );
