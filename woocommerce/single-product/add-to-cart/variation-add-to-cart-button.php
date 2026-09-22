<?php
/**
 * Single variation cart button — quantity stepper + Add to Cart + Buy Now.
 * Based on WooCommerce variation-add-to-cart-button.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.2
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<div class="sn-atc-row">
		<div class="sn-qty" data-qty>
			<button class="sn-qty-btn sn-qty-minus" type="button" aria-label="<?php esc_attr_e( 'Decrease quantity', 'snstore' ); ?>">&minus;</button>
			<?php
			woocommerce_quantity_input(
				array(
					'min_value'   => $product->get_min_purchase_quantity(),
					'max_value'   => $product->get_max_purchase_quantity(),
					'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
				)
			);
			?>
			<button class="sn-qty-btn sn-qty-plus" type="button" aria-label="<?php esc_attr_e( 'Increase quantity', 'snstore' ); ?>">+</button>
		</div>
	</div>

	<div class="sn-atc-actions">
		<button type="submit" class="sn-atc-btn single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		<button type="submit" name="sn_buy_now" value="<?php echo esc_attr( $product->get_id() ); ?>" class="sn-buy-btn">
			<?php esc_html_e( 'Buy Now', 'snstore' ); ?>
		</button>
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
