<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'inject_preload_prefetch_links_enabled',
		'label'       => esc_html__( 'Inject Preload & Prefetch Links', 'rhye' ),
		'description' => esc_html__( 'Discover theme assets that are used on the site pages and inject <link rel="preload"> and <link rel="prefetch"> tags into the head of the document for improved performance.', 'rhye' ),
		'section'     => 'performance',
		'default'     => true,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'        => 'enqueue_preloaded_css_as_stylesheets_enabled',
		'label'           => esc_html__( 'Enqueue Preloaded CSS as Stylesheets', 'rhye' ),
		'description'     => esc_html__( 'Enqueue the preloaded CSS files as stylesheets for improved performance. This is an experimental feature.', 'rhye' ),
		'section'         => 'performance',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting' => 'inject_preload_prefetch_links_enabled',
				'value'   => true,
			),
		),
		'priority'        => $priority++,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'html_minification_enabled',
		'label'       => esc_html__( 'HTML Minification', 'rhye' ),
		'description' => esc_html__( 'Minify the HTML markup of the theme pages for reduced transfer size.', 'rhye' ),
		'section'     => 'performance',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'performance_generic_divider' . $priority,
		'section'  => 'performance',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'high_performance_gpu_enabled',
		'label'       => esc_html__( 'Prefer High-Performance GPU', 'rhye' ),
		'description' => esc_html__( 'Tell desktop browsers to use high-performance GPU on dual GPU systems for the website rendering. Doesn\'t have an effect on touch devices.', 'rhye' ),
		'section'     => 'performance',
		'default'     => true,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Generic(
	array(
		'settings' => 'performance_generic_divider' . $priority,
		'section'  => 'performance',
		'priority' => $priority++,
		'choices'  => array(
			'element' => 'hr',
		),
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'full_size_images_enabled',
		'label'       => esc_html__( 'Force Load Full Size Images', 'rhye' ),
		'description' => esc_html__( 'Always use the original images uploaded to media library and don\'t use the WordPress generated thumbnails.', 'rhye' ),
		'section'     => 'performance',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Checkbox_Switch(
	array(
		'settings'    => 'seo_noscript_images_enabled',
		'label'       => esc_html__( 'Render SEO-friendly Images', 'rhye' ),
		'description' => esc_html__( 'When enabled, a non-lazy image wrapped to <noscript> will be rendered for every lazy image. This might help search engine crawlers to correctly index the site images.', 'rhye' ),
		'section'     => 'performance',
		'default'     => false,
		'priority'    => $priority++,
	)
);

new \Kirki\Field\Radio_Buttonset(
	array(
		'settings'    => 'lazy_placeholder_type',
		'label'       => esc_html__( 'Lazy Placeholder', 'rhye' ),
		'description' => esc_html__( 'Temporary lightweight image that appears before a lazy image is fully loaded.', 'rhye' ),
		'section'     => 'performance',
		'default'     => 'inline',
		'priority'    => $priority++,
		'choices'     => array(
			'inline'       => esc_html__( 'Inline Source', 'rhye' ),
			'custom_image' => esc_html__( 'Custom Image', 'rhye' ),
		),
	)
);

new \Kirki\Field\Textarea(
	array(
		'settings'        => 'lazy_placeholder_inline',
		'description'     => esc_html__( 'Base64 encoded image or external URL that will be appended to <img src="..."> attribute.', 'rhye' ),
		'section'         => 'performance',
		'default'         => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAHCGzyUAAAABGdBTUEAALGPC/xhBQAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAAaADAAQAAAABAAAAAQAAAADa6r/EAAAAC0lEQVQI12NolQQAASYAn89qhTcAAAAASUVORK5CYII=',
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'lazy_placeholder_type',
				'operator' => '==',
				'value'    => 'inline',
			),
		),
	)
);

new \Kirki\Field\Image(
	array(
		'settings'        => 'lazy_placeholder_image_url',
		'section'         => 'performance',
		'default'         => '',
		'priority'        => $priority++,
		'active_callback' => array(
			array(
				'setting'  => 'lazy_placeholder_type',
				'operator' => '==',
				'value'    => 'custom_image',
			),
		),
	)
);
