<?php

namespace Arts\LicenseManager\Managers;

use Arts\NoticeManager\Plugin as NoticeManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AdminNotices
 *
 * Handles the display of admin notices related to license validation.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class AdminNotices extends BaseManager {
	/**
	 * The page name for the license settings page.
	 *
	 * @var string
	 */
	private $page_name;

	/**
	 * Notice manager instance.
	 *
	 * @var NoticeManager
	 */
	private $notice_manager;

	/**
	 * Initialize the admin notices manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->page_name      = $this->args['theme_slug'] . '-license';
		$this->notice_manager = NoticeManager::instance();

		$this->add_managers( $managers );
	}

	/**
	 * Adds a notice for invalid license.
	 *
	 * Displays a warning if the license is missing or invalid.
	 * Does not display on the license settings page.
	 *
	 * @return void
	 */
	public function add_license_invalid_notice() {
		// Skip on the license page itself (it shows status inline) and degrade gracefully when the
		// optional NoticeManager dependency isn't loaded.
		if ( $this->is_theme_license_page() || ! $this->is_admin_notice_class_available() ) {
			return;
		}

		$license = trim( $this->managers->options->get( 'license_key' ) );
		$status  = $this->managers->options->get( 'license_key_status', false );

		if ( ! $license || $status !== 'valid' ) {
			$url = admin_url( 'themes.php?page=' . $this->page_name );

			$args = array(
				'title'          => $this->strings['license-cta-heading'],
				'message'        => \sprintf(
					'%1$s %2$s <a href="%3$s">%4$s</a> %5$s',
					$this->strings['license-cta-message-1'],
					$this->strings['license-cta-message-2'],
					$url,
					$this->strings['license-cta-message-3'],
					$this->strings['license-cta-message-4']
				),
				'link'           => array(
					'class' => 'button button-primary',
					'text'  => $this->strings['license-cta-link-text'],
					'url'   => admin_url( 'themes.php?page=' . $this->page_name ),
				),
				'dismiss_option' => $url,
				'notice_id'      => 'invalid_license',
			);

			$this->notice_manager->warning( $args );
		}
	}

	/**
	 * Adds notices for license activation success or failure.
	 *
	 * Shows success, error, or warning notices based on license activation results.
	 *
	 * @return void
	 */
	public function add_license_activation_notice() {
		if ( ! $this->is_admin_notice_class_available() ) {
			return;
		}

		if ( $this->is_theme_license_page() && isset( $_GET['success'] ) && ! empty( $_GET['message'] ) ) {
			$args = array(
				// Round-trips the urlencoded message set by License::get_page_location(); NoticeManager escapes it on output.
				'message'        => urldecode( $_GET['message'] ),
				'dismiss_option' => false,
			);

			switch ( $_GET['success'] ) {
				case 'yes':
					$args['notice_id'] = 'success_license';
					$this->notice_manager->success( $args );
					break;
				case 'no':
					$args['notice_id'] = 'error_license';
					$this->notice_manager->error( $args );
					break;
				default:
					$args['notice_id'] = 'warning_license';
					$this->notice_manager->warning( $args );
					break;
			}
		}
	}

	/**
	 * Checks if the current page is the theme license settings page.
	 *
	 * @return bool True if on the license settings page, false otherwise.
	 */
	private function is_theme_license_page() {
		global $pagenow;

		return $pagenow === 'themes.php' && ( isset( $_GET['page'] ) && $_GET['page'] === $this->page_name );
	}

	/**
	 * Checks if the Admin Notice class is available.
	 *
	 * @return bool True if the Notice Manager class exists, false otherwise.
	 */
	private function is_admin_notice_class_available() {
		return class_exists( NoticeManager::class );
	}
}
