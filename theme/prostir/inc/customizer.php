<?php
/**
 * Налаштування сайту через Customizer: контакти, тексти секцій, фото.
 *
 * @package Prostir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Опис усіх полів: секція → поля з типами й значеннями за замовчуванням (тексти з ТЗ).
 */
function prostir_customizer_config() {
	return array(
		'contacts' => array(
			'title'  => 'Контакти та реквізити',
			'fields' => array(
				'studio_name'      => array( 'label' => 'Назва студії', 'default' => 'Фотостудія «Простір»' ),
				'city'             => array( 'label' => 'Місто', 'default' => 'Обухів' ),
				'address'          => array( 'label' => 'Адреса', 'default' => 'м. Обухів, вул. Київська, 12' ),
				'phone'            => array( 'label' => 'Телефон', 'default' => '+380 67 123 45 67' ),
				'email'            => array( 'label' => 'E-mail', 'default' => 'hello@prostir-obukhiv.ua' ),
				'instagram'        => array( 'label' => 'Instagram (посилання)', 'default' => 'https://www.instagram.com/prostir.obukhiv', 'type' => 'url' ),
				'instagram_handle' => array( 'label' => 'Instagram (нік)', 'default' => '@prostir.obukhiv' ),
				'hours'            => array( 'label' => 'Режим роботи', 'default' => "Щодня 9:00–21:00\nЗа попереднім записом", 'type' => 'textarea' ),
				'map_embed'        => array( 'label' => 'Карта: адреса для вбудованої карти (iframe)', 'default' => 'https://maps.google.com/maps?q=%D0%9E%D0%B1%D1%83%D1%85%D1%96%D0%B2,+%D0%B2%D1%83%D0%BB.+%D0%9A%D0%B8%D1%97%D0%B2%D1%81%D1%8C%D0%BA%D0%B0,+12&z=15&hl=uk&output=embed', 'type' => 'url' ),
				'map_route'        => array( 'label' => 'Посилання «Прокласти маршрут»', 'default' => 'https://www.google.com/maps/dir/?api=1&destination=%D0%9E%D0%B1%D1%83%D1%85%D1%96%D0%B2,+%D0%B2%D1%83%D0%BB.+%D0%9A%D0%B8%D1%97%D0%B2%D1%81%D1%8C%D0%BA%D0%B0,+12', 'type' => 'url' ),
				'rent_price'       => array( 'label' => 'Ціна оренди студії, грн/год', 'default' => 600, 'type' => 'number' ),
				'footer_text'      => array( 'label' => 'Текст у футері', 'default' => 'Фотостудія «Простір», Обухів. Фотосесії під ключ: студія, фотограф, світло, готові фото.', 'type' => 'textarea' ),
			),
		),
		'hero' => array(
			'title'  => 'Головна: перший екран',
			'fields' => array(
				'hero_image'         => array( 'label' => 'Велике фото', 'type' => 'image' ),
				'hero_title'         => array( 'label' => 'Заголовок', 'default' => 'Фотостудія «Простір»' ),
				'hero_city'          => array( 'label' => 'Місто', 'default' => 'Обухів' ),
				'hero_subtitle'      => array( 'label' => 'Підзаголовок', 'default' => 'Фотосесії під ключ' ),
				'hero_lead'          => array( 'label' => 'Опис', 'default' => 'Студія + фотограф + професійне світло + готові фотографії' ),
				'hero_btn_primary'   => array( 'label' => 'Кнопка 1', 'default' => 'Записатися на фотосесію' ),
				'hero_btn_secondary' => array( 'label' => 'Кнопка 2', 'default' => 'Переглянути ціни' ),
				'hero_tags'          => array( 'label' => 'Напрямки (через •)', 'default' => 'Індивідуальні • Сімейні • Love Story • Контент' ),
			),
		),
		'benefits' => array(
			'title'  => 'Головна: переваги',
			'fields' => array(
				'benefits_title'  => array( 'label' => 'Заголовок', 'default' => 'Вам не потрібно окремо шукати фотографа і студію' ),
				'benefit_1_title' => array( 'label' => 'Перевага 1: назва', 'default' => 'Студія' ),
				'benefit_1_text'  => array( 'label' => 'Перевага 1: текст', 'default' => 'Професійне світло, фони, декорації та обладнання.', 'type' => 'textarea' ),
				'benefit_2_title' => array( 'label' => 'Перевага 2: назва', 'default' => 'Фотограф' ),
				'benefit_2_text'  => array( 'label' => 'Перевага 2: текст', 'default' => 'Допомога з позуванням і супровід під час зйомки.', 'type' => 'textarea' ),
				'benefit_3_title' => array( 'label' => 'Перевага 3: назва', 'default' => 'Готовий результат' ),
				'benefit_3_text'  => array( 'label' => 'Перевага 3: текст', 'default' => 'Оброблені фотографії, а в деяких пакетах також друковані фото та фотокартина.', 'type' => 'textarea' ),
			),
		),
		'sections' => array(
			'title'  => 'Головна: заголовки секцій',
			'fields' => array(
				'types_title'       => array( 'label' => 'Види фотосесій: заголовок', 'default' => 'Види фотосесій' ),
				'types_btn'         => array( 'label' => 'Види фотосесій: кнопка картки', 'default' => 'Детальніше' ),
				'packages_title'    => array( 'label' => 'Пакети: заголовок', 'default' => 'Пакети та ціни' ),
				'packages_text'     => array( 'label' => 'Пакети: підзаголовок', 'default' => 'У кожному пакеті вже є студія, фотограф, професійне світло та оброблені фотографії.', 'type' => 'textarea' ),
				'popular_label'     => array( 'label' => 'Бейдж популярного пакета', 'default' => 'Найпопулярніший' ),
				'portfolio_title'   => array( 'label' => 'Портфоліо: заголовок', 'default' => 'Портфоліо' ),
				'portfolio_count'   => array( 'label' => 'Портфоліо: кількість фото', 'default' => 16, 'type' => 'number' ),
				'portfolio_btn'     => array( 'label' => 'Портфоліо: кнопка', 'default' => 'Переглянути більше робіт' ),
				'portfolio_btn_url' => array( 'label' => 'Портфоліо: посилання кнопки', 'default' => '/photosession/', 'type' => 'url' ),
				'features_title'    => array( 'label' => 'Можливості студії: заголовок', 'default' => 'Більше, ніж просто фотостудія' ),
				'features_text'     => array( 'label' => 'Можливості студії: текст', 'default' => 'Імпульсне й постійне світло, фони, проєктор, дим і ефект вітру. Показуємо не обладнання, а те, що з ним виходить.', 'type' => 'textarea' ),
				'steps_title'       => array( 'label' => 'Як проходить фотосесія: заголовок', 'default' => 'Як проходить фотосесія' ),
				'step_1_title'      => array( 'label' => 'Крок 1', 'default' => 'Обираєте фотосесію' ),
				'step_1_text'       => array( 'label' => 'Крок 1: текст', 'default' => 'Пакет із сайту або просто ідея. Підкажемо формат.' ),
				'step_2_title'      => array( 'label' => 'Крок 2', 'default' => 'Узгоджуємо дату' ),
				'step_2_text'       => array( 'label' => 'Крок 2: текст', 'default' => 'Телефонуємо або пишемо, фіксуємо день і час.' ),
				'step_3_title'      => array( 'label' => 'Крок 3', 'default' => 'Готуємося до зйомки' ),
				'step_3_text'       => array( 'label' => 'Крок 3: текст', 'default' => 'Радимо образи, кольори та що взяти з собою.' ),
				'step_4_title'      => array( 'label' => 'Крок 4', 'default' => 'Фотосесія' ),
				'step_4_text'       => array( 'label' => 'Крок 4: текст', 'default' => 'Фотограф допомагає з позуванням, ви просто отримуєте задоволення.' ),
				'step_5_title'      => array( 'label' => 'Крок 5', 'default' => 'Отримуєте готові фото' ),
				'step_5_text'       => array( 'label' => 'Крок 5: текст', 'default' => 'Оброблені фотографії онлайн, а в деяких пакетах ще й друк.' ),
				'reviews_title'     => array( 'label' => 'Відгуки: заголовок', 'default' => 'Відгуки' ),
				'reviews_text'      => array( 'label' => 'Відгуки: підзаголовок', 'default' => 'Що кажуть люди після зйомки в «Просторі».' ),
			),
		),
		'blocks' => array(
			'title'  => 'Головна: сертифікат, друк, оренда, CTA',
			'fields' => array(
				'gift_title'        => array( 'label' => 'Сертифікат: заголовок', 'default' => 'Подаруйте не річ, а емоції' ),
				'gift_text'         => array( 'label' => 'Сертифікат: текст', 'default' => 'Подарунковий сертифікат на готову фотосесію. Студія, фотограф, світло й оброблені фото вже включені, людині лишається обрати дату.', 'type' => 'textarea' ),
				'gift_btn'          => array( 'label' => 'Сертифікат: кнопка', 'default' => 'Подарувати фотосесію' ),
				'gift_image'        => array( 'label' => 'Сертифікат: фото', 'type' => 'image' ),
				'print_title'       => array( 'label' => 'Друк: заголовок', 'default' => 'Фотографії, які можна тримати в руках' ),
				'print_text'        => array( 'label' => 'Друк: текст', 'default' => 'Друкуємо на якісному фотопапері. У пакети CLASSIC, FAMILY / LOVE STORY і PREMIUM друковані фото вже входять.', 'type' => 'textarea' ),
				'print_items'       => array( 'label' => 'Друк: список (кожен з нового рядка)', 'default' => "Фотодрук 10×15 і 15×20 см\nФотографії великого формату\nФотокартини 40×50 см", 'type' => 'textarea' ),
				'print_image'       => array( 'label' => 'Друк: фото', 'type' => 'image' ),
				'rent_title'        => array( 'label' => 'Оренда: заголовок', 'default' => 'Ви фотограф?' ),
				'rent_text'         => array( 'label' => 'Оренда: текст', 'default' => 'Орендуйте «Простір» для власної зйомки: дві знімальні зони, фони, імпульсне й постійне світло.', 'type' => 'textarea' ),
				'rent_price_label'  => array( 'label' => 'Оренда: підпис ціни', 'default' => 'Орієнтовна вартість' ),
				'rent_btn'          => array( 'label' => 'Оренда: кнопка', 'default' => 'Забронювати студію' ),
				'rent_image'        => array( 'label' => 'Оренда: фото', 'type' => 'image' ),
				'cta_title'         => array( 'label' => 'Фінальний CTA: заголовок', 'default' => 'Готові до вашої фотосесії?' ),
				'cta_text'          => array( 'label' => 'Фінальний CTA: текст', 'default' => 'Оберіть пакет або просто напишіть нам — допоможемо підібрати формат зйомки.', 'type' => 'textarea' ),
				'cta_btn_primary'   => array( 'label' => 'CTA: кнопка 1', 'default' => 'Записатися' ),
				'cta_btn_secondary' => array( 'label' => 'CTA: кнопка 2', 'default' => 'Написати в Instagram' ),
				'cta_image'         => array( 'label' => 'CTA: фонове фото', 'type' => 'image' ),
			),
		),
		'form' => array(
			'title'  => 'Форма запису',
			'fields' => array(
				'form_title'   => array( 'label' => 'Заголовок', 'default' => 'Записатися на фотосесію' ),
				'form_text'    => array( 'label' => 'Текст', 'default' => 'Залиште контакти. Ми зв’яжемося, щоб узгодити дату і час, і відповімо на всі питання.', 'type' => 'textarea' ),
				'form_btn'     => array( 'label' => 'Кнопка', 'default' => 'Надіслати заявку' ),
				'form_success' => array( 'label' => 'Повідомлення після відправлення', 'default' => 'Дякуємо! Ми отримали вашу заявку та зв’яжемося з вами для підтвердження дати і часу.', 'type' => 'textarea' ),
				'form_note'    => array( 'label' => 'Примітка під формою', 'default' => 'Без передоплати. Дату підтверджуємо після дзвінка.' ),
			),
		),
		'pages' => array(
			'title'  => 'Сторінки: підзаголовки й кнопки',
			'fields' => array(
				'photosession_lead' => array( 'label' => 'Фотосесії: підзаголовок', 'default' => 'Оберіть напрямок, подивіться приклади робіт і відповідні пакети. Усе інше організуємо ми.', 'type' => 'textarea' ),
				'rent_page_lead'    => array( 'label' => 'Оренда: підзаголовок', 'default' => 'Дві знімальні зони, фони, імпульсне й постійне світло, проєктор, дим і вітер. Для фотографів і відеографів.', 'type' => 'textarea' ),
				'rent_page_btn'     => array( 'label' => 'Оренда: кнопка', 'default' => 'Забронювати час' ),
				'gift_page_lead'    => array( 'label' => 'Сертифікат: підзаголовок', 'default' => 'Ви купуєте в подарунок готову фотосесію: студія, фотограф, світло та оброблені фото вже включені. Людина лише обирає дату.', 'type' => 'textarea' ),
				'gift_page_btn'     => array( 'label' => 'Сертифікат: кнопка в картці (префікс)', 'default' => 'Подарувати' ),
				'studio_page_lead'  => array( 'label' => 'Про студію: підзаголовок', 'default' => 'Світла студія в центрі Обухова, де ми організовуємо фотосесії під ключ: від ідеї й образів до готових оброблених фотографій.', 'type' => 'textarea' ),
				'contacts_lead'     => array( 'label' => 'Контакти: підзаголовок', 'default' => 'Телефонуйте, пишіть в Instagram або залишайте заявку. Відповідаємо щодня.', 'type' => 'textarea' ),
			),
		),
	);
}

/**
 * Значення за замовчуванням усіх полів.
 */
function prostir_customizer_defaults() {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = array();
		foreach ( prostir_customizer_config() as $section ) {
			foreach ( $section['fields'] as $key => $field ) {
				$defaults[ $key ] = $field['default'] ?? '';
			}
		}
	}
	return $defaults;
}

/**
 * Прочитати налаштування з урахуванням значення за замовчуванням.
 */
function prostir_opt( $key, $fallback = '' ) {
	$defaults = prostir_customizer_defaults();
	$value    = get_theme_mod( $key, $defaults[ $key ] ?? $fallback );
	return ( '' === $value || null === $value ) ? ( $defaults[ $key ] ?? $fallback ) : $value;
}

function prostir_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'prostir',
		array(
			'title'       => 'Простір: налаштування сайту',
			'description' => 'Контакти, тексти секцій головної та фото. Пакети, відгуки й портфоліо редагуються в меню «Простір».',
			'priority'    => 1,
		)
	);

	$priority = 10;
	foreach ( prostir_customizer_config() as $section_id => $section ) {
		$wp_customize->add_section( 'prostir_' . $section_id, array( 'title' => $section['title'], 'panel' => 'prostir', 'priority' => $priority++ ) );
		foreach ( $section['fields'] as $key => $field ) {
			$type = $field['type'] ?? 'text';
			$wp_customize->add_setting(
				$key,
				array(
					'default'           => $field['default'] ?? '',
					'type'              => 'theme_mod',
					'sanitize_callback' => prostir_customizer_sanitizer( $type ),
					'transport'         => 'refresh',
				)
			);
			if ( 'image' === $type ) {
				$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $key, array( 'label' => $field['label'], 'section' => 'prostir_' . $section_id, 'mime_type' => 'image' ) ) );
			} else {
				$wp_customize->add_control( $key, array( 'label' => $field['label'], 'section' => 'prostir_' . $section_id, 'type' => 'number' === $type ? 'number' : ( 'url' === $type ? 'url' : $type ) ) );
			}
		}
	}

	// Стандартні секції ховаємо за панель, щоб не плутати адміністратора.
	$wp_customize->get_section( 'title_tagline' )->priority = 2;
	$wp_customize->get_section( 'static_front_page' )->priority = 200;
}
add_action( 'customize_register', 'prostir_customize_register' );

function prostir_customizer_sanitizer( $type ) {
	switch ( $type ) {
		case 'textarea':
			return 'sanitize_textarea_field';
		case 'url':
			return 'prostir_sanitize_url_or_path';
		case 'number':
		case 'image':
			return 'absint';
		default:
			return 'sanitize_text_field';
	}
}

function prostir_sanitize_url_or_path( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( str_starts_with( $value, '/' ) || str_starts_with( $value, '#' ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value );
}
