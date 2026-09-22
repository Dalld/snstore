<?php
/**
 * Single product: gallery + buy panel (Add to Cart + Buy Now) + tabs + related.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $product ) || ! $product instanceof WC_Product ) {
	$product = wc_get_product( get_queried_object_id() );
}
if ( ! $product ) {
	return;
}

get_header( 'shop' );

$sn_main_id = $product->get_image_id();
$sn_gallery = $product->get_gallery_image_ids();
$sn_terms   = get_the_terms( $product->get_id(), 'product_cat' );
$sn_bread   = array(
	array( __( 'Home', 'snstore' ), home_url( '/' ) ),
);
if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) {
	$sn_bread[] = array( __( 'Shop', 'snstore' ), get_permalink( wc_get_page_id( 'shop' ) ) );
}
if ( $sn_terms && ! is_wp_error( $sn_terms ) ) {
	$sn_bread[] = array( $sn_terms[0]->name, get_term_link( $sn_terms[0] ) );
}
$sn_bread[] = array( $product->get_name(), get_permalink( $product->get_id() ) );
?>

<div class="sn-pdp">
	<div class="sn-crumb">
		<div class="sn-container">
			<?php
			$sn_last = count( $sn_bread ) - 1;
			foreach ( $sn_bread as $sn_i => $sn_crumb ) :
				if ( $sn_i === $sn_last ) :
					?>
					<span class="sn-crumb-here"><?php echo esc_html( $sn_crumb[0] ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $sn_crumb[1] ); ?>"><?php echo esc_html( $sn_crumb[0] ); ?></a> <span class="sn-crumb-sep">/</span>
				<?php endif;
			endforeach;
			?>
		</div>
	</div>

	<div class="sn-container sn-pdp-notices">
		<?php woocommerce_output_all_notices(); ?>
	</div>

	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		// Product JSON-LD for Google rich results — this theme's custom
		// purchase panel replaces the Woo summary hooks that normally
		// trigger the generator, so it runs explicitly here.
		if ( function_exists( 'WC' ) && isset( WC()->structured_data ) ) {
			WC()->structured_data->generate_product_data( wc_get_product( get_the_ID() ) );
		}
		?>
		<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sn-container sn-pdp-main', $product ); ?>>
			<div class="sn-pdp-gallery<?php echo empty( $sn_gallery ) ? ' is-single' : ''; ?>">
				<div class="sn-pdp-media">
					<?php if ( $sn_main_id ) : ?>
						<img class="sn-pdp-img" id="sn-pdp-main-img"
							src="<?php echo esc_url( wp_get_attachment_image_url( $sn_main_id, 'woocommerce_single' ) ); ?>"
							alt="<?php echo esc_attr( $product->get_name() ); ?>" />
					<?php else : ?>
						<?php echo wc_placeholder_img( 'woocommerce_single' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php endif; ?>
					<?php if ( $product->is_on_sale() ) : ?>
						<span class="sn-badge sn-badge--sale sn-pdp-flag"><?php esc_html_e( 'Sale', 'snstore' ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $sn_gallery ) ) : ?>
						<button class="sn-pdp-arrow sn-pdp-arrow--prev" type="button" data-dir="-1" aria-label="<?php esc_attr_e( 'Previous image', 'snstore' ); ?>"><?php echo sn_icon( 'chevron', 18, true ); ?></button>
						<button class="sn-pdp-arrow sn-pdp-arrow--next" type="button" data-dir="1" aria-label="<?php esc_attr_e( 'Next image', 'snstore' ); ?>"><?php echo sn_icon( 'chevron', 18, true ); ?></button>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $sn_gallery ) ) : ?>
					<div class="sn-pdp-thumbs">
						<button class="sn-pdp-thumb is-active" type="button" data-src="<?php echo esc_url( wp_get_attachment_image_url( $sn_main_id, 'woocommerce_single' ) ); ?>">
							<?php echo wp_get_attachment_image( $sn_main_id, 'woocommerce_gallery_thumbnail' ); ?>
						</button>
						<?php foreach ( $sn_gallery as $sn_gid ) : ?>
							<button class="sn-pdp-thumb" type="button" data-src="<?php echo esc_url( wp_get_attachment_image_url( $sn_gid, 'woocommerce_single' ) ); ?>">
								<?php echo wp_get_attachment_image( $sn_gid, 'woocommerce_gallery_thumbnail' ); ?>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="sn-pdp-panel">
				<div class="sn-pdp-panel-inner">
					<h1 class="sn-pdp-title"><?php the_title(); ?></h1>

					<?php if ( $product->get_review_count() > 0 ) : ?>
						<div class="sn-pdp-rating">
							<span class="sn-card-stars">
								<?php
								$sn_stars = max( 1, (int) round( (float) $product->get_average_rating() ) );
								for ( $sn_s = 1; $sn_s <= 5; $sn_s++ ) {
									echo '<span class="sn-star' . ( $sn_s <= $sn_stars ? ' is-on' : '' ) . '">&#9733;</span>';
								}
								?>
							</span>
							<a class="sn-pdp-rating-link" href="#sn-reviews">
								<?php
								printf(
									/* translators: %s: review count */
									_n( '%s review', '%s reviews', $product->get_review_count(), 'snstore' ),
									esc_html( number_format_i18n( $product->get_review_count() ) )
								);
								?>
							</a>
						</div>
					<?php endif; ?>

					<div class="sn-pdp-price"><?php woocommerce_template_single_price(); ?></div>
					<div class="sn-pdp-excerpt"><?php woocommerce_template_single_excerpt(); ?></div>

					<div class="sn-pdp-form">
						<?php woocommerce_template_single_add_to_cart(); ?>
					</div>

					<ul class="sn-pdp-usps">
						<li><?php sn_icon( 'truck', 18 ); ?><?php esc_html_e( 'Free shipping on qualifying orders', 'snstore' ); ?></li>
						<li><?php sn_icon( 'return', 18 ); ?><?php esc_html_e( '30-day easy returns', 'snstore' ); ?></li>
						<li><?php sn_icon( 'shield', 18 ); ?><?php esc_html_e( 'Secure checkout', 'snstore' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	<?php endwhile; ?>

	<div class="sn-container sn-pdp-tabs-zone">
		<?php
		/**
		 * Description / additional information / reviews, rendered by Woo.
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
	</div>

	<div class="sn-container sn-pdp-related">
		<?php woocommerce_output_related_products(); ?>
	</div>
</div>

<?php
get_footer( 'shop' );
