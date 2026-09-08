<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$priority = 1;
$choices  = arts_add_fonts_custom_choice();

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Parallax Big Letters', 'rhye' ),
		'settings'  => 'projects_big_letters_font',
		'section'   => 'typography_misc',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => 'regular',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.figure-project__letter, .section-services__letter',
			),
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Blog Pagination', 'rhye' ),
		'settings'  => 'blog_pagination_font',
		'section'   => 'typography_misc',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => 'regular',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.pagination, .page-links .page-number',
			),
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Blog Comments Author', 'rhye' ),
		'settings'  => 'blog_comments_author_font',
		'section'   => 'typography_misc',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => '700',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.comment-body .fn',
			),
		),
	)
);

new \Kirki\Field\Typography(
	array(
		'label'     => esc_html__( 'Widgets Additional Info', 'rhye' ),
		'settings'  => 'widgets_additional_info_font',
		'section'   => 'typography_misc',
		'default'   => array(
			'font-family' => 'Cinzel',
			'variant'     => '700',
		),
		'priority'  => $priority++,
		'choices'   => $choices,
		'transport' => 'auto',
		'output'    => array(
			array(
				'element' => '.widget_recent_comments ul li a, .widget_recent_entries ul li a, .widget_rss .rsswidget',
			),
		),
	)
);
