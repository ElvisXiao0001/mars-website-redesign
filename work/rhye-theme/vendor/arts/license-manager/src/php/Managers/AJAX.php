<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class AJAX
 *
 * Handles AJAX requests for license management operations.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class AJAX extends BaseManager {
	/**
	 * The nonce ID used for AJAX security.
	 *
	 * @var string
	 */
	private $nonce_id;

	/**
	 * Initialize the AJAX manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->nonce_id = $this->args['theme_slug'] . '-license-options';

		$this->add_managers( $managers );
	}

	/**
	 * Handles AJAX request to refresh license information.
	 *
	 * Refreshes license data and returns updated information or error message.
	 *
	 * @return void
	 */
	public function refresh_license() {
		check_admin_referer( $this->nonce_id );

		$result = $this->managers->license->do_refresh_license();

		if ( $result['success'] ) {
			// Drop cached option reads so the status/date getters below reflect the freshly written data.
			wp_cache_flush();

			$data = array(
				'message'                     => esc_html( $result['message'] ),
				'status'                      => esc_html( $this->managers->license->get_status() ),
				'is_local'                    => esc_html( $this->managers->license->is_local() ),
				'is_support_provided'         => esc_html( $this->managers->license->is_support_provided() ),
				'expires'                     => esc_html( $this->managers->strings->get_expiration_date() ),
				'date_purchased'              => esc_html( $this->managers->strings->get_purchase_date() ),
				'date_supported_until'        => esc_html( $this->managers->strings->get_date_supported_full() ),
				'date_updates_provided_until' => esc_html( $this->managers->strings->get_date_updates_provided_until() ),
				'site_count'                  => esc_html( $this->managers->strings->get_license_site_count() ),
				'license_limit'               => esc_html( $this->managers->strings->get_license_limit() ),
				'activations_left'            => esc_html( $this->managers->strings->get_license_activations_left() ),
				'email_link_state'            => esc_html( $this->managers->options->get( 'email_link_state', 'alias' ) ),
				'masked_email'                => esc_html( $this->managers->options->get( 'masked_email', '' ) ),
				'order_history_url'           => esc_url( $this->managers->options->get( 'order_history_url', '' ) ),
				'owner_strings'               => array(
					'linked'        => esc_html( $this->strings['owner-linked'] ),
					'not_linked'    => esc_html( $this->strings['owner-not-linked'] ),
					'connect'       => esc_html( $this->strings['owner-connect'] ),
					'manage_online' => esc_html( $this->strings['owner-manage-online'] ),
					'helper_1'      => esc_html( $this->strings['owner-helper-1'] ),
					'helper_2'      => esc_html( $this->strings['owner-helper-2'] ),
				),
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error(
				array(
					'message'  => $result['message'],
					'location' => $this->managers->license->get_page_location( $result ),
				)
			);
		}
	}

	/**
	 * Handles AJAX request to clear the license.
	 *
	 * Removes the license key and returns success or error message.
	 *
	 * @return void
	 */
	public function clear_license() {
		check_admin_referer( $this->nonce_id );

		$result = $this->managers->license->do_clear_license();

		if ( $result['success'] ) {
			// Drop cached option reads so the key/status getters below reflect the cleared data.
			wp_cache_flush();

			$data = array(
				'message' => esc_html( $result['message'] ),
				'key'     => esc_html( $this->managers->license->get_key() ),
				'status'  => esc_html( $this->managers->license->get_status() ),
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error(
				array(
					'message'  => $result['message'],
					'location' => $this->managers->license->get_page_location( $result ),
				)
			);
		}
	}

	/**
	 * Handles AJAX request to update email link state.
	 *
	 * Updates the email_link_state option when user successfully links their email.
	 *
	 * @return void
	 */
	public function update_email_link_state() {
		// Verify nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'arts_license_manager_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'arts-license-manager' ) ) );
		}

		// Verify email_link_state parameter
		if ( ! isset( $_POST['email_link_state'] ) || ! in_array( $_POST['email_link_state'], array( 'alias', 'real' ), true ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid email link state', 'arts-license-manager' ) ) );
		}

		$email_link_state = sanitize_text_field( wp_unslash( $_POST['email_link_state'] ) );

		// Update the option
		$this->managers->options->update( 'email_link_state', $email_link_state );

		wp_send_json_success(
			array(
				'message'          => esc_html__( 'Email link state updated successfully', 'arts-license-manager' ),
				'email_link_state' => $email_link_state,
			)
		);
	}

	/**
	 * Handles AJAX request to activate the license.
	 *
	 * Activates the license key and returns success or error message with data.
	 *
	 * @return void
	 */
	public function activate_license() {
		check_admin_referer( $this->nonce_id );

		$result = $this->managers->license->do_activate_license();

		if ( $result['success'] ) {
			wp_cache_flush();

			$license_key = $this->managers->license->get_key();

			$data = array(
				'message'                     => esc_html( $result['message'] ),
				'status'                      => esc_html( $this->managers->license->get_status() ),
				'is_local'                    => esc_html( $this->managers->license->is_local() ),
				'is_support_provided'         => esc_html( $this->managers->license->is_support_provided() ),
				'expires'                     => esc_html( $this->managers->strings->get_expiration_date() ),
				'date_purchased'              => esc_html( $this->managers->strings->get_purchase_date() ),
				'date_supported_until'        => esc_html( $this->managers->strings->get_date_supported_full() ),
				'date_updates_provided_until' => esc_html( $this->managers->strings->get_date_updates_provided_until() ),
				'site_count'                  => esc_html( $this->managers->strings->get_license_site_count() ),
				'license_limit'               => esc_html( $this->managers->strings->get_license_limit() ),
				'activations_left'            => esc_html( $this->managers->strings->get_license_activations_left() ),
				'email_link_state'            => esc_html( $this->managers->options->get( 'email_link_state', 'alias' ) ),
				'masked_email'                => esc_html( $this->managers->options->get( 'masked_email', '' ) ),
				'order_history_url'           => esc_url( $this->managers->options->get( 'order_history_url', '' ) ),
				'should_prompt_email'         => $this->managers->options->get( 'should_prompt_email', false ) && ! $this->managers->options->is_modal_suppressed( $license_key ),
				'owner_strings'               => array(
					'linked'        => esc_html( $this->strings['owner-linked'] ),
					'not_linked'    => esc_html( $this->strings['owner-not-linked'] ),
					'connect'       => esc_html( $this->strings['owner-connect'] ),
					'manage_online' => esc_html( $this->strings['owner-manage-online'] ),
					'helper_1'      => esc_html( $this->strings['owner-helper-1'] ),
					'helper_2'      => esc_html( $this->strings['owner-helper-2'] ),
				),
				// Raw on purpose: this is JSON data the client feeds back into the email-link API.
				// esc_html() would entity-mangle opaque keys ('&' → '&amp;') and break the link flow;
				// the client escapes at its HTML insertion points instead.
				'license_key'                 => $license_key,
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error(
				array(
					'message'  => $result['message'],
					'location' => $this->managers->license->get_page_location( $result ),
				)
			);
		}
	}

	/**
	 * Handles AJAX request to deactivate the license.
	 *
	 * Deactivates the license key and returns success or error message with data.
	 *
	 * @return void
	 */
	public function deactivate_license() {
		check_admin_referer( $this->nonce_id );

		$result = $this->managers->license->do_deactivate_license();

		if ( $result['success'] ) {
			wp_cache_flush();

			$data = array(
				'message'     => esc_html( $result['message'] ),
				'status'      => esc_html( $this->managers->license->get_status() ),
				// Raw on purpose — see activate_license(): JSON data, not HTML output.
				'license_key' => $this->managers->license->get_key(),
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error(
				array(
					'message'  => $result['message'],
					'location' => $this->managers->license->get_page_location( $result ),
				)
			);
		}
	}
}
