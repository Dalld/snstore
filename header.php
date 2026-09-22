<?php
/**
 * Site header: announcement bar + sticky nav + search overlay + mobile drawer.
 *
 * @package snstore
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="preload" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/fonts/InterVariable.woff2' ); ?>" as="font" type="font/woff2" crossorigin />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#sn-main"><?php esc_html_e( 'Skip to content', 'snstore' ); ?></a>

<div class="sn-header-wrapper">
	<?php
	$sn_announce = trim( (string) sn_replace_tokens( get_theme_mod( 'sn_announcement_text', 'Free Shipping And Free Returns' ) ) );
	if ( '' !== $sn_announce ) :
		?>
		<div class="sn-announce">
			<span class="sn-announce-text"><?php echo esc_html( $sn_announce ); ?></span>
		</div>
	<?php endif; ?>

	<header class="sn-header" role="banner">
		<div class="sn-container sn-header-row">
			<button id="sn-burger" class="sn-iconbtn sn-burger" type="button" aria-expanded="false" aria-controls="sn-drawer" aria-label="<?php esc_attr_e( 'Open menu', 'snstore' ); ?>">
				<?php sn_icon( 'burger', 22 ); ?>
			</button>

			<div class="sn-brand">
				<?php sn_logo(); ?>
			</div>

			<nav class="sn-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'snstore' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'sn-nav-list',
							'depth'          => 2,
						)
					);
				} else {
					echo '<ul class="sn-nav-list">';
					echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'snstore' ) . '</a></li>';
					if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
						echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop All', 'snstore' ) . '</a></li>';
					}
					$sn_about = get_page_by_path( 'about-us' );
					if ( $sn_about instanceof WP_Post ) {
						echo '<li><a href="' . esc_url( get_permalink( $sn_about ) ) . '">' . esc_html__( 'About Us', 'snstore' ) . '</a></li>';
					}
					$sn_contact = get_page_by_path( 'contact-us' );
					if ( $sn_contact instanceof WP_Post ) {
						echo '<li><a href="' . esc_url( get_permalink( $sn_contact ) ) . '">' . esc_html__( 'Contact Us', 'snstore' ) . '</a></li>';
					}
					echo '</ul>';
				}
				?>
			</nav>

			<div class="sn-actions">
				<button class="sn-iconbtn" id="sn-search-toggle" type="button" aria-expanded="false" aria-controls="sn-search-panel" aria-label="<?php esc_attr_e( 'Search', 'snstore' ); ?>">
					<?php sn_icon( 'search', 21 ); ?>
				</button>
				<?php if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'myaccount' ) > 0 ) : ?>
					<a class="sn-iconbtn sn-iconbtn--link" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" aria-label="<?php esc_attr_e( 'My account', 'snstore' ); ?>">
						<?php sn_icon( 'user', 21 ); ?>
					</a>
				<?php endif; ?>
				<?php if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'cart' ) > 0 ) : ?>
					<a class="sn-iconbtn sn-iconbtn--link sn-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'snstore' ); ?>">
						<?php sn_icon( 'cart', 21 ); ?>
						<?php sn_cart_count_badge(); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div id="sn-search-panel" class="sn-search-panel" hidden>
			<div class="sn-container">
				<?php get_search_form(); ?>
				<p class="sn-search-hint"><?php esc_html_e( 'Search products, articles and pages — press Enter to search.', 'snstore' ); ?></p>
			</div>
		</div>
	</header>
</div>

<div id="sn-drawer-overlay" hidden></div>
<aside id="sn-drawer" class="sn-drawer" hidden aria-hidden="true" aria-label="<?php esc_attr_e( 'Mobile menu', 'snstore' ); ?>">
	<div class="sn-drawer-head">
		<span class="sn-drawer-brand"><?php echo esc_html( sn_brand() ); ?></span>
		<button id="sn-drawer-close" class="sn-iconbtn" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'snstore' ); ?>">
			<?php sn_icon( 'close', 20 ); ?>
		</button>
	</div>

	<form role="search" method="get" class="sn-drawer-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php sn_icon( 'search', 17 ); ?>
		<input type="search" name="s" placeholder="<?php esc_attr_e( 'Search products…', 'snstore' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'snstore' ); ?>" />
	</form>

	<nav class="sn-drawer-nav" aria-label="<?php esc_attr_e( 'Mobile menu', 'snstore' ); ?>">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'sn-drawer-list',
					'depth'          => 2,
				)
			);
		}
		?>
	</nav>

	<div class="sn-drawer-foot">
		<?php
		sn_footer_menu( 'footer_service' );
		sn_footer_menu( 'footer_company' );
		?>
	</div>
</aside>

<main id="sn-main" class="sn-main">
