<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Strings
 *
 * Manages string formatting and retrieval for license information.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class Strings extends BaseManager {
	/**
	 * Date format for displaying dates.
	 *
	 * @var string
	 */
	private $date_format;

	/**
	 * Initialize the Strings manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->date_format = $this->args['date_format'];

		$this->add_managers( $managers );
	}

	/**
	 * Gets the formatted license expiration date.
	 *
	 * @return string The formatted expiration date or "never expires" for lifetime licenses.
	 */
	public function get_expiration_date() {
		$license_expires = $this->managers->options->get( 'license_expires' );

		// The license API uses the literal 'lifetime' sentinel for non-expiring licenses.
		if ( strtolower( $license_expires ) === 'lifetime' ) {
			return $this->strings['expires-never'];
		}

		// strtotime() returns false on unparseable input, which would make gmdate() emit a bogus epoch date.
		if ( $license_expires && strtotime( $license_expires ) !== false ) {
			return gmdate( $this->date_format, strtotime( $license_expires ) );
		}

		return '';
	}

	/**
	 * Gets the formatted purchase date.
	 *
	 * @return string The formatted purchase date or "unknown" if not available.
	 */
	public function get_purchase_date() {
		$date_purchased = $this->managers->options->get( 'license_date_purchased' );
		$purchase_date  = $date_purchased ? gmdate( $this->date_format, strtotime( $date_purchased ) ) : $this->strings['date-unknown'];

		return $purchase_date;
	}

	/**
	 * Gets the formatted support expiration date.
	 *
	 * @return string The formatted support expiration date or "unknown" if not available.
	 */
	public function get_date_supported_until() {
		$support_provided_until      = $this->managers->options->get( 'license_date_supported_until' );
		$support_provided_until_date = $support_provided_until ? gmdate( $this->date_format, strtotime( $support_provided_until ) ) : $this->strings['date-unknown'];

		return $support_provided_until_date;
	}

	/**
	 * Gets the formatted support information with status.
	 *
	 * @return string The support information with active or expired status.
	 */
	public function get_date_supported_full() {
		$support_provided_until_date = $this->get_date_supported_until();
		$is_support_provided         = $this->managers->license->is_support_provided();

		if ( $is_support_provided ) {
			return $this->strings['support-supported-until'] . ' ' . $support_provided_until_date;
		}

		return $this->strings['support-expired'] . ' ' . $support_provided_until_date;
	}

	/**
	 * Gets the formatted updates provided until date.
	 *
	 * @return string The formatted updates expiration date, "lifetime" or "unknown".
	 */
	public function get_date_updates_provided_until() {
		$updates_provided_until = $this->managers->options->get( 'license_date_updates_provided_until' );

		// 'lifetime' sentinel from the API maps to the "lifetime updates" label instead of a date.
		if ( strtolower( $updates_provided_until ) === 'lifetime' ) {
			$updates_provided_until_date = $this->strings['license-lifetime-updates'];
		} else {
			$updates_provided_until_date = $updates_provided_until ? gmdate( $this->date_format, strtotime( $updates_provided_until ) ) : $this->strings['date-unknown'];
		}

		return $updates_provided_until_date;
	}

	/**
	 * Gets the number of sites where the license is activated.
	 *
	 * @return string The site count.
	 */
	public function get_license_site_count() {
		return $this->managers->options->get( 'license_site_count' );
	}

	/**
	 * Gets the maximum number of sites allowed for the license.
	 *
	 * @return string The license activation limit.
	 */
	public function get_license_limit() {
		return $this->managers->options->get( 'license_limit' );
	}

	/**
	 * Gets the number of remaining license activations.
	 *
	 * @return string The number of activations left.
	 */
	public function get_license_activations_left() {
		return $this->managers->options->get( 'license_activations_left' );
	}
}
