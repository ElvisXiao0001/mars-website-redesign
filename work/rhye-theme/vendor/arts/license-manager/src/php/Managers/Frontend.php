<?php

namespace Arts\LicenseManager\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Frontend
 *
 * Handles the frontend interface for license management in the WordPress admin.
 *
 * @package Arts\LicenseManager\Managers
 * @since 1.0.0
 */
class Frontend extends BaseManager {
	/**
	 * The theme slug.
	 *
	 * @var string
	 */
	private $theme_slug;

	/**
	 * Date format for displaying dates.
	 *
	 * @var string
	 */
	private $date_format;

	/**
	 * URL to the directory containing assets.
	 *
	 * @var string
	 */
	private $dir_url;

	/**
	 * Version number for assets.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Remote API URL for license validation.
	 *
	 * @var string
	 */
	private $remote_api_url;

	/**
	 * Initialize the Frontend manager.
	 *
	 * @param \stdClass $managers Other managers used by this manager.
	 * @return void
	 */
	public function init( $managers ) {
		$this->theme_slug     = $this->args['theme_slug'];
		$this->date_format    = $this->args['date_format'];
		$this->dir_url        = $this->args['dir_url'];
		$this->version        = $this->args['version'];
		$this->remote_api_url = $this->args['remote_api_url'];

		$this->add_managers( $managers );
	}

	/**
	 * Enqueues JavaScript files for the license manager.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$current_screen = get_current_screen();

		// Only enqueue on the license page
		if ( ! $current_screen || strpos( $current_screen->id, $this->theme_slug . '-license' ) === false ) {
			return;
		}

		wp_enqueue_script(
			'arts-license-manager',
			esc_url( untrailingslashit( $this->dir_url ) . '/libraries/arts-license-manager/index.umd.js' ),
			array(),
			$this->version,
			true
		);

		// Localize script with necessary data for the email modal
		wp_localize_script(
			'arts-license-manager',
			'artsLicenseManagerParams',
			array(
				'email_api_url' => trailingslashit( $this->remote_api_url ) . 'edd/v1/email/link',
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'arts_license_manager_nonce' ),
				'strings'       => array(
					// Modal strings
					'continue'                 => $this->strings['modal-continue'],
					'skip_for_now'             => $this->strings['modal-skip-for-now'],
					'close_modal'              => $this->strings['modal-close-modal'],
					'email_label'              => $this->strings['modal-email-label'],
					'enter_valid_email'        => $this->strings['modal-enter-valid-email'],
					'agree_legal'              => $this->strings['modal-agree-legal'],
					'terms_of_service'         => $this->strings['modal-terms-of-service'],
					'privacy_policy'           => $this->strings['modal-privacy-policy'],
					'marketing_consent'        => $this->strings['modal-marketing-consent'],
					'agree_to_continue'        => $this->strings['modal-agree-to-continue'],
					'modal_title'              => $this->strings['modal-title'],
					'modal_description'        => $this->strings['modal-description'],
					'creating_account_info'    => $this->strings['modal-creating-account-info'],
					// License strings for UI updates
					'activate_license'         => $this->strings['activate-license'],
					'deactivate_license'       => $this->strings['deactivate-license'],
					'refresh_license'          => $this->strings['refresh-license'],
					'license_activated'        => $this->strings['license-key-activated'],
					'manage_account'           => $this->strings['owner-manage-online'],
					'linked_to_account'        => $this->strings['owner-linked'],
					'linked_to_email'          => $this->strings['owner-linked-to'],
					'not_linked_to_account'    => $this->strings['owner-not-linked'],
					'connect_to_account'       => $this->strings['owner-connect'],
					// Email modal response messages
					'email_success_message'    => esc_html__( 'Check your inbox to confirm your email. You can continue setup now.', 'arts-license-manager' ),
					'email_error_generic'      => esc_html__( 'We couldn\'t send the email. Try again or skip for now.', 'arts-license-manager' ),
					// Modal field labels
					'purchase_code_label'      => $this->strings['license-key'],
					// Error messages (failure responses carry a server-localized message; this is the net)
					'generic_error_message'    => esc_html__( 'An error occurred. Please try again.', 'arts-license-manager' ),
					// Alert messages for AJAX errors
					'error_refresh_license'    => esc_html__( 'An error occurred while refreshing the license.', 'arts-license-manager' ),
					'error_clear_license'      => esc_html__( 'An error occurred while clearing the license.', 'arts-license-manager' ),
					'error_activate_license'   => esc_html__( 'Unable to activate license. Please check your connection and license key.', 'arts-license-manager' ),
					'error_deactivate_license' => esc_html__( 'Unable to deactivate license. Please check your connection.', 'arts-license-manager' ),
				),
				'urls'          => array(
					// Store-provided URLs (persisted from license responses) win; theme-string
					// defaults cover first-run and older servers that don't send them.
					'terms_of_service' => $this->store_url_with_fallback( 'terms_of_service_url', 'modal-terms-of-service-url' ),
					'privacy_policy'   => $this->store_url_with_fallback( 'privacy_policy_url', 'modal-privacy-policy-url' ),
					'account_site'     => $this->strings['modal-account-site-url'],
				),
			)
		);
	}

	/**
	 * Enqueues CSS files for the license manager.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		$current_screen = get_current_screen();

		// Only enqueue on the license page
		if ( ! $current_screen || strpos( $current_screen->id, $this->theme_slug . '-license' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'arts-license-manager',
			esc_url( untrailingslashit( $this->dir_url ) . '/libraries/arts-license-manager/index.css' ),
			array(),
			$this->version
		);
	}

	/**
	 * Adds the theme license page to the WordPress admin menu.
	 *
	 * @return void
	 */
	public function add_theme_license_menu() {
		add_theme_page(
			$this->strings['theme-license'],
			$this->strings['theme-license'],
			'manage_options',
			$this->theme_slug . '-license',
			array( $this, 'add_theme_license_page' )
		);
	}

	/**
	 * Renders the theme license page content.
	 *
	 * @return void
	 */
	public function add_theme_license_page() {
		$strings = $this->strings;

		// Only a 'valid' status unlocks the full info UI (expiration, activations, support rows);
		// any other status falls back to the bare key-entry form.
		$license          = trim( $this->managers->options->get( 'license_key' ) );
		$status           = $this->managers->options->get( 'license_key_status', false );
		$is_valid_license = $license && $status === 'valid';

		// Check if we should auto-open the modal
		$should_auto_open = $this->should_auto_open_modal( $license );

		?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( $strings['theme-license'] ); ?></h1>
	<hr class="wp-header-end">
	<!-- Persistent notice for AJAX responses -->
	<div id="license-notice" class="notice license-notice" style="display: none;" role="alert">
		<p class="license-notice-text"></p>
	</div>
		<?php if ( $should_auto_open ) : ?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				if (window.ArtsLicenseManager && window.ArtsLicenseManager.openEmailModal) {
					setTimeout(function() {
						window.ArtsLicenseManager.openEmailModal('<?php echo esc_js( $license ); ?>', 'license_screen');
					}, 500);
				}
			});
		</script>
	<?php endif; ?>
	<form method="post" action="options.php" data-action-ajax="<?php echo admin_url( 'admin-ajax.php' ); ?>"
		id="arts-license-form">
		<?php settings_fields( $this->theme_slug . '-license' ); ?>
		
		<!-- Always render hidden input for license key -->
		<input type="hidden" name="<?php echo esc_attr( $this->theme_slug . '_license_key' ); ?>" value="<?php echo esc_attr( $license ); ?>" />
		
		<!-- Always render both deactivated and activated views -->
		<?php $this->render_deactivated_view( $license, $is_valid_license ); ?>
		<?php $this->render_activated_view( $license, $is_valid_license ); ?>
		<?php if ( ! $license ) : ?>
			<?php $this->render_license_cta(); ?>
		<?php endif; ?>
	</form>
</div>
		<?php
	}

	/**
	 * Registers the license key setting.
	 *
	 * @return void
	 */
	public function register_setting() {
		register_setting(
			$this->theme_slug . '-license',
			$this->theme_slug . '_license_key',
			array( $this, 'sanitize_license' )
		);
	}

	/**
	 * Sanitizes the license key and handles status changes.
	 *
	 * @param string $new The new license key value.
	 * @return string The sanitized license key.
	 */
	public function sanitize_license( $new ) {
		$old = $this->managers->options->get( 'license_key' );

		if ( $old && $old !== $new ) {
			// New license has been entered, so must reactivate
			$this->managers->options->delete( 'license_key_status' );
		}

		return $new;
	}

	/**
	 * Renders the license call-to-action section.
	 *
	 * @return void
	 */
	private function render_license_cta() {
		$strings = $this->strings;

		?>
<div class="card" id="license-cta-card">
	<h2><?php echo esc_html( $strings['license-help-no-purchase-code-heading'] ); ?></h2>
	<p>
		<?php printf( $strings['license-help-no-purchase-code-text'], wp_kses_post( '<a href="' . esc_url( $strings['item-checkout-url'] ) . '" target="_blank" rel="nofollow">' . esc_html( $strings['license-help-no-purchase-code-link'] ) . '</a>' ) ); ?>
		<?php echo esc_html( $strings['license-help-no-purchase-code-benefits-before'] ); ?></p>
		<?php if ( is_array( $strings['license-help-no-purchase-code-benefits'] ) && ! empty( $strings['license-help-no-purchase-code-benefits'] ) ) : ?>
	<ul class="ul-disc">
			<?php foreach ( $strings['license-help-no-purchase-code-benefits'] as $benefit ) : ?>
		<li><?php echo esc_html( $benefit ); ?></li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
	<p>
		<a class="button button-primary button-hero" href="<?php echo esc_url( $strings['item-checkout-url'] ); ?>"
			target="_blank"><?php echo esc_html( $strings['item-checkout-link'] ); ?></a>
		<a class="button button-secondary button-hero" href="<?php echo esc_url( $strings['item-page-url'] ); ?>"
			target="_blank"><?php echo esc_html( $strings['item-page-link'] ); ?></a>
	</p>
</div>
		<?php
	}

	/**
	 * Renders the license key input row.
	 *
	 * @param string $license The current license key.
	 * @param bool   $is_valid_license Whether the license is valid.
	 * @return void
	 */
	private function render_license_row( $license, $is_valid_license ) {
		$strings = $this->strings;

		// Checks license status to display under license key
		if ( ! $license ) {
			$message = $strings['enter-key'];
		} else {
			$message = $strings['license-key-activated'];
		}

		// Hide the trash/clear button until a key exists — there's nothing to clear otherwise.
		$clear_button_class = $license ? 'button arts-license-ajax-button license-input-wrapper__button' : 'button arts-license-ajax-button license-input-wrapper__button license-hidden';

		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $strings['license-key'] ); ?></th>
	<td>
		<?php if ( $is_valid_license ) : ?>
		<div class="card" style="margin-top: 0;">
			<?php endif; ?>
			<?php if ( $is_valid_license ) : ?>
			<h2 class="title"><?php echo esc_html( $license ); ?></h2>
			<p class="description license-color-active"><?php echo esc_html( $message ); ?></p>
			<br>
			<?php else : ?>
				<?php // pattern allows 8–128 chars rather than a UUID, so Freemius keys with markers like %, +, ^ pass HTML5 validation (commit 5dbc353). ?>
			<div class="license-input-wrapper">
				<input id="<?php echo esc_attr( $this->theme_slug . '_license_key' ); ?>"
					name="<?php echo esc_attr( $this->theme_slug . '_license_key' ); ?>" type="text"
					class="regular-text license-input" value="<?php echo esc_attr( $license ); ?>" autocomplete="off"
					autocorrect="off" autocapitalize="off" spellcheck="false" maxlength="128"
					placeholder="License key..."
					pattern=".{8,128}" required
					title="Paste your license key" />
				<button id="clear-license-button" class="<?php echo esc_attr( $clear_button_class ); ?>" type="button"
					name="<?php echo esc_attr( $this->theme_slug . '_license_clear' ); ?>" data-ajax-action="clear_license"
					aria-label="<?php echo esc_attr( $strings['clear-license'] ); ?>">
					<span class="arts-license-ajax-button__icon dashicons dashicons-trash"></span>
				</button>
			</div>
			<p class="description">
				<a href="<?php echo esc_html( $strings['license-help-purchase-code-url'] ); ?>"
					target="_blank"><?php echo esc_html( $strings['license-help-purchase-code'] ); ?></a>
			</p>
			<br>
			<?php endif; ?>
			<?php
			// Activate/deactivate use type="submit" (full POST handled by register_license_actions on admin_init);
			// refresh/clear carry a data-ajax-action and are intercepted by JS for an in-place AJAX update.
			?>
			<?php if ( $license ) : ?>
				<?php if ( $is_valid_license ) : ?>
			<input type="submit" class="button button-primary button-large"
				name="<?php echo esc_attr( $this->theme_slug . '_license_deactivate' ); ?>"
				value="<?php echo esc_attr( $strings['deactivate-license'] ); ?>"
				data-ajax-action="deactivate_license" />
			<button id="refresh-license-button" class="button button-secondary arts-license-ajax-button right" type="submit"
				name="<?php echo esc_attr( $this->theme_slug . '_license_refresh' ); ?>"
				value="<?php echo esc_attr( $strings['refresh-license'] ); ?>" data-ajax-action="refresh_license">
				<span class="arts-license-ajax-button__icon-animated dashicons dashicons-update"></span>
				<span class="arts-license-ajax-button__label"><?php echo esc_html( $strings['refresh-license'] ); ?></span>
			</button>
			<?php else : ?>
			<input type="submit" class="button button-primary button-large"
				name="<?php echo esc_attr( $this->theme_slug . '_license_activate' ); ?>"
				value="<?php echo esc_attr( $strings['activate-license'] ); ?>"
				data-ajax-action="activate_license" />
			<?php endif; ?>
			<?php else : ?>
			<input type="submit" class="button button-primary button-large"
				name="<?php echo esc_attr( $this->theme_slug . '_license_activate' ); ?>"
				value="<?php echo esc_attr( $strings['activate-license'] ); ?>"
				data-ajax-action="activate_license" />
			<?php endif; ?>
			<?php if ( $is_valid_license ) : ?>
		</div>
		<?php endif; ?>
	</td>
</tr>
		<?php
	}

	/**
	 * Renders the license expiration row.
	 *
	 * @return void
	 */
	private function render_expiration_row() {
		$license_expiration_date = $this->get_expiration_date_string();
		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $this->strings['license-expiration-date'] ); ?></th>
	<td><span id="license-expires" class="license-info"><?php echo esc_html( $license_expiration_date ); ?></span></td>
</tr>
		<?php
	}

	/**
	 * Renders the license activation information row.
	 *
	 * @return void
	 */
	private function render_activation_row() {
		$activated_sites   = $this->managers->options->get( 'license_site_count' );
		$activation_limit  = $this->managers->options->get( 'license_limit' );
		$is_local          = $this->managers->options->get( 'license_is_local' );
		$description_class = $is_local ? 'description license-info' : 'description license-hidden license-info';
		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $this->strings['license-activations'] ); ?></th>
	<td>
		<strong><span id="license-site-count"
				class="license-info"><?php echo esc_html( $activated_sites ); ?></span>&nbsp;<span
				id="license-site-count-limit-delimiter" class="license-info">/</span>&nbsp;<span id="license-limit"
				class="license-info"><?php echo esc_html( $activation_limit ); ?></span></strong>
		<p id="license-site-count-description" class="<?php echo esc_attr( $description_class ); ?>">
			<em><?php echo esc_html( $this->strings['license-local-info'] ); ?></em>
		</p>
	</td>
</tr>
		<?php
	}

	/**
	 * Renders the purchase date row.
	 *
	 * @return void
	 */
	private function render_purchase_date_row() {
		$purchase_date = $this->get_purchase_date_string();
		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $this->strings['license-purchase-date'] ); ?></th>
	<td><span id="license-date-purchased" class="license-info"><?php echo esc_html( $purchase_date ); ?></span></td>
</tr>
		<?php
	}

	/**
	 * Renders the updates provided until row.
	 *
	 * @return void
	 */
	private function render_updates_row() {
		$updates_provided_until_date = $this->get_date_updates_provided_until_string();
		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $this->strings['license-updates-provided-until'] ); ?></th>
	<td><span id="license-date-updates-provided-until"
			class="license-info"><?php echo esc_html( $updates_provided_until_date ); ?></span></td>
</tr>
		<?php
	}

	/**
	 * Renders the support row.
	 *
	 * @return void
	 */
	private function render_support_row() {
		$is_support_provided             = $this->managers->license->is_support_provided();
		$date_supported_full_string      = $this->get_date_supported_full_string();
		$date_supported_until_class      = $is_support_provided ? 'license-info license-color-active' : 'license-info license-color-expired';
		$description_support_forum_class = $is_support_provided ? 'description license-info' : 'description license-hidden license-info';
		$description_renew_support_class = $is_support_provided ? 'description license-hidden license-info' : 'description license-info';

		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $this->strings['license-supported-until'] ); ?></th>
	<td>
		<span id="license-date-supported-until"
			class="<?php echo esc_attr( $date_supported_until_class ); ?>"><?php echo esc_html( $date_supported_full_string ); ?></span>
		<p id="license-support-forum" class="<?php echo esc_attr( $description_support_forum_class ); ?>">
			<a class="button button-secondary button-large"
				href="<?php echo esc_url( $this->strings['support-forum-url'] ); ?>"
				target="_blank"><?php echo esc_html( $this->strings['support-forum-link'] ); ?></a>
		</p>
		<p id="license-renew-support" class="<?php echo esc_attr( $description_renew_support_class ); ?>">
			<a class="button button-secondary button-large" href="<?php echo esc_url( $this->strings['item-page-url'] ); ?>"
				target="_blank"><?php echo esc_html( $this->strings['support-renew'] ); ?></a>
		</p>
	</td>
</tr>
		<?php
	}

	/**
	 * Gets the formatted expiration date string.
	 *
	 * @return string The formatted expiration date.
	 */
	private function get_expiration_date_string() {
		$license_expires = $this->managers->options->get( 'license_expires' );

		// The license API uses the literal 'lifetime' sentinel for non-expiring licenses.
		if ( strtolower( $license_expires ) === 'lifetime' ) {
			return $this->strings['expires-never'];
		}

		return $license_expires ? gmdate( $this->date_format, strtotime( $license_expires ) ) : '';
	}

	/**
	 * Gets the formatted date supported until string.
	 *
	 * @return string The formatted support until date.
	 */
	private function get_date_supported_until_string() {
		$support_provided_until      = $this->managers->options->get( 'license_date_supported_until' );
		$support_provided_until_date = $support_provided_until ? gmdate( $this->date_format, strtotime( $support_provided_until ) ) : $this->strings['date-unknown'];

		return $support_provided_until_date;
	}

	/**
	 * Gets the formatted purchase date string.
	 *
	 * @return string The formatted purchase date.
	 */
	private function get_purchase_date_string() {
		$date_purchased = $this->managers->options->get( 'license_date_purchased' );
		$purchase_date  = $date_purchased ? gmdate( $this->date_format, strtotime( $date_purchased ) ) : $this->strings['date-unknown'];

		return $purchase_date;
	}

	/**
	 * Gets the formatted support date with status message.
	 *
	 * @return string The formatted support date with status.
	 */
	private function get_date_supported_full_string() {
		$support_provided_until_date = $this->get_date_supported_until_string();
		$is_support_provided         = $this->managers->license->is_support_provided();

		if ( $is_support_provided ) {
			return $this->strings['support-supported-until'] . ' ' . $support_provided_until_date;
		}

		return $this->strings['support-expired'] . ' ' . $support_provided_until_date;
	}

	/**
	 * Gets the formatted updates provided until date string.
	 *
	 * @return string The formatted updates until date.
	 */
	private function get_date_updates_provided_until_string() {
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
	 * A store-provided URL (persisted from license responses) with the theme-string fallback.
	 *
	 * @param string $option_key  Option holding the store-provided URL.
	 * @param string $string_key  Theme-string key with the bundled default.
	 * @return string
	 */
	private function store_url_with_fallback( $option_key, $string_key ) {
		$url = $this->managers->options->get( $option_key, '' );

		return ! empty( $url ) ? $url : $this->strings[ $string_key ];
	}

	/**
	 * Checks if the email modal should auto-open and handles suppression.
	 *
	 * @param string $license_key The current license key.
	 * @return bool True if the modal should auto-open, false otherwise.
	 */
	private function should_auto_open_modal( $license_key ) {
		// Check if connect=1 parameter is present
		if ( ! isset( $_GET['connect'] ) || $_GET['connect'] !== '1' ) {
			return false;
		}

		// Verify nonce
		if ( ! isset( $_GET['_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_nonce'] ) ), 'license_connect_' . wp_hash( $license_key ) ) ) {
			return false;
		}

		// Verify license hash
		if ( ! isset( $_GET['lh'] ) || $_GET['lh'] !== wp_hash( $license_key ) ) {
			return false;
		}

		// Check if license is still unlinked
		$email_link_state = $this->managers->options->get( 'email_link_state', 'alias' );
		if ( $email_link_state !== 'alias' ) {
			return false;
		}

		// All checks passed - set suppression flag and return true
		$this->managers->options->set_modal_suppressed( $license_key, 'license_screen' );
		return true;
	}

	/**
	 * Renders the license owner row.
	 *
	 * @return void
	 */
	private function render_owner_row() {
		$strings           = $this->strings;
		$email_link_state  = $this->managers->options->get( 'email_link_state', 'alias' );
		$masked_email      = $this->managers->options->get( 'masked_email', '' );
		$order_history_url = $this->managers->options->get( 'order_history_url', '' );
		$is_linked         = $email_link_state === 'real';

		?>
<tr valign="top">
	<th class="row-title"><?php echo esc_html( $strings['owner'] ); ?></th>
	<td>
		<div class="card owner-block" style="margin-top: 0;">
			<?php if ( $is_linked ) : ?>
				<?php
				// Use personalized title if we have masked email, otherwise use generic linked title
				$linked_title = ! empty( $masked_email )
					? sprintf( $strings['owner-linked-to'], esc_html( $masked_email ) )
					: esc_html( $strings['owner-linked'] );

				// Use dynamic URL if available, otherwise fall back to the theme-configured account site
				$manage_url = ! empty( $order_history_url )
					? $order_history_url
					: $strings['modal-account-site-url'];
				?>
				<h2 class="title"><?php echo wp_kses_post( $linked_title ); ?></h2>
				<p class="description license-color-active">
					<a href="<?php echo esc_url( $manage_url ); ?>" target="_blank" rel="noopener">
						<?php echo esc_html( $strings['owner-manage-online'] ); ?>
					</a>
				</p>
			<?php else : ?>
				<h2 class="title"><?php echo esc_html( $strings['owner-not-linked'] ); ?></h2>
				<p class="description"><?php echo esc_html( $strings['owner-helper-1'] ); ?></p>
				<p class="description"><?php echo esc_html( $strings['owner-helper-2'] ); ?></p>
				<br>
				<button type="button" id="connect-to-account-btn" class="button button-primary" data-source="license_screen">
					<?php echo esc_html( $strings['owner-connect'] ); ?>
				</button>
			<?php endif; ?>
		</div>
	</td>
		</tr>
		<?php
	}   /**
		 * Renders the deactivated license view (input field + activate button)
		 *
		 * @param string $license The license key.
		 * @param bool   $is_valid_license Whether the license is valid.
		 * @return void
		 */
	private function render_deactivated_view( $license, $is_valid_license ) {
		$strings = $this->strings;

		// Show deactivated view when license is not valid
		$view_class = $is_valid_license ? 'license-view license-view--deactivated license-hidden' : 'license-view license-view--deactivated';

		?>
		<div class="<?php echo esc_attr( $view_class ); ?>" id="license-deactivated-view">
			<table class="form-table">
				<tbody>
					<?php $this->render_license_row( $license, false ); ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Renders the activated license view (license details + deactivate button)
	 *
	 * @param string $license The license key.
	 * @param bool   $is_valid_license Whether the license is valid.
	 * @return void
	 */
	private function render_activated_view( $license, $is_valid_license ) {
		// Show activated view when license is valid
		$view_class = $is_valid_license ? 'license-view license-view--activated' : 'license-view license-view--activated license-hidden';

		?>
		<div class="<?php echo esc_attr( $view_class ); ?>" id="license-activated-view">
			<table class="form-table" role="presentation">
				<tbody>
					<?php if ( $is_valid_license ) : ?>
						<?php $this->render_license_row( $license, true ); ?>
						<?php $this->render_owner_row(); ?>
						<?php $this->render_expiration_row(); ?>
						<?php $this->render_activation_row(); ?>
						<?php $this->render_purchase_date_row(); ?>
						<?php $this->render_updates_row(); ?>
						<?php $this->render_support_row(); ?>
					<?php else : ?>
						<!-- Render empty activated view structure for smooth transitions -->
						<?php $this->render_license_row( $license, true ); ?>
						<?php $this->render_owner_row_empty(); ?>
						<?php $this->render_expiration_row_empty(); ?>
						<?php $this->render_activation_row_empty(); ?>
						<?php $this->render_purchase_date_row_empty(); ?>
						<?php $this->render_updates_row_empty(); ?>
						<?php $this->render_support_row_empty(); ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Renders an empty owner row for smooth transitions
	 */
	private function render_owner_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['owner'] ); ?></th>
			<td>
				<div class="card owner-block" style="margin-top: 0;">
					<!-- Empty content will be populated on activation -->
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Renders an empty expiration row for smooth transitions
	 */
	private function render_expiration_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['license-expiration-date'] ); ?></th>
			<td><span id="license-expires" class="license-info"></span></td>
		</tr>
		<?php
	}

	/**
	 * Renders an empty activation row for smooth transitions
	 */
	private function render_activation_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['license-activations'] ); ?></th>
			<td>
				<strong><span id="license-site-count" class="license-info"></span>&nbsp;<span
					id="license-site-count-limit-delimiter" class="license-info">/</span>&nbsp;<span id="license-limit"
					class="license-info"></span></strong>
				<p id="license-site-count-description" class="description license-info">
					<em><?php echo esc_html( $this->strings['license-local-info'] ); ?></em>
				</p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Renders an empty purchase date row for smooth transitions
	 */
	private function render_purchase_date_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['license-purchase-date'] ); ?></th>
			<td><span id="license-date-purchased" class="license-info"></span></td>
		</tr>
		<?php
	}

	/**
	 * Renders an empty updates row for smooth transitions
	 */
	private function render_updates_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['license-updates-provided-until'] ); ?></th>
			<td><span id="license-date-updates-provided-until" class="license-info"></span></td>
		</tr>
		<?php
	}

	/**
	 * Renders an empty support row for smooth transitions
	 */
	private function render_support_row_empty() {
		?>
		<tr valign="top">
			<th class="row-title"><?php echo esc_html( $this->strings['license-supported-until'] ); ?></th>
			<td>
				<span id="license-date-supported-until" class="license-info"></span>
				<p id="license-support-forum" class="description license-hidden license-info">
					<a class="button button-secondary button-large"
						href="<?php echo esc_url( $this->strings['support-forum-url'] ); ?>"
						target="_blank"><?php echo esc_html( $this->strings['support-forum-link'] ); ?></a>
				</p>
				<p id="license-renew-support" class="description license-info">
					<a class="button button-secondary button-large" href="<?php echo esc_url( $this->strings['item-page-url'] ); ?>"
						target="_blank"><?php echo esc_html( $this->strings['support-renew'] ); ?></a>
				</p>
			</td>
		</tr>
		<?php
	}
}
