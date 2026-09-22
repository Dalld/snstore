<?php
/**
 * Search results: products and articles, sectioned.
 *
 * @package snstore
 */

get_header();

$sn_query   = get_search_query();
$sn_total_p = 0;
$sn_total_a = 0;

// Products.
$sn_products = new WP_Query(
	array(
		's'                   => $sn_query,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => 12,
		'ignore_sticky_posts' => 1,
	)
);

// Articles.
$sn_articles = new WP_Query(
	array(
		's'                   => $sn_query,
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => 1,
	)
);
?>
<div class="sn-searchpage">
	<div class="sn-pagehead">
		<div class="sn-container sn-container--narrow">
			<h1 class="sn-pagehead-title">
				<?php
				if ( $sn_query ) {
					printf( esc_html__( 'Results for “%s”', 'snstore' ), esc_html( $sn_query ) );
				} else {
					esc_html_e( 'Search', 'snstore' );
				}
				?>
			</h1>
			<div class="sn-searchpage-form"><?php get_search_form(); ?></div>
		</div>
	</div>

	<?php if ( ! $sn_query ) : ?>
		<div class="sn-container"><div class="sn-empty"><p><?php esc_html_e( 'Type something in the search box above.', 'snstore' ); ?></p></div></div>
	<?php elseif ( ! $sn_products->have_posts() && ! $sn_articles->have_posts() ) : ?>
		<div class="sn-container">
			<div class="sn-empty">
				<p><?php esc_html_e( 'Nothing matched your search. Try different keywords or browse the shop.', 'snstore' ); ?></p>
				<?php if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) : ?>
					<a class="sn-btn sn-btn--dark" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse the shop', 'snstore' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php else : ?>

		<?php if ( $sn_products->have_posts() ) : ?>
			<section class="sn-section">
				<div class="sn-container">
					<div class="sn-sect-head">
						<h2 class="sn-sect-title"><?php esc_html_e( 'Products', 'snstore' ); ?></h2>
						<span class="sn-sect-count"><?php echo (int) $sn_products->found_posts; ?></span>
					</div>
					<ul class="sn-grid sn-grid--4">
						<?php
						while ( $sn_products->have_posts() ) :
							$sn_products->the_post();
							sn_product_card();
						endwhile;
						wp_reset_postdata();
						?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $sn_articles->have_posts() ) : ?>
			<section class="sn-section sn-section--surface">
				<div class="sn-container">
					<div class="sn-sect-head">
						<h2 class="sn-sect-title"><?php esc_html_e( 'Articles', 'snstore' ); ?></h2>
						<span class="sn-sect-count"><?php echo (int) $sn_articles->found_posts; ?></span>
					</div>
					<div class="sn-post-grid">
						<?php
						while ( $sn_articles->have_posts() ) :
							$sn_articles->the_post();
							sn_post_card();
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php endif; ?>
</div>

<?php
get_footer();
