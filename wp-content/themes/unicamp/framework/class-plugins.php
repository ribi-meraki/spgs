<?php
defined( 'ABSPATH' ) || exit;

/**
 * Plugin installation and activation for WordPress themes
 */
if ( ! class_exists( 'Unicamp_Register_Plugins' ) ) {
	class Unicamp_Register_Plugins {

		protected static $instance = null;

		const GOOGLE_DRIVER_API = 'AIzaSyDXOs0Bxx-uBEA4fH4fzgoHtl64g0RWv-g';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_filter( 'insight_core_tgm_plugins', array( $this, 'register_required_plugins' ) );
		}

		public function register_required_plugins( $plugins ) {
			/*
			 * Array of plugin arrays. Required keys are name and slug.
			 * If the source is NOT from the .org repo, then source is also required.
			 */
			$new_plugins = array(
				array(
					'name'     => esc_html__( 'Insight Core', 'unicamp' ),
					'slug'     => 'insight-core',
					'source'   => $this->get_plugin_google_driver_url( '18Ed9yJ6c8uh70wokhdZg4rxBxFw3osKR' ),
					'version'  => '2.7.4',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Unicamp Addons', 'unicamp' ),
					'slug'     => 'unicamp-addons',
					'source'   => $this->get_plugin_google_driver_url( '1_ruGi1n_ZOyK0zhksv3EKnwGWuhOXIts' ),
					'version'  => '1.1.0',
					'required' => true,
				),
				array(
					'name'     => esc_html__( 'Elementor', 'unicamp' ),
					'slug'     => 'elementor',
					'required' => true,
				),
				array(
					'name'        => esc_html__( 'ThemeMove Addons For Elementor', 'unicamp' ),
					'description' => 'Additional functions for Elementor',
					'slug'        => 'tm-addons-for-elementor',
					'source'      => $this->get_plugin_google_driver_url( '1n6fzKXb7Ij1EocMBo7WPNClFZFTzfCoL' ),
					'version'     => '2.1.0',
				),
				array(
					'name'    => esc_html__( 'Revolution Slider', 'unicamp' ),
					'slug'    => 'revslider',
					'source'  => $this->get_plugin_google_driver_url( '1GlDq6xLQ5mer8uUm_HB9iF7rkuatJgCQ' ),
					'version' => '6.7.38',
				),
				array(
					'name' => esc_html__( 'WP Events Manager', 'unicamp' ),
					'slug' => 'wp-events-manager',
				),
				array(
					'name' => esc_html__( 'WordPress Social Login', 'unicamp' ),
					'slug' => 'miniorange-login-openid',
				),
				array(
					'name' => esc_html__( 'Contact Form 7', 'unicamp' ),
					'slug' => 'contact-form-7',
				),
				array(
					'name' => esc_html__( 'MailChimp for WordPress', 'unicamp' ),
					'slug' => 'mailchimp-for-wp',
				),
				array(
					'name' => esc_html__( 'WooCommerce', 'unicamp' ),
					'slug' => 'woocommerce',
				),
				array(
					'name' => esc_html__( 'WPC Smart Compare for WooCommerce', 'unicamp' ),
					'slug' => 'woo-smart-compare',
				),
				array(
					'name' => esc_html__( 'WPC Smart Wishlist for WooCommerce', 'unicamp' ),
					'slug' => 'woo-smart-wishlist',
				),
				array(
					'name' => esc_html__( 'Widget CSS Classes', 'unicamp' ),
					'slug' => 'widget-css-classes',
				),
				array(
					'name' => esc_html__( 'Radio Buttons for Taxonomies', 'unicamp' ),
					'slug' => 'radio-buttons-for-taxonomies',
				),
				array(
					'name' => esc_html__( 'Image Hotspot by DevVN', 'unicamp' ),
					'slug' => 'devvn-image-hotspot',
				),
				array(
					'name' => esc_html__( 'Tutor LMS', 'unicamp' ),
					'slug' => 'tutor',
				),
			);

			return array_merge( $plugins, $new_plugins );
		}

		public function get_plugin_google_driver_url( $file_id ) {
			return "https://www.googleapis.com/drive/v3/files/{$file_id}?alt=media&key=" . self::GOOGLE_DRIVER_API;
		}
	}

	Unicamp_Register_Plugins::instance()->initialize();
}
