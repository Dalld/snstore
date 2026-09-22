<?php
/**
 * Footer: brand + contact info / Customer Service menu / Company menu /
 * social + payment icons + copyright. Contact details come from
 * Customizer "Site Info" — one source for the whole site.
 *
 * @package snstore
 */
?>
</main>

<footer class="sn-footer" role="contentinfo">
	<div class="sn-container">
		<div class="sn-footer-grid">

			<div class="sn-footer-brand">
				<p class="sn-footer-logo"><?php echo esc_html( sn_brand() ); ?></p>
				<p class="sn-footer-about"><?php sn_e_mod( 'sn_footer_about', 'Useful products, fair prices and friendly support — delivered to your door.' ); ?></p>

				<div class="sn-footer-contact">
					<?php if ( sn_email() ) : ?>
						<p>
							<?php sn_icon( 'mail', 16 ); ?>
							<a href="mailto:<?php echo esc_attr( sn_email() ); ?>"><?php echo esc_html( sn_email() ); ?></a>
						</p>
					<?php endif; ?>
					<?php if ( sn_phone() ) : ?>
						<p><?php sn_icon( 'phone', 16 ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', sn_phone() ) ); ?>"><?php echo esc_html( sn_phone() ); ?></a></p>
					<?php endif; ?>
					<?php if ( sn_address() ) : ?>
						<p><?php sn_icon( 'pin', 16 ); ?><span><?php echo esc_html( sn_address() ); ?></span></p>
					<?php endif; ?>
					<?php if ( sn_hours() ) : ?>
						<p><?php sn_icon( 'clock', 16 ); ?><span><?php echo esc_html( sn_hours() ); ?></span></p>
					<?php endif; ?>
				</div>

				<?php if ( sn_social_links() ) : ?>
					<div class="sn-social">
						<?php echo sn_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="sn-footer-col">
				<p class="sn-footer-title"><?php esc_html_e( 'Customer Service', 'snstore' ); ?></p>
				<?php sn_footer_menu( 'footer_service' ); ?>
			</div>

			<div class="sn-footer-col">
				<p class="sn-footer-title"><?php esc_html_e( 'Company', 'snstore' ); ?></p>
				<?php sn_footer_menu( 'footer_company' ); ?>
			</div>

		</div>

		<div class="sn-footer-bottom">
			<?php if ( sn_sanitize_checkbox( get_theme_mod( 'sn_footer_payments', true ) ) ) : ?>
				<?php sn_payment_badges(); ?>
			<?php endif; ?>
			<p class="sn-copyright"><?php sn_e_mod( 'sn_footer_copyright', '© {{year}} {{brand}}. All rights reserved.' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
