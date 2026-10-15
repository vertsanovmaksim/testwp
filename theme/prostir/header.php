<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#121212">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Перейти до контенту</a>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( prostir_opt( 'studio_name' ) . ', ' . prostir_opt( 'city' ) ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="brand__name">Простір</span><span class="brand__city"><?php echo esc_html( prostir_opt( 'city' ) ); ?></span>
			<?php endif; ?>
		</a>

		<nav class="site-nav" aria-label="Головне меню">
			<?php prostir_nav( 'primary', 'site-nav__list' ); ?>
		</nav>

		<div class="site-header__actions">
			<a class="site-header__phone" href="<?php echo esc_attr( prostir_phone_href() ); ?>">
				<?php echo prostir_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span><?php echo esc_html( prostir_opt( 'phone' ) ); ?></span>
			</a>
			<?php echo prostir_button( 'Записатися', prostir_booking_url(), array( 'class' => 'site-header__cta', 'size' => 'sm' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu">
				<?php echo prostir_icon( 'menu', 'nav-toggle__open' ) . prostir_icon( 'close', 'nav-toggle__close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="visually-hidden">Меню</span>
			</button>
		</div>
	</div>

	<div class="mobile-menu" id="mobile-menu" hidden>
		<div class="container mobile-menu__inner">
			<nav aria-label="Мобільне меню">
				<?php prostir_nav( 'primary', 'mobile-menu__list' ); ?>
			</nav>
			<div class="mobile-menu__contacts">
				<a href="<?php echo esc_attr( prostir_phone_href() ); ?>"><?php echo prostir_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( prostir_opt( 'phone' ) ); ?></a>
				<a href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo prostir_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( prostir_opt( 'instagram_handle' ) ); ?></a>
				<p class="mobile-menu__address"><?php echo esc_html( prostir_opt( 'address' ) ); ?></p>
			</div>
			<?php echo prostir_button( 'Записатися на фотосесію', prostir_booking_url(), array( 'style' => 'light', 'size' => 'lg', 'class' => 'mobile-menu__cta' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</header>

<main id="main" class="site-main">
