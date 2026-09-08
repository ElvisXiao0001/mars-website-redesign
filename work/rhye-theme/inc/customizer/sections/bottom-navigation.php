<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority                      = 1;
$bottom_nav_enabled            = array(
	'setting' => 'portfolio_nav_enabled',
	'value'   => true,
);
$bottom_nav_is_auto_scroll     = array(
	'setting' => 'portfolio_nav_style',
	'value'   => 'portfolio-auto-scroll',
);
$bottom_nav_is_prev_next_hover = array(
	'setting' => 'portfolio_nav_style',
	'value'   => 'portfolio-prev-next-hover',
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'portfolio_nav_enabled',
		'label'       => esc_html__( 'Enable Bottom Navigation', 'rhye' ),
		'description' => esc_html__( 'Appears at the bottom of the selected posts types.', 'rhye' ),
		'section'     => 'bottom_nav',
		'default'     => true,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'portfolio_nav_post_types',
		'label'           => esc_html__( 'Post Types', 'rhye' ),
		'description'     => esc_html__( 'Choose post types where the bottom navigation will be displayed.', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => array( 'arts_portfolio_item' ),
		'priority'        => $priority++,
		'multiple'        => 999,
		'choices'         => array(
			'arts_portfolio_item' => esc_html__( 'Portfolio Items', 'rhye' ),
			'arts_service'        => esc_html__( 'Services', 'rhye' ),
			'arts_album'          => esc_html__( 'Albums', 'rhye' ),
			'page'                => esc_html__( 'Pages', 'rhye' ),
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Radio(
	array(
		'settings'        => 'portfolio_nav_include_portfolio_taxonomies',
		'label'           => esc_html__( 'Portfolio Items', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => '',
		'priority'        => $priority++,
		'choices'         => array(
			''                        => esc_html__( 'Loop navigation for all published portfolio items.', 'rhye' ),
			'arts_portfolio_category' => esc_html__( 'Loop navigation only for portfolio items of the same category.', 'rhye' ),
		),
		'active_callback' => array(
			$bottom_nav_enabled,
			array(
				'setting'  => 'portfolio_nav_post_types',
				'operator' => 'contains',
				'value'    => 'arts_portfolio_item',
			),
		),
	)
);

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'        => 'portfolio_nav_style',
		'label'           => esc_html__( 'Navigation Style', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'portfolio-auto-scroll',
		'priority'        => $priority++,
		'choices'         => array(
			'portfolio-auto-scroll'     => esc_html__( 'Auto Scroll Next', 'rhye' ),
			'portfolio-prev-next-hover' => esc_html__( 'Prev & Next Hover', 'rhye' ),
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'portfolio_nav_scroll_down_enabled',
		'label'           => esc_html__( 'Enable Scroll Down Button', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			$bottom_nav_enabled,
			$bottom_nav_is_auto_scroll,
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'portfolio_nav_image_transition_enabled',
		'label'           => esc_html__( 'Enable Image Transition', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'portfolio_loop_enabled',
		'label'           => esc_html__( 'Enable Loop Navigation', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			$bottom_nav_enabled,
			$bottom_nav_is_prev_next_hover,
		),
	)
);

new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'portfolio_next_first_mobile',
		'label'           => esc_html__( 'Enable "Next" Item to Appear as First in the Stack on Mobiles', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => true,
		'priority'        => $priority++,
		'active_callback' => array(
			$bottom_nav_enabled,
			$bottom_nav_is_prev_next_hover,
		),
	)
);

new \Kirki\Field\Text(
	array(
		'description'     => esc_html__( 'Scroll Down Label', 'rhye' ),
		'settings'        => 'portfolio_nav_scroll_down_label',
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'default'         => esc_html__( 'Keep Scrolling', 'rhye' ),
		'active_callback' => array(
			$bottom_nav_enabled,
			$bottom_nav_is_auto_scroll,
			array(
				'setting' => 'portfolio_nav_scroll_down_enabled',
				'value'   => true,
			),
		),
	)
);

new \Kirki\Field\Text(
	array(
		'description'     => esc_html__( 'Previous Label', 'rhye' ),
		'settings'        => 'portfolio_nav_prev_label',
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'default'         => esc_html__( 'Previous Project', 'rhye' ),
		'transport'       => 'postMessage',
		'active_callback' => array(
			$bottom_nav_enabled,
			$bottom_nav_is_prev_next_hover,
		),
	)
);

new \Kirki\Field\Text(
	array(
		'description'     => esc_html__( 'Next Label', 'rhye' ),
		'settings'        => 'portfolio_nav_next_label',
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'default'         => esc_html__( 'Next Project', 'rhye' ),
		'active_callback' => array( $bottom_nav_enabled ),
		'transport'       => 'postMessage',
	)
);

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'        => 'portfolio_nav_direction',
		'label'           => esc_html__( 'Posts Navigation Direction', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'forward',
		'priority'        => $priority++,
		'choices'         => array(
			'backward' => esc_html__( 'Old -> New', 'rhye' ),
			'forward'  => esc_html__( 'New -> Old', 'rhye' ),
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

/**
 * Typography
 */
new \Kirki\Field\Generic(
	array(
		'settings'        => 'portfolio_nav_generic_heading' . $priority,
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'hr',
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Typography', 'rhye' ),
		'settings'        => 'portfolio_nav_generic_heading' . $priority,
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'portfolio_nav_headings_preset',
		'description'     => esc_html__( 'Headings', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'h1',
		'priority'        => $priority++,
		'choices'         => ARTS_THEME_TYPOGRAHY_ARRAY,
		'transport'       => 'postMessage',
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'portfolio_nav_labels_preset',
		'description'     => esc_html__( 'Labels', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'subheading',
		'priority'        => $priority++,
		'choices'         => ARTS_THEME_TYPOGRAHY_ARRAY,
		'transport'       => 'postMessage',
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

/**
 * Color Theme
 */
new \Kirki\Field\Generic(
	array(
		'settings'        => 'portfolio_nav_generic_heading' . $priority,
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'hr',
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Generic(
	array(
		'label'           => esc_html__( 'Color Theme', 'rhye' ),
		'settings'        => 'portfolio_nav_generic_heading' . $priority,
		'section'         => 'bottom_nav',
		'priority'        => $priority++,
		'choices'         => array(
			'element' => 'span',
		),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'portfolio_nav_background',
		'description'     => esc_html__( 'Background Color', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'bg-light-1',
		'priority'        => $priority++,
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'choices'         => ARTS_THEME_COLORS_ARRAY,
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

new \Kirki\Field\Select(
	array(
		'settings'        => 'portfolio_nav_theme',
		'description'     => esc_html__( 'Main Elements Color', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => 'dark',
		'priority'        => $priority++,
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'choices'         => ARTS_THEME_COLOR_THEMES_ARRAY,
		'active_callback' => array( $bottom_nav_enabled ),
	)
);

/**
 * Border
 */
new \Kirki\Field\Checkbox(
	array(
		'settings'        => 'portfolio_nav_divider_enabled',
		'label'           => esc_html__( 'Enable Section Divider', 'rhye' ),
		'section'         => 'bottom_nav',
		'default'         => true,
		'priority'        => $priority++,
		'tooltip'         => esc_html__( 'This option may be overriden for the current page from Elementor document settings.', 'rhye' ),
		'active_callback' => array( $bottom_nav_enabled ),
	)
);
