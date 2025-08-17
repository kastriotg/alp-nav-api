<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://anchorzup.com/
 * @since      1.0.0
 *
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/includes
 * @author     AnchorzUP <kastriotsgashi@gmail.com>
 */
class Alp_Nav_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'alp-nav-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
