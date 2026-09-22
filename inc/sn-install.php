<?php
/**
 * Activation routine: create default pages (policies/company/contact) and
 * navigation menus. Idempotent — existing pages/posts are never overwritten.
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Create the default pages shipped with the theme.
 * WooCommerce pre-creates a "privacy-policy" page on fresh stores — if it is
 * still the untouched Woo default, it is upgraded to the themed policy page.
 * Any page the user has customized (or any other existing page) is left alone.
 */
function sn_install_create_pages() {
	foreach ( sn_default_pages() as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing instanceof WP_Post ) {
			$template_map = array(
				'policy'  => 'page-templates/policy.php',
				'contact' => 'page-templates/contact.php',
			);

			if ( 'publish' === $existing->post_status ) {
				$is_ours = (bool) get_post_meta( $existing->ID, 'sn_eyebrow', true );
				if ( ! $is_ours ) {
					continue; // User-customized or created by something else — hands off.
				}
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_content' => $data['content'],
					)
				);
			} else {
				// Draft left by WP/Woo core (empty, or the privacy-policy
				// tutorial draft) — adopt it instead of duplicating the slug.
				$is_core_draft = '' === trim( (string) $existing->post_content )
					|| false !== strpos( (string) $existing->post_content, 'privacy-policy-tutorial' );
				if ( ! $is_core_draft ) {
					continue;
				}
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_status'  => 'publish',
						'post_title'   => $data['title'],
						'post_content' => $data['content'],
					)
				);
			}

			update_post_meta( $existing->ID, 'sn_eyebrow', $data['eyebrow'] );
			if ( isset( $template_map[ $data['template'] ] ) ) {
				update_post_meta( $existing->ID, '_wp_page_template', $template_map[ $data['template'] ] );
			}
			continue;
		}
		$page_id = wp_insert_post(
			array(
				'post_title'     => $data['title'],
				'post_name'      => $slug,
				'post_content'   => $data['content'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_post_meta( $page_id, 'sn_eyebrow', $data['eyebrow'] );
			if ( 'policy' === $data['template'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'page-templates/policy.php' );
			} elseif ( 'contact' === $data['template'] ) {
				update_post_meta( $page_id, '_wp_page_template', 'page-templates/contact.php' );
			}
		}
	}
}

/**
 * Create + assign the navigation menus.
 */
function sn_install_create_menus() {
	$sets = array(
		'primary' => array(
			'name'  => 'Main Menu',
			'items' => array(
				'Home'          => home_url( '/' ),
				'Shop All'      => get_permalink( wc_get_page_id( 'shop' ) ),
				'About Us'      => sn_install_page_link( 'about-us' ),
				'Contact Us'    => sn_install_page_link( 'contact-us' ),
			),
		),
		'footer_service' => array(
			'name'  => 'Footer — Customer Service',
			'items' => array(
				'Shipping Policy'       => sn_install_page_link( 'shipping-policy' ),
				'Privacy Policy'        => sn_install_page_link( 'privacy-policy' ),
				'Warranty Policy'       => sn_install_page_link( 'warranty-policy' ),
				'Return & Refund Policy' => sn_install_page_link( 'return-refund-policy' ),
			),
		),
		'footer_company' => array(
			'name'  => 'Footer — Company',
			'items' => array(
				'About Us'        => sn_install_page_link( 'about-us' ),
				'Contact Us'      => sn_install_page_link( 'contact-us' ),
				'Payment Methods' => sn_install_page_link( 'payment-methods' ),
				'Terms and Conditions' => sn_install_page_link( 'terms-and-conditions' ),
			),
		),
	);

	$locations = get_theme_mod( 'nav_menu_locations', array() );

	foreach ( $sets as $location => $set ) {
		$assigned = ! empty( $locations[ $location ] ) && is_nav_menu( $locations[ $location ] );
		$menu     = null;

		if ( $assigned ) {
			continue; // Site owner already has a menu there — never touch it.
		}

		$existing = wp_get_nav_menu_object( $set['name'] );
		if ( $existing ) {
			$menu = (int) $existing->term_id;
		} else {
			$menu = wp_create_nav_menu( $set['name'] );
		}
		if ( is_wp_error( $menu ) || ! $menu ) {
			continue;
		}

		foreach ( $set['items'] as $title => $url ) {
			if ( ! $url ) {
				continue;
			}
			// Skip if a same-titled item already exists in this menu.
			$found = wp_get_nav_menu_items( $menu );
			$dupe  = false;
			foreach ( (array) $found as $item ) {
				if ( wp_strip_all_tags( html_entity_decode( $item->post_title ) ) === wp_strip_all_tags( html_entity_decode( $title ) ) ) {
					$dupe = true;
					break;
				}
			}
			if ( $dupe ) {
				continue;
			}
			wp_update_nav_menu_item(
				$menu,
				0,
				array(
					'menu-item-title'  => $title,
					'menu-item-url'    => $url,
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);
		}

		$locations[ $location ] = $menu;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Permalink for one of the default pages, or empty string when missing.
 *
 * @param string $slug Page slug.
 * @return string
 */
function sn_install_page_link( $slug ) {
	$page = get_page_by_path( $slug );
	return $page instanceof WP_Post ? get_permalink( $page ) : '';
}

/**
 * Ensure a posts page exists so the blog grid is reachable. Also switches the
 * front page to "static" — in "your latest posts" mode WordPress ignores
 * page_for_posts and the Blog URL renders as an empty page. The front page
 * itself is always rendered by front-page.php, so this changes nothing
 * visually.
 */
function sn_install_blog_page() {
	$home = get_page_by_path( 'home' );
	if ( ! $home instanceof WP_Post ) {
		$home_id = wp_insert_post(
			array(
				'post_title'     => 'Home',
				'post_name'      => 'home',
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			)
		);
	} else {
		$home_id = $home->ID;
	}
	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	$page = get_page_by_path( 'blog' );
	if ( ! $page instanceof WP_Post ) {
		$page_id = wp_insert_post(
			array(
				'post_title'     => 'Blog',
				'post_name'      => 'blog',
				'post_content'   => '',
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed',
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_option( 'page_for_posts', $page_id );
		}
	} else {
		update_option( 'page_for_posts', $page->ID );
	}
}

/**
 * Create the three default blog posts shipped with the theme. Content is
 * evergreen and product-agnostic: images/titles/links are {{product_*:N}}
 * tokens resolved live from whatever the store currently sells, so the same
 * posts work on every site without edits. Idempotent by slug.
 */
function sn_install_default_posts() {
	$defaults = array(
		'everyday-essentials' => array(
			'title'   => 'Everyday Essentials: Build a Collection You Actually Use',
			'content' => sn_install_post_content_1(),
		),
		'how-to-choose-well'  => array(
			'title'   => 'How to Choose Well: A Simple Three-Step Checklist',
			'content' => sn_install_post_content_2(),
		),
		'care-and-keep'       => array(
			'title'   => 'Care & Keep: Make Your Favorites Last Longer',
			'content' => sn_install_post_content_3(),
		),
	);

	// wp_filter_post_kses treats {{product_image:1}} inside src/href as a bad
	// URI protocol and eats the token prefix — suspend kses for these inserts.
	kses_remove_filters();
	$post_ids = array();
	foreach ( $defaults as $slug => $data ) {
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $existing instanceof WP_Post ) {
			continue;
		}
		$new_id = wp_insert_post(
			array(
				'post_title'     => $data['title'],
				'post_name'      => $slug,
				'post_content'   => $data['content'],
				'post_status'    => 'publish',
				'post_type'      => 'post',
				'comment_status' => 'closed',
			)
		);
		if ( $new_id && ! is_wp_error( $new_id ) ) {
			$post_ids[] = $new_id;
		}
	}
	kses_init_filters();

	// Group the default posts under a generic "Journal" category so they
	// don't land in Uncategorized.
	if ( $post_ids ) {
		$term = get_term_by( 'slug', 'journal', 'category' );
		if ( ! $term ) {
			$new_term = wp_insert_term( 'Journal', 'category', array( 'slug' => 'journal' ) );
			$term     = is_wp_error( $new_term ) ? null : get_term( $new_term['term_id'] );
		}
		if ( $term && ! is_wp_error( $term ) ) {
			foreach ( $post_ids as $pid ) {
				wp_set_post_categories( $pid, array( $term->term_id ) );
			}
		}
	}
}

/**
 * Post body 1 — everyday essentials.
 *
 * @return string
 */
function sn_install_post_content_1() {
	return <<<'HTML'
<p>The best collections are not the biggest ones. They are the ones you reach for every single day — pieces that earned their place through use, not impulse. Building one is simpler than it sounds: buy slowly, choose deliberately, and let repetition tell you what really works.</p>
<figure class="sn-post-fig"><img src="{{product_image:1}}" alt="{{product_name:1}}" loading="lazy" /><figcaption><a href="{{product_link:1}}">{{product_name:1}}</a></figcaption></figure>
<p>Start with the items that touch your routine most. If something serves you morning and night, it deserves the better version. A well-made daily piece pays for itself in the small satisfaction of every use — and it usually outlasts three of its cheaper cousins.</p>
<figure class="sn-post-fig"><img src="{{product_image:2}}" alt="{{product_name:2}}" loading="lazy" /><figcaption><a href="{{product_link:2}}">{{product_name:2}}</a></figcaption></figure>
<p>One honest test before any purchase: picture the object in your hands a month from now. If you cannot see the moment it gets used, leave it on the shelf. If you can, it belongs in your collection — <a href="{{product_link:3}}">{{product_name:3}}</a> is exactly the kind of piece that passes the test.</p>
<p>Browse the full range in <a href="{{site_url}}shop/">our shop</a> and start with one piece you will use tomorrow.</p>
HTML;
}

/**
 * Post body 2 — how to choose well.
 *
 * @return string
 */
function sn_install_post_content_2() {
	return <<<'HTML'
<p>Good decisions do not need a spreadsheet — they need three honest questions, asked in order. Use this checklist the next time something catches your eye, online or off.</p>
<h2>1. Where will it live?</h2>
<p>Every object has an address: a hook, a shelf, a bag pocket. Decide the address first. If nothing in your home or day claims it, the item is a guest, not a resident — and guests eventually overstay.</p>
<h2>2. What is it made of?</h2>
<p>Materials are the honest part of any product page. Look for what it does, not what it sounds like: weight, finish, stitching, seals. Quality you can describe in one sentence is usually quality you can feel.</p>
<figure class="sn-post-fig"><img src="{{product_image:3}}" alt="{{product_name:3}}" loading="lazy" /><figcaption><a href="{{product_link:3}}">{{product_name:3}}</a></figcaption></figure>
<h2>3. How often will you use it?</h2>
<p>Frequency is the multiplier of value. A modest item used weekly beats a luxury item used once a year. Our <a href="{{product_link:4}}">{{product_name:4}}</a> is a quiet example — unglamorous math, daily payoff.</p>
<figure class="sn-post-fig"><img src="{{product_image:5}}" alt="{{product_name:5}}" loading="lazy" /><figcaption><a href="{{product_link:5}}">{{product_name:5}}</a></figcaption></figure>
<p>Three questions, under a minute. Ask them every time and you will notice the pattern: the right choices almost argue for themselves. When one does, you will find it in <a href="{{site_url}}shop/">the shop</a>.</p>
HTML;
}

/**
 * Post body 3 — care and keep.
 *
 * @return string
 */
function sn_install_post_content_3() {
	return <<<'HTML'
<p>Nothing wears better than something cared for. The good news: proper care is less about effort and more about small habits, repeated. Here is the short version worth keeping.</p>
<h2>Keep it clean, gently</h2>
<p>Dust, salt and daily grime do slow, quiet damage. A soft cloth and mild soap solve most of it — skip the harsh chemicals and the dishwasher, and let pieces dry fully before storing them away.</p>
<figure class="sn-post-fig"><img src="{{product_image:6}}" alt="{{product_name:6}}" loading="lazy" /><figcaption><a href="{{product_link:6}}">{{product_name:6}}</a></figcaption></figure>
<h2>Store it like you mean it</h2>
<p>Pressure and sunlight are the two quiet enemies of everything you own. Give pieces room, keep them out of direct sun, and let shapes rest empty rather than stuffed or folded against their will.</p>
<h2>Repair before you replace</h2>
<p>A loose thread, a wobbly rivet, a tired seal — most failures are small and early. Tending to them the week you notice is the difference between a five-year piece and a fifty-year one.</p>
<figure class="sn-post-fig"><img src="{{product_image:7}}" alt="{{product_name:7}}" loading="lazy" /><figcaption><a href="{{product_link:7}}">{{product_name:7}}</a></figcaption></figure>
<p>Take care of the things you love and they return the favor for years. If something in your rotation is ready for an upgrade, you will find built-to-last options in <a href="{{site_url}}shop/">the shop</a>.</p>
HTML;
}

/**
 * Runs once per activation.
 */
function sn_activate_theme() {
	sn_install_create_pages();
	sn_install_blog_page();
	sn_install_create_menus();
	sn_install_default_posts();

	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	$wp_rewrite->flush_rules( true );
}
add_action( 'after_switch_theme', 'sn_activate_theme' );
