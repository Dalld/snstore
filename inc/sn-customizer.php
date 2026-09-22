<?php
/**
 * Customizer settings — every site-specific thing lives here.
 *
 * Panels/sections:
 *  - Site Info            (brand + contact, feeds footer/policy/contact pages)
 *  - Announcement Bar
 *  - Homepage > Hero Banner / Service Bar / Section 1..6
 *  - Footer               (about, social, payment icons, copyright)
 *
 * @package snstore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Checkbox sanitizer.
 */
function sn_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/**
 * Select sanitizer limited to registered choices.
 */
function sn_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return array_key_exists( $input, $choices ) ? $input : $setting->default;
}

/**
 * Default configuration for homepage slot $i.
 *
 * @param int $i Slot number 1..6.
 * @return array
 */
function sn_default_slot( $i ) {
	$defaults = array(
		1 => array( 'type' => 'products', 'title' => 'Best Sellers', 'sub' => '', 'source' => 'featured', 'count' => 8, 'cols' => '4', 'layout' => 'grid' ),
		2 => array( 'type' => 'banner', 'title' => 'Mid-Season Sale', 'sub' => 'Save up to 30% on selected styles. Limited time only.', 'btn_text' => 'Shop the Sale', 'source' => '' ),
		3 => array( 'type' => 'products', 'title' => 'New Arrivals', 'sub' => '', 'source' => 'latest', 'count' => 8, 'cols' => '4', 'layout' => 'scroll' ),
		4 => array( 'type' => 'categories', 'title' => 'Shop by Category', 'sub' => '', 'source' => '' ),
		5 => array( 'type' => 'posts', 'title' => 'From the Blog', 'sub' => '', 'source' => '', 'count' => 3 ),
		6 => array( 'type' => 'newsletter', 'title' => 'Get on the list', 'sub' => 'Subscribe for early access to new arrivals and exclusive offers.', 'source' => '' ),
	);
	return isset( $defaults[ $i ] ) ? $defaults[ $i ] : array();
}

/**
 * Read a homepage slot's full config.
 *
 * @param int $i Slot number.
 * @return array
 */
function sn_get_slot( $i ) {
	$def = sn_default_slot( $i );
	return array(
		'enable'   => sn_sanitize_checkbox( get_theme_mod( "sn_sec_{$i}_enable", true ) ),
		'type'     => get_theme_mod( "sn_sec_{$i}_type", isset( $def['type'] ) ? $def['type'] : 'products' ),
		'title'    => sn_replace_tokens( get_theme_mod( "sn_sec_{$i}_title", isset( $def['title'] ) ? $def['title'] : '' ) ),
		'sub'      => sn_replace_tokens( get_theme_mod( "sn_sec_{$i}_sub", isset( $def['sub'] ) ? $def['sub'] : '' ) ),
		'source'   => get_theme_mod( "sn_sec_{$i}_source", isset( $def['source'] ) ? $def['source'] : 'latest' ),
		'count'    => (int) get_theme_mod( "sn_sec_{$i}_count", isset( $def['count'] ) ? $def['count'] : 8 ),
		'cols'     => (string) get_theme_mod( "sn_sec_{$i}_cols", isset( $def['cols'] ) ? $def['cols'] : '4' ),
		'layout'   => get_theme_mod( "sn_sec_{$i}_layout", isset( $def['layout'] ) ? $def['layout'] : 'grid' ),
		'btn_text' => get_theme_mod( "sn_sec_{$i}_btn_text", isset( $def['btn_text'] ) ? $def['btn_text'] : '' ),
		'btn_url'  => get_theme_mod( "sn_sec_{$i}_btn_url", '' ),
		'img'      => get_theme_mod( "sn_sec_{$i}_img", '' ),
		'cats'     => get_theme_mod( "sn_sec_{$i}_cats", '' ),
		'html'     => get_theme_mod( "sn_sec_{$i}_html", '' ),
	);
}

/**
 * Register everything.
 */
function sn_customize_register( $wp_customize ) {

	/* ---------------------------------------------------------------------
	 * Site Info
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sn_site_info',
		array(
			'title'       => __( 'Site Info — brand & contact', 'snstore' ),
			'priority'    => 24,
			'description' => __( 'Used in the footer, policy pages, contact page and everywhere {{email}} {{phone}} {{address}} {{hours}} {{brand}} {{domain}} tokens appear. Empty email falls back to info@your-domain.', 'snstore' ),
		)
	);

	$fields = array(
		'sn_brand_name'      => array( __( 'Brand name', 'snstore' ), 'text', __( 'Defaults to the site title.', 'snstore' ) ),
		'sn_contact_email'   => array( __( 'Email', 'snstore' ), 'text', '' ),
		'sn_contact_phone'   => array( __( 'Phone', 'snstore' ), 'text', '' ),
		'sn_contact_address' => array( __( 'Address', 'snstore' ), 'text', '' ),
		'sn_contact_hours'   => array( __( 'Service hours', 'snstore' ), 'text', __( 'e.g. Monday – Friday, 9:00 AM – 6:00 PM (PT)', 'snstore' ) ),
	);
	foreach ( $fields as $id => $data ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $data[0],
				'description' => $data[2],
				'section'     => 'sn_site_info',
				'type'        => $data[1],
			)
		);
	}

	/* ---------------------------------------------------------------------
	 * Announcement bar
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sn_announcement',
		array(
			'title'    => __( 'Announcement Bar', 'snstore' ),
			'priority' => 25,
		)
	);
	$wp_customize->add_setting(
		'sn_announcement_text',
		array(
			'default'           => 'Free Shipping And Free Returns',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'sn_announcement_text',
		array(
			'label'       => __( 'Announcement text', 'snstore' ),
			'description' => __( 'Leave empty to hide the bar. Tokens like {{brand}} are replaced.', 'snstore' ),
			'section'     => 'sn_announcement',
			'type'        => 'text',
		)
	);

	/* ---------------------------------------------------------------------
	 * Homepage panel
	 * ------------------------------------------------------------------- */
	$wp_customize->add_panel(
		'sn_home_panel',
		array(
			'title'    => __( 'Homepage', 'snstore' ),
			'priority' => 26,
		)
	);

	/* --- Hero --- */
	$wp_customize->add_section(
		'sn_hero',
		array(
			'title'       => __( 'Hero Banner (carousel)', 'snstore' ),
			'panel'       => 'sn_home_panel',
			'description' => __( 'Ships with 3 slides using bundled product imagery — replace the images and copy below. Tokens like {{brand}} are replaced.', 'snstore' ),
		)
	);
	$wp_customize->add_setting(
		'sn_hero_height',
		array(
			'default'           => 'medium',
			'sanitize_callback' => 'sn_sanitize_select',
		)
	);
	$wp_customize->add_control(
		'sn_hero_height',
		array(
			'label'   => __( 'Hero height', 'snstore' ),
			'section' => 'sn_hero',
			'type'    => 'select',
			'choices' => array(
				'small'  => __( 'Compact (420px)', 'snstore' ),
				'medium' => __( 'Medium (540px)', 'snstore' ),
				'large'  => __( 'Large (640px)', 'snstore' ),
			),
		)
	);
	for ( $i = 1; $i <= 5; $i++ ) {
		$hero_defaults = array(
			1 => array( __( 'Welcome to {{brand}}', 'snstore' ), __( 'Thoughtfully made products, fair prices and free returns.', 'snstore' ), __( 'Shop Now', 'snstore' ) ),
			2 => array( __( 'The New Collection', 'snstore' ), __( 'Fresh designs just landed — be the first to shop.', 'snstore' ), __( 'Shop New Arrivals', 'snstore' ) ),
			3 => array( __( 'Mid-Season Sale', 'snstore' ), __( 'Save up to 30% on selected favorites.', 'snstore' ), __( 'Shop the Sale', 'snstore' ) ),
		);
		$hero_def = isset( $hero_defaults[ $i ] ) ? $hero_defaults[ $i ] : array( '', '', '' );

		$wp_customize->add_setting(
			"sn_hero_{$i}_enable",
			array(
				'default'           => $i <= 3,
				'sanitize_callback' => 'sn_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			"sn_hero_{$i}_enable",
			array(
				/* translators: %d: slide number */
				'label'   => sprintf( __( 'Enable slide %d', 'snstore' ), $i ),
				'section' => 'sn_hero',
				'type'    => 'checkbox',
			)
		);
		$wp_customize->add_setting(
			"sn_hero_{$i}_img",
			array(
				'default'           => sn_hero_default_img( $i ),
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				"sn_hero_{$i}_img",
				array(
					/* translators: %d: slide number */
					'label'   => sprintf( __( 'Slide %d image', 'snstore' ), $i ),
					'section' => 'sn_hero',
				)
			)
		);
		$wp_customize->add_setting(
			"sn_hero_{$i}_title",
			array(
				'default'           => $hero_def[0],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_hero_{$i}_title",
			array(
				/* translators: %d: slide number */
				'label'   => sprintf( __( 'Slide %d heading', 'snstore' ), $i ),
				'section' => 'sn_hero',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"sn_hero_{$i}_sub",
			array(
				'default'           => $hero_def[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_hero_{$i}_sub",
			array(
				/* translators: %d: slide number */
				'label'   => sprintf( __( 'Slide %d subheading', 'snstore' ), $i ),
				'section' => 'sn_hero',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"sn_hero_{$i}_btn_text",
			array(
				'default'           => $hero_def[2],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_hero_{$i}_btn_text",
			array(
				/* translators: %d: slide number */
				'label'   => sprintf( __( 'Slide %d button text', 'snstore' ), $i ),
				'section' => 'sn_hero',
				'type'    => 'text',
			)
		);
		$wp_customize->add_setting(
			"sn_hero_{$i}_btn_url",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			"sn_hero_{$i}_btn_url",
			array(
				/* translators: %d: slide number */
				'label'   => sprintf( __( 'Slide %d button link', 'snstore' ), $i ),
				'section' => 'sn_hero',
				'type'    => 'url',
			)
		);
	}

	/* --- USP bar --- */
	$wp_customize->add_section(
		'sn_usp',
		array(
			'title' => __( 'Service Bar (USP)', 'snstore' ),
			'panel' => 'sn_home_panel',
		)
	);
	$wp_customize->add_setting(
		'sn_usp_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'sn_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'sn_usp_enable',
		array(
			'label'   => __( 'Show service bar', 'snstore' ),
			'section' => 'sn_usp',
			'type'    => 'checkbox',
		)
	);
	$usp_icons = array(
		'truck'   => __( 'Truck (shipping)', 'snstore' ),
		'return'  => __( 'Arrow loop (returns)', 'snstore' ),
		'shield'  => __( 'Shield (secure)', 'snstore' ),
		'support' => __( 'Chat (support)', 'snstore' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		$wp_customize->add_setting(
			"sn_usp_{$i}_icon",
			array(
				'default'           => array_keys( $usp_icons )[ $i - 1 ],
				'sanitize_callback' => 'sn_sanitize_select',
			)
		);
		$wp_customize->add_control(
			"sn_usp_{$i}_icon",
			array(
				/* translators: %d: item number */
				'label'   => sprintf( __( 'Item %d icon', 'snstore' ), $i ),
				'section' => 'sn_usp',
				'type'    => 'select',
				'choices' => $usp_icons,
			)
		);
		$wp_customize->add_setting(
			"sn_usp_{$i}_text",
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_usp_{$i}_text",
			array(
				/* translators: %d: item number */
				'label'   => sprintf( __( 'Item %d text', 'snstore' ), $i ),
				'section' => 'sn_usp',
				'type'    => 'text',
			)
		);
	}

	/* --- Flexible sections 1..6 --- */
	$source_options = array(
		'latest'   => __( 'Latest products', 'snstore' ),
		'featured' => __( 'Featured products', 'snstore' ),
		'on_sale'  => __( 'On-sale products', 'snstore' ),
	);
	$cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'parent'     => 0,
		)
	);
	if ( ! is_wp_error( $cats ) ) {
		foreach ( $cats as $cat ) {
			/* translators: %s: category name */
			$source_options[ 'cat:' . $cat->slug ] = sprintf( __( 'Category: %s', 'snstore' ), $cat->name );
		}
	}
	$tags = get_terms(
		array(
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		)
	);
	if ( ! is_wp_error( $tags ) ) {
		foreach ( $tags as $tag ) {
			/* translators: %s: tag name */
			$source_options[ 'tag:' . $tag->slug ] = sprintf( __( 'Tag: %s', 'snstore' ), $tag->name );
		}
	}

	$type_choices = array(
		'products'   => __( 'Product row', 'snstore' ),
		'banner'     => __( 'Promo banner', 'snstore' ),
		'categories' => __( 'Category tiles', 'snstore' ),
		'posts'      => __( 'Blog posts', 'snstore' ),
		'newsletter' => __( 'Newsletter', 'snstore' ),
		'richtext'   => __( 'Rich text / HTML', 'snstore' ),
	);

	for ( $i = 1; $i <= 6; $i++ ) {
		$wp_customize->add_section(
			"sn_sec_{$i}",
			array(
				/* translators: %d: section number */
				'title' => sprintf( __( 'Homepage Section %d', 'snstore' ), $i ),
				'panel' => 'sn_home_panel',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_enable",
			array(
				'default'           => true,
				'sanitize_callback' => 'sn_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_enable",
			array(
				'label'   => __( 'Show this section', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'checkbox',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_type",
			array(
				'default'           => sn_default_slot( $i )['type'] ?? 'products',
				'sanitize_callback' => 'sn_sanitize_select',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_type",
			array(
				'label'   => __( 'Section type', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'select',
				'choices' => $type_choices,
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_title",
			array(
				'default'           => sn_default_slot( $i )['title'] ?? '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_title",
			array(
				'label'   => __( 'Heading', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_sub",
			array(
				'default'           => sn_default_slot( $i )['sub'] ?? '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_sub",
			array(
				'label'       => __( 'Subheading', 'snstore' ),
				'description' => __( 'For promo banners this is the banner text.', 'snstore' ),
				'section'     => "sn_sec_{$i}",
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_source",
			array(
				'default'           => sn_default_slot( $i )['source'] ?? 'latest',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_source",
			array(
				'label'       => __( 'Products source', 'snstore' ),
				'description' => __( 'Empty/missing sources fall back to latest products. Category tiles: comma-separated category slugs (max 4, empty = first categories).', 'snstore' ),
				'section'     => "sn_sec_{$i}",
				'type'        => 'select',
				'choices'     => $source_options,
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_count",
			array(
				'default'           => sn_default_slot( $i )['count'] ?? 8,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_count",
			array(
				'label'   => __( 'Items to show', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'number',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_cols",
			array(
				'default'           => sn_default_slot( $i )['cols'] ?? '4',
				'sanitize_callback' => 'sn_sanitize_select',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_cols",
			array(
				'label'   => __( 'Grid columns', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'select',
				'choices' => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_layout",
			array(
				'default'           => sn_default_slot( $i )['layout'] ?? 'grid',
				'sanitize_callback' => 'sn_sanitize_select',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_layout",
			array(
				'label'   => __( 'Layout', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'select',
				'choices' => array(
					'grid'   => __( 'Grid', 'snstore' ),
					'scroll' => __( 'Horizontal scroll row', 'snstore' ),
				),
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_btn_text",
			array(
				'default'           => sn_default_slot( $i )['btn_text'] ?? '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_btn_text",
			array(
				'label'   => __( 'Button text', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_btn_url",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_btn_url",
			array(
				'label'       => __( 'Button link', 'snstore' ),
				'description' => __( 'Empty = shop page for product sections.', 'snstore' ),
				'section'     => "sn_sec_{$i}",
				'type'        => 'url',
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_img",
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				"sn_sec_{$i}_img",
				array(
					'label'       => __( 'Banner image', 'snstore' ),
					'description' => __( 'Promo banner background. Empty = bundled default backdrop.', 'snstore' ),
					'section'     => "sn_sec_{$i}",
				)
			)
		);

		$wp_customize->add_setting(
			"sn_sec_{$i}_html",
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
			)
		);
		$wp_customize->add_control(
			"sn_sec_{$i}_html",
			array(
				'label'   => __( 'Rich text content', 'snstore' ),
				'section' => "sn_sec_{$i}",
				'type'    => 'textarea',
			)
		);
	}

	/* ---------------------------------------------------------------------
	 * Footer
	 * ------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sn_footer',
		array(
			'title'       => __( 'Footer', 'snstore' ),
			'priority'    => 27,
			'description' => __( 'Footer link columns are WP menus: "Footer — Customer Service" and "Footer — Company" (created automatically on activation).', 'snstore' ),
		)
	);
	$wp_customize->add_setting(
		'sn_footer_about',
		array(
			'default'           => __( 'Useful products, fair prices and friendly support — delivered to your door.', 'snstore' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'sn_footer_about',
		array(
			'label'   => __( 'Footer blurb', 'snstore' ),
			'section' => 'sn_footer',
			'type'    => 'textarea',
		)
	);
	$socials = array(
		'sn_social_instagram' => __( 'Instagram URL', 'snstore' ),
		'sn_social_facebook'  => __( 'Facebook URL', 'snstore' ),
		'sn_social_tiktok'    => __( 'TikTok URL', 'snstore' ),
		'sn_social_youtube'   => __( 'YouTube URL', 'snstore' ),
		'sn_social_x'         => __( 'X / Twitter URL', 'snstore' ),
		'sn_social_pinterest' => __( 'Pinterest URL', 'snstore' ),
	);
	foreach ( $socials as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $label,
				'description' => __( 'Leave empty to hide the icon.', 'snstore' ),
				'section'     => 'sn_footer',
				'type'        => 'url',
			)
		);
	}
	$wp_customize->add_setting(
		'sn_footer_payments',
		array(
			'default'           => true,
			'sanitize_callback' => 'sn_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'sn_footer_payments',
		array(
			'label'   => __( 'Show payment icons', 'snstore' ),
			'section' => 'sn_footer',
			'type'    => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'sn_footer_copyright',
		array(
			'default'           => __( '© {{year}} {{brand}}. All rights reserved.', 'snstore' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'sn_footer_copyright',
		array(
			'label'   => __( 'Copyright line', 'snstore' ),
			'section' => 'sn_footer',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'sn_customize_register' );
