<?php

namespace Arts\WizardSetup\Managers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Arts\Utilities\Utilities;

/**
 * Class WooCommerce
 *
 * Manages WooCommerce settings and configurations for the theme setup wizard.
 *
 * @since      1.0.0
 * @package    Arts\WizardSetup\Managers
 * @author     Artem Semkin
 */
class WooCommerce extends BaseManager {
	/**
	 * Performs the setup for WooCommerce.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array The results of the setup operations.
	 */
	public function setup() {
		$result = array(
			'update_woocommerce_pages' => self::update_woocommerce_pages(),
		);

		return $result;
	}

	/**
	 * Remove WooCommerce setup wizard screen on the initial plugin activation.
	 *
	 * Used to prevent issues when the Merlin wizard is installing and activating the required plugins.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function remove_setup_wizard_screen() {
		add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
	}

	/**
	 * Sets to draft the default WooCommerce pages if they exist.
	 *
	 * @since  1.0.0
	 * @access public static
	 * @param  array $args Arguments passed to the function.
	 * @return array The original arguments.
	 */
	public static function unset_default_woocommerce_pages( $args ) {
		$page_ids = array(
			get_option( 'woocommerce_cart_page_id' ),
			get_option( 'woocommerce_checkout_page_id' ),
			get_option( 'woocommerce_myaccount_page_id' ),
			get_option( 'woocommerce_shop_page_id' ),
		);

		foreach ( $page_ids as $page_id ) {
			if ( $page_id ) {
				wp_update_post(
					array(
						'ID'          => $page_id,
						'post_status' => 'draft',
					)
				);
			}
		}

		return $args;
	}

	/**
	 * Updates WooCommerce pages options with their corresponding page IDs.
	 *
	 * @since  1.0.0
	 * @access private static
	 * @return bool False if WooCommerce is not active, true otherwise.
	 */
	private static function update_woocommerce_pages() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		$page_names = array(
			'woocommerce_cart_page_id'      => 'Cart',
			'woocommerce_checkout_page_id'  => 'Checkout',
			'woocommerce_myaccount_page_id' => 'My Account',
			'woocommerce_shop_page_id'      => 'Shop',
		);

		foreach ( $page_names as $option => $page_name ) {
			$page_object = Utilities::get_page_by_title( $page_name );

			if ( $page_object && $page_object->ID ) {
				update_option( $option, $page_object->ID );
			}
		}

		return true;
	}
}
