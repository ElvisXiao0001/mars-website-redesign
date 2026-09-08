<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'arts_the_lazy_image' ) ) {
	/**
	 * Print the lazy background/image/video HTML markup.
	 *
	 * @param array $args {
	 *  Optional. An array of arguments.
	 *
	 *  @type int    $id            The attachment ID.
	 *  @type string $type          The type of the image. Default is 'background'.
	 *  @type string $fetchpriority The fetch priority of the image. Default is null.
	 *  @type string $size          The image size. Default is 'full'.
	 *  @type string $caption       The caption of the image. Default is null.
	 *  @type array  $class         The classes for the image. Default is empty array.
	 *  @type array  $parallax      The parallax settings. Default is empty array.
	 *  @type bool   $animation     Whether to enable the animation. Default is false.
	 *  @type bool   $mask          Whether to enable the mask. Default is false.
	 *  @type bool   $overlay       Whether to enable the overlay. Default is false.
	 * }
	 */
	function arts_the_lazy_image( $args ) {
		$defaults = array(
			'id'            => null,
			'type'          => 'background',
			'fetchpriority' => null,
			'size'          => 'full',
			'caption'       => null,
			'class'         => array(
				'section' => array(),
				'wrapper' => array(),
				'image'   => array(),
				'caption' => array(),
				'overlay' => array(),
			),
			'parallax'      => array(
				'enabled' => false,
				'factor'  => 0.1,
			),
			'animation'     => false,
			'mask'          => false,
			'overlay'       => false,
		);

		$class_section = '';
		$attrs_section = '';

		$attrs_wrapper = '';
		$class_wrapper = '';

		$class_caption = '';

		$class_media = '';

		$class_overlay = '';

		$lazy_placeholder_src       = '#';
		$lazy_placeholder_type      = get_theme_mod( 'lazy_placeholder_type', 'inline' );
		$lazy_placeholder_inline    = get_theme_mod ( 'lazy_placeholder_inline', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAHCGzyUAAAABGdBTUEAALGPC/xhBQAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAAaADAAQAAAABAAAAAQAAAADa6r/EAAAAC0lEQVQI12NolQQAASYAn89qhTcAAAAASUVORK5CYII=');
		$lazy_placeholder_image_url = get_theme_mod( 'lazy_placeholder_image_url', '' );
		$seo_noscript_images_enabled = get_theme_mod( 'seo_noscript_images_enabled', false);

		if ( $lazy_placeholder_type === 'inline' && ! empty( $lazy_placeholder_inline ) ) {
			$lazy_placeholder_src = $lazy_placeholder_inline;
		}

		if ( $lazy_placeholder_type === 'custom_image' && ! empty( $lazy_placeholder_image_url ) ) {
			$lazy_placeholder_src = $lazy_placeholder_image_url;
		}

		$args = wp_parse_args( $args, $defaults );

		if ( ! $args['id'] || ! $args['type'] ) {
			return;
		}

		$fetch_priority_allowed_values = array(
			'auto',
			'high',
			'low',
		);
		$fetch_priority_value = null;

		if ( in_array( $args['fetchpriority'], $fetch_priority_allowed_values ) ) {
			$fetch_priority_value = $args['fetchpriority'];
		}

		// section
		if ( array_key_exists( 'section', $args['class'] ) && is_array( $args['class']['section'] ) && ! empty( $args['class']['section'] ) ) {
			$class_section = implode( ' ', $args['class']['section'] );
		}

		// wrapper
		if ( array_key_exists( 'wrapper', $args['class'] ) && is_array( $args['class']['wrapper'] ) && ! empty( $args['class']['wrapper'] ) ) {
			$class_wrapper = implode( ' ', $args['class']['wrapper'] );
		}

		// caption
		if ( array_key_exists( 'caption', $args['class'] ) && is_array( $args['class']['caption'] ) && ! empty( $args['class']['caption'] ) ) {
			$class_caption = implode( ' ', $args['class']['caption'] );
		}

		// image
		if ( array_key_exists( 'image', $args['class'] ) && is_array( $args['class']['image'] ) && ! empty( $args['class']['image'] ) ) {
			$class_media = implode( ' ', $args['class']['image'] );
		}

		// parallax
		if ( array_key_exists( 'parallax', $args ) && is_array( $args['parallax'] ) && $args['parallax']['enabled'] ) {
			$class_wrapper .= ' overflow';
			$attrs_section .= ' data-arts-parallax=true';
			$attrs_section .= ' data-arts-parallax-factor=' . floatval( $args['parallax']['factor'] );
		}

		// overlay
		if ( $args['overlay'] && array_key_exists( 'overlay', $args['class'] ) && is_array( $args['class']['overlay'] ) && ! empty( $args['class']['overlay'] ) ) {
			$class_overlay = implode( ' ', $args['class']['overlay'] );
		}

		// mask
		if ( $args['mask'] ) {
			$class_wrapper .= ' mask-reveal';
		}

		// animation
		if ( $args['animation'] ) {
			$attrs_section .= ' data-arts-os-animation=true';
		}

		switch ( $args['type'] ) {
			case 'background':
				if ( $args['class']['wrapper'] !== false ) {
					$class_wrapper .= ' lazy-bg';
				}
				$class_media .= ' of-cover';
				break;
			case 'image':
				if ( $args['class']['wrapper'] !== false ) {
					$class_wrapper .= ' lazy';
				}
				break;
			case 'texture':
				if ( $args['class']['wrapper'] !== false ) {
					$class_wrapper .= ' lazy';
				}
				break;
			case 'background-video':
				$class_media .= ' of-cover';
				break;
			case 'video':
				if ( $args['class']['wrapper'] !== false ) {
					$class_wrapper .= ' lazy-video';
				}
				break;
		}

		if ( $args['type'] === 'background' || $args['type'] === 'image' || $args['type'] === 'texture' ) {
			$full_size_images_enabled = get_theme_mod( 'full_size_images_enabled', false );
			$attrs  = wp_get_attachment_image_src( $args['id'], $args['size'] );

			if ( ! is_array( $attrs ) || ! isset( $attrs[0], $attrs[1], $attrs[2] ) ) {
				return;
			}

			$srcset = '';
			$sizes  = '';
			$alt    = get_post_meta( $args['id'], '_wp_attachment_image_alt', true );

			if ( ! $full_size_images_enabled ) {
				$srcset = wp_get_attachment_image_srcset( $args['id'], $args['size'] );
				$sizes  = wp_get_attachment_image_sizes( $args['id'], $args['size'] );
			}
		}

		if ( $args['type'] === 'video' ) {
			$video_metadata = wp_get_attachment_metadata( $args['id'] );
			$video_width = ! empty( $video_metadata['width'] ) ? $video_metadata['width'] : 1;
			$video_height = ! empty( $video_metadata['height'] ) ? $video_metadata['height'] : 1;
		}

		?>
		<?php if ( ! empty( $class_section ) || ! empty( $attrs_section ) ) : ?>
			<div class="<?php echo esc_attr( trim( $class_section ) ); ?>" <?php echo esc_attr( trim( $attrs_section ) ); ?>>
		<?php endif; ?>
			<?php if ( ! empty( $class_wrapper ) || ! empty( $attrs_wrapper ) ) : ?>
				<?php if ( $args['type'] === 'image' || $args['type'] === 'texture' ) : ?>
					<div class="<?php echo esc_attr( trim( $class_wrapper ) ); ?>" <?php echo esc_attr( trim( $attrs_wrapper ) ); ?> style="padding-bottom: calc( (<?php echo esc_attr( $attrs[2] ); ?> / <?php echo esc_attr( $attrs[1] ); ?>) * 100% ); height: 0;">
				<?php elseif ( $args['type'] === 'video' ) : ?>
					<div class="<?php echo esc_attr( trim( $class_wrapper ) ); ?>" <?php echo esc_attr( trim( $attrs_wrapper ) ); ?> style="padding-bottom: calc( (<?php echo esc_attr( $video_height ); ?> / <?php echo esc_attr( $video_width ); ?>) * 100% ); height: 0;">
				<?php else : ?>
					<div class="<?php echo esc_attr( trim( $class_wrapper ) ); ?>" <?php echo esc_attr( trim( $attrs_wrapper ) ); ?>>
				<?php endif; ?>
				<?php if ( $args['mask'] ) : ?>
					<div class="mask-reveal__layer mask-reveal__layer-1">
						<div class="mask-reveal__layer mask-reveal__layer-2">
				<?php endif; ?>
			<?php endif; ?>
				<?php
				switch ( $args['type'] ) {
					case 'background':
						?>
							<img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $lazy_placeholder_src ); ?>" <?php if ( $fetch_priority_value ): ?> fetchpriority="<?php echo esc_attr( $fetch_priority_value ); ?>"<?php endif; ?>data-id="<?php echo esc_attr( $args['id'] ); ?>" data-src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> data-srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> data-sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>" />
							<?php if ( $seo_noscript_images_enabled ): ?><noscript><img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>" /></noscript><?php endif; ?>
							<?php
						break;
					case 'image':
						?>
							<img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $lazy_placeholder_src ); ?>" <?php if ( $fetch_priority_value ): ?> fetchpriority="<?php echo esc_attr( $fetch_priority_value ); ?>"<?php endif; ?>data-id="<?php echo esc_attr( $args['id'] ); ?>" data-src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> data-srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> data-sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>"/>
							<?php if ( $seo_noscript_images_enabled ) : ?><noscript><img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>"/></noscript><?php endif; ?>
							<?php
						break;
					case 'texture':
						?>
							<img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $attrs[0] ); ?>" <?php if ( $fetch_priority_value ): ?> fetchpriority="<?php echo esc_attr( $fetch_priority_value ); ?>"<?php endif; ?>data-id="<?php echo esc_attr( $args['id'] ); ?>" data-src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>"/>
							<?php if ( $seo_noscript_images_enabled ) : ?><noscript><img class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_attr( $attrs[0] ); ?>" width="<?php echo esc_attr( $attrs[1] ); ?>" height="<?php echo esc_attr( $attrs[2] ); ?>" <?php if ( $srcset ) : ?> srcset="<?php echo esc_attr( $srcset ); ?>" <?php endif; ?> <?php if ( $sizes ) : ?> sizes="<?php echo esc_attr( $sizes ); ?>" <?php endif; ?> alt="<?php echo esc_attr( $alt ); ?>"/></noscript><?php endif; ?>
							<?php
						break;
					case 'background-video':
						$video_url = wp_get_attachment_url( $args['id'] );
						$video_metadata = wp_get_attachment_metadata( $args['id'] );
						$video_width = ! empty( $video_metadata['width'] ) ? $video_metadata['width'] : '';
						$video_height = ! empty( $video_metadata['height'] ) ? $video_metadata['height'] : '';
						?>
							<video class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_url( $video_url ); ?>" <?php if ( $video_width ) : ?>width="<?php echo esc_attr( $video_width ); ?>"<?php endif; ?> <?php if ( $video_height ) : ?>height="<?php echo esc_attr( $video_height ); ?>"<?php endif; ?> playsinline loop muted autoplay></video>
							<?php
						break;
					case 'video':
						$video_url = wp_get_attachment_url( $args['id'] );
						$video_metadata = wp_get_attachment_metadata( $args['id'] );
						$video_width = ! empty( $video_metadata['width'] ) ? $video_metadata['width'] : '';
						$video_height = ! empty( $video_metadata['height'] ) ? $video_metadata['height'] : '';
						?>
							<video class="<?php echo esc_attr( trim( $class_media ) ); ?>" src="<?php echo esc_url( $video_url ); ?>" data-src="<?php echo esc_url( $video_url ); ?>" <?php if ( $video_width ) : ?>width="<?php echo esc_attr( $video_width ); ?>"<?php endif; ?> <?php if ( $video_height ) : ?>height="<?php echo esc_attr( $video_height ); ?>"<?php endif; ?> playsinline loop muted autoplay></video>
							<?php
						break;
				}
				?>
			<?php if ( ! empty( $class_wrapper ) ) : ?>
				<?php if ( $args['mask'] ) : ?>
							<?php if ( $args['overlay'] ) : ?>
								<div class="overlay <?php echo esc_attr( trim( $class_overlay ) ); ?>"></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( array_key_exists( 'caption', $args ) && ! empty( $args['caption'] ) ) : ?>
				<?php
				if ( $args['caption'] === true ) {
					$caption = wp_get_attachment_caption( $args['id'] );
				} else {
					$caption = $args['caption'];
				}
				?>
				<div class="<?php echo esc_attr( $class_caption ); ?>"><?php echo esc_html( $caption ); ?></div>
			<?php endif; ?>
			<?php if ( $args['overlay'] && ! $args['mask'] ) : ?>
				<div class="overlay <?php echo esc_attr( trim( $class_overlay ) ); ?>"></div>
			<?php endif; ?>
		<?php if ( ! empty( $class_section ) || ! empty( $attrs_section ) ) : ?>
			</div>
		<?php endif; ?>
		<?php
	}
}
