<?php
/**
 * Handles admin AJAX and logic for Locations.
 */
class Alp_Nav_Api_Admin_Locations {
    public static function register_ajax() {
        add_action('wp_ajax_alpnav_get_and_save_locations', [__CLASS__, 'ajax_get_and_save_locations']);
    }

    public static function ajax_get_and_save_locations() {
        check_ajax_referer('alpnav_get_and_save_locations', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/api/class-alp-nav-api-client.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cpt/class-alp-nav-api-cpt-locations.php';
        $response = Alp_Nav_Api_Client::get('locations');
        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }
        $locations = isset($response['data']) ? $response['data'] : $response;
        $json = json_encode($locations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = plugin_dir_path(dirname(__FILE__)) . 'admin/locations-data.json';
        $result = file_put_contents($filename, $json);
        if ($result === false) {
            wp_send_json_error('Failed to write locations-data.json');
        }
        wp_send_json_success(['file' => basename($filename), 'count' => count($locations)]);
    }
}
