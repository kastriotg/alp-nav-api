<?php
/**
 * Handles admin AJAX and logic for Brands.
 */
class Alp_Nav_Api_Admin_Brands {
    public static function register_ajax() {
        add_action('wp_ajax_alpnav_get_and_save_brands', [__CLASS__, 'ajax_get_and_save_brands']);
    }

    public static function ajax_get_and_save_brands() {
        check_ajax_referer('alpnav_get_and_save_brands', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/api/class-alp-nav-api-client.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cpt/class-alp-nav-api-cpt-brands.php';
        $response = Alp_Nav_Api_Client::get('brands');
        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }
        $brands = isset($response['data']) ? $response['data'] : $response;
        $saved = [];
        foreach ($brands as $brand) {
            // Map API keys to CPT keys
            $brand['brandId'] = isset($brand['brandID']) ? $brand['brandID'] : (isset($brand['brandId']) ? $brand['brandId'] : '');
            $brand['telephone'] = isset($brand['telephone']) ? strval($brand['telephone']) : '';
            // Check for required fields
            if (empty($brand['brandId']) || empty($brand['name'])) continue;
            // Find all posts with this brandId
            $existing = get_posts([
                'post_type' => 'brand',
                'meta_key' => 'brandId',
                'meta_value' => $brand['brandId'],
                'post_status' => 'any',
                'fields' => 'ids',
                'numberposts' => -1
            ]);
            $post_id = null;
            if ($existing && count($existing) > 0) {
                // Keep the first, delete the rest
                $post_id = $existing[0];
                foreach (array_slice($existing, 1) as $dup_id) {
                    wp_delete_post($dup_id, true);
                }
                wp_update_post([
                    'ID' => $post_id,
                    'post_title' => $brand['name'],
                ]);
            } else {
                $post_id = wp_insert_post([
                    'post_type' => 'brand',
                    'post_title' => $brand['name'],
                    'post_status' => 'publish',
                ]);
            }
            if ($post_id && !is_wp_error($post_id)) {
                $fields = ['brandId', 'brand', 'name', 'website', 'email', 'telephone'];
                foreach ($fields as $field) {
                    if (isset($brand[$field])) {
                        update_post_meta($post_id, $field, sanitize_text_field($brand[$field]));
                    }
                }
                $saved[] = $post_id;
            }
        }
        wp_send_json_success(['saved' => $saved, 'count' => count($saved)]);
    }
}
