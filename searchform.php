<?php
/**
 * Shared search form (header overlay, search page, 404).
 *
 * @package snstore
 */
?>
<form role="search" method="get" class="sn-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php sn_icon( 'search', 18 ); ?>
	<label class="screen-reader-text" for="sn-search-input"><?php esc_html_e( 'Search', 'snstore' ); ?></label>
	<input id="sn-search-input" type="search" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search products and articles…', 'snstore' ); ?>" />
	<button type="submit" class="sn-btn sn-btn--dark"><?php esc_html_e( 'Search', 'snstore' ); ?></button>
</form>
