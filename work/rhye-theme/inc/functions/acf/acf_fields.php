<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Hide ACF Menu
 */
add_filter( 'acf/settings/show_admin', '__return_false' );

add_action( 'acf/init', 'arts_acf_register_fields' );
if ( ! function_exists( 'arts_acf_register_fields' ) ) {
	/**
	 * Register ACF Fields
	 */
	function arts_acf_register_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			// Additional posts content
			acf_add_local_field_group(
				array(
					'key'                   => 'group_5cd5a9f03a2a8',
					'title'                 => esc_html__( 'Additional Content', 'rhye' ),
					'fields'                => array(
						array(
							'key'               => 'field_5cd5aa039713f',
							'label'             => esc_html__( 'Properties', 'rhye' ),
							'name'              => 'properties',
							'type'              => 'repeater',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'collapsed'         => '',
							'min'               => 0,
							'max'               => 0,
							'layout'            => 'table',
							'button_label'      => '',
							'sub_fields'        => array(
								array(
									'key'               => 'field_5cd5aa1697140',
									'label'             => esc_html__( 'Name', 'rhye' ),
									'name'              => 'name',
									'type'              => 'text',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								),
								array(
									'key'               => 'field_5cd5ad1e97141',
									'label'             => esc_html__( 'List', 'rhye' ),
									'name'              => 'list',
									'type'              => 'repeater',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'collapsed'         => '',
									'min'               => 0,
									'max'               => 0,
									'layout'            => 'table',
									'button_label'      => '',
									'sub_fields'        => array(
										array(
											'key'          => 'field_5cd5ad4a97143',
											'label'        => esc_html__( 'Value', 'rhye' ),
											'name'         => 'value',
											'type'         => 'text',
											'instructions' => '',
											'required'     => 0,
											'conditional_logic' => 0,
											'wrapper'      => array(
												'width' => '',
												'class' => '',
												'id'    => '',
											),
											'default_value' => '',
											'placeholder'  => '',
											'prepend'      => '',
											'append'       => '',
											'maxlength'    => '',
										),
									),
								),
							),
						),
						array(
							'key'               => 'field_5d591b7f53d12',
							'label'             => esc_html__( 'Secondary Featured Image', 'rhye' ),
							'name'              => 'secondary_image',
							'type'              => 'image',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'id',
							'preview_size'      => 'medium',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => '',
						),
						array(
							'key'               => 'field_599f76f5fa1eb',
							'label'             => esc_html__( 'Featured Video', 'rhye' ),
							'name'              => 'featured_video',
							'type'              => 'file',
							'instructions'      => esc_html__( 'Set featured video', 'rhye' ),
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'object',
							'library'           => 'all',
							'min_size'          => '',
							'max_size'          => '',
							'mime_types'        => 'mp4, webm, ogg, ogv',
						),
						array(
							'key'               => 'field_5f0bf6f07954f',
							'label'             => esc_html__( 'Subheading', 'rhye' ),
							'name'              => 'subheading',
							'type'              => 'text',
							'instructions'      => esc_html__( 'This field won\'t appear if there is a portfolio category assigned to this post.', 'rhye' ),
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5d55449378eb9',
							'label'             => 'Text',
							'name'              => 'text',
							'type'              => 'textarea',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'rows'              => '',
							'new_lines'         => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'page',
							),
						),
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'arts_portfolio_item',
							),
						),
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'arts_service',
							),
						),
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'arts_album',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'acf_after_title',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

			// Albums content
			acf_add_local_field_group(
				array(
					'key'                   => 'group_5f0ee2e8b9534',
					'title'                 => esc_html__( 'Album Content', 'rhye' ),
					'fields'                => array(
						array(
							'key'               => 'field_5f0ee3f095a2e',
							'label'             => esc_html__( 'Media Gallery', 'rhye' ),
							'name'              => 'media_gallery',
							'type'              => 'gallery',
							'instructions'      => esc_html__( 'Images & self-hosted videos', 'rhye' ),
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'preview_size'      => 'medium',
							'insert'            => 'append',
							'library'           => 'all',
							'min'               => '',
							'max'               => '',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => 'jpg, jpeg, gif, webp, webp, svg, mp4, webm, ogg, ogv',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'arts_album',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

			// Custom fonts fields
			acf_add_local_field_group(
				array(
					'key'                   => 'group_5da01c79399ce',
					'title'                 => esc_html__( 'Custom Fonts', 'rhye' ),
					'fields'                => array(
						array(
							'key'               => 'field_5da01caa50c9a',
							'label'             => '',
							'name'              => 'custom_fonts',
							'type'              => 'repeater',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'collapsed'         => 'field_5da01cfa50c9b',
							'min'               => 0,
							'max'               => 0,
							'layout'            => 'block',
							'button_label'      => esc_html__( 'Add Custom Font', 'rhye' ),
							'sub_fields'        => array(
								array(
									'key'               => 'field_5da01cfa50c9b',
									'label'             => esc_html__( 'Font Family Name', 'rhye' ),
									'name'              => 'font_name',
									'type'              => 'text',
									'instructions'      => '',
									'required'          => 1,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								),
								array(
									'key'               => 'field_5da3c469ba39d',
									'label'             => esc_html__( 'Font Display', 'rhye' ),
									'name'              => 'font_display',
									'type'              => 'select',
									'instructions'      => sprintf(
										'<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display" target="_blank">%1s</a> %2s',
										esc_html__( 'More information', 'rhye' ),
										esc_html__( 'related to "font-display" descriptor.', 'rhye' )
									),
									'required'          => 1,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'choices'           => array(
										'auto'     => 'auto',
										'block'    => 'block',
										'swap'     => 'swap',
										'fallback' => 'fallback',
										'optional' => 'optional',
									),
									'default_value'     => array(
										0 => 'auto',
									),
									'allow_null'        => 0,
									'multiple'          => 0,
									'ui'                => 0,
									'return_format'     => 'value',
									'ajax'              => 0,
									'placeholder'       => '',
								),
								array(
									'key'               => 'field_5da01d3550c9c',
									'label'             => esc_html__( 'Font Files', 'rhye' ),
									'name'              => 'font_files',
									'type'              => 'repeater',
									'instructions'      => '',
									'required'          => 1,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'collapsed'         => 'field_5da01e9550c9e',
									'min'               => 0,
									'max'               => 0,
									'layout'            => 'block',
									'button_label'      => esc_html__( 'Add Font File', 'rhye' ),
									'sub_fields'        => array(
										array(
											'key'          => 'field_5da01d7250c9d',
											'label'        => 'Font File',
											'name'         => 'font_file',
											'type'         => 'file',
											'instructions' => esc_html__( 'Upload .woff or .woff2 font file', 'rhye' ),
											'required'     => 1,
											'conditional_logic' => 0,
											'wrapper'      => array(
												'width' => '',
												'class' => '',
												'id'    => '',
											),
											'return_format' => 'array',
											'library'      => 'all',
											'min_size'     => '',
											'max_size'     => '',
											'mime_types'   => 'woff, woff2',
										),
										array(
											'key'          => 'field_5da01e9550c9e',
											'label'        => esc_html__( 'Font Weight', 'rhye' ),
											'name'         => 'font_weight',
											'type'         => 'select',
											'instructions' => esc_html__( 'Select a font weight of the uploaded font file', 'rhye' ),
											'required'     => 1,
											'conditional_logic' => 0,
											'wrapper'      => array(
												'width' => '',
												'class' => '',
												'id'    => '',
											),
											'choices'      => array(
												100 => esc_html__( '100 (Thin)', 'rhye' ),
												'100italic' => esc_html__( '100i (Thin Italic)', 'rhye' ),
												200 => esc_html__( ' 200 (Ultra Light)', 'rhye' ),
												'200italic' => esc_html__( '200i (Ultra Light Italic)', 'rhye' ),
												300 => esc_html__( ' 300 (Light)', 'rhye' ),
												'300italic' => esc_html__( '300i (Light Italic)', 'rhye' ),
												400 => esc_html__( '400 (Regular)', 'rhye' ),
												'400italic' => esc_html__( '400i (Regular Italic)', 'rhye' ),
												500 => esc_html__( '500 (Medium)', 'rhye' ),
												'500italic' => esc_html__( '500i (Medium Italic)', 'rhye' ),
												600 => esc_html__( '600 (Semi Bold)', 'rhye' ),
												'600italic' => esc_html__( '600i (Semi Bold Italic)', 'rhye' ),
												700 => esc_html__( '700 (Bold)', 'rhye' ),
												'700italic' => esc_html__( '700i (Bold Italic)', 'rhye' ),
												800 => esc_html__( '800 (Extra Bold)', 'rhye' ),
												'800italic' => esc_html__( '800i (Extra Bold Italic)', 'rhye' ),
												900 => esc_html__( '900 (Black)', 'rhye' ),
												'900italic' => esc_html__( '900i (Black Italic)', 'rhye' ),
											),
											'default_value' => array(),
											'allow_null'   => 0,
											'multiple'     => 0,
											'ui'           => 0,
											'return_format' => 'value',
											'ajax'         => 0,
											'placeholder'  => '',
										),
									),
								),
							),
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'options_page',
								'operator' => '==',
								'value'    => 'custom-fonts-settings',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

			// Additional media fields
			acf_add_local_field_group(
				array(
					'key'                   => 'group_5f19bda9c9e9d',
					'title'                 => esc_html__( 'Additional Media Fields', 'rhye' ),
					'fields'                => array(
						array(
							'key'               => 'field_5f19be3f007f2',
							'label'             => esc_html__( 'External URL', 'rhye' ),
							'name'              => 'external_media',
							'type'              => 'link',
							'instructions'      => esc_html__( 'Internal Page or External Media Source (Youtube, Vimeo)', 'rhye' ),
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'attachment',
								'operator' => '==',
								'value'    => 'image',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
		}

		/**
		 * Custom Fonts Admin Page
		 */
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title'      => 'Custom Fonts',
					'menu_title'      => 'Custom Fonts',
					'menu_slug'       => 'custom-fonts-settings',
					'capability'      => 'customize',
					'icon_url'        => 'dashicons-editor-textcolor',
					'update_button'   => esc_html__( 'Save Changes', 'rhye' ),
					'updated_message' => sprintf(
						'%1s <a href="%2s" target="_blank">%3s</a>',
						esc_html__( 'Fonts are saved and ready to use from', 'rhye' ),
						admin_url( 'customize.php' ),
						esc_html__( 'WordPress Customizer.', 'rhye' )
					),
				)
			);
		}
	}
}
