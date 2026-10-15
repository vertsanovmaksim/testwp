<?php
/**
 * Поля записів (без ACF): ціна, тривалість, галерея, пов'язані пакети тощо.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Опис полів для кожного типу записів.
 */
function prostir_meta_fields() {
	return array(
		'prostir_package' => array(
			'title'  => 'Параметри пакета',
			'fields' => array(
				'price'        => array( 'label' => 'Ціна, грн', 'type' => 'number' ),
				'duration'     => array( 'label' => 'Тривалість', 'type' => 'text', 'placeholder' => '1 година' ),
				'participants' => array( 'label' => 'Учасники', 'type' => 'text', 'placeholder' => '1 учасник' ),
				'looks'        => array( 'label' => 'Образи', 'type' => 'text', 'placeholder' => '2–3 образи' ),
				'items'        => array( 'label' => 'Що входить', 'type' => 'textarea', 'help' => 'Кожен пункт з нового рядка.' ),
				'button'       => array( 'label' => 'Текст кнопки', 'type' => 'text', 'placeholder' => 'Обрати MINI' ),
				'popular'      => array( 'label' => 'Виділити як «Найпопулярніший»', 'type' => 'checkbox' ),
				'gift'         => array( 'label' => 'Пропонувати як подарунковий сертифікат', 'type' => 'checkbox' ),
			),
		),
		'prostir_session' => array(
			'title'  => 'Параметри виду фотосесії',
			'fields' => array(
				'short'    => array( 'label' => 'Короткий опис для картки', 'type' => 'textarea' ),
				'gallery'  => array( 'label' => 'Галерея (сторінка «Фотосесії»)', 'type' => 'gallery' ),
				'packages' => array( 'label' => 'Відповідні пакети', 'type' => 'posts', 'post_type' => 'prostir_package' ),
				'on_home'  => array( 'label' => 'Показувати на головній (4 картки)', 'type' => 'checkbox' ),
				'form_value' => array( 'label' => 'Значення для форми запису', 'type' => 'text', 'help' => 'Наприклад, business. Якщо порожньо, у формі обереться перший відповідний пакет.' ),
			),
		),
		'prostir_feature' => array(
			'title'  => 'Параметри',
			'fields' => array(
				'subtitle' => array( 'label' => 'Підпис під назвою', 'type' => 'text', 'placeholder' => 'Профайл, короткі тіні' ),
			),
		),
		'prostir_review' => array(
			'title'  => 'Параметри відгуку',
			'fields' => array(
				'tag'    => array( 'label' => 'Тип фотосесії', 'type' => 'text', 'placeholder' => 'Сімейна фотосесія' ),
				'source' => array( 'label' => 'Джерело', 'type' => 'text', 'placeholder' => 'Instagram' ),
			),
		),
		'prostir_lead' => array(
			'title'  => 'Дані заявки',
			'fields' => array(
				'name'    => array( 'label' => 'Ім’я', 'type' => 'text', 'readonly' => true ),
				'phone'   => array( 'label' => 'Телефон', 'type' => 'text', 'readonly' => true ),
				'package' => array( 'label' => 'Фотосесія', 'type' => 'text', 'readonly' => true ),
				'date'    => array( 'label' => 'Бажана дата', 'type' => 'text', 'readonly' => true ),
				'comment' => array( 'label' => 'Коментар', 'type' => 'textarea', 'readonly' => true ),
				'source'  => array( 'label' => 'Сторінка', 'type' => 'text', 'readonly' => true ),
			),
		),
	);
}

/**
 * Значення поля запису.
 */
function prostir_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_prostir_' . $key, true );
	return ( '' === $value || null === $value ) ? $default : $value;
}

function prostir_add_meta_boxes() {
	foreach ( prostir_meta_fields() as $post_type => $box ) {
		add_meta_box( 'prostir_' . $post_type, $box['title'], 'prostir_render_meta_box', $post_type, 'normal', 'high', array( 'fields' => $box['fields'] ) );
	}
}
add_action( 'add_meta_boxes', 'prostir_add_meta_boxes' );

function prostir_render_meta_box( $post, $box ) {
	wp_nonce_field( 'prostir_meta_' . $post->ID, 'prostir_meta_nonce' );
	echo '<div class="prostir-fields">';
	foreach ( $box['args']['fields'] as $key => $field ) {
		$name  = 'prostir_' . $key;
		$value = prostir_meta( $post->ID, $key, $field['default'] ?? '' );
		$ro    = ! empty( $field['readonly'] ) ? ' readonly' : '';
		echo '<p class="prostir-field prostir-field--' . esc_attr( $field['type'] ) . '">';
		if ( 'checkbox' !== $field['type'] ) {
			echo '<label for="' . esc_attr( $name ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
		}
		switch ( $field['type'] ) {
			case 'number':
				printf( '<input type="number" class="regular-text" id="%1$s" name="%1$s" value="%2$s" min="0" step="1"%3$s>', esc_attr( $name ), esc_attr( $value ), $ro );
				break;
			case 'textarea':
				printf( '<textarea class="large-text" rows="6" id="%1$s" name="%1$s"%3$s>%2$s</textarea>', esc_attr( $name ), esc_textarea( $value ), $ro );
				break;
			case 'checkbox':
				printf( '<label><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s> %3$s</label>', esc_attr( $name ), checked( $value, 1, false ), esc_html( $field['label'] ) );
				break;
			case 'posts':
				$items = get_posts( array( 'post_type' => $field['post_type'], 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC', 'post_status' => 'publish' ) );
				$value = is_array( $value ) ? array_map( 'intval', $value ) : array();
				foreach ( $items as $item ) {
					printf( '<label style="display:block;margin:2px 0"><input type="checkbox" name="%1$s[]" value="%2$d" %3$s> %4$s</label>', esc_attr( $name ), $item->ID, checked( in_array( $item->ID, $value, true ), true, false ), esc_html( $item->post_title ) );
				}
				break;
			case 'gallery':
				$ids = is_array( $value ) ? array_map( 'intval', $value ) : array();
				echo '<div class="prostir-gallery" data-field="' . esc_attr( $name ) . '">';
				echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( implode( ',', $ids ) ) . '">';
				echo '<div class="prostir-gallery__grid">';
				foreach ( $ids as $id ) {
					echo '<span class="prostir-gallery__item" data-id="' . (int) $id . '">' . wp_get_attachment_image( $id, 'thumbnail' ) . '<button type="button" class="prostir-gallery__remove" aria-label="Прибрати">×</button></span>';
				}
				echo '</div>';
				echo '<button type="button" class="button prostir-gallery__add">Обрати фото</button> ';
				echo '<span class="description">Можна обрати кілька фото одразу. Перетягуйте, щоб змінити порядок.</span>';
				echo '</div>';
				break;
			default:
				printf( '<input type="text" class="large-text" id="%1$s" name="%1$s" value="%2$s" placeholder="%3$s"%4$s>', esc_attr( $name ), esc_attr( $value ), esc_attr( $field['placeholder'] ?? '' ), $ro );
		}
		if ( ! empty( $field['help'] ) ) {
			echo '<br><span class="description">' . esc_html( $field['help'] ) . '</span>';
		}
		echo '</p>';
	}
	echo '</div>';
}

function prostir_save_meta( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! isset( $_POST['prostir_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['prostir_meta_nonce'] ), 'prostir_meta_' . $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$all = prostir_meta_fields();
	if ( empty( $all[ $post->post_type ] ) ) {
		return;
	}
	foreach ( $all[ $post->post_type ]['fields'] as $key => $field ) {
		if ( ! empty( $field['readonly'] ) ) {
			continue;
		}
		$name = 'prostir_' . $key;
		$raw  = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		switch ( $field['type'] ) {
			case 'number':
				$value = ( null === $raw || '' === $raw ) ? '' : absint( $raw );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( (string) $raw );
				break;
			case 'checkbox':
				$value = $raw ? 1 : 0;
				break;
			case 'posts':
				$value = is_array( $raw ) ? array_values( array_map( 'absint', $raw ) ) : array();
				break;
			case 'gallery':
				$value = array_values( array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) );
				break;
			default:
				$value = sanitize_text_field( (string) $raw );
		}
		update_post_meta( $post_id, '_prostir_' . $key, $value );
	}
}
add_action( 'save_post', 'prostir_save_meta', 10, 2 );

/**
 * Скрипти адмінки: вибір галереї через медіатеку.
 */
function prostir_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( 'toplevel_page_prostir' === $hook ) {
		wp_enqueue_style( 'prostir-admin', PROSTIR_URI . '/assets/css/admin.css', array(), PROSTIR_VERSION );
		return;
	}
	if ( ! $screen || ! in_array( $screen->post_type, array_keys( prostir_meta_fields() ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'prostir-admin', PROSTIR_URI . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), PROSTIR_VERSION, true );
	wp_enqueue_style( 'prostir-admin', PROSTIR_URI . '/assets/css/admin.css', array(), PROSTIR_VERSION );
}
add_action( 'admin_enqueue_scripts', 'prostir_admin_assets' );

/**
 * Колонки у списках записів.
 */
function prostir_package_columns( $columns ) {
	return array(
		'cb'      => $columns['cb'],
		'title'   => 'Пакет',
		'price'   => 'Ціна',
		'popular' => 'Найпопулярніший',
		'order'   => 'Порядок',
	);
}
add_filter( 'manage_prostir_package_posts_columns', 'prostir_package_columns' );

function prostir_package_column( $column, $post_id ) {
	switch ( $column ) {
		case 'price':
			echo esc_html( prostir_price( prostir_meta( $post_id, 'price', 0 ) ) );
			break;
		case 'popular':
			echo prostir_meta( $post_id, 'popular' ) ? '★ так' : '—';
			break;
		case 'order':
			echo (int) get_post_field( 'menu_order', $post_id );
			break;
	}
}
add_action( 'manage_prostir_package_posts_custom_column', 'prostir_package_column', 10, 2 );

function prostir_lead_columns( $columns ) {
	return array(
		'cb'      => $columns['cb'],
		'title'   => 'Заявка',
		'phone'   => 'Телефон',
		'package' => 'Фотосесія',
		'ldate'   => 'Бажана дата',
		'date'    => 'Отримано',
	);
}
add_filter( 'manage_prostir_lead_posts_columns', 'prostir_lead_columns' );

function prostir_lead_column( $column, $post_id ) {
	$map = array( 'phone' => 'phone', 'package' => 'package', 'ldate' => 'date' );
	if ( isset( $map[ $column ] ) ) {
		echo esc_html( prostir_meta( $post_id, $map[ $column ], '—' ) );
	}
}
add_action( 'manage_prostir_lead_posts_custom_column', 'prostir_lead_column', 10, 2 );

function prostir_session_columns( $columns ) {
	return array(
		'cb'      => $columns['cb'],
		'thumb'   => 'Фото',
		'title'   => 'Назва',
		'on_home' => 'На головній',
		'order'   => 'Порядок',
	);
}
add_filter( 'manage_prostir_session_posts_columns', 'prostir_session_columns' );

function prostir_session_column( $column, $post_id ) {
	if ( 'thumb' === $column ) {
		echo get_the_post_thumbnail( $post_id, array( 60, 75 ) );
	} elseif ( 'on_home' === $column ) {
		echo prostir_meta( $post_id, 'on_home' ) ? 'так' : '—';
	} elseif ( 'order' === $column ) {
		echo (int) get_post_field( 'menu_order', $post_id );
	}
}
add_action( 'manage_prostir_session_posts_custom_column', 'prostir_session_column', 10, 2 );

function prostir_thumb_columns( $columns ) {
	$new = array( 'cb' => $columns['cb'], 'thumb' => 'Фото' );
	unset( $columns['cb'] );
	return $new + $columns;
}
add_filter( 'manage_prostir_work_posts_columns', 'prostir_thumb_columns' );
add_filter( 'manage_prostir_feature_posts_columns', 'prostir_thumb_columns' );

function prostir_thumb_column( $column, $post_id ) {
	if ( 'thumb' === $column ) {
		echo get_the_post_thumbnail( $post_id, array( 60, 75 ) );
	}
}
add_action( 'manage_prostir_work_posts_custom_column', 'prostir_thumb_column', 10, 2 );
add_action( 'manage_prostir_feature_posts_custom_column', 'prostir_thumb_column', 10, 2 );

function prostir_review_columns( $columns ) {
	return array(
		'cb'    => $columns['cb'],
		'title' => 'Автор',
		'text'  => 'Відгук',
		'tag'   => 'Тип фотосесії',
	);
}
add_filter( 'manage_prostir_review_posts_columns', 'prostir_review_columns' );

function prostir_review_column( $column, $post_id ) {
	if ( 'text' === $column ) {
		echo esc_html( wp_trim_words( get_post_field( 'post_content', $post_id ), 18 ) );
	} elseif ( 'tag' === $column ) {
		echo esc_html( prostir_meta( $post_id, 'tag', '—' ) );
	}
}
add_action( 'manage_prostir_review_posts_custom_column', 'prostir_review_column', 10, 2 );
