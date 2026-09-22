<?php
/**
 * Variable product add to cart — pill-button attribute picker instead of the
 * bare dropdowns. The native select stays in the DOM (hidden) so WooCommerce's
 * variation engine keeps working; snstore.js syncs button clicks to it.
 *
 * Based on WooCommerce variable.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.1.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart sn-varform" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'snstore' ) ) ); ?></p>
	<?php else : ?>
		<div class="variations sn-variations">
			<?php foreach ( $attributes as $attribute_name => $options ) : ?>
				<div class="sn-var-group">
					<div class="sn-var-head">
						<span class="sn-var-label"><?php echo esc_html( wc_attribute_label( $attribute_name ) ); ?>: <span class="sn-var-value"></span></span>
						<?php
						echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__( 'Clear options', 'snstore' ) . '">' . esc_html__( 'Clear', 'snstore' ) . '</a>' ) ) : '';
						?>
					</div>
					<div class="sn-var-options">
						<?php
						$seen_options = array();
						foreach ( $options as $option ) :
							// Pills must carry the exact value Woo renders in the hidden
							// select: term slug for taxonomy attributes, the raw option
							// text for custom attributes. Otherwise the JS-synced value
							// never matches and the variation engine stays dead.
							$slug_key = sanitize_title( $option );
							if ( '' === $slug_key || isset( $seen_options[ $slug_key ] ) ) {
								continue;
							}
							$seen_options[ $slug_key ] = true;
							$label      = $option;
							$data_value = $option;
							if ( taxonomy_exists( $attribute_name ) ) {
								$term = get_term_by( 'slug', $slug_key, $attribute_name );
								if ( $term ) {
									$label      = $term->name;
									$data_value = $term->slug;
								}
							}
							?>
							<button type="button" class="sn-var-btn" data-value="<?php echo esc_attr( $data_value ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $label, $option, $attribute_name, $product ) ); ?></button>
						<?php endforeach; ?>
					</div>
					<div class="sn-var-select">
						<?php
						wc_dropdown_variation_attribute_options(
							array(
								'options'   => $options,
								'attribute' => $attribute_name,
								'product'   => $product,
							)
						);
						?>
					</div>
				</div>
			<?php endforeach; ?>
			<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
			<template class="wc-product-gallery-default-template"><?php echo wc_get_product_gallery_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></template>
			<?php do_action( 'woocommerce_after_variations_table' ); ?>

			<div class="single_variation_wrap">
				<?php
				do_action( 'woocommerce_before_single_variation' );
				do_action( 'woocommerce_single_variation' );
				do_action( 'woocommerce_after_single_variation' );
				?>
			</div>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
