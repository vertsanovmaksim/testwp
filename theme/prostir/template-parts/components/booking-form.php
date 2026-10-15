<?php
/**
 * Форма запису. Використовується на головній, контактах і сторінках послуг.
 *
 * @package Prostir
 */

$prostir_options  = prostir_form_options();
$prostir_selected = isset( $_GET['package'] ) ? sanitize_text_field( wp_unslash( $_GET['package'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$prostir_sent     = isset( $_GET['sent'] ); // phpcs:ignore WordPress.Security.NonceVerification
$prostir_error    = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( $_GET['error'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
$prostir_current  = home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
?>
<section class="section booking" id="booking">
	<div class="container booking__inner">
		<div class="booking__intro reveal">
			<p class="eyebrow">Запис</p>
			<h2 class="section-title"><?php echo esc_html( prostir_opt( 'form_title' ) ); ?></h2>
			<p class="section-text"><?php echo esc_html( prostir_opt( 'form_text' ) ); ?></p>
			<ul class="booking__contacts">
				<li><a href="<?php echo esc_attr( prostir_phone_href() ); ?>"><?php echo prostir_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( prostir_opt( 'phone' ) ); ?></span></a></li>
				<li><a href="<?php echo esc_url( prostir_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo prostir_icon( 'instagram' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( prostir_opt( 'instagram_handle' ) ); ?></span></a></li>
				<li><?php echo prostir_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( prostir_opt( 'address' ) ); ?></span></li>
			</ul>
		</div>

		<form class="booking-form reveal" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-endpoint="<?php echo esc_url( rest_url( 'prostir/v1/lead' ) ); ?>">
			<input type="hidden" name="action" value="prostir_lead">
			<input type="hidden" name="source" value="<?php echo esc_url( $prostir_current ); ?>">
			<p class="booking-form__hp" aria-hidden="true"><label>Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>

			<div class="booking-form__fields"<?php echo $prostir_sent ? ' hidden' : ''; ?>>
				<div class="field">
					<label class="field__label" for="lead-name">Ім’я</label>
					<input class="field__input" id="lead-name" name="name" type="text" autocomplete="name" required placeholder="Як до вас звертатися">
				</div>
				<div class="field">
					<label class="field__label" for="lead-phone">Телефон</label>
					<input class="field__input" id="lead-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required placeholder="+380">
				</div>
				<div class="field field--full">
					<label class="field__label" for="lead-package">Яка фотосесія вас цікавить?</label>
					<div class="field__select">
						<select class="field__input" id="lead-package" name="package">
							<?php foreach ( $prostir_options as $prostir_value => $prostir_label ) : ?>
								<option value="<?php echo esc_attr( $prostir_value ); ?>"<?php selected( $prostir_selected, $prostir_value ); ?>><?php echo esc_html( $prostir_label ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php echo prostir_icon( 'arrow-down' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				</div>
				<div class="field">
					<label class="field__label" for="lead-date">Бажана дата</label>
					<input class="field__input" id="lead-date" name="date" type="date" min="<?php echo esc_attr( gmdate( 'Y-m-d' ) ); ?>">
				</div>
				<div class="field">
					<label class="field__label" for="lead-comment">Коментар</label>
					<input class="field__input" id="lead-comment" name="comment" type="text" placeholder="Скільки людей, ідеї, питання">
				</div>
				<div class="field field--full booking-form__submit">
					<button class="btn btn--primary btn--lg" type="submit"><span><?php echo esc_html( prostir_opt( 'form_btn' ) ); ?></span><?php echo prostir_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
					<p class="booking-form__note"><?php echo esc_html( prostir_opt( 'form_note' ) ); ?></p>
				</div>
				<p class="booking-form__error" role="alert"<?php echo $prostir_error ? '' : ' hidden'; ?>><?php echo esc_html( $prostir_error ); ?></p>
			</div>

			<div class="booking-form__success" role="status"<?php echo $prostir_sent ? '' : ' hidden'; ?>>
				<?php echo prostir_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<p><?php echo esc_html( prostir_opt( 'form_success' ) ); ?></p>
			</div>
		</form>
	</div>
</section>
