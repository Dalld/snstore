<?php
/**
 * Simple product: quantity stepper + Add to Cart + Buy Now (straight to checkout).
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	echo '<p class="sn-oos">' . esc_html__( 'This product is currently out of stock and unavailable.', 'snstore' ) . '</p>';
	return;
}

echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput

if ( ! $product->is_in_stock() ) {
	return;
}
?>

<form class="cart sn-atc" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
	<div class="sn-atc-row">
		<div class="sn-qty" data-qty>
			<button class="sn-qty-btn sn-qty-minus" type="button" aria-label="<?php esc_attr_e( 'Decrease quantity', 'snstore' ); ?>">&minus;</button>
			<?php
			woocommerce_quantity_input(
				array(
					'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
					'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
					'input_value' => $product->get_min_purchase_quantity(),
				)
			);
			?>
			<button class="sn-qty-btn sn-qty-plus" type="button" aria-label="<?php esc_attr_e( 'Increase quantity', 'snstore' ); ?>">+</button>
		</div>
	</div>
	<div class="sn-atc-actions">
		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="sn-atc-btn single_add_to_cart_button">
			<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
		</button>
		<button type="submit" name="sn_buy_now" value="<?php echo esc_attr( $product->get_id() ); ?>" class="sn-buy-btn">
			<?php esc_html_e( 'Buy Now', 'snstore' ); ?>
		</button>
	</div>
</form>
