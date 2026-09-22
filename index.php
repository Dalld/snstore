<?php
/**
 * Blog listing (posts page and archives).
 *
 * @package snstore
 */

get_header();

$sn_title = get_the_title( (int) get_option( 'page_for_posts' ) );
if ( is_home() && ! $sn_title ) {
	$sn_title = __( 'Blog', 'snstore' );
}
if ( is_category() || is_tag() || is_author() || is_date() ) {
	$sn_title = wp_strip_all_tags( get_the_archive_title() );
}
?>
<div class="sn-blog">
	<div class="sn-pagehead">
		<div class="sn-container">
			<h1 class="sn-pagehead-title"><?php echo esc_html( $sn_title ); ?></h1>
		</div>
	</div>

	<div class="sn-container">
		<?php if ( have_posts() ) : ?>
			<div class="sn-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					sn_post_card();
				endwhile;
				?>
			</div>
			<div class="sn-pagination">
				<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
			</div>
		<?php else : ?>
			<div class="sn-empty">
				<p><?php esc_html_e( 'No articles yet — check back soon.', 'snstore' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
