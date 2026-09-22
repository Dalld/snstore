<?php
/**
 * Generic page.
 *
 * @package snstore
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'sn-page' ); ?>>
		<div class="sn-pagehead">
			<div class="sn-container">
				<h1 class="sn-pagehead-title"><?php the_title(); ?></h1>
			</div>
		</div>
		<div class="sn-container">
			<div class="sn-prose sn-prose--wide">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
