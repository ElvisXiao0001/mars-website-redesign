<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Scheduler
 *
 * Manages scheduled tasks related to license checking.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class Scheduler extends BaseManager {
	/**
	 * The name of the cron job for license checking.
	 *
	 * @var string
	 */
	private $cron_job_name;

	/**
	 * Initialize the Scheduler manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->cron_job_name = $this->args['theme_slug'] . '_license_check_event';

		$this->add_managers( $managers );
	}

	/**
	 * Schedules the automatic license check event.
	 *
	 * Creates a daily cron job if the license is valid.
	 *
	 * @return Scheduler Returns the current instance for method chaining.
	 */
	public function schedule_license_check() {
		$license_status = $this->managers->options->get( 'license_key_status' );

		if ( $license_status === 'valid' ) {
			if ( ! wp_next_scheduled( $this->cron_job_name ) ) {
				wp_schedule_event( time(), 'daily', $this->cron_job_name );
			}
		} else {
			$this->clear_scheduled_license_check();
		}

		return $this;
	}

	/**
	 * Clears the scheduled license check event.
	 *
	 * @return Scheduler Returns the current instance for method chaining.
	 */
	public function clear_scheduled_license_check() {
		$timestamp = wp_next_scheduled( $this->cron_job_name );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, $this->cron_job_name );
		}

		return $this;
	}
}
