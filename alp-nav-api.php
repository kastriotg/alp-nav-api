<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://anchorzup.com/
 * @since             1.0.0
 * @package           Alp_Nav_Api
 *
 * @wordpress-plugin
 * Plugin Name:       Alp Nav API
 * Plugin URI:        https://anchorzup.com/
 * Description:       This plugin integrates with the GTG platform via REST API calls, automatically processing the received data and performing the required actions based on the API responses.
 * Version:           1.0.0
 * Author:            AnchorzUP
 * Author URI:        https://anchorzup.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       alp-nav-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ALP_NAV_API_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-alp-nav-api-activator.php
 */
function activate_alp_nav_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/core/class-alp-nav-api-activator.php';
	Alp_Nav_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-alp-nav-api-deactivator.php
 */
function deactivate_alp_nav_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/core/class-alp-nav-api-deactivator.php';
	Alp_Nav_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_alp_nav_api' );
register_deactivation_hook( __FILE__, 'deactivate_alp_nav_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-alp-nav-api.php';
// Register AJAX handler for Test Auth
if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-alp-nav-api-admin.php';
	Alp_Nav_Api_Admin::register_ajax();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_alp_nav_api() {

	$plugin = new Alp_Nav_Api();
	$plugin->run();

}
run_alp_nav_api();
