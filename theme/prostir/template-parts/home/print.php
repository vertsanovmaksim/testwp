<?php
/**
 * Друк фотографій.
 *
 * @package Prostir
 */

$prostir_print_items = prostir_lines( prostir_opt( 'print_items' ) );
?>
<section class="section split split--reverse print" id="print">
	<div class="container split__inner">
		<div class="split__media reveal">
			<?php echo prostir_image( (int) prostir_opt( 'print_image' ), 'large', array( 'sizes' => '(min-width: 1024px) 50vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
		<div class="split__body reveal">
			<?php echo prostir_section_heading( prostir_opt( 'print_title' ), array( 'eyebrow' => 'Друк фотографій', 'text' => prostir_opt( 'print_text' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php if ( $prostir_print_items ) : ?>
				<ul class="check-list">
					<?php foreach ( $prostir_print_items as $prostir_item ) : ?>
						<li><?php echo prostir_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $prostir_item ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
