<?php

namespace Arts\NoticeManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Frontend
 *
 * Handles frontend functionalities for the plugin.
 *
 * @package Arts\NoticeManager\Managers
 */
class Frontend extends BaseManager {
	/**
	 * URL of the directory where the plugin is located.
	 *
	 * @var string
	 */
	private $dir_url;

	/**
	 * Version of the plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Initializes the frontend manager with the provided managers.
	 *
	 * @param \stdClass $managers
	 */
	public function init( $managers ) {
		$this->dir_url = $this->args['dir_url'];
		$this->version = $this->args['version'];

		$this->add_managers( $managers );
	}

	/**
	 * Enqueues the frontend JavaScript for the plugin.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'arts-notice-manager',
			esc_url( untrailingslashit( $this->dir_url ) . '/libraries/arts-notice-manager/index.umd.js' ),
			array(),
			$this->version,
			true
		);
	}

	/**
	 * Placeholder for admin styles. No-op for now: the build ships no stylesheet
	 * (Sass is disabled in project.config.js). Kept hooked so styles can be added
	 * later without rewiring `admin_enqueue_scripts`.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
	}
}
