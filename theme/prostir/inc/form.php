<?php
/**
 * Форма запису: REST-ендпоінт + запасний варіант без JS.
 * Заявка зберігається як запис «Заявки». Відправку на e-mail/Telegram
 * можна додати через хук `prostir_lead_created`.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

function prostir_register_rest() {
	register_rest_route(
		'prostir/v1',
		'/lead',
		array(
			'methods'             => 'POST',
			'callback'            => 'prostir_rest_lead',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'prostir_register_rest' );

function prostir_rest_lead( WP_REST_Request $request ) {
	$result = prostir_create_lead( $request->get_params() );
	if ( is_wp_error( $result ) ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => $result->get_error_message() ), 422 );
	}
	return new WP_REST_Response( array( 'ok' => true, 'message' => prostir_opt( 'form_success' ) ), 200 );
}

/**
 * Запасний варіант без JavaScript: звичайний POST на admin-post.php.
 */
function prostir_admin_post_lead() {
	$result   = prostir_create_lead( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.NonceVerification
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$redirect = remove_query_arg( array( 'sent', 'error' ), $redirect );
	$redirect = add_query_arg( is_wp_error( $result ) ? array( 'error' => rawurlencode( $result->get_error_message() ) ) : array( 'sent' => '1' ), $redirect );
	wp_safe_redirect( $redirect . '#booking' );
	exit;
}
add_action( 'admin_post_nopriv_prostir_lead', 'prostir_admin_post_lead' );
add_action( 'admin_post_prostir_lead', 'prostir_admin_post_lead' );

/**
 * Валідація і збереження заявки.
 *
 * @return int|WP_Error ID заявки.
 */
function prostir_create_lead( $data ) {
	// Пастка для ботів: приховане поле має бути порожнім.
	if ( ! empty( $data['website'] ) ) {
		return new WP_Error( 'spam', 'Не вдалося надіслати заявку.' );
	}

	$name    = sanitize_text_field( $data['name'] ?? '' );
	$phone   = sanitize_text_field( $data['phone'] ?? '' );
	$package = sanitize_text_field( $data['package'] ?? '' );
	$date    = sanitize_text_field( $data['date'] ?? '' );
	$comment = sanitize_textarea_field( $data['comment'] ?? '' );
	$source  = esc_url_raw( $data['source'] ?? '' );

	if ( mb_strlen( $name ) < 2 ) {
		return new WP_Error( 'name', 'Вкажіть, будь ласка, ім’я.' );
	}
	if ( strlen( preg_replace( '/\D/', '', $phone ) ) < 9 ) {
		return new WP_Error( 'phone', 'Вкажіть коректний номер телефону.' );
	}

	// Простий ліміт: не більше 5 заявок на хвилину з однієї адреси.
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$key = 'prostir_lead_' . md5( $ip );
	$hits = (int) get_transient( $key );
	if ( $hits >= 5 ) {
		return new WP_Error( 'rate', 'Забагато спроб. Спробуйте за хвилину.' );
	}
	set_transient( $key, $hits + 1, MINUTE_IN_SECONDS );

	$package_label = $package ? prostir_form_option_label( $package ) : '';

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'prostir_lead',
			'post_status' => 'publish',
			'post_title'  => trim( $name . ' · ' . $phone . ( $package_label ? ' · ' . $package_label : '' ) ),
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'save', 'Не вдалося зберегти заявку. Зателефонуйте нам, будь ласка.' );
	}

	$meta = array(
		'name'    => $name,
		'phone'   => $phone,
		'package' => $package_label,
		'date'    => $date,
		'comment' => $comment,
		'source'  => $source,
	);
	foreach ( $meta as $k => $v ) {
		update_post_meta( $post_id, '_prostir_' . $k, $v );
	}

	/**
	 * Хук для сповіщень. Приклад (e-mail):
	 * add_action( 'prostir_lead_created', function ( $id, $lead ) { wp_mail( get_option( 'admin_email' ), 'Нова заявка', print_r( $lead, true ) ); }, 10, 2 );
	 * Для Telegram: wp_remote_post( "https://api.telegram.org/bot{TOKEN}/sendMessage", [ 'body' => [ 'chat_id' => ..., 'text' => ... ] ] );
	 */
	do_action( 'prostir_lead_created', $post_id, $meta );

	return $post_id;
}
