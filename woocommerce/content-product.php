<?php
/**
 * The product loop item — render the theme card component so grids look
 * identical everywhere (shop, related products, search).
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

sn_product_card( $product );
