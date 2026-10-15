<?php
/**
 * Демо-контент: сторінки, меню, пакети, види фотосесій, портфоліо, відгуки, фото.
 * Викликається з blueprint (runPHP) або кнопкою в адмінці.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

define( 'PROSTIR_DEMO_VERSION', '1' );

/**
 * Головна функція встановлення.
 *
 * @param bool $force Перевстановити, навіть якщо вже встановлено.
 */
function prostir_install_demo( $force = false ) {
	if ( ! $force && get_option( 'prostir_demo_installed' ) === PROSTIR_DEMO_VERSION ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/post.php';

	set_time_limit( 0 );
	wp_raise_memory_limit( 'admin' );

	// Під час імпорту не генеруємо проміжні розміри: фото вже оптимізовані, а WASM-GD повільний.
	add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
	add_filter( 'big_image_size_threshold', '__return_false' );

	prostir_demo_cleanup();
	$packages = prostir_demo_packages();
	prostir_demo_sessions( $packages );
	prostir_demo_works();
	prostir_demo_features();
	prostir_demo_reviews();
	$pages = prostir_demo_pages();
	prostir_demo_menu( $pages );
	prostir_demo_options( $pages );

	remove_filter( 'intermediate_image_sizes_advanced', '__return_empty_array' );
	remove_filter( 'big_image_size_threshold', '__return_false' );

	update_option( 'prostir_demo_installed', PROSTIR_DEMO_VERSION );
	flush_rewrite_rules( false );
}

/**
 * Підписи (alt) до демо-фото. Ключ — ID фото на Pexels (див. CREDITS.md).
 */
function prostir_demo_image_alts() {
	return array(
		37233404 => 'Портрет у студії «Простір»: дівчина в чорному на теплому фоні',
		8070398  => 'Індивідуальна фотосесія в бежевих тонах',
		37409074 => 'Сімейна фотосесія: тато з дитиною на плечах',
		39059472 => 'Love Story: пара в обіймах на теплому фоні',
		16052900 => 'Бізнес-портрет: жінка в костюмі',
		9896786  => 'Дитяча фотосесія: кучерява дитина дивиться вгору',
		28517486 => 'Premium: портрет у кольоровому світлі',
		23911184 => 'Індивідуальний портрет у теплому світлі',
		16762656 => 'Чоловічий портрет із драматичним світлом',
		9385084  => 'Сімейна фотосесія трьох поколінь',
		9440848  => 'Хлопчик з іграшкою в студії',
		36646359 => 'Портрет у незвичайному світлі',
		7541544  => 'Портрет із візерунком від проєктора',
		19055933 => 'Зйомка з димом у студії',
		31497537 => 'Ефект вітру: волосся в русі',
		33847264 => 'Індивідуальна фотосесія на білому фоні',
		33748644 => 'Сімейна фотосесія на темному фоні',
		19697908 => 'Дитина з мильними бульбашками',
		20607813 => 'Love Story: ніжний момент пари',
		3851165  => 'Мінімалістичний портрет у чорному',
		7148717  => 'Чорно-білий портрет з імпульсним світлом',
		13327382 => 'Портрет із постійним світлом',
		36239188 => 'Кольоровий паперовий фон у студії',
		4584400  => 'Ефект вітру в студії',
		7383644  => 'Світла знімальна зона з циклорамою',
		8143699  => 'Зона з денним світлом та дерев’яною підлогою',
		37468393 => 'Знімальна зона з бежевим фоном',
		9396404  => 'Куточок студії з вікном і софтбоксом',
		25526512 => 'Світлове обладнання студії',
		1932666  => 'Білий фон і софтбокси',
		16008692 => 'Лофтова зона студії з великими вікнами',
		6045331  => 'Простора студія з високою стелею',
		36697538 => 'Фотограф студії з камерою',
		16135648 => 'Фотографиня студії за роботою',
		36697247 => 'Фотограф підказує позу',
		16671736 => 'Зйомка в світлій зоні студії: фотограф і модель',
		39190655 => 'Підготовка до зйомки в студії',
		19791016 => 'Подарункова коробка з темною стрічкою',
		5872188  => 'Подарунковий сертифікат у білій коробці',
		9219062  => 'Надруковані фотографії в руках',
		7015070  => 'Надруковані фото на столі',
		39915457 => 'Чоловічий портрет на стільці',
		33523784 => 'Чорно-білий чоловічий портрет',
		4692269  => 'Чорно-білий дитячий портрет',
		33100431 => 'Бізнес-портрет з телефоном',
		31307734 => 'Діловий портрет жінки',
		35245328 => 'Сімейна фотосесія з немовлям',
		331208   => 'Пара тримається за руки',
		15438804 => 'Фотосесія для вагітних',
		7541519  => 'Проєкція візерунка на обличчі',
		30147916 => 'Портрет у синьому світлі',
		36913998 => 'Чоловічий портрет у димі',
		33681513 => 'Портрет у тумані',
		26803801 => 'Чорно-білий жіночий портрет',
		31747504 => 'Усміхнена дівчина на стільці',
	);
}

/**
 * Імпорт фото з теми в медіатеку (один раз; повторні виклики повертають той самий ID).
 */
function prostir_demo_attach( $pexels_id ) {
	$map = get_option( 'prostir_demo_images', array() );
	if ( ! empty( $map[ $pexels_id ] ) && get_post( $map[ $pexels_id ] ) ) {
		return (int) $map[ $pexels_id ];
	}
	$file = PROSTIR_DIR . '/assets/img/demo/pexels-' . $pexels_id . '.webp';
	if ( ! file_exists( $file ) ) {
		return 0;
	}
	$alts   = prostir_demo_image_alts();
	$alt    = $alts[ $pexels_id ] ?? 'Фотостудія «Простір»';
	$upload = wp_upload_bits( 'prostir-' . $pexels_id . '.webp', null, file_get_contents( $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/webp',
			'post_title'     => $alt,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}
	$meta = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
	if ( empty( $meta['width'] ) ) {
		$size = @getimagesize( $upload['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		$meta = array(
			'width'  => $size[0] ?? 0,
			'height' => $size[1] ?? 0,
			'file'   => _wp_relative_upload_path( $upload['file'] ),
			'sizes'  => array(),
		);
	}
	wp_update_attachment_metadata( $attachment_id, $meta );
	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	$map[ $pexels_id ] = $attachment_id;
	update_option( 'prostir_demo_images', $map, false );
	return (int) $attachment_id;
}

/**
 * Видаляємо попередній демо-контент (крім заявок і фото).
 */
function prostir_demo_cleanup() {
	foreach ( array( 'prostir_package', 'prostir_session', 'prostir_work', 'prostir_feature', 'prostir_review' ) as $type ) {
		$ids = get_posts( array( 'post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids' ) );
		foreach ( $ids as $id ) {
			wp_delete_post( $id, true );
		}
	}
	foreach ( get_terms( array( 'taxonomy' => 'prostir_work_cat', 'hide_empty' => false ) ) as $term ) {
		wp_delete_term( $term->term_id, 'prostir_work_cat' );
	}
	// Стандартні записи WordPress.
	foreach ( array( 1 ) as $id ) {
		$p = get_post( $id );
		if ( $p && 'post' === $p->post_type ) {
			wp_delete_post( $id, true );
		}
	}
	$sample = get_page_by_path( 'sample-page' );
	if ( $sample ) {
		wp_delete_post( $sample->ID, true );
	}
}

function prostir_demo_insert( $args, $meta = array() ) {
	$args = wp_parse_args( $args, array( 'post_status' => 'publish' ) );
	$id   = wp_insert_post( $args, true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, '_prostir_' . $key, $value );
	}
	return $id;
}

/**
 * Пакети з ТЗ.
 */
function prostir_demo_packages() {
	$data = array(
		'mini' => array(
			'title'   => 'MINI',
			'content' => 'Швидка зйомка для портрета, оновлення фото в соцмережах чи просто для себе.',
			'meta'    => array(
				'price'        => 1900,
				'duration'     => '30 хвилин',
				'participants' => '1 учасник',
				'looks'        => '1 образ',
				'items'        => "Студія + фотограф\nУсі якісні фотографії з кольорокорекцією\n5 фотографій з детальною ретушшю\nДопомога з позуванням\n5 надрукованих фото 10×15 см",
				'button'       => 'Обрати MINI',
				'popular'      => 0,
				'gift'         => 1,
			),
		),
		'classic' => array(
			'title'   => 'CLASSIC',
			'content' => 'Повноцінна година в студії з кількома образами. Найчастіший вибір для індивідуальної зйомки.',
			'meta'    => array(
				'price'        => 3200,
				'duration'     => '1 година',
				'participants' => '1 учасник',
				'looks'        => '2–3 образи',
				'items'        => "Студія + фотограф\nУсі якісні фотографії з кольорокорекцією\n10 фотографій з детальною ретушшю\nДопомога з позуванням\n5 надрукованих фотографій 15×20 см",
				'button'       => 'Обрати CLASSIC',
				'popular'      => 1,
				'gift'         => 1,
			),
		),
		'family-love-story' => array(
			'title'   => 'FAMILY / LOVE STORY',
			'content' => 'Для родини або пари: до п’яти учасників, кілька образів і більше надрукованих фото.',
			'meta'    => array(
				'price'        => 3500,
				'duration'     => '1 година',
				'participants' => '2–5 учасників',
				'looks'        => 'Кілька образів',
				'items'        => "Студія + фотограф\nУсі якісні фотографії з кольорокорекцією\n12 фотографій з детальною ретушшю\nДопомога з позуванням\n10 надрукованих фотографій 15×20 см",
				'button'       => 'Обрати FAMILY / LOVE STORY',
				'popular'      => 0,
				'gift'         => 1,
			),
		),
		'premium' => array(
			'title'   => 'PREMIUM',
			'content' => 'Максимум можливостей студії: проєктор, дим, ефект вітру, найбільше ретуші та фотокартина на згадку.',
			'meta'    => array(
				'price'        => 4900,
				'duration'     => '1,5 години',
				'participants' => 'До 5 учасників',
				'looks'        => 'До 4 образів',
				'items'        => "Студія + фотограф\nПрофесійне світло\nПроєктор, дим та ефект вітру\nУсі якісні фотографії з кольорокорекцією\n20 фотографій з детальною ретушшю\n10 надрукованих фотографій 15×20 см\nФотокартина 40×50 см",
				'button'       => 'Обрати PREMIUM',
				'popular'      => 0,
				'gift'         => 1,
			),
		),
	);
	$ids   = array();
	$order = 1;
	foreach ( $data as $slug => $item ) {
		$ids[ $slug ] = prostir_demo_insert(
			array(
				'post_type'    => 'prostir_package',
				'post_title'   => $item['title'],
				'post_name'    => $slug,
				'post_content' => $item['content'],
				'menu_order'   => $order++,
			),
			$item['meta']
		);
	}
	return $ids;
}

/**
 * Види фотосесій.
 */
function prostir_demo_sessions( $packages ) {
	$data = array(
		'individual' => array(
			'title'   => 'Індивідуальна фотосесія',
			'short'   => 'Портрет для себе, для соцмереж чи для резюме. Допоможемо з образом і позуванням.',
			'content' => "<p>Зйомка для однієї людини: портрет, фото для соцмереж, оновлення фотографій для сайту чи просто на згадку. Не хвилюйтеся, якщо ніколи не позували: фотограф показує кожен рух і слідкує за світлом, вам лишається бути собою.</p>\n<p>Радимо взяти два-три образи: щось спокійне й нейтральне та щось яскравіше. Усі якісні кадри віддаємо з кольорокорекцією, найкращі ретушуємо детально.</p>",
			'thumb'   => 8070398,
			'gallery' => array( 23911184, 33847264, 31747504, 3851165, 26803801 ),
			'packages' => array( 'mini', 'classic' ),
			'on_home' => 1,
		),
		'family' => array(
			'title'   => 'Сімейна фотосесія',
			'short'   => 'Тепла зйомка для всієї родини: з дітьми, батьками, бабусями й дідусями.',
			'content' => "<p>Сімейні фотографії, які хочеться надрукувати й повісити на стіну. Знімаємо родини від двох до п’яти людей: пари з дітьми, кілька поколінь, братів і сестер.</p>\n<p>Підкажемо, як підібрати одяг, щоб усі виглядали гармонійно, а з дітьми працюємо через гру, тому на фото справжні емоції, а не вимушені посмішки.</p>",
			'thumb'   => 37409074,
			'gallery' => array( 9385084, 33748644, 35245328 ),
			'packages' => array( 'family-love-story' ),
			'on_home' => 1,
		),
		'love-story' => array(
			'title'   => 'Love Story',
			'short'   => 'Історія двох у студії: ніжно, стильно, без незручності перед камерою.',
			'content' => "<p>Фотосесія для пари на річницю, перед весіллям або просто так. Темний фон і кіношне світло або світла циклорама й легкий настрій: оберемо разом під вашу історію.</p>\n<p>Фотограф підказує пози, які виглядають природно, і ловить моменти між ними. Саме ці кадри зазвичай стають улюбленими.</p>",
			'thumb'   => 39059472,
			'gallery' => array( 20607813, 331208, 15438804 ),
			'packages' => array( 'family-love-story' ),
			'on_home' => 1,
		),
		'kids' => array(
			'title'   => 'Дитяча фотосесія',
			'short'   => 'Граємо, а не позуємо. Фотограф знає, як розговорити навіть найсором’язливіших.',
			'content' => "<p>Діти не сидять на місці, і це добре: ми не просимо їх позувати. Мильні бульбашки, улюблена іграшка, кілька хвилин на звикання до студії, і кадри з’являються самі.</p>\n<p>Для малюків до року знімаємо разом із батьками. Для школярів можна зробити стильний портрет на пам’ять.</p>",
			'thumb'   => 9896786,
			'gallery' => array( 9440848, 19697908, 4692269 ),
			'packages' => array( 'mini', 'classic', 'family-love-story' ),
			'on_home' => 0,
		),
		'business' => array(
			'title'   => 'Контент для бізнесу',
			'short'   => 'Портрети для сайту й соцмереж, фото команди та продукту в єдиному стилі.',
			'content' => "<p>Ділові портрети, фото команди, контент для соцмереж і сайту. Знімаємо в єдиному стилі, щоб усі фото виглядали як одна система, а не набір випадкових кадрів.</p>\n<p>Для експертів і підприємців радимо пакет CLASSIC із двома-трьома образами. Для команд та продуктової зйомки розрахуємо індивідуально.</p>",
			'thumb'   => 16052900,
			'gallery' => array( 33100431, 31307734, 39915457, 33523784 ),
			'packages' => array( 'classic', 'premium' ),
			'on_home' => 1,
			'form_value' => 'business',
		),
		'premium' => array(
			'title'   => 'Premium',
			'short'   => 'Проєктор, дим, ефект вітру та кольорове світло. Фотосесія, яка не схожа на інші.',
			'content' => "<p>Для тих, хто хоче фото, яких ні в кого немає. Проєктор малює візерунки й текст на тілі та фоні, дим додає глибини, вітер рухає волосся і тканину, а кольорове світло задає настрій.</p>\n<p>У пакет PREMIUM входить півтори години зйомки, до чотирьох образів, 20 фотографій з детальною ретушшю й фотокартина 40×50 см.</p>",
			'thumb'   => 28517486,
			'gallery' => array( 7541544, 7541519, 30147916, 19055933, 36913998, 33681513, 36646359 ),
			'packages' => array( 'premium' ),
			'on_home' => 0,
		),
	);
	$order = 1;
	foreach ( $data as $slug => $item ) {
		$gallery = array_filter( array_map( 'prostir_demo_attach', $item['gallery'] ) );
		$pkg_ids = array();
		foreach ( $item['packages'] as $p ) {
			if ( ! empty( $packages[ $p ] ) ) {
				$pkg_ids[] = $packages[ $p ];
			}
		}
		$id = prostir_demo_insert(
			array(
				'post_type'    => 'prostir_session',
				'post_title'   => $item['title'],
				'post_name'    => $slug,
				'post_content' => $item['content'],
				'menu_order'   => $order++,
			),
			array(
				'short'      => $item['short'],
				'gallery'    => array_values( $gallery ),
				'packages'   => $pkg_ids,
				'on_home'    => $item['on_home'],
				'form_value' => $item['form_value'] ?? '',
			)
		);
		if ( $id ) {
			set_post_thumbnail( $id, prostir_demo_attach( $item['thumb'] ) );
		}
	}
}

/**
 * Портфоліо: 16 фото з напрямками.
 */
function prostir_demo_works() {
	$cats = array(
		'individual' => 'Індивідуальні',
		'family'     => 'Сімейні',
		'kids'       => 'Діти',
		'love-story' => 'Love Story',
		'business'   => 'Бізнес і контент',
		'light'      => 'Незвичайне світло',
		'projector'  => 'Проєктор',
		'smoke'      => 'Дим',
		'wind'       => 'Вітер',
	);
	$term_ids = array();
	foreach ( $cats as $slug => $name ) {
		$term = wp_insert_term( $name, 'prostir_work_cat', array( 'slug' => $slug ) );
		if ( ! is_wp_error( $term ) ) {
			$term_ids[ $slug ] = (int) $term['term_id'];
		}
	}
	$works = array(
		array( 23911184, 'Індивідуальний портрет', 'individual' ),
		array( 16762656, 'Чоловічий портрет', 'individual' ),
		array( 9385084, 'Сімейна фотосесія', 'family' ),
		array( 9440848, 'Дитяча фотосесія', 'kids' ),
		array( 39059472, 'Love Story', 'love-story' ),
		array( 16052900, 'Бізнес-портрет', 'business' ),
		array( 36646359, 'Незвичайне світло', 'light' ),
		array( 7541544, 'Проєктор', 'projector' ),
		array( 19055933, 'Дим', 'smoke' ),
		array( 31497537, 'Ефект вітру', 'wind' ),
		array( 33847264, 'Індивідуальна фотосесія', 'individual' ),
		array( 33748644, 'Сімейна фотосесія', 'family' ),
		array( 19697908, 'Діти', 'kids' ),
		array( 20607813, 'Love Story', 'love-story' ),
		array( 28517486, 'Кольорове світло', 'light' ),
		array( 3851165, 'Портрет', 'individual' ),
	);
	$order = 1;
	foreach ( $works as $work ) {
		$id = prostir_demo_insert(
			array(
				'post_type'  => 'prostir_work',
				'post_title' => $work[1],
				'menu_order' => $order++,
			)
		);
		if ( $id ) {
			set_post_thumbnail( $id, prostir_demo_attach( $work[0] ) );
			if ( ! empty( $term_ids[ $work[2] ] ) ) {
				wp_set_object_terms( $id, array( $term_ids[ $work[2] ] ), 'prostir_work_cat' );
			}
		}
	}
}

/**
 * Можливості студії: результат, а не обладнання.
 */
function prostir_demo_features() {
	$features = array(
		array( 7148717, 'Імпульсне світло', 'Чіткий малюнок світла й тіней' ),
		array( 13327382, 'Постійне світло', 'М’яке кіношне світло' ),
		array( 36239188, 'Фони', 'Кольорові паперові й тканинні фони' ),
		array( 7541544, 'Проєктор', 'Візерунки й текст на тілі та фоні' ),
		array( 19055933, 'Дим', 'Об’єм і атмосфера в кадрі' ),
		array( 4584400, 'Ефект вітру', 'Рух волосся й тканини' ),
	);
	$order = 1;
	foreach ( $features as $f ) {
		$id = prostir_demo_insert(
			array(
				'post_type'  => 'prostir_feature',
				'post_title' => $f[1],
				'menu_order' => $order++,
			),
			array( 'subtitle' => $f[2] )
		);
		if ( $id ) {
			set_post_thumbnail( $id, prostir_demo_attach( $f[0] ) );
		}
	}
}

/**
 * Відгуки: про позування, комфорт, дітей, якість і друк.
 */
function prostir_demo_reviews() {
	$reviews = array(
		array( 'Оксана', 'Сімейна фотосесія', 'Прийшли з двома дітьми й чоловіком, який «не любить фотографуватися». Фотограф за п’ять хвилин розговорила всіх, діти бігали й сміялися, а на фото це виглядає як справжнє щастя. Надруковані фото вже на стіні.' ),
		array( 'Дмитро', 'Контент для бізнесу', 'Потрібні були портрети для сайту та LinkedIn. Отримав акуратні, живі фото без «офісного» пластику. Зняли за годину, готові файли прийшли через три дні.' ),
		array( 'Марина', 'Індивідуальна фотосесія', 'Боялася, що не вмію позувати. Виявилось, це не моя робота: фотограф показує кожен рух, і на знімках я нарешті собі подобаюсь.' ),
		array( 'Ірина та Андрій', 'Love Story', 'Хотіли щось стильне на річницю. Темний фон, трохи диму, кіношне світло. Виглядає як кадри з фільму, а не як типове «студійне» фото.' ),
		array( 'Наталія', 'Дитяча фотосесія', 'Дочці три роки, сидіти на місці не вміє. У студії це нікого не напружило: з нею грали, і за пів години є десятки чудових кадрів. Друк 15×20 — окрема радість.' ),
		array( 'Олена', 'PREMIUM', 'Брала PREMIUM із фотокартиною. Проєктор і вітер — це вау, фото ні на що не схожі. Картина 40×50 тепер у вітальні, гості одразу питають, де знімали.' ),
	);
	$order = 1;
	foreach ( $reviews as $r ) {
		prostir_demo_insert(
			array(
				'post_type'    => 'prostir_review',
				'post_title'   => $r[0],
				'post_content' => $r[2],
				'menu_order'   => $order++,
			),
			array( 'tag' => $r[1], 'source' => 'Instagram' )
		);
	}
}

/**
 * Блок галереї Gutenberg з ID вкладень.
 */
function prostir_demo_gallery_block( $pexels_ids, $columns = 3 ) {
	$items = '';
	foreach ( $pexels_ids as $pid ) {
		$id = prostir_demo_attach( $pid );
		if ( ! $id ) {
			continue;
		}
		$url = wp_get_attachment_image_url( $id, 'large' );
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$items .= sprintf(
			"<!-- wp:image {\"id\":%1\$d,\"sizeSlug\":\"large\",\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"%2\$s\" alt=\"%3\$s\" class=\"wp-image-%1\$d\"/></figure>\n<!-- /wp:image -->\n\n",
			$id,
			esc_url( $url ),
			esc_attr( $alt )
		);
	}
	return sprintf(
		"<!-- wp:gallery {\"columns\":%1\$d,\"linkTo\":\"none\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-%1\$d is-cropped\">%2\$s</figure>\n<!-- /wp:gallery -->\n\n",
		(int) $columns,
		$items
	);
}

function prostir_demo_media_text_block( $pexels_id, $html, $media_right = false ) {
	$id  = prostir_demo_attach( $pexels_id );
	$url = $id ? wp_get_attachment_image_url( $id, 'large' ) : '';
	$alt = $id ? get_post_meta( $id, '_wp_attachment_image_alt', true ) : '';
	$pos = $media_right ? 'right' : 'left';
	return sprintf(
		"<!-- wp:media-text {\"mediaId\":%1\$d,\"mediaPosition\":\"%5\$s\",\"mediaType\":\"image\",\"imageFill\":true} -->\n<div class=\"wp-block-media-text alignwide has-media-on-the-%5\$s is-stacked-on-mobile is-image-fill\"><figure class=\"wp-block-media-text__media\" style=\"background-image:url(%2\$s);background-position:50%% 50%%\"><img src=\"%2\$s\" alt=\"%3\$s\" class=\"wp-image-%1\$d size-full\"/></figure><div class=\"wp-block-media-text__content\">%4\$s</div></div>\n<!-- /wp:media-text -->\n\n",
		$id,
		esc_url( $url ),
		esc_attr( $alt ),
		$html,
		$pos
	);
}

function prostir_demo_p( $text ) {
	return "<!-- wp:paragraph -->\n<p>" . $text . "</p>\n<!-- /wp:paragraph -->\n\n";
}

function prostir_demo_h( $text, $level = 2 ) {
	return sprintf( "<!-- wp:heading {\"level\":%1\$d} -->\n<h%1\$d class=\"wp-block-heading\">%2\$s</h%1\$d>\n<!-- /wp:heading -->\n\n", $level, $text );
}

function prostir_demo_list( $items ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->\n";
	}
	return "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . $li . "</ul>\n<!-- /wp:list -->\n\n";
}

/**
 * Сторінки з ТЗ.
 */
function prostir_demo_pages() {
	$pages = array(
		'home' => array(
			'title'    => 'Головна',
			'content'  => '',
			'template' => '',
		),
		'photosession' => array(
			'title'    => 'Фотосесії',
			'content'  => '',
			'template' => 'templates/page-photosession.php',
			'thumb'    => 16671736,
		),
		'rent' => array(
			'title'    => 'Оренда фотостудії в Обухові',
			'template' => 'templates/page-rent.php',
			'thumb'    => 6045331,
			'content'  =>
				prostir_demo_h( 'Дві знімальні зони' ) .
				prostir_demo_media_text_block( 7383644, prostir_demo_h( 'Зона 1. Світла циклорама', 3 ) . prostir_demo_p( 'Біла циклорама 5×6 м, імпульсне світло, набір софтбоксів і рефлекторів. Для портретів, каталожної зйомки та відео на білому.' ) ) .
				prostir_demo_media_text_block( 8143699, prostir_demo_h( 'Зона 2. Лофт із денним світлом', 3 ) . prostir_demo_p( 'Великі вікна, дерев’яна підлога, кольорові паперові й тканинні фони. Для атмосферних портретів, Love Story та контенту.' ), true ) .
				prostir_demo_h( 'Фони та обладнання' ) .
				prostir_demo_list(
					array(
						'Паперові фони: білий, чорний, бежевий, теракотовий, сіро-блакитний',
						'Тканинні фони й циклорама',
						'Імпульсне світло Godox 400 Дж, 3 джерела',
						'Постійне LED-світло, 2 панелі',
						'Софтбокси, октабокси, стрипи, рефлектори, соти',
						'Проєктор, димогенератор, вентилятор для ефекту вітру',
						'Стійки, журавель, відбивачі, стільці та кубики',
					)
				) .
				prostir_demo_gallery_block( array( 37468393, 9396404, 25526512, 1932666, 16008692, 7383644 ), 3 ) .
				prostir_demo_h( 'Умови оренди' ) .
				prostir_demo_list(
					array(
						'Мінімальне бронювання: 1 година',
						'Світло, фони та обладнання входять у вартість',
						'Бронювання телефоном або через форму на сайті',
						'Можна запросити власного візажиста, є гримерна зона',
						'Паркування біля входу',
					)
				),
		),
		'gift' => array(
			'title'    => 'Подарунковий сертифікат на фотосесію',
			'template' => 'templates/page-gift.php',
			'thumb'    => 5872188,
			'content'  =>
				prostir_demo_h( 'Як це працює' ) .
				prostir_demo_list(
					array(
						'Обираєте пакет: MINI, CLASSIC, FAMILY / LOVE STORY або PREMIUM',
						'Ми надсилаємо сертифікат у PDF або готуємо друкований у конверті',
						'Людина телефонує нам і обирає зручну дату',
						'Фотосесія в студії та готові оброблені фото',
					)
				) .
				prostir_demo_p( 'Сертифікат діє 6 місяців. Якщо людина захоче інший пакет, різницю можна доплатити на місці.' ),
		),
		'studio' => array(
			'title'    => 'Про студію',
			'template' => 'templates/page-studio.php',
			'thumb'    => 8143699,
			'content'  =>
				prostir_demo_p( '«Простір» працює в Обухові з 2021 року. Ми почали з оренди студії для фотографів, але швидко зрозуміли: більшості людей потрібна не студія, а готові гарні фотографії. Так з’явилися фотосесії під ключ, у яких уже є студія, фотограф, світло й обробка.' ) .
				prostir_demo_gallery_block( array( 7383644, 16008692, 9396404, 37468393 ), 2 ) .
				prostir_demo_h( 'Команда' ) .
				prostir_demo_media_text_block( 16135648, prostir_demo_h( 'Катерина, фотограф', 3 ) . prostir_demo_p( 'Знімає портрети та сімейні історії понад вісім років. Найкраще вміє заспокоїти тих, хто «не фотогенічний», і домовитися з дітьми.' ) ) .
				prostir_demo_media_text_block( 36697538, prostir_demo_h( 'Олексій, фотограф', 3 ) . prostir_demo_p( 'Любить складне світло: проєктор, дим, кольорові гелі. Відповідає за Premium-зйомки й контент для бізнесу.' ), true ) .
				prostir_demo_h( 'Обладнання та можливості' ) .
				prostir_demo_list(
					array(
						'Дві знімальні зони: світла циклорама і лофт із денним світлом',
						'Імпульсне та постійне світло, повний набір насадок',
						'Паперові й тканинні фони',
						'Проєктор, димогенератор, вентилятор для ефекту вітру',
						'Гримерна зона, кава та чай',
					)
				),
		),
		'contacts' => array(
			'title'    => 'Контакти',
			'template' => 'templates/page-contacts.php',
			'content'  => '',
		),
	);

	$ids = array();
	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $slug );
		$args     = array(
			'post_type'    => 'page',
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_content' => $page['content'],
			'post_status'  => 'publish',
			'comment_status' => 'closed',
		);
		if ( $existing ) {
			$args['ID'] = $existing->ID;
			$id         = wp_update_post( $args, true );
		} else {
			$id = wp_insert_post( $args, true );
		}
		if ( is_wp_error( $id ) ) {
			continue;
		}
		$ids[ $slug ] = (int) $id;
		if ( ! empty( $page['template'] ) ) {
			update_post_meta( $id, '_wp_page_template', $page['template'] );
		} else {
			delete_post_meta( $id, '_wp_page_template' );
		}
		if ( ! empty( $page['thumb'] ) ) {
			set_post_thumbnail( $id, prostir_demo_attach( $page['thumb'] ) );
		}
	}
	return $ids;
}

/**
 * Меню: Фотосесії, Оренда, Сертифікат, Про студію, Контакти.
 */
function prostir_demo_menu( $pages ) {
	$name = 'Головне меню';
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		foreach ( wp_get_nav_menu_items( $menu->term_id ) ?: array() as $item ) {
			wp_delete_post( $item->ID, true );
		}
		$menu_id = $menu->term_id;
	} else {
		$menu_id = wp_create_nav_menu( $name );
	}
	if ( is_wp_error( $menu_id ) ) {
		return;
	}
	$items = array(
		'photosession' => 'Фотосесії',
		'rent'         => 'Оренда',
		'gift'         => 'Сертифікат',
		'studio'       => 'Про студію',
		'contacts'     => 'Контакти',
	);
	$pos = 1;
	foreach ( $items as $slug => $label ) {
		if ( empty( $pages[ $slug ] ) ) {
			continue;
		}
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $pages[ $slug ],
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $pos++,
			)
		);
	}
	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	$locations['footer']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Налаштування сайту й фото в Customizer.
 */
function prostir_demo_options( $pages ) {
	update_option( 'blogname', 'Простір Обухів' );
	update_option( 'blogdescription', 'Фотостудія: фотосесії під ключ' );
	update_option( 'timezone_string', 'Europe/Kyiv' );
	update_option( 'date_format', 'j F Y' );
	update_option( 'time_format', 'H:i' );
	update_option( 'start_of_week', 1 );
	update_option( 'default_comment_status', 'closed' );
	update_option( 'default_pingback_flag', 0 );
	// Через $wp_rewrite, щоб правила перегенерувалися з новою структурою в цьому ж запиті.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	if ( ! empty( $pages['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
		update_option( 'page_for_posts', 0 );
	}

	set_theme_mod( 'hero_image', prostir_demo_attach( 37233404 ) );
	set_theme_mod( 'gift_image', prostir_demo_attach( 19791016 ) );
	set_theme_mod( 'print_image', prostir_demo_attach( 9219062 ) );
	set_theme_mod( 'rent_image', prostir_demo_attach( 7383644 ) );
	set_theme_mod( 'cta_image', prostir_demo_attach( 6045331 ) );
}

/**
 * Українська мова адмінки (не критично: якщо завантажити пакет не вдалося, лишається англійська).
 */
function prostir_demo_language() {
	if ( 'uk' === get_option( 'WPLANG' ) ) {
		return true;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	$result = wp_download_language_pack( 'uk' );
	if ( $result ) {
		update_option( 'WPLANG', $result );
		return true;
	}
	return false;
}
