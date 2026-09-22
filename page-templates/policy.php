<?php
/**
 * Template Name: Policy Page
 * Policy pages (shipping / privacy / warranty / returns / terms / about /
 * payment): content + auto CONTACT box fed by Customizer "Site Info".
 *
 * @package snstore
 */

get_header();

while ( have_posts() ) :
	the_post();
	$sn_eyebrow = get_post_meta( get_the_ID(), 'sn_eyebrow', true );
	?>
	<article <?php post_class( 'sn-policy' ); ?>>
		<div class="sn-pagehead">
			<div class="sn-container">
				<?php if ( $sn_eyebrow ) : ?>
					<p class="sn-eyebrow"><?php echo esc_html( $sn_eyebrow ); ?></p>
				<?php endif; ?>
				<h1 class="sn-pagehead-title"><?php the_title(); ?></h1>
			</div>
		</div>

		<div class="sn-container sn-policy-body">
			<div class="sn-prose">
				<?php the_content(); ?>
			</div>

			<?php
			// CONTACT box — matches the reference design: bordered panel with
			// uppercase labels, values from Customizer Site Info.
			if ( sn_email() || sn_address() || sn_phone() || sn_hours() ) :
				?>
				<div class="sn-contact-box-wrap">
					<h2 class="sn-contact-heading"><?php esc_html_e( 'Contact', 'snstore' ); ?></h2>
					<div class="sn-contact-box">
						<?php if ( sn_email() ) : ?>
							<div class="sn-contact-item">
								<p class="sn-contact-label"><?php esc_html_e( 'Email', 'snstore' ); ?></p>
								<p class="sn-contact-value"><a class="sn-mailto" href="mailto:<?php echo esc_attr( sn_email() ); ?>"><?php echo esc_html( sn_email() ); ?></a></p>
							</div>
						<?php endif; ?>
						<?php if ( sn_address() ) : ?>
							<div class="sn-contact-item">
								<p class="sn-contact-label"><?php esc_html_e( 'Address', 'snstore' ); ?></p>
								<p class="sn-contact-value"><?php echo esc_html( sn_address() ); ?></p>
							</div>
						<?php endif; ?>
						<?php if ( sn_phone() ) : ?>
							<div class="sn-contact-item">
								<p class="sn-contact-label"><?php esc_html_e( 'Phone', 'snstore' ); ?></p>
								<p class="sn-contact-value"><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', sn_phone() ) ); ?>"><?php echo esc_html( sn_phone() ); ?></a></p>
							</div>
						<?php endif; ?>
						<?php if ( sn_hours() ) : ?>
							<div class="sn-contact-item">
								<p class="sn-contact-label"><?php esc_html_e( 'Service hours', 'snstore' ); ?></p>
								<p class="sn-contact-value"><?php echo esc_html( sn_hours() ); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
