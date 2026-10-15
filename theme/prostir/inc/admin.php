<?php
/**
 * Адмінка: меню «Простір», сторінка огляду, прибирання зайвого.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

function prostir_admin_menu() {
	add_menu_page( 'Простір', 'Простір', 'edit_pages', 'prostir', 'prostir_admin_page', 'dashicons-camera-alt', 3 );
	add_submenu_page( 'prostir', 'Огляд', 'Огляд', 'edit_pages', 'prostir', 'prostir_admin_page' );
	add_submenu_page( 'prostir', 'Налаштування сайту', 'Контакти і тексти', 'edit_theme_options', 'customize.php?autofocus[panel]=prostir' );

	// Блог не використовується.
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'prostir_admin_menu' );

/**
 * Підменю CPT мають з'являтися після «Огляду».
 */
function prostir_admin_menu_order() {
	global $submenu;
	if ( empty( $submenu['prostir'] ) ) {
		return;
	}
	$order = array( 'prostir', 'edit.php?post_type=prostir_package', 'edit.php?post_type=prostir_session', 'edit.php?post_type=prostir_work', 'edit.php?post_type=prostir_feature', 'edit.php?post_type=prostir_review', 'edit.php?post_type=prostir_lead', 'customize.php?autofocus[panel]=prostir' );
	usort(
		$submenu['prostir'],
		function ( $a, $b ) use ( $order ) {
			$ia = array_search( $a[2], $order, true );
			$ib = array_search( $b[2], $order, true );
			return ( false === $ia ? 99 : $ia ) <=> ( false === $ib ? 99 : $ib );
		}
	);
}
add_action( 'admin_menu', 'prostir_admin_menu_order', 100 );

function prostir_admin_page() {
	$leads = wp_count_posts( 'prostir_lead' );
	$links = array(
		array( 'customize.php?autofocus[panel]=prostir', 'Контакти і тексти', 'Телефон, адреса, Instagram, ціна оренди, тексти секцій головної' ),
		array( 'edit.php?post_type=prostir_package', 'Пакети та ціни', 'Ціни, тривалість, що входить, «Найпопулярніший»' ),
		array( 'edit.php?post_type=prostir_session', 'Види фотосесій', 'Картки на головній та сторінка «Фотосесії»' ),
		array( 'edit.php?post_type=prostir_work', 'Портфоліо', 'Фото на головній, 12–16 найкращих' ),
		array( 'edit.php?post_type=prostir_feature', 'Можливості студії', 'Світло, фони, проєктор, дим, вітер' ),
		array( 'edit.php?post_type=prostir_review', 'Відгуки', '5–6 коротких відгуків на головній' ),
		array( 'edit.php?post_type=prostir_lead', 'Заявки', ( $leads->publish ?? 0 ) . ' заявок з форми' ),
		array( 'edit.php?post_type=page', 'Сторінки', 'Оренда, Сертифікат, Про студію, Контакти' ),
		array( 'upload.php', 'Медіатека', 'Завантажити нові фото' ),
	);
	?>
	<div class="wrap prostir-admin-page">
		<h1>Простір: керування сайтом</h1>
		<p>Усе, що бачить відвідувач, змінюється тут без розробника. Оберіть розділ:</p>
		<div class="prostir-links">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( admin_url( $link[0] ) ); ?>"><?php echo esc_html( $link[1] ); ?><span><?php echo esc_html( $link[2] ); ?></span></a>
			<?php endforeach; ?>
		</div>
		<?php if ( isset( $_GET['demo'] ) && 'done' === $_GET['demo'] ) : // phpcs:ignore WordPress.Security.NonceVerification ?>
			<div class="notice notice-success"><p>Демо-контент перевстановлено.</p></div>
		<?php endif; ?>
		<div class="card">
			<h2>Демо-контент</h2>
			<p>Кнопка нижче повертає початковий демо-контент: сторінки, пакети, відгуки, портфоліо та фото. Ваші зміни в цих записах буде перезаписано.</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Перевстановити демо-контент? Зміни буде перезаписано.');">
				<input type="hidden" name="action" value="prostir_reinstall_demo">
				<?php wp_nonce_field( 'prostir_reinstall_demo' ); ?>
				<?php submit_button( 'Перевстановити демо-контент', 'secondary', 'submit', false ); ?>
			</form>
		</div>
	</div>
	<?php
}

function prostir_reinstall_demo_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Недостатньо прав.' );
	}
	check_admin_referer( 'prostir_reinstall_demo' );
	prostir_install_demo( true );
	wp_safe_redirect( admin_url( 'admin.php?page=prostir&demo=done' ) );
	exit;
}
add_action( 'admin_post_prostir_reinstall_demo', 'prostir_reinstall_demo_handler' );

/**
 * Заголовок заявки не редагується.
 */
function prostir_lead_title_readonly() {
	$screen = get_current_screen();
	if ( $screen && 'prostir_lead' === $screen->post_type ) {
		echo '<style>#titlediv input{pointer-events:none;background:#f6f7f7}#titlediv #edit-slug-box{display:none}</style>';
	}
}
add_action( 'admin_head', 'prostir_lead_title_readonly' );

/**
 * Віджет на дашборді.
 */
function prostir_dashboard_widget() {
	wp_add_dashboard_widget( 'prostir_dashboard', 'Простір: швидкі дії', 'prostir_dashboard_widget_render' );
}
add_action( 'wp_dashboard_setup', 'prostir_dashboard_widget' );

function prostir_dashboard_widget_render() {
	$leads = get_posts( array( 'post_type' => 'prostir_lead', 'posts_per_page' => 5 ) );
	echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'customize.php?autofocus[panel]=prostir' ) ) . '">Контакти і тексти</a> ';
	echo '<a class="button" href="' . esc_url( admin_url( 'edit.php?post_type=prostir_package' ) ) . '">Пакети та ціни</a> ';
	echo '<a class="button" href="' . esc_url( admin_url( 'edit.php?post_type=prostir_review' ) ) . '">Відгуки</a></p>';
	echo '<h4>Останні заявки</h4>';
	if ( ! $leads ) {
		echo '<p>Поки що заявок немає.</p>';
		return;
	}
	echo '<ul>';
	foreach ( $leads as $lead ) {
		echo '<li><a href="' . esc_url( get_edit_post_link( $lead->ID ) ) . '">' . esc_html( $lead->post_title ) . '</a> <small>' . esc_html( get_the_date( 'd.m.Y H:i', $lead ) ) . '</small></li>';
	}
	echo '</ul>';
}

/**
 * Підказка біля «Order» для порядку записів.
 */
function prostir_admin_footer_text( $text ) {
	return 'Сайт фотостудії «Простір». Тема без page builder’ів, усе редагується в цій адмінці.';
}
add_filter( 'admin_footer_text', 'prostir_admin_footer_text' );
