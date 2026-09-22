<?php
/**
 * Light SEO layer.
 *
 * - Meta description + Open Graph/Twitter tags: only when no SEO plugin is
 *   active (a plugin owns those tags and duplicating them is harmful).
 * - Structured data: WooCommerce's Product schema is regenerated for this
 *   theme's custom templates (the theme strips the default Woo hooks that
 *   normally trigger it), plus WebSite/Organization and BreadcrumbList JSON-LD.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * True when a dedicated SEO plugin manages meta tags for us.
 *
 * @return bool
 */
function sn_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )         // Yoast.
		|| defined( 'RANK_MATH_VERSION' )     // Rank Math.
		|| defined( 'AIOSEO_VERSION' )        // All in One SEO.
		|| defined( 'SEOPRESS_VERSION' );     // SEOPress.
}

/**
 * Meta description text for the current view (~160 chars).
 *
 * @return string
 */
function sn_seo_description() {
	if ( is_front_page() ) {
		$desc = get_bloginfo( 'description' );
		if ( '' !== trim( $desc ) ) {
			return $desc;
		}
		$brand = function_exists( 'sn_brand' ) ? sn_brand() : get_bloginfo( 'name' );
		/* translators: %s: brand name */
		return sprintf( __( 'Shop %s online — quality products, fair prices and friendly support.', 'snstore' ), $brand );
	}
	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( '' === trim( $excerpt ) ) {
			$excerpt = get_the_title();
		}
		return wp_html_excerpt( wp_strip_all_tags( $excerpt ), 165, '…' );
	}
	if ( is_product_category() || is_product_tag() || is_category() || is_tag() ) {
		$desc = term_description();
		if ( $desc && '' !== trim( wp_strip_all_tags( $desc ) ) ) {
			return wp_html_excerpt( wp_strip_all_tags( $desc ), 165, '…' );
		}
		return get_bloginfo( 'description' );
	}
	if ( function_exists( 'wc_get_page_id' ) && is_shop() ) {
		$excerpt = get_the_excerpt( wc_get_page_id( 'shop' ) );
		if ( $excerpt && '' !== trim( $excerpt ) ) {
			return wp_html_excerpt( wp_strip_all_tags( $excerpt ), 165, '…' );
		}
	}
	return get_bloginfo( 'description' );
}

/**
 * Share image URL for the current view.
 *
 * @return string
 */
function sn_seo_image() {
	if ( is_singular() ) {
		if ( has_post_thumbnail() ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			if ( $img ) {
				return $img[0];
			}
		}
		if ( is_product() && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( get_the_ID() );
			if ( $product && $product->get_image_id() ) {
				$img = wp_get_attachment_image_src( $product->get_image_id(), 'large' );
				if ( $img ) {
					return $img[0];
				}
			}
		}
	}
	$icon = get_site_icon_url();
	return $icon;
}

/**
 * Meta description tag.
 */
function sn_seo_meta_description() {
	if ( sn_seo_plugin_active() ) {
		return;
	}
	$desc = trim( (string) sn_seo_description() );
	if ( '' === $desc ) {
		return;
	}
	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $desc ) );
}

/**
 * Open Graph + Twitter card tags.
 */
function sn_seo_og() {
	if ( sn_seo_plugin_active() ) {
		return;
	}
	$desc = trim( (string) sn_seo_description() );
	$type = is_front_page() ? 'website' : ( is_singular( 'product' ) ? 'product' : 'article' );
	$url  = is_front_page() ? home_url( '/' ) : get_permalink();

	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( wp_get_document_title() ) );
	if ( '' !== $desc ) {
		printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $desc ) );
	}
	if ( $url ) {
		printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
	}
	$image = sn_seo_image();
	if ( $image ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image ) );
		printf( '<meta name="twitter:card" content="summary_large_image" />' . "\n" );
		printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $image ) );
	} else {
		printf( '<meta name="twitter:card" content="summary" />' . "\n" );
	}
}

/**
 * WooCommerce structured data that this theme's hook-stripping removed:
 * WebSite (all views) and BreadcrumbList (Woo views).
 */
function sn_seo_woo_structured_data() {
	if ( ! function_exists( 'WC' ) || ! isset( WC()->structured_data ) ) {
		return;
	}
	WC()->structured_data->generate_website_data();
	if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_shop() || is_product_category() || is_product_tag() ) && class_exists( 'WC_Breadcrumb' ) ) {
		$sn_breadcrumbs = new WC_Breadcrumb();
		$sn_breadcrumbs->generate();
		WC()->structured_data->generate_breadcrumblist_data( $sn_breadcrumbs );
	}
}

/**
 * WebSite + Organization JSON-LD on the front page. (Woo's own website data
 * only outputs when the shop page IS the front page, so emit it here.)
 */
function sn_seo_json_ld() {
	if ( ! is_front_page() ) {
		return;
	}
	$ld = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type'       => 'Organization',
				'@id'         => home_url( '/#organization' ),
				'name'        => function_exists( 'sn_brand' ) ? sn_brand() : get_bloginfo( 'name' ),
				'url'         => home_url( '/' ),
				'description' => sn_seo_description(),
			),
			array(
				'@type'     => 'WebSite',
				'@id'       => home_url( '/#website' ),
				'url'       => home_url( '/' ),
				'name'      => get_bloginfo( 'name' ),
				'publisher' => array( '@id' => home_url( '/#organization' ) ),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => array(
						'@type'       => 'EntryPoint',
						'urlTemplate' => home_url( '/?s={search_term_string}' ),
					),
					'query-input' => 'required name=search_term_string',
				),
			),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $ld, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}

add_action( 'wp_head', 'sn_seo_meta_description', 1 );
add_action( 'wp_head', 'sn_seo_og', 2 );
add_action( 'wp_head', 'sn_seo_woo_structured_data', 3 );
add_action( 'wp_head', 'sn_seo_json_ld', 4 );
