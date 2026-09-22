<?php
/**
 * SN Store theme bootstrap — universal Storefront child theme.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

define( 'SNSTORE_VERSION', '1.1.15' );

require_once get_stylesheet_directory() . '/inc/sn-site-info.php';
require_once get_stylesheet_directory() . '/inc/sn-seo.php';
require_once get_stylesheet_directory() . '/inc/sn-template-tags.php';
require_once get_stylesheet_directory() . '/inc/sn-policy-content.php';
require_once get_stylesheet_directory() . '/inc/sn-customizer.php';
require_once get_stylesheet_directory() . '/inc/sn-install.php';

/**
 * Asset URL with hard cache-busting: some CDNs ignore query strings, so when
 * a versioned COPY of the file ships (snstore-{version}.css/js) it is served
 * under a new path; otherwise the canonical file + ?ver= is used.
 *
 * @param string $rel Path relative to the theme root, e.g. /assets/css/snstore.css.
 * @return string
 */
function sn_asset_url( $rel ) {
	$file = get_stylesheet_directory() . $rel;
	$info = pathinfo( $file );
	$versioned = $info['dirname'] . '/' . $info['filename'] . '-' . SNSTORE_VERSION . '.' . $info['extension'];
	$url = file_exists( $versioned )
		? dirname( $rel ) . '/' . $info['filename'] . '-' . SNSTORE_VERSION . '.' . $info['extension']
		: $rel;
	return esc_url( get_stylesheet_directory_uri() . $url );
}

/**
 * Front-end assets.
 */
function sn_enqueue_assets() {
	wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', array(), SNSTORE_VERSION );
	wp_enqueue_style( 'snstore-style', sn_asset_url( '/assets/css/snstore.css' ), array( 'storefront-style' ), SNSTORE_VERSION );
	wp_enqueue_script( 'snstore-script', sn_asset_url( '/assets/js/snstore.js' ), array(), SNSTORE_VERSION, true );

	if ( is_product() ) {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}
}
add_action( 'wp_enqueue_scripts', 'sn_enqueue_assets' );

/**
 * Theme supports.
 */
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'customize-selective-refresh-widgets' );

/**
 * Menu locations. Footer columns map to the menus created on activation.
 * Registered on after_setup_theme so translation calls load at the right time.
 */
add_action(
	'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'primary'        => __( 'Main Menu', 'snstore' ),
				'footer_service' => __( 'Footer — Customer Service', 'snstore' ),
				'footer_company' => __( 'Footer — Company', 'snstore' ),
			)
		);
	}
);
add_theme_support(
	'html5',
	array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
);
add_theme_support(
	'custom-logo',
	array(
		'height'      => 80,
		'width'       => 280,
		'flex-height' => true,
		'flex-width'  => true,
	)
);
add_theme_support(
	'woocommerce',
	array(
		'thumbnail_image_width' => 700,
		'single_image_width'    => 1200,
		'product_grid'          => array(
			'default_rows'    => 4,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	)
);
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

/**
 * Catalog size.
 */
function sn_loop_per_page() {
	return 48;
}
add_filter( 'loop_shop_per_page', 'sn_loop_per_page', 20 );

/**
 * Strip WooCommerce / Storefront layout wrappers so custom templates control markup.
 */
add_action(
	'wp_loaded',
	function () {
		if ( is_admin() ) {
			return;
		}
		$tags = array(
			'woocommerce_before_main_content',
			'woocommerce_after_main_content',
			'woocommerce_sidebar',
			'woocommerce_show_page_title',
			'storefront_before_content',
			'storefront_after_content',
		);
		foreach ( $tags as $tag ) {
			if ( isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
				foreach ( array_keys( $GLOBALS['wp_filter'][ $tag ]->callbacks ) as $pri ) {
					remove_all_filters( $tag, $pri );
				}
			}
		}

		// The shop archive template renders its own toolbar, grid and pagination —
		// strip every Woo/Storefront hook from the loop chrome (sorting wrapper
		// divs, native count/ordering/pagination) so nothing leaks in or doubles up.
		foreach ( array( 'woocommerce_before_shop_loop', 'woocommerce_after_shop_loop' ) as $tag ) {
			remove_all_actions( $tag );
		}

		// Storefront's prev/next product thumbs hang off the viewport edges —
		// off-brand for this template, so they are removed.
		remove_action( 'woocommerce_after_single_product_summary', 'storefront_single_product_pagination', 30 );
		remove_action( 'woocommerce_after_single_product', 'storefront_product_pagination', 30 );
	}
);

/**
 * Related products: 4 items in 4 columns.
 */
add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns']        = 4;
		return $args;
	}
);

/**
 * Minimal content wrapper for pages without a custom template (cart/checkout/account).
 */
add_action(
	'woocommerce_before_main_content',
	function () {
		echo '<div class="sn-page"><div class="sn-container sn-plain-page">';
	},
	10
);
add_action(
	'woocommerce_after_main_content',
	function () {
		echo '</div></div>';
	},
	10
);

/**
 * Disable Storefront default homepage logic and breadcrumbs — templates render their own.
 * The shop toolbar renders its own count + ordering, so drop the native duplicates.
 */
add_action(
	'init',
	function () {
		remove_action( 'homepage', 'storefront_homepage_content', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}
);

/**
 * Cart badge fragment refresh.
 */
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		ob_start();
		sn_cart_count_badge();
		$fragments['span.sn-cart-count'] = ob_get_clean();
		return $fragments;
	}
);

/**
 * Buy Now: add to cart from the native product form, then go straight to checkout.
 * Fires on wp_loaded; Woo's own handler ignores these submits (no add-to-cart param).
 */
function sn_buy_now_handler() {
	if ( empty( $_REQUEST['sn_buy_now'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}
	$product_id = absint( wp_unslash( $_REQUEST['sn_buy_now'] ) );
	if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
		return;
	}
	$quantity   = isset( $_REQUEST['quantity'] ) ? wc_stock_amount( wp_unslash( $_REQUEST['quantity'] ) ) : 1;
	$quantity   = $quantity > 0 ? $quantity : 1;
	$variation  = 0;
	$attributes = array();
	if ( isset( $_REQUEST['variation_id'] ) ) {
		$variation = absint( wp_unslash( $_REQUEST['variation_id'] ) );
	}
	foreach ( wp_unslash( $_REQUEST ) as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( is_string( $key ) && 0 === strpos( $key, 'attribute_' ) && '' !== $value ) {
			$attributes[ sanitize_title( $key ) ] = sanitize_text_field( $value );
		}
	}
	WC()->cart->add_to_cart( $product_id, $quantity, $variation, $attributes );
	wp_safe_redirect( wc_get_checkout_url() );
	exit;
}
add_action( 'wp_loaded', 'sn_buy_now_handler', 20 );

/**
 * Variable products submit the native add-to-cart field, so Woo's own handler
 * runs first — force its redirect to checkout whenever Buy Now was clicked.
 */
add_filter(
	'woocommerce_add_to_cart_redirect',
	function ( $url ) {
		if ( ! empty( $_REQUEST['sn_buy_now'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return wc_get_checkout_url();
		}
		return $url;
	}
);

/**
 * Newsletter subscribe handler (stores emails in options, no external service).
 */
function sn_subscribe_handler() {
	$email = isset( $_POST['sn_email'] ) ? sanitize_email( wp_unslash( $_POST['sn_email'] ) ) : '';
	if ( $email && is_email( $email ) ) {
		$list   = get_option( 'sn_newsletter_list', array() );
		$list   = is_array( $list ) ? $list : array();
		$list[] = array(
			'email' => $email,
			'time'  => time(),
		);
		update_option( 'sn_newsletter_list', $list, false );
	}
	wp_safe_redirect( home_url( add_query_arg( 'sn-subscribed', '1', '/' ) ) );
	exit;
}
add_action( 'admin_post_sn_subscribe', 'sn_subscribe_handler' );
add_action( 'admin_post_nopriv_sn_subscribe', 'sn_subscribe_handler' );

/**
 * Contact form handler: emails the Site Info address (falls back to admin email).
 */
function sn_contact_handler() {
	$name    = isset( $_POST['sn_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sn_name'] ) ) : '';
	$email   = isset( $_POST['sn_from'] ) ? sanitize_email( wp_unslash( $_POST['sn_from'] ) ) : '';
	$order   = isset( $_POST['sn_order'] ) ? sanitize_text_field( wp_unslash( $_POST['sn_order'] ) ) : '';
	$message = isset( $_POST['sn_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sn_message'] ) ) : '';
	$ok      = $name && is_email( $email ) && $message;

	if ( $ok ) {
		$to   = sn_email();
		$to   = $to ? $to : get_option( 'admin_email' );
		$body = sprintf( "Name: %s\nEmail: %s\nOrder: %s\n\n%s", $name, $email, $order ? $order : '-', $message );
		wp_mail( $to, sprintf( '[%s] New message from %s', get_bloginfo( 'name' ), $name ), $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
	}
	$back = isset( $_POST['_wp_http_referer'] ) ? wp_unslash( $_POST['_wp_http_referer'] ) : home_url( '/' );
	wp_safe_redirect( add_query_arg( 'sn-contact', $ok ? '1' : '0', home_url( strtok( $back, '?' ) ) ) . '#sn-contact-form' );
	exit;
}
add_action( 'admin_post_sn_contact', 'sn_contact_handler' );
add_action( 'admin_post_nopriv_sn_contact', 'sn_contact_handler' );

/**
 * Replace {{tokens}} in content so policy text always shows current site info.
 */
add_filter( 'the_content', 'sn_replace_tokens', 20 );

/**
 * Trim head noise.
 */
add_filter( 'oembed_discovery_links', '__return_empty_string', 10 );
add_action(
	'wp_head',
	function () {
		wp_deregister_script( 'wp-embed' );
	},
	100
);
