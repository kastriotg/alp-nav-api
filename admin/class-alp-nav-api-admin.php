<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-alp-nav-api-settings.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-alp-nav-api-auth-tester.php';
require_once plugin_dir_path( __FILE__ ) . '/class-alp-nav-api-admin-view.php';
require_once plugin_dir_path( __FILE__ ) . '/class-alp-nav-api-admin-assets.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://anchorzup.com/
 * @since      1.0.0
 *
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Alp_Nav_Api
 * @subpackage Alp_Nav_Api/admin
 * @author     AnchorzUP <kastriotsgashi@gmail.com>
 */

class Alp_Nav_Api_Admin {
	/**
	* The ID of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
		private $plugin_name;

		/**
	* The version of this plugin.
	*
	* @since    1.0.0
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
		private $version;

		/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.0
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array($this, 'add_admin_menu'));
		if (class_exists('Alp_Nav_Api_Admin_Brands')) {
			Alp_Nav_Api_Admin_Brands::register_ajax();
		} else {
			require_once plugin_dir_path(__FILE__) . 'class-alp-nav-api-admin-brands.php';
			Alp_Nav_Api_Admin_Brands::register_ajax();
		}
		if (class_exists('Alp_Nav_Api_Admin_Locations')) {
			Alp_Nav_Api_Admin_Locations::register_ajax();
		} else {
			require_once plugin_dir_path(__FILE__) . 'class-alp-nav-api-admin-locations.php';
			Alp_Nav_Api_Admin_Locations::register_ajax();
		}
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
	}

	public function enqueue_admin_scripts($hook) {
		// Only enqueue on our brands or areas admin page
		if (
			$hook === 'toplevel_page_alpnav-plugin-brands' || $hook === 'alpnav-plugin_page_alpnav-plugin-brands' ||
			$hook === 'toplevel_page_alpnav-plugin-locations' || $hook === 'alpnav-plugin_page_alpnav-plugin-locations'
		) {
			wp_enqueue_script('alpnav-admin-js', plugin_dir_url(__FILE__) . 'js/alp-nav-api-admin.js', array('jquery'), null, true);
			wp_localize_script('alpnav-admin-js', 'alpnavAdmin', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('alpnav_get_and_save_brands'),
				'nonce_save_brand' => wp_create_nonce('alpnav_save_selected_brand'),
				'nonce_get_and_save_locations' => wp_create_nonce('alpnav_get_and_save_locations'),
			));
		}
	}


	public function add_admin_menu() {
		add_menu_page(
			'AlpNav Plugin', // Page title
			'AlpNav Plugin', // Menu title
			'manage_options', // Capability
			'alpnav-plugin', // Menu slug
			array( $this, 'display_admin_page' ), // Callback
			'dashicons-admin-generic', // Icon
			25 // Position
		);
		add_submenu_page(
			'alpnav-plugin',
			'Plugin Settings',
			'Plugin Settings',
			'manage_options',
			'alpnav-plugin',
			array( $this, 'display_admin_page' )
		);
		add_submenu_page(
			'alpnav-plugin',
			'Brands',
			'Brands',
			'manage_options',
			'alpnav-plugin-brands',
			array( $this, 'display_brands_admin_page' )
		);
		add_submenu_page(
			'alpnav-plugin',
			'Locations',
			'Locations',
			'manage_options',
			'alpnav-plugin-locations',
			array( $this, 'display_locations_admin_page' )
		);
	}

	public function display_brands_admin_page() {
		include plugin_dir_path(__FILE__) . 'partials/alp-nav-api-admin-brands.php';
	}

	public function display_locations_admin_page() {
		include plugin_dir_path(__FILE__) . 'partials/alp-nav-api-admin-locations.php';
	}

	
	public function display_admin_page() {
		   $settings = Alp_Nav_Api_Settings::get_instance();
		   echo '<div class="wrap"><h1>AlpNav Plugin</h1>';
		   if ( isset( $_POST['alpnav_plugin_settings_nonce'] ) && wp_verify_nonce( $_POST['alpnav_plugin_settings_nonce'], 'alpnav_plugin_save_settings' ) ) {
			   $client_id = isset($_POST['alpnav_client_id']) ? $_POST['alpnav_client_id'] : '';
			   $client_secret = isset($_POST['alpnav_client_secret']) ? $_POST['alpnav_client_secret'] : '';
			   $settings->save( $client_id, $client_secret );
			   // Get and save token
			   $result = Alp_Nav_Api_Auth_Tester::test_auth( $client_id, $client_secret );
			   if ( $result['success'] && !empty($result['token']) ) {
				   update_option('alpnav_api_token', $result['token']);
				   $expires_in = !empty($result['expires_in']) ? intval($result['expires_in']) : 3600;
				   update_option('alpnav_api_token_expires_at', time() + $expires_in);
				   echo '<div class="updated notice is-dismissible"><p>Settings and token saved.</p></div>';
			   } else {
				   delete_option('alpnav_api_token');
				   delete_option('alpnav_api_token_expires_at');
				   $msg = isset($result['message']) ? esc_html($result['message']) : 'Could not retrieve token.';
				   echo '<div class="error notice is-dismissible"><p>Settings saved, but token not retrieved: ' . $msg . '</p></div>';
			   }
		   }
		   if ( isset( $_POST['alpnav_plugin_clear_nonce'] ) && wp_verify_nonce( $_POST['alpnav_plugin_clear_nonce'], 'alpnav_plugin_clear_credentials' ) ) {
			   delete_option('alpnav_client_id');
			   delete_option('alpnav_client_secret');
			   echo '<div class="updated notice is-dismissible"><p>Credentials cleared. You can now set new credentials.</p></div>';
		   }
		   $client_id = $settings->get_client_id();
		   $client_secret = $settings->get_client_secret();
		   $creds_set = !empty($client_id) && !empty($client_secret);
		Alp_Nav_Api_Admin_View::render_settings_page($creds_set, $client_id, $client_secret);
		Alp_Nav_Api_Admin_Assets::render_test_auth_js();
	}

	public static function register_ajax() {
		add_action( 'wp_ajax_alpnav_test_auth', [__CLASS__, 'handle_test_auth'] );
	}

	public static function handle_test_auth() {
		$client_id = isset($_POST['client_id']) ? sanitize_text_field($_POST['client_id']) : '';
		$client_secret = isset($_POST['client_secret']) ? sanitize_text_field($_POST['client_secret']) : '';
		$result = Alp_Nav_Api_Auth_Tester::test_auth( $client_id, $client_secret );
		if ( $result['success'] ) {
			wp_send_json_success();
		} else {
			wp_send_json_error( $result['message'] );
		}
		wp_die();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alp_Nav_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alp_Nav_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/alp-nav-api-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Alp_Nav_Api_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Alp_Nav_Api_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/alp-nav-api-admin.js', array( 'jquery' ), $this->version, false );

	}

}
