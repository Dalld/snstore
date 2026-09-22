<?php
/**
 * Template helpers: icons, product cards, homepage sections, footer pieces.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme asset URL (relative to assets/).
 *
 * @param string $rel Relative path.
 * @return string
 */
function sn_asset( $rel ) {
	return get_stylesheet_directory_uri() . '/assets/' . ltrim( (string) $rel, '/' );
}

/**
 * Inline SVG icon. Echoes by default; set $return true to get the markup
 * for use inside string concatenation.
 *
 * @param string $name   Icon name.
 * @param int    $size   Pixel size.
 * @param bool   $return Return instead of echo.
 * @return string
 */
function sn_icon( $name, $size = 20, $return = false ) {
	$stroke = 'fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
	$icons  = array(
		'search'  => '<circle cx="9" cy="9" r="6.2" ' . $stroke . '/><path d="M13.6 13.6 18 18" ' . $stroke . '/>',
		'cart'    => '<path d="M3 4h2l1.2 10.2a1.6 1.6 0 0 0 1.6 1.4h7.9a1.6 1.6 0 0 0 1.6-1.3L19 8H6" ' . $stroke . '/><circle cx="9.4" cy="19.4" r="1.4" fill="currentColor" stroke="none"/><circle cx="16" cy="19.4" r="1.4" fill="currentColor" stroke="none"/>',
		'user'    => '<circle cx="10" cy="6.4" r="3.4" ' . $stroke . '/><path d="M3.6 18.4c.8-3.4 3.4-5.2 6.4-5.2s5.6 1.8 6.4 5.2" ' . $stroke . '/>',
		'burger'  => '<path d="M3 6h14M3 10.5h14M3 15h14" ' . $stroke . '/>',
		'close'   => '<path d="M4.5 4.5l11 11M15.5 4.5l-11 11" ' . $stroke . '/>',
		'chevron' => '<path d="M5 8l5 5 5-5" ' . $stroke . '/>',
		'arrow'   => '<path d="M3 10h13M11.5 4.5 17 10l-5.5 5.5" ' . $stroke . '/>',
		'truck'   => '<path d="M2.5 5h10.5v9.5H2.5zM13 8.5h3.4L19 11.4v3.1H13z" ' . $stroke . '/><circle cx="6.4" cy="16.6" r="1.7" ' . $stroke . '/><circle cx="15.6" cy="16.6" r="1.7" ' . $stroke . '/>',
		'return'  => '<path d="M4 8.5h9a4.5 4.5 0 0 1 0 9H8" ' . $stroke . '/><path d="M7.5 5 4 8.5 7.5 12" ' . $stroke . '/>',
		'shield'  => '<path d="M10 2.5 17 5v5c0 4.4-3 7.2-7 8.5-4-1.3-7-4.1-7-8.5V5z" ' . $stroke . '/><path d="M7 10l2.2 2.2L13.4 8" ' . $stroke . '/>',
		'support' => '<path d="M3.5 5.5h13v8h-7l-3.5 3v-3h-2.5z" ' . $stroke . '/><path d="M6.8 9.5h6.4M6.8 11.5h4" ' . $stroke . '/>',
		'star'    => '<path d="M10 2.8l2.2 4.6 5 .7-3.6 3.5.9 5-4.5-2.4-4.5 2.4.9-5L2.8 8.1l5-.7z" ' . $stroke . '/>',
		'mail'    => '<rect x="2.8" y="4.5" width="14.4" height="11" rx="1.8" ' . $stroke . '/><path d="m3.5 6 6.5 5 6.5-5" ' . $stroke . '/>',
		'phone'   => '<path d="M5.2 3.5h2.6l1.3 3.4-1.8 1.3a10.5 10.5 0 0 0 4.5 4.5l1.3-1.8 3.4 1.3v2.6a1.8 1.8 0 0 1-2 1.8A13.7 13.7 0 0 1 3.4 5.5a1.8 1.8 0 0 1 1.8-2z" ' . $stroke . '/>',
		'pin'     => '<path d="M10 18s-6-5.2-6-9.4A6 6 0 0 1 16 8.6C16 12.8 10 18 10 18z" ' . $stroke . '/><circle cx="10" cy="8.4" r="2.1" ' . $stroke . '/>',
		'clock'   => '<circle cx="10" cy="10" r="7.2" ' . $stroke . '/><path d="M10 5.8V10l3 1.8" ' . $stroke . '/>',
		'menu2'   => '<path d="M4 6h12M4 10.5h12M4 15h12" ' . $stroke . '/>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	$svg = sprintf(
		'<svg class="sn-icon sn-icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 20 20" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$icons[ $name ]
	);
	if ( $return ) {
		return $svg;
	}
	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput
	return '';
}

/**
 * Social brand icons (filled, simple).
 *
 * @param string $name Network name.
 * @param int    $size Pixel size.
 */
function sn_social_icon( $name, $size = 18 ) {
	$fills = array(
		'instagram' => '<rect x="2.6" y="2.6" width="14.8" height="14.8" rx="4" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="10" cy="10" r="3.4" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="14.4" cy="5.6" r="1" fill="currentColor"/>',
		'facebook'  => '<path d="M11.6 18v-6.4h2.2l.4-2.6h-2.6V7.3c0-.8.3-1.4 1.4-1.4h1.3V3.6c-.3 0-1.1-.1-2-.1-2.1 0-3.5 1.3-3.5 3.6V9H6.5v2.6h2.3V18z" fill="currentColor"/>',
		'tiktok'    => '<path d="M13.6 3c.3 1.7 1.4 2.9 3.3 3.1v2.4c-1.3 0-2.4-.4-3.3-1v4.9A4.9 4.9 0 1 1 8.7 7.5c.3 0 .7 0 1 .1v2.5a2.4 2.4 0 1 0 1.7 2.3V3z" fill="currentColor"/>',
		'youtube'   => '<path d="M17.6 6.4c-.2-.8-.8-1.3-1.5-1.5C14.8 4.6 10 4.6 10 4.6s-4.8 0-6.1.3c-.7.2-1.3.7-1.5 1.5C2 7.7 2 10 2 10s0 2.3.4 3.6c.2.8.8 1.3 1.5 1.5 1.3.3 6.1.3 6.1.3s4.8 0 6.1-.3c.7-.2 1.3-.7 1.5-1.5.4-1.3.4-3.6.4-3.6s0-2.3-.4-3.6zM8.4 12.5v-5l4.2 2.5z" fill="currentColor"/>',
		'x'         => '<path d="M11.7 8.7 17.3 2h-1.5l-4.8 5.7L7.2 2H2.6l5.9 8.7L2.6 18h1.5l5.1-6 4 6h4.6zM10 11.1l-.6-.9-4.6-6.7h2l3.7 5.4.6.9 4.8 7h-2z" fill="currentColor"/>',
		'pinterest' => '<path d="M10 2.5a7.5 7.5 0 0 0-2.9 14.4c0-.6-.1-1.6 0-2.3l1-4.1s-.2-.5-.2-1.2c0-1.2.7-2 1.5-2 .7 0 1 .5 1 1.2 0 .7-.5 1.8-.7 2.8-.2.8.4 1.5 1.3 1.5 1.5 0 2.7-1.6 2.7-4 0-2.1-1.5-3.5-3.6-3.5a3.8 3.8 0 0 0-3.9 3.8c0 .8.3 1.6.7 2l.1.4-.3 1.1c0 .2-.2.3-.4.2-1.1-.5-1.8-2.2-1.8-3.7 0-2.9 2.1-5.6 6.2-5.6 3.2 0 5.7 2.3 5.7 5.4 0 3.2-2 5.8-4.9 5.8-1 0-1.9-.5-2.2-1.1l-.6 2.3c-.2.9-.8 1.9-1.2 2.6a7.5 7.5 0 1 0 2.4-14.6z" fill="currentColor"/>',
	);
	if ( ! isset( $fills[ $name ] ) ) {
		return;
	}
	printf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 20 20" aria-hidden="true" focusable="false">%2$s</svg>',
		(int) $size,
		$fills[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * Logo: custom logo when set, brand name otherwise.
 */
function sn_logo() {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	printf(
		'<a class="sn-logo-text" href="%s" rel="home">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_html( sn_brand() )
	);
}

/**
 * Cart count badge (targeted by Woo fragment refresh).
 */
function sn_cart_count_badge() {
	$count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	echo '<span class="sn-cart-count">' . esc_html( $count ) . '</span>';
}

/**
 * Badges for a product (based on product_tag + sale state).
 *
 * @param WC_Product $product Product.
 * @return string[]
 */
function sn_product_badges( $product ) {
	$out   = array();
	$names = array();
	foreach ( $product->get_tag_ids() as $tag_id ) {
		$term = get_term( $tag_id, 'product_tag' );
		if ( $term && ! is_wp_error( $term ) ) {
			$names[] = strtolower( trim( $term->name ) );
		}
	}
	if ( in_array( 'new', $names, true ) || in_array( 'new arrival', $names, true ) || in_array( 'new arrivals', $names, true ) ) {
		$out[] = 'NEW';
	}
	if ( in_array( 'best seller', $names, true ) || in_array( 'bestseller', $names, true ) || in_array( 'best sellers', $names, true ) ) {
		$out[] = 'BEST SELLER';
	}
	if ( in_array( 'limited', $names, true ) || in_array( 'limited time', $names, true ) ) {
		$out[] = 'LIMITED';
	}
	if ( $product->is_on_sale() ) {
		$out[] = 'SALE';
	}
	if ( ! $product->is_in_stock() ) {
		$out = array( 'SOLD OUT' );
	}
	return array_slice( array_unique( $out ), 0, 2 );
}

/**
 * Product card used by every grid and row on the site.
 *
 * @param WC_Product|null $product Product object.
 */
function sn_product_card( $product = null ) {
	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_the_ID() );
	}
	if ( ! $product ) {
		return;
	}
	$link   = get_permalink( $product->get_id() );
	$name   = $product->get_name();
	$badges = sn_product_badges( $product );

	echo '<li class="sn-card">';
	echo '<div class="sn-card-media">';
	echo '<a class="sn-card-imagelink" href="' . esc_url( $link ) . '" aria-label="' . esc_attr( $name ) . '">';
	echo wp_get_attachment_image(
		$product->get_image_id(),
		'woocommerce_thumbnail',
		false,
		array(
			'class'   => 'sn-card-img',
			'loading' => 'lazy',
		)
	);
	echo '</a>';
	if ( $badges ) {
		echo '<div class="sn-card-badges">';
		foreach ( $badges as $badge ) {
			echo '<span class="sn-badge sn-badge--' . esc_attr( strtolower( str_replace( ' ', '-', $badge ) ) ) . '">' . esc_html( $badge ) . '</span>';
		}
		echo '</div>';
	}

	// Quick add for simple products; "Choose options" for the rest.
	if ( $product->is_type( 'simple' ) && $product->is_in_stock() && $product->is_purchasable() ) {
		echo '<form class="sn-card-add" method="post" action="' . esc_url( $product->add_to_cart_url() ) . '">';
		echo '<button type="submit" class="sn-card-addbtn" aria-label="' . esc_attr__( 'Add to cart', 'snstore' ) . '"><span>' . esc_html__( 'Add', 'snstore' ) . '</span></button>';
		echo '</form>';
	} elseif ( $product->is_type( 'variable' ) ) {
		echo '<a class="sn-card-add sn-card-add--link" href="' . esc_url( $link ) . '"><span>' . esc_html__( 'Choose options', 'snstore' ) . '</span></a>';
	}

	echo '</div>';
	echo '<p class="sn-card-name"><a href="' . esc_url( $link ) . '">' . esc_html( $name ) . '</a></p>';
	if ( $product->get_review_count() > 0 ) {
		$stars = max( 1, (int) round( (float) $product->get_average_rating() ) );
		echo '<div class="sn-card-stars" aria-label="' . esc_attr( sprintf( '%s/5', $product->get_average_rating() ) ) . '">';
		for ( $s = 1; $s <= 5; $s++ ) {
			echo '<span class="sn-star' . ( $s <= $stars ? ' is-on' : '' ) . '">&#9733;</span>';
		}
		echo '</div>';
	}
	echo '<div class="sn-card-price">' . wp_kses_post( $product->get_price_html() ) . '</div>';
	echo '</li>';
}

/**
 * Resolve a slot source key to a WP_Query args array. Homepage product rows
 * are fully randomized (orderby rand) so the same products are not always on
 * display regardless of import order.
 *
 * @param string $source Source key (latest|featured|on_sale|cat:slug|tag:slug).
 * @param int    $count  Number of products.
 * @return array
 */
function sn_slot_query_args( $source, $count ) {
	$args = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 2, min( 24, (int) $count ) ),
		'ignore_sticky_posts' => 1,
		'orderby'             => 'rand',
	);
	switch ( $source ) {
		case 'featured':
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				),
			);
			break;
		case 'on_sale':
			$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			break;
		default:
			if ( 0 === strpos( (string) $source, 'cat:' ) ) {
				$slug = substr( $source, 4 );
				$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $slug,
					),
				);
			} elseif ( 0 === strpos( (string) $source, 'tag:' ) ) {
				$slug = substr( $source, 4 );
				$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'taxonomy' => 'product_tag',
						'field'    => 'slug',
						'terms'    => $slug,
					),
				);
			}
	}
	return $args;
}

/**
 * Section header (heading + sub + optional link).
 *
 * @param string $title    Heading.
 * @param string $sub      Subheading.
 * @param string $btn_text Button label.
 * @param string $btn_url  Button URL.
 */
function sn_section_header( $title, $sub = '', $btn_text = '', $btn_url = '' ) {
	if ( '' === $title && '' === $sub ) {
		return;
	}
	echo '<div class="sn-sect-head">';
	echo '<div class="sn-sect-titles">';
	if ( $title ) {
		echo '<h2 class="sn-sect-title">' . esc_html( $title ) . '</h2>';
	}
	if ( $sub ) {
		echo '<p class="sn-sect-sub">' . esc_html( $sub ) . '</p>';
	}
	echo '</div>';
	if ( $btn_text && $btn_url ) {
		echo '<a class="sn-sect-link" href="' . esc_url( $btn_url ) . '">' . esc_html( $btn_text ) . ' ' . sn_icon( 'arrow', 16, true ) . '</a>';
	}
	echo '</div>';
}

/**
 * Resolve the WP_Query for a slot source, falling back to latest products
 * when a configured source is empty. Returns null when the site has no
 * products at all (the whole section is then hidden).
 *
 * @param array  $slot   Slot config.
 * @return WP_Query|null
 */
function sn_products_query( $slot ) {
	$args = sn_slot_query_args( $slot['source'], $slot['count'] );
	$q    = new WP_Query( $args );
	if ( ! $q->have_posts() && 'latest' !== $slot['source'] ) {
		// Configured source empty — fall back to latest so the section is
		// never blank while the store has products.
		unset( $args['tax_query'], $args['post__in'] );
		$q = new WP_Query( $args );
	}
	return $q->have_posts() ? $q : null;
}

/**
 * Render one homepage slot.
 *
 * @param int $i Slot number.
 */
function sn_render_slot( $i ) {
	$slot = sn_get_slot( $i );
	if ( ! $slot['enable'] ) {
		return;
	}
	$shop_link = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );

	switch ( $slot['type'] ) {

		case 'products':
			$q = sn_products_query( $slot );
			if ( ! $q ) {
				break; // No products on the site — hide the whole section.
			}
			echo '<section class="sn-section">';
			echo '<div class="sn-container">';
			sn_section_header( $slot['title'], $slot['sub'], $slot['btn_text'], $slot['btn_url'] ? $slot['btn_url'] : $shop_link );
			$scroll = ( 'scroll' === $slot['layout'] );
			echo '<div class="sn-grid-wrap' . ( $scroll ? ' is-scroll' : '' ) . '">';
			echo '<ul class="sn-grid sn-grid--' . esc_attr( $slot['cols'] ) . ( $scroll ? ' sn-grid--scroll' : '' ) . '">';
			while ( $q->have_posts() ) {
				$q->the_post();
				sn_product_card();
			}
			wp_reset_postdata();
			echo '</ul>';
			if ( $scroll ) {
				$left  = sn_icon( 'chevron', 18, true );
				$right = sn_icon( 'chevron', 18, true );
				echo '<div class="sn-scroll-nav"><button class="sn-scroll-btn" data-scroll="-1" type="button" aria-label="' . esc_attr__( 'Scroll left', 'snstore' ) . '">' . $left . '</button><button class="sn-scroll-btn sn-scroll-btn--next" data-scroll="1" type="button" aria-label="' . esc_attr__( 'Scroll right', 'snstore' ) . '">' . $right . '</button></div>';
			}
			echo '</div></div></section>';
			break;

		case 'banner':
			$img = $slot['img'] ? $slot['img'] : sn_asset( 'img/promo-sale.jpg' ); // Bundled generic sale backdrop.
			echo '<section class="sn-section"><div class="sn-container">';
			echo '<div class="sn-banner"' . ( $img ? ' style="background-image:url(' . esc_url( $img ) . ')"' : '' ) . '>';
			echo '<div class="sn-banner-inner">';
			echo '<h2 class="sn-banner-title">' . esc_html( $slot['title'] ) . '</h2>';
			if ( $slot['sub'] ) {
				echo '<p class="sn-banner-text">' . esc_html( $slot['sub'] ) . '</p>';
			}
			if ( $slot['btn_text'] ) {
				echo '<a class="sn-btn sn-btn--light" href="' . esc_url( $slot['btn_url'] ? $slot['btn_url'] : $shop_link ) . '">' . esc_html( $slot['btn_text'] ) . '</a>';
			}
			echo '</div></div></div></section>';
			break;

		case 'categories':
			$slugs = array_filter( array_map( 'trim', explode( ',', (string) $slot['cats'] ) ) );
			$terms = array();
			if ( $slugs ) {
				foreach ( array_slice( $slugs, 0, 4 ) as $slug ) {
					$term = get_term_by( 'slug', $slug, 'product_cat' );
					if ( $term && ! is_wp_error( $term ) ) {
						$terms[] = $term;
					}
				}
			} else {
				$found = get_terms(
					array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => true, // Auto mode: only categories that actually have products.
						'parent'     => 0,
						'number'     => 4,
					)
				);
				if ( ! is_wp_error( $found ) ) {
					$terms = $found;
				}
			}
			if ( ! $terms ) {
				return;
			}
			echo '<section class="sn-section"><div class="sn-container">';
			sn_section_header( $slot['title'], $slot['sub'] );
			echo '<div class="sn-cat-tiles sn-cat-tiles--' . esc_attr( count( $terms ) ) . '">';
			foreach ( $terms as $term ) {
				$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
				echo '<a class="sn-cat-tile" href="' . esc_url( get_term_link( $term ) ) . '">';
				echo '<span class="sn-cat-media">';
				if ( $thumb_id ) {
					echo wp_get_attachment_image( $thumb_id, 'woocommerce_thumbnail', false, array( 'class' => 'sn-cat-img', 'loading' => 'lazy' ) );
				} else {
					$fallback = sn_category_fallback_image( $term );
					if ( $fallback ) {
						echo '<img class="sn-cat-img" src="' . esc_url( $fallback ) . '" alt="' . esc_attr( $term->name ) . '" loading="lazy" />';
					} else {
						echo '<span class="sn-cat-fallback">' . esc_html( mb_substr( $term->name, 0, 1 ) ) . '</span>';
					}
				}
				echo '</span>';
				echo '<span class="sn-cat-name">' . esc_html( $term->name ) . ' ' . sn_icon( 'arrow', 15, true ) . '</span>';
				echo '</a>';
			}
			echo '</div></div></section>';
			break;

		case 'posts':
			$q = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => max( 2, min( 6, (int) $slot['count'] ) ),
					'ignore_sticky_posts' => 1,
				)
			);
			if ( ! $q->have_posts() ) {
				return;
			}
			$blog_link = get_permalink( get_option( 'page_for_posts' ) );
			echo '<section class="sn-section sn-section--surface"><div class="sn-container">';
			sn_section_header( $slot['title'], $slot['sub'], $blog_link ? __( 'All articles', 'snstore' ) : '', $blog_link ? $blog_link : '' );
			echo '<div class="sn-post-grid">';
			while ( $q->have_posts() ) {
				$q->the_post();
				sn_post_card();
			}
			wp_reset_postdata();
			echo '</div></div></section>';
			break;

		case 'newsletter':
			echo '<section class="sn-section sn-section--surface"><div class="sn-container">';
			echo '<div class="sn-newsletter">';
			echo '<h2 class="sn-newsletter-title">' . esc_html( $slot['title'] ) . '</h2>';
			echo '<p class="sn-newsletter-sub">' . esc_html( $slot['sub'] ) . '</p>';
			if ( isset( $_GET['sn-subscribed'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				echo '<p class="sn-form-ok">' . esc_html__( 'Thanks for subscribing — check your inbox for a welcome note.', 'snstore' ) . '</p>';
			}
			echo '<form class="sn-newsletter-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
			echo '<input type="hidden" name="action" value="sn_subscribe" />';
			echo '<input type="email" name="sn_email" required placeholder="' . esc_attr__( 'Enter your email', 'snstore' ) . '" aria-label="' . esc_attr__( 'Email address', 'snstore' ) . '" />';
			echo '<button type="submit" class="sn-btn sn-btn--dark">' . esc_html__( 'Subscribe', 'snstore' ) . '</button>';
			echo '</form></div></div></section>';
			break;

		case 'richtext':
			if ( '' === trim( (string) $slot['html'] ) ) {
				return;
			}
			echo '<section class="sn-section"><div class="sn-container">';
			sn_section_header( $slot['title'], $slot['sub'] );
			echo '<div class="sn-richtext">' . wp_kses_post( wpautop( sn_replace_tokens( $slot['html'] ) ) ) . '</div>';
			echo '</div></section>';
			break;
	}
}

/**
 * Image URL for a product category without a set thumbnail: a random
 * published product's image from that category, so fresh stores get real
 * tiles for free and the tiles keep up as products change.
 *
 * @param WP_Term $term Category term.
 * @return string Image URL or '' when the category has no image-capable product.
 */
function sn_category_fallback_image( $term ) {
	$ids = get_posts(
		array(
			'post_type'     => 'product',
			'post_status'   => 'publish',
			'numberposts'   => 12,
			'fields'        => 'ids',
			'no_found_rows' => true,
			'meta_query'    => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
			'tax_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
		)
	);
	if ( ! $ids || ! function_exists( 'wc_get_product' ) ) {
		return '';
	}
	return sn_product_image_url( wc_get_product( $ids[ array_rand( $ids ) ] ), wp_rand( 1, 3 ) );
}

/**
 * Card media for a blog post: featured image, else the first image in the
 * post content (tokens resolved), else the letter fallback.
 */
function sn_post_card_image() {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'medium_large', array( 'class' => 'sn-post-img', 'loading' => 'lazy' ) );
		return;
	}
	$src = sn_post_first_image( get_post() );
	if ( $src ) {
		echo '<img class="sn-post-img" src="' . esc_url( $src ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy" />';
		return;
	}
	echo '<span class="sn-cat-fallback">' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
}

/**
 * First <img> URL inside a post's content (after token replacement).
 *
 * @param WP_Post|null $post Post.
 * @return string Image URL or ''.
 */
function sn_post_first_image( $post ) {
	$content = ( $post instanceof WP_Post ) ? sn_replace_tokens( $post->post_content ) : '';
	if ( $content && preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Blog post card.
 */
function sn_post_card() {
	$cats = get_the_category();
	?>
	<article class="sn-post-card">
		<a class="sn-post-media" href="<?php the_permalink(); ?>">
			<?php sn_post_card_image(); ?>
		</a>
		<div class="sn-post-body">
			<p class="sn-post-meta">
				<?php
				if ( $cats ) {
					echo '<span class="sn-post-cat">' . esc_html( $cats[0]->name ) . '</span>';
				}
				echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
				?>
			</p>
			<h3 class="sn-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="sn-post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
		</div>
	</article>
	<?php
}

/**
 * Bundled default hero images (AI-generated product scenes covering
 * drinkware, bags/backpacks and glass kitchenware).
 *
 * @param int $i Slide number.
 * @return string Image URL or '' when the slide has no bundled default.
 */
function sn_hero_default_img( $i ) {
	if ( $i >= 1 && $i <= 3 ) {
		return esc_url( get_stylesheet_directory_uri() . '/assets/img/hero/hero-' . (int) $i . '.jpg' );
	}
	return '';
}

/**
 * Configured hero slides. Falls back to three default slides with bundled
 * imagery so a fresh install gets a working hero carousel out of the box.
 *
 * @return array[]
 */
function sn_hero_slides() {
	$defaults = array(
		1 => array(
			'enable'   => true,
			'title'    => __( 'Welcome to {{brand}}', 'snstore' ),
			'sub'      => __( 'Thoughtfully made products, fair prices and free returns.', 'snstore' ),
			'btn_text' => __( 'Shop Now', 'snstore' ),
			'btn_url'  => '',
		),
		2 => array(
			'enable'   => true,
			'title'    => __( 'The New Collection', 'snstore' ),
			'sub'      => __( 'Fresh designs just landed — be the first to shop.', 'snstore' ),
			'btn_text' => __( 'Shop New Arrivals', 'snstore' ),
			'btn_url'  => '',
		),
		3 => array(
			'enable'   => true,
			'title'    => __( 'Mid-Season Sale', 'snstore' ),
			'sub'      => __( 'Save up to 30% on selected favorites.', 'snstore' ),
			'btn_text' => __( 'Shop the Sale', 'snstore' ),
			'btn_url'  => '',
		),
	);

	$slides = array();
	for ( $i = 1; $i <= 5; $i++ ) {
		$def = isset( $defaults[ $i ] ) ? $defaults[ $i ] : array(
			'enable'   => false,
			'title'    => '',
			'sub'      => '',
			'btn_text' => '',
			'btn_url'  => '',
		);
		if ( ! sn_sanitize_checkbox( get_theme_mod( "sn_hero_{$i}_enable", $def['enable'] ) ) ) {
			continue;
		}
		$img   = get_theme_mod( "sn_hero_{$i}_img", sn_hero_default_img( $i ) );
		$title = sn_replace_tokens( get_theme_mod( "sn_hero_{$i}_title", $def['title'] ) );
		$sub   = sn_replace_tokens( get_theme_mod( "sn_hero_{$i}_sub", $def['sub'] ) );
		if ( ! $img && ! $title && ! $sub ) {
			continue;
		}
		$slides[] = array(
			'img'      => $img,
			'title'    => $title,
			'sub'      => $sub,
			'btn_text' => get_theme_mod( "sn_hero_{$i}_btn_text", $def['btn_text'] ),
			'btn_url'  => get_theme_mod( "sn_hero_{$i}_btn_url", $def['btn_url'] ),
		);
	}
	return $slides;
}

/**
 * USP bar items with defaults.
 *
 * @return array[] name => icon key + label
 */
function sn_usp_items() {
	if ( ! sn_sanitize_checkbox( get_theme_mod( 'sn_usp_enable', true ) ) ) {
		return array();
	}
	$defaults = array(
		__( 'Free shipping', 'snstore' ),
		__( '30-day easy returns', 'snstore' ),
		__( 'Secure checkout', 'snstore' ),
		__( 'Friendly support', 'snstore' ),
	);
	$items = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$icon    = get_theme_mod( "sn_usp_{$i}_icon", array( 'truck', 'return', 'shield', 'support' )[ $i - 1 ] );
		$text    = sn_replace_tokens( get_theme_mod( "sn_usp_{$i}_text", $defaults[ $i - 1 ] ) );
		$items[] = array(
			'icon' => $icon,
			'text' => $text,
		);
	}
	return $items;
}

/**
 * Payment badges (inline SVG, no external assets).
 */
function sn_payment_badges() {
	echo '<div class="sn-payments" aria-label="' . esc_attr__( 'Accepted payments', 'snstore' ) . '">';
	// Visa.
	echo '<span class="sn-pay"><svg viewBox="0 0 48 30" role="img" aria-label="Visa"><rect width="48" height="30" rx="5" fill="#fff"/><text x="24" y="20" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-style="italic" font-weight="700" fill="#1a1f71">VISA</text></svg></span>';
	// Mastercard.
	echo '<span class="sn-pay"><svg viewBox="0 0 48 30" role="img" aria-label="Mastercard"><rect width="48" height="30" rx="5" fill="#fff"/><circle cx="20" cy="15" r="8" fill="#eb001b"/><circle cx="28" cy="15" r="8" fill="#f79e1b" fill-opacity="0.9"/></svg></span>';
	// PayPal.
	echo '<span class="sn-pay"><svg viewBox="0 0 48 30" role="img" aria-label="PayPal"><rect width="48" height="30" rx="5" fill="#fff"/><text x="24" y="19" text-anchor="middle" font-family="Arial, sans-serif" font-size="9" font-weight="700" fill="#003087">Pay<tspan fill="#009cde">Pal</tspan></text></svg></span>';
	// Amex.
	echo '<span class="sn-pay"><svg viewBox="0 0 48 30" role="img" aria-label="American Express"><rect width="48" height="30" rx="5" fill="#2e77bc"/><text x="24" y="18.5" text-anchor="middle" font-family="Arial, sans-serif" font-size="7.5" font-weight="700" fill="#fff">AMEX</text></svg></span>';
	// Discover.
	echo '<span class="sn-pay"><svg viewBox="0 0 48 30" role="img" aria-label="Discover"><rect width="48" height="30" rx="5" fill="#fff"/><text x="20" y="19" text-anchor="middle" font-family="Arial, sans-serif" font-size="7.5" font-weight="700" fill="#231f20">DISC</text><circle cx="35.5" cy="15" r="5" fill="#f76b1c"/></svg></span>';
	echo '</div>';
}

/**
 * Social links from Customizer (non-empty only).
 */
function sn_social_links() {
	$networks = array(
		'sn_social_instagram' => 'instagram',
		'sn_social_facebook'  => 'facebook',
		'sn_social_tiktok'    => 'tiktok',
		'sn_social_youtube'   => 'youtube',
		'sn_social_x'         => 'x',
		'sn_social_pinterest' => 'pinterest',
	);
	$out = '';
	foreach ( $networks as $mod => $name ) {
		$url = get_theme_mod( $mod, '' );
		if ( $url ) {
			$out .= '<a class="sn-social-link" href="' . esc_url( $url ) . '" target="_blank" rel="noopener nofollow" aria-label="' . esc_attr( ucfirst( $name ) ) . '">' . sn_social_icon( $name ) . '</a>';
		}
	}
	return $out;
}

/**
 * Footer menu by location (empty-safe).
 *
 * @param string $location Menu location.
 */
function sn_footer_menu( $location ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu(
			array(
				'theme_location' => $location,
				'container'      => false,
				'menu_class'     => 'sn-foot-menu',
				'depth'          => 1,
				'echo'           => true,
			)
		);
	}
}
