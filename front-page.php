<?php
/**
 * Homepage: hero carousel + service bar + six configurable sections.
 *
 * @package snstore
 */

get_header();

$sn_slides = sn_hero_slides();
$sn_height = get_theme_mod( 'sn_hero_height', 'medium' );

if ( $sn_slides ) :
	?>
	<section class="sn-hero sn-hero--<?php echo esc_attr( $sn_height ); ?>" id="sn-hero" aria-roledescription="carousel">
		<div class="sn-hero-track">
			<?php
			$sn_idx = 0;
			foreach ( $sn_slides as $sn_slide ) :
				?>
				<div class="sn-hero-slide<?php echo 0 === $sn_idx ? ' is-active' : ''; ?>"<?php echo $sn_slide['img'] ? ' style="background-image:url(' . esc_url( $sn_slide['img'] ) . ')"' : ''; ?>>
					<div class="sn-hero-shade" aria-hidden="true"></div>
					<div class="sn-container sn-hero-content">
						<?php if ( $sn_slide['title'] ) : ?>
							<h2 class="sn-hero-title"><?php echo esc_html( $sn_slide['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( $sn_slide['sub'] ) : ?>
							<p class="sn-hero-sub"><?php echo esc_html( $sn_slide['sub'] ); ?></p>
						<?php endif; ?>
						<?php
						if ( $sn_slide['btn_text'] ) :
							$sn_btn_url = $sn_slide['btn_url'] ? $sn_slide['btn_url'] : ( function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' ) );
							?>
							<a class="sn-btn sn-btn--light" href="<?php echo esc_url( $sn_btn_url ); ?>"><?php echo esc_html( $sn_slide['btn_text'] ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<?php
				$sn_idx++;
			endforeach;
			?>
		</div>

		<?php if ( count( $sn_slides ) > 1 ) : ?>
			<div class="sn-hero-nav">
				<button class="sn-hero-arrow sn-hero-arrow--prev" type="button" data-hero="-1" aria-label="<?php esc_attr_e( 'Previous slide', 'snstore' ); ?>"><?php sn_icon( 'chevron', 20 ); ?></button>
				<div class="sn-hero-dots">
					<?php foreach ( $sn_slides as $sn_i => $sn_slide ) : ?>
						<button class="sn-hero-dot<?php echo 0 === $sn_i ? ' is-active' : ''; ?>" type="button" data-dot="<?php echo (int) $sn_i; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'snstore' ), $sn_i + 1 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
				<button class="sn-hero-arrow sn-hero-arrow--next" type="button" data-hero="1" aria-label="<?php esc_attr_e( 'Next slide', 'snstore' ); ?>"><?php sn_icon( 'chevron', 20 ); ?></button>
			</div>
		<?php endif; ?>
	</section>
	<?php
endif;

$sn_usps = sn_usp_items();
if ( $sn_usps ) :
	?>
	<section class="sn-uspbar" aria-label="<?php esc_attr_e( 'Our services', 'snstore' ); ?>">
		<div class="sn-container">
			<ul class="sn-usp-list">
				<?php foreach ( $sn_usps as $sn_usp ) : ?>
					<li class="sn-usp">
						<?php sn_icon( $sn_usp['icon'], 22 ); ?>
						<span><?php echo esc_html( $sn_usp['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endif;

for ( $sn_i = 1; $sn_i <= 6; $sn_i++ ) {
	sn_render_slot( $sn_i );
}

get_footer();
