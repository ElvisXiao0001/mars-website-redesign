<?php

namespace Arts\NoticeManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AJAX
 *
 * Handles AJAX-related functionalities for notices.
 *
 * @package Arts\NoticeManager\Managers
 */
class AJAX extends BaseManager {
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
	 * Initializes the AJAX manager with the provided managers.
	 *
	 * @param \stdClass $managers
	 */
	public function init( $managers ) {
		$this->action_dismiss   = $this->args['action_dismiss'];
		$this->dismissed_prefix = $this->args['dismissed_prefix'];

		$this->add_managers( $managers );
	}

	/**
	 * AJAX hook to update the dismissal status of a notice in the options table.
	 *
	 * @return void
	 */
	public function update_notice_dismissed() {
		// Reject forged requests — the nonce proves the dismissal came from our own admin UI.
		check_ajax_referer( "{$this->action_dismiss}_nonce" );

		if ( ! isset( $_GET['notice_id'] ) || ! isset( $_GET['dismiss'] ) ) {
			wp_send_json_error();
		}

		$is_dismissed = $_GET['dismiss'] === '1';
		$notice_id    = sanitize_key( $_GET['notice_id'] );

		$updated = update_option( "{$this->dismissed_prefix}_{$notice_id}", $is_dismissed );

		if ( ! $updated ) {
			wp_send_json_error();
		}

		wp_send_json_success();
	}
}
