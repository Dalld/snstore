<?php
/**
 * Site info variables — the single data source for brand, domain and contact
 * details used across the header, footer, policy pages and contact page.
 *
 * Tokens available inside any editable text:
 *   {{brand}} {{domain}} {{site_url}} {{email}} {{phone}} {{address}} {{hours}} {{year}}
 *   {{product_image:N}} {{product_name:N}} {{product_link:N}} {{product_price:N}}
 *   (N = 1-based index into the newest published products, resolved live —
 *   posts written with these tokens always show the current store's products)
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Current site domain, e.g. example.com (taken from the site URL in the DB).
 *
 * @return string
 */
function sn_domain() {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	return $host ? preg_replace( '/^www\./i', '', $host ) : '';
}

/**
 * Brand display name.
 *
 * @return string
 */
function sn_brand() {
	$custom = trim( (string) get_theme_mod( 'sn_brand_name', '' ) );
	return $custom ? $custom : get_bloginfo( 'name' );
}

/**
 * Contact email. Falls back to info@<current domain>.
 *
 * @return string
 */
function sn_email() {
	$email = trim( (string) get_theme_mod( 'sn_contact_email', '' ) );
	if ( $email ) {
		return $email;
	}
	$domain = sn_domain();
	return $domain ? 'info@' . $domain : get_option( 'admin_email' );
}

/**
 * Contact phone.
 *
 * @return string
 */
function sn_phone() {
	return trim( (string) get_theme_mod( 'sn_contact_phone', '' ) );
}

/**
 * Contact address.
 *
 * @return string
 */
function sn_address() {
	return trim( (string) get_theme_mod( 'sn_contact_address', '' ) );
}

/**
 * Service hours.
 *
 * @return string
 */
function sn_hours() {
	return trim( (string) get_theme_mod( 'sn_contact_hours', '' ) );
}

/**
 * IDs of the newest published products that actually have a featured image
 * (stable order, cached per request). Tokens must always resolve to a real
 * product photo, even when the store also holds image-less products.
 *
 * @return int[]
 */
function sn_product_pool() {
	static $pool = null;
	if ( null === $pool ) {
		$pool = get_posts(
			array(
				'post_type'     => 'product',
				'post_status'   => 'publish',
				'numberposts'   => 24,
				'orderby'       => array(
					'date' => 'DESC',
					'ID'   => 'DESC',
				),
				'fields'        => 'ids',
				'no_found_rows' => true,
				'meta_query'    => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			)
		);
	}
	return $pool;
}

/**
 * Image URL for one product: featured image, else first gallery image,
 * else a bundled default so tokens never render broken images.
 *
 * @param WC_Product $product Product.
 * @param int        $n       1-based token index (picks the bundled fallback).
 * @return string
 */
function sn_product_image_url( $product, $n = 1 ) {
	$img_id = $product ? $product->get_image_id() : 0;
	if ( ! $img_id && $product ) {
		$gallery = $product->get_gallery_image_ids();
		$img_id  = $gallery ? (int) $gallery[0] : 0;
	}
	if ( $img_id ) {
		$url = wp_get_attachment_image_url( $img_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}
	return sn_hero_default_img( ( ( max( 1, $n ) - 1 ) % 3 ) + 1 );
}

/**
 * Resolve one dynamic {{product_*:N}} token against the newest products.
 *
 * @param string $part image|name|link|price.
 * @param int    $n    1-based index.
 * @return string
 */
function sn_product_token( $part, $n ) {
	$pool = sn_product_pool();
	if ( empty( $pool ) ) {
		return 'image' === $part ? sn_hero_default_img( ( ( max( 1, $n ) - 1 ) % 3 ) + 1 ) : '';
	}
	$id      = $pool[ ( max( 1, $n ) - 1 ) % count( $pool ) ];
	$product = function_exists( 'wc_get_product' ) ? wc_get_product( $id ) : null;

	switch ( $part ) {
		case 'image':
			return $product ? sn_product_image_url( $product, $n ) : '';
		case 'name':
			return get_the_title( $id );
		case 'link':
			return get_permalink( $id );
		case 'price':
			return ( $product ? wp_strip_all_tags( $product->get_price_html() ) : '' );
	}
	return '';
}

/**
 * Replace tokens in a string.
 *
 * @param string $text Raw text.
 * @return string
 */
function sn_replace_tokens( $text ) {
	if ( ! is_string( $text ) || false === strpos( $text, '{{' ) ) {
		return $text;
	}
	$map = array(
		'{{brand}}'   => sn_brand(),
		'{{domain}}'  => sn_domain(),
		'{{site_url}}'=> home_url( '/' ),
		'{{email}}'   => sn_email(),
		'{{phone}}'   => sn_phone(),
		'{{address}}' => sn_address(),
		'{{hours}}'   => sn_hours(),
		'{{year}}'    => gmdate( 'Y' ),
	);
	$text = str_replace( array_keys( $map ), array_values( $map ), $text );
	if ( false !== strpos( $text, '{{product_' ) ) {
		$text = preg_replace_callback(
			'/\{\{product_(image|name|link|price):(\d+)\}\}/',
			function ( $m ) {
				return sn_product_token( $m[1], (int) $m[2] );
			},
			$text
		);
	}
	return $text;
}

/**
 * Echo a setting with token replacement.
 *
 * @param string $mod     Theme mod name.
 * @param string $default Default value.
 */
function sn_e_mod( $mod, $default = '' ) {
	echo esc_html( sn_replace_tokens( get_theme_mod( $mod, $default ) ) );
}
