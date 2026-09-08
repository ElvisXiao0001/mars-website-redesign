<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class License
 *
 * Manages license activation, deactivation, validation, and status checks.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class License extends BaseManager {
	/**
	 * Theme slug identifier.
	 *
	 * @var string
	 */
	private $theme_slug;

	/**
	 * Remote API URL for license validation.
	 *
	 * @var string
	 */
	private $remote_api_url;

	/**
	 * Initialize the License manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->theme_slug     = $this->args['theme_slug'];
		$this->remote_api_url = $this->args['remote_api_url'];

		$this->add_managers( $managers );
	}

	/**
	 * Activates the license and redirects to the license page.
	 *
	 * Thin wrapper over do_activate_license(): the public *_license() handlers redirect (used by form
	 * POSTs), while the do_*_license() methods return a result array so AJAX can reuse the same logic.
	 *
	 * @return void
	 */
	public function activate_license() {
		$result = $this->do_activate_license();
		$this->redirect( $result );
	}

	/**
	 * Performs the license activation process.
	 *
	 * Makes an API request to activate the license key.
	 *
	 * @return array Result array with success status and message.
	 */
	public function do_activate_license() {
		$result  = array(
			'success' => false,
			'message' => '',
		);
		$key     = isset( $_POST[ $this->theme_slug . '_license_key' ] ) ? $this->sanitize_license_key( wp_unslash( $_POST[ $this->theme_slug . '_license_key' ] ) ) : '';
		$license = ! empty( $key ) ? $key : trim( $this->managers->options->get( 'license_key' ) );
		$api_url = trailingslashit( $this->remote_api_url ) . 'edd/v1/activate/' . $this->theme_slug . '/theme';

		// Data to send in our API request. `installed_version` lets the store record what this
		// site runs (the server attributes it to the theme by the endpoint's /theme segment).
		$api_params = array(
			'key'               => $license,
			'url'               => esc_url( home_url( '/' ) ),
			'installed_version' => (string) ( $this->args['version'] ?? '' ),
		);

		$response = $this->get_api_response( $api_params, $api_url );

		// Treat transport errors and non-200 responses as failures.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$result['message'] = $response->get_error_message();
			} else {
				$result['message'] = $this->strings['error-generic'];
			}
		} else {
			$license_data      = json_decode( wp_remote_retrieve_body( $response ) );
			$result['message'] = $license_data->message;

			if ( $license_data && isset( $license_data->license ) && $license_data->license === 'valid' ) {
				$result['success'] = true;

				// Removes the default EDD hook for this option, which breaks the AJAX call.
				remove_all_actions( 'update_option_' . $this->theme_slug . '_license_key', 10 );

				$this->managers->options->update( 'license_key_status', sanitize_text_field( $license_data->license ) );
				$this->managers->options->update( 'license_key', $this->sanitize_license_key( $license ) );

				$this->update_license_data_options( $license_data );
			} else {
				if ( $license_data && isset( $license_data->license ) ) {
					$this->managers->options->update( 'license_key_status', sanitize_text_field( $license_data->license ) );
				}

				$this->managers->options->delete( 'license_message' );
				$this->managers->options->delete( 'license_expires' );
				$this->managers->options->delete( 'license_date_purchased' );
				$this->managers->options->delete( 'license_date_supported_until' );
				$this->managers->options->delete( 'license_date_updates_provided_until' );
				$this->managers->options->delete( 'license_is_local' );
				$this->managers->options->delete( 'license_limit' );
				$this->managers->options->delete( 'license_site_count' );
				$this->managers->options->delete( 'license_activations_left' );
				$this->managers->options->delete( 'masked_email' );
				$this->managers->options->delete( 'order_history_url' );
			}
		}

		return $result;
	}

	/**
	 * Deactivates the license and redirects to the license page.
	 *
	 * @return void
	 */
	public function deactivate_license() {
		$result = $this->do_deactivate_license();
		$this->redirect( $result );
	}

	/**
	 * Performs the license deactivation process.
	 *
	 * Makes an API request to deactivate the license key.
	 *
	 * @return array Result array with success status and message.
	 */
	public function do_deactivate_license() {
		$result  = array(
			'success' => false,
			'message' => '',
		);
		$key     = isset( $_POST[ $this->theme_slug . '_license_key' ] ) ? $this->sanitize_license_key( wp_unslash( $_POST[ $this->theme_slug . '_license_key' ] ) ) : '';
		$license = ! empty( $key ) ? $key : trim( $this->managers->options->get( 'license_key' ) );
		$api_url = trailingslashit( $this->remote_api_url ) . 'edd/v1/deactivate/' . $this->theme_slug . '/theme';

		// Data to send in our API request.
		$api_params = array(
			'key' => $license,
			'url' => esc_url( home_url( '/' ) ),
		);

		$response = $this->get_api_response( $api_params, $api_url );

		// Treat transport errors and non-200 responses as failures.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$result['message'] = $response->get_error_message();
			} else {
				$result['message'] = $this->strings['error-generic'];
			}
		} else {
			$license_data      = json_decode( wp_remote_retrieve_body( $response ) );
			$result['message'] = $license_data->message;

			if ( $license_data ) {
				if ( $license_data->message ) {
					$result['message'] = $license_data->message;
				}

				// $license_data->license will be either "deactivated" or "failed"
				if ( $license_data->license === 'deactivated' ) {
					$result['success'] = true;

					$this->managers->options->update( 'license_key_status', sanitize_text_field( $license_data->license ) );
					$this->managers->options->delete( 'license_message' );
					$this->managers->options->delete( 'license_expires' );
					$this->managers->options->delete( 'license_date_purchased' );
					$this->managers->options->delete( 'license_date_supported_until' );
					$this->managers->options->delete( 'license_date_updates_provided_until' );
					$this->managers->options->delete( 'license_is_local' );
					$this->managers->options->delete( 'license_limit' );
					$this->managers->options->delete( 'license_site_count' );
					$this->managers->options->delete( 'license_activations_left' );
					$this->managers->options->delete( 'masked_email' );
					$this->managers->options->delete( 'order_history_url' );

					// Unschedule the license check cron job if the license is deactivated
					$this->managers->scheduler->clear_scheduled_license_check();

					// Delete the update transients to force a new check
					$this->managers->updates->delete_theme_update_transient();
				}
			}
		}

		return $result;
	}

	/**
	 * Refreshes the license information and redirects to the license page.
	 *
	 * @return void
	 */
	public function refresh_license() {
		$result = $this->do_refresh_license();
		$this->redirect( $result );
	}

	/**
	 * Performs the license refresh process.
	 *
	 * Makes an API request to check and update license information.
	 *
	 * @return array Result array with success status and message.
	 */
	public function do_refresh_license() {
		$result  = array(
			'success' => false,
			'message' => '',
		);
		$key     = isset( $_POST[ $this->theme_slug . '_license_key' ] ) ? $this->sanitize_license_key( wp_unslash( $_POST[ $this->theme_slug . '_license_key' ] ) ) : '';
		$license = ! empty( $key ) ? $key : trim( $this->managers->options->get( 'license_key' ) );
		$api_url = trailingslashit( $this->remote_api_url ) . 'edd/v1/check/' . $this->theme_slug . '/theme';

		// Data to send in our API request (see do_activate_license on `installed_version`).
		$api_params = array(
			'key'               => $license,
			'url'               => esc_url( home_url( '/' ) ),
			'installed_version' => (string) ( $this->args['version'] ?? '' ),
		);

		$response = $this->get_api_response( $api_params, $api_url );

		// Treat transport errors and non-200 responses as failures.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$result['message'] = $response->get_error_message();
			} else {
				$result['message'] = $this->strings['error-generic'];
			}
		} else {
			$license_data      = json_decode( wp_remote_retrieve_body( $response ) );
			$result['message'] = $license_data->message;

			if ( $license_data && isset( $license_data->license ) ) {
				// A check can report 'valid' or 'active'; both mean the key is usable here, so either counts as success.
				if ( $license_data->license === 'valid' || $license_data->license === 'active' ) {
					$result['success'] = true;
				}

				if ( $license_data->license === 'valid' ) {
					$result['message'] = $this->strings['license-key-is-active'];
				}

				// Removes the default EDD hook for this option, which breaks the AJAX call.
				remove_all_actions( 'update_option_' . $this->theme_slug . '_license_key', 10 );

				$this->managers->options->update( 'license_key_status', sanitize_text_field( $license_data->license ) );
				$this->managers->options->update( 'license_key', $this->sanitize_license_key( $license ) );

				$this->update_license_data_options( $license_data );
			}
		}

		return $result;
	}

	/**
	 * Clears the license and redirects to the license page.
	 *
	 * @return void
	 */
	public function clear_license() {
		$result = $this->do_clear_license();
		$this->redirect( $result );
	}

	/**
	 * Performs the license clearing process.
	 *
	 * Removes the license key and status from the options.
	 *
	 * @return array Result array with success status and message.
	 */
	public function do_clear_license() {
		$result = array(
			'success' => true,
			'message' => $this->strings['license-key-cleared'],
		);

		$this->managers->options->delete( 'license_key' );
		$this->managers->options->delete( 'license_key_status' );

		return $result;
	}

	/**
	 * Redirects to the license page with status parameters.
	 *
	 * @param array $args Arguments containing success status and message.
	 * @return void
	 */
	private function redirect( $args ) {
		$location = $this->get_page_location( $args );
		wp_redirect( $location );
		die();
	}

	/**
	 * Gets the license page URL with status parameters.
	 *
	 * @param array $args Arguments containing success status and message.
	 * @return string The URL to redirect to.
	 */
	public function get_page_location( $args ) {
		$base_url   = admin_url( 'themes.php?page=' . $this->theme_slug . '-license' );
		$query_args = array(
			'success' => isset( $args['success'] ) && $args['success'] ? 'yes' : 'no',
			'message' => isset( $args['message'] ) ? urlencode( $args['message'] ) : '',
		);

		// Check if we should add the connect parameter for auto-opening the modal
		if ( isset( $args['success'] ) && $args['success'] ) {
			$license_key   = trim( $this->managers->options->get( 'license_key' ) );
			$should_prompt = $this->managers->options->get( 'should_prompt_email', false );
			$is_suppressed = $this->managers->options->is_modal_suppressed( $license_key );

			if ( $license_key && $should_prompt && ! $is_suppressed ) {
				$query_args['connect'] = '1';
				$query_args['lh']      = wp_hash( $license_key );
				$query_args['_nonce']  = wp_create_nonce( 'license_connect_' . wp_hash( $license_key ) );
			}
		}

		$location = add_query_arg( $query_args, $base_url );

		return $location;
	}

	/**
	 * Registers license action handlers for form submissions.
	 *
	 * Handles activation, deactivation, refresh, and clear actions.
	 *
	 * @return void
	 */
	public function register_license_actions() {
		if ( isset( $_POST[ $this->theme_slug . '_license_activate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '-license-options' ) ) {
				$this->activate_license();
			}
		}

		if ( isset( $_POST[ $this->theme_slug . '_license_deactivate' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '-license-options' ) ) {
				$this->deactivate_license();
			}
		}

		if ( isset( $_POST[ $this->theme_slug . '_license_refresh' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '-license-options' ) ) {
				$this->refresh_license();
			}
		}

		if ( isset( $_POST[ $this->theme_slug . '_license_clear' ] ) ) {
			if ( check_admin_referer( $this->theme_slug . '-license-options' ) ) {
				$this->clear_license();
			}
		}
	}

	/**
	 * Sanitize an opaque license key — fully byte-preserving for every printable char a marketplace
	 * can emit (`%XX`, `+`, `^`, `@`, `=`, `;`, `.`, `-`, `_`, `<`, `>`, mixed case). Only NUL/control/DEL
	 * bytes (transport noise) are stripped. Mirrors the Freemius WP SDK pattern at every license-key
	 * intake (`fs_request_get_raw` + `trim` — no `sanitize_text_field`, no tag-strip, no `<>` strip).
	 * The key is never echoed to HTML unescaped, so tag-stripping was paying for an XSS risk we don't
	 * have AND was actively mangling any future key containing `<>`.
	 *
	 * @param mixed $raw Raw license key from request or storage.
	 * @return string
	 */
	private function sanitize_license_key( $raw ) {
		return trim( (string) preg_replace( '/[\x00-\x1F\x7F]/', '', (string) $raw ) );
	}

	/**
	 * Makes an API request to the license server.
	 *
	 * @param array  $api_params Parameters to send to the API.
	 * @param string $url        The API URL to call.
	 * @return array|\WP_Error The API response or WP_Error on failure.
	 */
	private function get_api_response( $api_params, $url ) {
		if ( ! $url ) {
			$url = $this->remote_api_url;
		}

		// Call the custom API.
		$verify_ssl = apply_filters( 'edd_sl_api_request_verify_ssl', true );
		$response   = wp_remote_post(
			$url,
			array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => $api_params,
			)
		);

		return $response;
	}

	/**
	 * Updates license data options from the API response.
	 *
	 * @param object $license_data License data object from API response.
	 * @return void
	 */
	private function update_license_data_options( $license_data ) {
		if ( ! $license_data || ! is_object( $license_data ) ) {
			return;
		}

		if ( isset( $license_data->license ) ) {
			$this->managers->options->update( 'license_key_status', sanitize_text_field( $license_data->license ) );
		} else {
			$this->managers->options->delete( 'license_key_status' );
		}

		if ( isset( $license_data->message ) ) {
			$this->managers->options->update( 'license_message', $license_data->message );
		} else {
			$this->managers->options->delete( 'license_message' );
		}

		if ( isset( $license_data->expires ) ) {
			$this->managers->options->update( 'license_expires', sanitize_text_field( $this->replace_lifetime_string( $license_data->expires ) ) );
		} else {
			$this->managers->options->delete( 'license_expires' );
		}

		if ( isset( $license_data->date_purchased ) ) {
			$this->managers->options->update( 'license_date_purchased', sanitize_text_field( $license_data->date_purchased ) );
		} else {
			$this->managers->options->delete( 'license_date_purchased' );
		}

		if ( isset( $license_data->date_supported_until ) ) {
			$this->managers->options->update( 'license_date_supported_until', sanitize_text_field( $this->replace_lifetime_string( $license_data->date_supported_until ) ) );
		} else {
			$this->managers->options->delete( 'license_date_supported_until' );
		}

		if ( isset( $license_data->date_updates_provided_until ) ) {
			$this->managers->options->update( 'license_date_updates_provided_until', sanitize_text_field( $this->replace_lifetime_string( $license_data->date_updates_provided_until ) ) );
		} else {
			$this->managers->options->delete( 'license_date_updates_provided_until' );
		}

		if ( isset( $license_data->is_local ) ) {
			$this->managers->options->update( 'license_is_local', boolval( $license_data->is_local ) );
		} else {
			$this->managers->options->delete( 'license_is_local' );
		}

		if ( isset( $license_data->license_limit ) ) {
			$this->managers->options->update( 'license_limit', sanitize_text_field( $license_data->license_limit ) );
		} else {
			$this->managers->options->delete( 'license_limit' );
		}

		if ( isset( $license_data->site_count ) ) {
			$this->managers->options->update( 'license_site_count', sanitize_text_field( $license_data->site_count ) );
		} else {
			$this->managers->options->delete( 'license_site_count' );
		}

		if ( isset( $license_data->activations_left ) ) {
			$this->managers->options->update( 'license_activations_left', sanitize_text_field( $license_data->activations_left ) );
		} else {
			$this->managers->options->delete( 'license_activations_left' );
		}

		// Email-link state: trust the server's fields only. Absent fields FAIL CLOSED — never
		// fabricate an 'alias' state or a prompt; a server that doesn't send them (older store,
		// error path) must not trigger the email modal.
		if ( isset( $license_data->email_link_state ) ) {
			$this->managers->options->update( 'email_link_state', sanitize_text_field( $license_data->email_link_state ) );
		}

		$should_prompt = isset( $license_data->should_prompt_email ) && boolval( $license_data->should_prompt_email );
		$this->managers->options->update( 'should_prompt_email', $should_prompt );

		// Handle masked email - store if provided by API
		if ( isset( $license_data->masked_email ) ) {
			$this->managers->options->update( 'masked_email', sanitize_text_field( $license_data->masked_email ) );
		} else {
			$this->managers->options->delete( 'masked_email' );
		}

		// Handle order history URL - store if provided by API
		if ( isset( $license_data->order_history_url ) ) {
			$this->managers->options->update( 'order_history_url', esc_url_raw( $license_data->order_history_url ) );
		} else {
			$this->managers->options->delete( 'order_history_url' );
		}

		// Store-owned legal links (config, not state): update when sent, keep the last known
		// value when absent — an older server must not wipe known-good URLs.
		if ( isset( $license_data->terms_of_service_url ) ) {
			$this->managers->options->update( 'terms_of_service_url', esc_url_raw( $license_data->terms_of_service_url ) );
		}

		if ( isset( $license_data->privacy_policy_url ) ) {
			$this->managers->options->update( 'privacy_policy_url', esc_url_raw( $license_data->privacy_policy_url ) );
		}
	}

	/**
	 * Replaces 'lifetime' with a localized string.
	 *
	 * @param string $expires The expiration string.
	 * @return string The processed expiration string.
	 */
	private function replace_lifetime_string( $expires ) {
		if ( 'lifetime' === $expires ) {
			$expires = $this->strings['license-never-expires'];
		}

		return $expires;
	}

	/**
	 * Checks if the license is for a local development site.
	 *
	 * @return bool True if the license is for a local site, false otherwise.
	 */
	public function is_local() {
		return $this->managers->options->get( 'license_is_local' );
	}

	/**
	 * Gets the current license status.
	 *
	 * @return string The license status.
	 */
	public function get_status() {
		return $this->managers->options->get( 'license_key_status' );
	}

	/**
	 * Gets the current license key.
	 *
	 * @return string The license key.
	 */
	public function get_key() {
		return $this->managers->options->get( 'license_key' );
	}

	/**
	 * Checks if support is currently provided for the license.
	 *
	 * Compares the support expiration date with the current date.
	 *
	 * @return bool True if support is still provided, false otherwise.
	 */
	public function is_support_provided() {
		$support_provided_until = $this->managers->options->get( 'license_date_supported_until' );
		$current_date           = gmdate( 'Y-m-d' );

		return strtotime( $support_provided_until ) >= strtotime( $current_date );
	}
}
