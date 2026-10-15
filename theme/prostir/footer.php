</main>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__brand">
			<a class="brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="brand__name">Простір</span><span class="brand__city"><?php echo esc_html( prostir_opt( 'city' ) ); ?></span></a>
			<p class="site-footer__text"><?php echo esc_html( prostir_opt( 'footer_text' ) ); ?></p>
		</div>
		<nav class="site-footer__nav" aria-label="Меню у футері">
			<p class="site-footer__label">Розділи</p>
			<?php prostir_nav( 'footer', 'site-footer__list' ); ?>
		</nav>
		<div class="site-footer__contacts">
			<p class="site-footer__label">Контакти</p>
			<ul class="site-footer__list">
				<li><a href="<?php echo esc_attr( prostir_phone_href() ); ?>"><?php echo esc_html( prostir_opt( 'phone' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( prostir_opt( 'instagram_handle' ) ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( prostir_opt( 'email' ) ); ?>"><?php echo esc_html( prostir_opt( 'email' ) ); ?></a></li>
				<li><?php echo esc_html( prostir_opt( 'address' ) ); ?></li>
				<?php foreach ( prostir_lines( prostir_opt( 'hours' ) ) as $line ) : ?>
					<li class="site-footer__muted"><?php echo esc_html( $line ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="container site-footer__bottom">
		<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( prostir_opt( 'studio_name' ) ); ?>, <?php echo esc_html( prostir_opt( 'city' ) ); ?></p>
		<p class="site-footer__muted">Фото в демо: Pexels, ліцензія вільного використання.</p>
	</div>
</footer>

<div class="mobile-bar" aria-label="Швидкі дії">
	<a class="mobile-bar__call" href="<?php echo esc_attr( prostir_phone_href() ); ?>" aria-label="Зателефонувати"><?php echo prostir_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	<a class="mobile-bar__ig" href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener" aria-label="Instagram"><?php echo prostir_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
	<a class="btn btn--light mobile-bar__cta" href="<?php echo esc_url( prostir_booking_url() ); ?>"><span>Записатися</span></a>
</div>

<dialog class="lightbox" id="lightbox" aria-label="Перегляд фото">
	<button class="lightbox__close" type="button" data-lightbox-close aria-label="Закрити"><?php echo prostir_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	<button class="lightbox__nav lightbox__nav--prev" type="button" data-lightbox-prev aria-label="Попереднє"><?php echo prostir_icon( 'chevron-left' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
	<figure class="lightbox__figure"><img class="lightbox__img" src="" alt=""><figcaption class="lightbox__caption"></figcaption></figure>
	<button class="lightbox__nav lightbox__nav--next" type="button" data-lightbox-next aria-label="Наступне"><?php echo prostir_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
</dialog>

<?php wp_footer(); ?>
</body>
</html>
