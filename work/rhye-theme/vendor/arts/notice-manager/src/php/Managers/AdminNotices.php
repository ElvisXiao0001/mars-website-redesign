<?php

namespace Arts\NoticeManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AdminNotices
 *
 * Responsible for creating and displaying admin notices.
 *
 * @package Arts\NoticeManager\Managers
 */
class AdminNotices extends BaseManager {
	/**
	 * Prefix for the notice classes.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Storage for the admin notices.
	 *
	 * @var \stdClass $admin_notices
	 */
	private $admin_notices;

	/**
	 * Prefix used to identify the dismissed notices.
	 *
	 * @var string
	 */
	private $dismissed_prefix;

	/**
	 * AJAX action used to dismiss the notice.
	 *
	 * @var string
	 */
	private $action_dismiss;

	/**
	 * Initializes the admin notices manager with the provided managers.
	 *
	 * @param \stdClass $managers
	 */
	public function init( $managers ) {
		$this->init_admin_notices();
		$this->init_properties();
		$this->add_managers( $managers );
	}

	/**
	 * Initializes the admin notices object.
	 *
	 * @return AdminNotices Returns the current instance for method chaining.
	 */
	private function init_admin_notices() {
		$this->admin_notices = new \stdClass();

		foreach ( $this->args['types'] as $type ) {
			$this->admin_notices->{$type} = array();
		}

		return $this;
	}

	/**
	 * Initializes the properties for the class.
	 *
	 * @return AdminNotices Returns the current instance for method chaining.
	 */
	private function init_properties() {
		$this->prefix           = $this->args['prefix'];
		$this->action_dismiss   = $this->args['action_dismiss'];
		$this->dismissed_prefix = $this->args['dismissed_prefix'];

		return $this;
	}

	/**
	 * Renders the admin notices in the WordPress admin panel.
	 *
	 * @return AdminNotices Returns the current instance for method chaining.
	 */
	public function action_admin_notices() {
		foreach ( $this->args['types'] as $type ) {
			foreach ( $this->admin_notices->{$type} as $admin_notice ) {
				$is_dismissed = get_option( "{$this->dismissed_prefix}_{$admin_notice->notice_id}" );

				if ( $this->should_display_notice( $admin_notice, $is_dismissed ) ) {
					$class_names = $this->get_class_names( $type, $admin_notice );
					$dismiss_url = $this->get_dismiss_url( $admin_notice );

					$this->render_notice( $admin_notice, $class_names, $dismiss_url );
				}
			}
		}

		return $this;
	}

	/**
	 * Adds a notice to the admin panel.
	 *
	 * @param string $type The type of notice to add.
	 * @param array  $args The arguments for the notice.
	 *
	 * @return AdminNotices Returns the current instance for method chaining.
	 */
	public function create( $type, $args ) {
		$args                           = $this->get_notice_args( $args );
		$notice                         = $this->get_notice_object( $args );
		$this->admin_notices->{$type}[] = $notice;

		return $this;
	}

	/**
	 * Retrieves the notice arguments, merging with default values.
	 *
	 * @param array $args {
	 *   Optional. An array of notice arguments.
	 *
	 *   @type string $title          The title of the notice. Default empty.
	 *   @type string $message        The message content of the notice. Default empty.
	 *   @type array  $link           An array containing link details. Default empty array.
	 *   @type bool   $is_alt_style   Whether to use an alternative style for the notice. Default false.
	 *   @type bool   $dismiss_option Whether the notice can be dismissed. Default false.
	 *   @type string $notice_id      The unique identifier for the notice. Default empty.
	 * }
	 * @return array The parsed notice arguments, merged with defaults.
	 */
	private function get_notice_args( $args ) {
		if ( ! is_array( $args ) ) {
			$args = array();
		}

		$defaults = array(
			'title'          => '',
			'message'        => '',
			'link'           => array(),
			'is_alt_style'   => false,
			'dismiss_option' => false,
			'notice_id'      => '',
		);

		return wp_parse_args( $args, $defaults );
	}

	/**
	 * Converts a parsed args array into the notice object stored for rendering.
	 *
	 * @param array $args Notice args already merged with defaults (see get_notice_args()).
	 * @return \stdClass The notice object.
	 */
	private function get_notice_object( $args ) {
		$notice                 = new \stdClass();
		$notice->title          = $args['title'];
		$notice->message        = $args['message'];
		$notice->link           = $args['link'];
		$notice->is_alt_style   = $args['is_alt_style'];
		$notice->dismiss_option = $args['dismiss_option'];
		$notice->notice_id      = $args['notice_id'];

		return $notice;
	}

	/**
	 * Determines whether an admin notice should be displayed.
	 *
	 * @param \stdClass $admin_notice The admin notice object.
	 * @param bool      $is_dismissed Whether the notice has been dismissed.
	 * @return bool     True if the notice should be displayed, false otherwise.
	 */
	private function should_display_notice( $admin_notice, $is_dismissed ) {
		$is_not_dismissed = ! $is_dismissed || ! $admin_notice->dismiss_option;
		$has_content      = ! empty( $admin_notice->title ) || ! empty( $admin_notice->message );

		return $is_not_dismissed && $has_content;
	}

	/**
	 * Generates an array of CSS class names for an admin notice.
	 *
	 * @param string $type The type of the notice (e.g., 'error', 'warning', 'success', 'info').
	 * @param object $admin_notice The admin notice object containing properties such as is_alt_style and dismiss_option.
	 * @return array An array of CSS class names for the admin notice.
	 */
	private function get_class_names( $type, $admin_notice ) {
		$class_names = array(
			'notice',
			$this->prefix . '-notice',
			'notice-' . $type,
		);

		if ( $admin_notice->is_alt_style ) {
			$class_names[] = 'notice-alt';
		}

		if ( $admin_notice->dismiss_option ) {
			$class_names[] = 'is-dismissible';
		}

		return $class_names;
	}

	/**
	 * Generates the URL to dismiss an admin notice.
	 *
	 * @param object $admin_notice The admin notice object containing the dismiss option and notice ID.
	 * @return string The URL to dismiss the admin notice, or an empty string if the dismiss option is not set.
	 */
	private function get_dismiss_url( $admin_notice ) {
		if ( ! $admin_notice->dismiss_option ) {
			return '';
		}

		$query_args = array(
			'action'    => $this->action_dismiss,
			'notice_id' => $admin_notice->notice_id,
			'dismiss'   => true,
			'_wpnonce'  => wp_create_nonce( "{$this->action_dismiss}_nonce" ),
		);

		return add_query_arg( $query_args, admin_url( 'admin-ajax.php' ) );
	}

	/**
	 * Renders an admin notice.
	 *
	 * @param object $admin_notice The notice data.
	 * @param array  $class_names  CSS classes for the notice.
	 * @param string $dismiss_url  URL to dismiss the notice.
	 */
	private function render_notice( $admin_notice, $class_names, $dismiss_url ) {
		$url = ! empty( $dismiss_url ) ? $dismiss_url : '';
		?>
		<div class="<?php echo esc_attr( implode( ' ', $class_names ) ); ?>" data-dismiss-url="<?php echo esc_url( $url ); ?>">
			<?php $this->render_notice_title( $admin_notice ); ?>
			<?php $this->render_notice_message( $admin_notice ); ?>
			<?php $this->render_notice_link( $admin_notice ); ?>
		</div>
		<?php
	}

	/**
	 * Renders the title of an admin notice if it exists.
	 *
	 * @param object $admin_notice The admin notice object containing the title.
	 */
	private function render_notice_title( $admin_notice ) {
		if ( empty( $admin_notice->title ) ) {
			return;
		}
		?>
		<h2 class="notice__heading"><?php echo esc_html( $admin_notice->title ); ?></h2>
		<?php
	}

	/**
	 * Renders the notice message if it exists.
	 *
	 * @param object $admin_notice The admin notice object containing the message.
	 */
	private function render_notice_message( $admin_notice ) {
		if ( empty( $admin_notice->message ) ) {
			return;
		}
		?>
		<p class="notice__text"><?php echo wp_kses_post( $admin_notice->message ); ?></p>
		<?php
	}

	/**
	 * Renders the notice link if it exists.
	 *
	 * @param object $admin_notice The admin notice object containing the link.
	 */
	private function render_notice_link( $admin_notice ) {
		if ( empty( $admin_notice->link ) || ! isset( $admin_notice->link['text'] ) ) {
			return;
		}

		$target = '_self';
		if ( array_key_exists( 'target', $admin_notice->link ) ) {
			$target = $admin_notice->link['target'];
		}
		?>
		<p><a class="<?php echo esc_attr( $admin_notice->link['class'] ); ?>" href="<?php echo esc_url( $admin_notice->link['url'] ); ?>" target="<?php echo esc_attr( $target ); ?>"><?php echo esc_html( $admin_notice->link['text'] ); ?></a></p>
		<?php
	}
}
