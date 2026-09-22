<?php
/**
 * Article (single post).
 *
 * @package snstore
 */

get_header();

while ( have_posts() ) :
	the_post();
	$sn_cats = get_the_category();
	?>
	<article <?php post_class( 'sn-article' ); ?>>
		<div class="sn-article-head">
			<div class="sn-container sn-container--narrow">
				<p class="sn-post-meta">
					<?php
					if ( $sn_cats ) {
						echo '<span class="sn-post-cat">' . esc_html( $sn_cats[0]->name ) . '</span>';
					}
					echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
					?>
				</p>
				<h1 class="sn-article-title"><?php the_title(); ?></h1>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="sn-container sn-article-thumb">
				<?php the_post_thumbnail( 'large', array( 'class' => 'sn-article-img' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="sn-container sn-container--narrow">
			<div class="sn-prose">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>

			<?php if ( has_tag() ) : ?>
				<div class="sn-tags"><?php the_tags( '', '' ); ?></div>
			<?php endif; ?>

			<nav class="sn-postnav" aria-label="<?php esc_attr_e( 'Post navigation', 'snstore' ); ?>">
				<?php
				previous_post_link( '<span class="sn-postnav-prev">%link</span>', '&larr; %title' );
				next_post_link( '<span class="sn-postnav-next">%link</span>', '%title &rarr;' );
				?>
			</nav>

			<?php
			// Related: same category, fallback latest.
			$sn_related_args = array(
				'post_type'           => 'post',
				'posts_per_page'      => 3,
				'post__not_in'        => array( get_the_ID() ),
				'ignore_sticky_posts' => 1,
			);
			if ( $sn_cats ) {
				$sn_related_args['category__in'] = wp_list_pluck( $sn_cats, 'term_id' );
			}
			$sn_related = new WP_Query( $sn_related_args );
			if ( $sn_related->have_posts() ) :
				?>
				<div class="sn-related">
					<h2 class="sn-sect-title"><?php esc_html_e( 'You may also like', 'snstore' ); ?></h2>
					<div class="sn-post-grid sn-post-grid--3">
						<?php
						while ( $sn_related->have_posts() ) :
							$sn_related->the_post();
							sn_post_card();
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif; ?>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
