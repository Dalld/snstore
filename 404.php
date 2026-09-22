<?php
/**
 * 404.
 *
 * @package snstore
 */

get_header();
?>
<div class="sn-404">
	<div class="sn-container sn-container--narrow">
		<p class="sn-404-code">404</p>
		<h1 class="sn-404-title"><?php esc_html_e( 'This page could not be found.', 'snstore' ); ?></h1>
		<p class="sn-404-text"><?php esc_html_e( 'The link may be outdated. Try a search, or head back to the shop.', 'snstore' ); ?></p>
		<div class="sn-404-search"><?php get_search_form(); ?></div>
		<div class="sn-404-links">
			<a class="sn-btn sn-btn--dark" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go home', 'snstore' ); ?></a>
			<?php if ( function_exists( 'wc_get_page_id' ) && wc_get_page_id( 'shop' ) > 0 ) : ?>
				<a class="sn-btn sn-btn--ghost" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse the shop', 'snstore' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php
get_footer();
