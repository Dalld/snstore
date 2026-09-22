<?php
/**
 * Shop & category archive: page header, toolbar, category chips, grid, pagination.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$sn_title = '';
ob_start();
woocommerce_page_title( true );
$sn_title = trim( ob_get_clean() );

$sn_body_class = is_shop() ? 'sn-shop' : 'sn-archive';
?>
<div class="<?php echo esc_attr( $sn_body_class ); ?>">

	<div class="sn-pagehead">
		<div class="sn-container">
			<h1 class="sn-pagehead-title"><?php echo wp_kses_post( $sn_title ? $sn_title : __( 'Shop', 'snstore' ) ); ?></h1>
			<?php if ( is_shop() ) : ?>
				<p class="sn-pagehead-sub"><?php esc_html_e( 'Browse the full collection — quality picks, fair prices.', 'snstore' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php
	// Category chips on the top level.
	$sn_chips = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
		)
	);
	if ( ! is_wp_error( $sn_chips ) && $sn_chips ) :
		?>
		<div class="sn-chips">
			<div class="sn-container sn-chips-row">
				<a class="sn-chip<?php echo is_shop() ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'All', 'snstore' ); ?></a>
				<?php foreach ( $sn_chips as $sn_chip ) : ?>
					<a class="sn-chip<?php echo ( is_product_category( $sn_chip->slug ) ) ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $sn_chip ) ); ?>"><?php echo esc_html( $sn_chip->name ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="sn-container">

		<?php do_action( 'woocommerce_before_shop_loop' ); ?>

		<form class="sn-toolbar" method="get">
			<?php
			$sn_orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
			$sn_options = array(
				'menu_order' => __( 'Default sorting', 'snstore' ),
				'popularity' => __( 'Sort by popularity', 'snstore' ),
				'rating'     => __( 'Sort by rating', 'snstore' ),
				'date'       => __( 'Sort by latest', 'snstore' ),
				'price'      => __( 'Sort by price: low to high', 'snstore' ),
				'price-desc' => __( 'Sort by price: high to low', 'snstore' ),
			);
			foreach ( $_GET as $sn_key => $sn_val ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( 'orderby' !== $sn_key && ! is_array( $sn_val ) ) {
					echo '<input type="hidden" name="' . esc_attr( $sn_key ) . '" value="' . esc_attr( $sn_val ) . '" />';
				}
			}
			?>
			<p class="woocommerce-notices-hook"></p>
			<label class="sn-toolbar-count"><?php echo esc_html( wc_get_loop_prop( 'total' ) ); ?> <?php esc_html_e( 'items', 'snstore' ); ?></label>
			<select name="orderby" class="sn-orderby" aria-label="<?php esc_attr_e( 'Sort products', 'snstore' ); ?>">
				<?php foreach ( $sn_options as $sn_value => $sn_label ) : ?>
					<option value="<?php echo esc_attr( $sn_value ); ?>" <?php selected( $sn_orderby, $sn_value ); ?>><?php echo esc_html( $sn_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</form>

		<?php if ( woocommerce_product_loop() ) : ?>
			<?php
			woocommerce_product_loop_start();
			while ( have_posts() ) {
				the_post();
				sn_product_card();
			}
			woocommerce_product_loop_end();
			?>
			<div class="sn-pagination">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => __( '&larr;', 'snstore' ),
						'next_text' => __( '&rarr;', 'snstore' ),
					)
				);
				?>
			</div>
		<?php else : ?>
			<div class="sn-empty">
				<p><?php esc_html_e( 'No products were found matching your selection.', 'snstore' ); ?></p>
				<a class="sn-btn sn-btn--dark" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Back to shop', 'snstore' ); ?></a>
			</div>
		<?php endif; ?>

		<?php
		do_action( 'woocommerce_after_shop_loop' );
		?>

	</div>
</div>

<?php
get_footer( 'shop' );
