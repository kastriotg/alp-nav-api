<?php

/**
 * Fired during plugin activation
 *
 * @link       https://anchorzup.com/
 * @since      1.0.0
 *
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/includes
 * @author     AnchorzUP <kastriotsgashi@gmail.com>
 */
class Alp_Nav_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Check if Elementor is active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !is_plugin_active( 'elementor/elementor.php' ) ) {
			deactivate_plugins( plugin_basename( __FILE__, 3 ) . '/alp-nav-api.php' );
			wp_die(
				__( 'This plugin requires Elementor Plugin to be installed and active.', 'alp-nav-api' ),
				__( 'Plugin dependency check', 'alp-nav-api' ),
				array( 'back_link' => true )
			);
		}
	}

}