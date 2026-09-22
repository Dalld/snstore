<?php
/**
 * Template Name: Contact Page
 * Contact info (from Customizer Site Info) + message form.
 *
 * @package snstore
 */

get_header();

while ( have_posts() ) :
	the_post();
	$sn_eyebrow = get_post_meta( get_the_ID(), 'sn_eyebrow', true );
	$sn_sent    = isset( $_GET['sn-contact'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	?>
	<article <?php post_class( 'sn-contactpage' ); ?>>
		<div class="sn-pagehead">
			<div class="sn-container">
				<?php if ( $sn_eyebrow ) : ?>
					<p class="sn-eyebrow"><?php echo esc_html( $sn_eyebrow ); ?></p>
				<?php endif; ?>
				<h1 class="sn-pagehead-title"><?php the_title(); ?></h1>
			</div>
		</div>

		<div class="sn-container">
			<div class="sn-prose sn-contact-intro"><?php the_content(); ?></div>

			<div class="sn-contact-grid">
				<div class="sn-contact-card">
					<?php if ( sn_email() ) : ?>
						<div class="sn-contact-row">
							<?php sn_icon( 'mail', 18 ); ?>
							<div>
								<p class="sn-contact-label"><?php esc_html_e( 'Email', 'snstore' ); ?></p>
								<p class="sn-contact-value"><a class="sn-mailto" href="mailto:<?php echo esc_attr( sn_email() ); ?>"><?php echo esc_html( sn_email() ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( sn_phone() ) : ?>
						<div class="sn-contact-row">
							<?php sn_icon( 'phone', 18 ); ?>
							<div>
								<p class="sn-contact-label"><?php esc_html_e( 'Phone', 'snstore' ); ?></p>
								<p class="sn-contact-value"><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', sn_phone() ) ); ?>"><?php echo esc_html( sn_phone() ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( sn_address() ) : ?>
						<div class="sn-contact-row">
							<?php sn_icon( 'pin', 18 ); ?>
							<div>
								<p class="sn-contact-label"><?php esc_html_e( 'Address', 'snstore' ); ?></p>
								<p class="sn-contact-value"><?php echo esc_html( sn_address() ); ?></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( sn_hours() ) : ?>
						<div class="sn-contact-row">
							<?php sn_icon( 'clock', 18 ); ?>
							<div>
								<p class="sn-contact-label"><?php esc_html_e( 'Service hours', 'snstore' ); ?></p>
								<p class="sn-contact-value"><?php echo esc_html( sn_hours() ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="sn-contact-formwrap" id="sn-contact-form">
					<?php if ( $sn_sent ) : ?>
						<?php if ( '1' === sanitize_text_field( wp_unslash( $_GET['sn-contact'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
							<p class="sn-form-ok"><?php esc_html_e( 'Thanks — your message has been sent. We usually reply within one business day.', 'snstore' ); ?></p>
						<?php else : ?>
							<p class="sn-form-err"><?php esc_html_e( 'Please fill in your name, a valid email and a message.', 'snstore' ); ?></p>
						<?php endif; ?>
					<?php endif; ?>

					<form class="sn-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="sn_contact" />
						<div class="sn-form-grid">
							<p>
								<label for="sn-cf-name"><?php esc_html_e( 'Name *', 'snstore' ); ?></label>
								<input id="sn-cf-name" type="text" name="sn_name" required />
							</p>
							<p>
								<label for="sn-cf-email"><?php esc_html_e( 'Email *', 'snstore' ); ?></label>
								<input id="sn-cf-email" type="email" name="sn_from" required />
							</p>
						</div>
						<p>
							<label for="sn-cf-order"><?php esc_html_e( 'Order number (optional)', 'snstore' ); ?></label>
							<input id="sn-cf-order" type="text" name="sn_order" />
						</p>
						<p>
							<label for="sn-cf-msg"><?php esc_html_e( 'How can we help? *', 'snstore' ); ?></label>
							<textarea id="sn-cf-msg" name="sn_message" rows="6" required></textarea>
						</p>
						<button type="submit" class="sn-btn sn-btn--dark sn-btn--wide"><?php esc_html_e( 'Send message', 'snstore' ); ?></button>
					</form>
				</div>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
