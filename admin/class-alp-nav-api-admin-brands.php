<?php
/**
 * Handles admin AJAX and logic for Brands.
 */
class Alp_Nav_Api_Admin_Brands {
    public static function register_ajax() {
        add_action('wp_ajax_alpnav_get_and_save_brands', [__CLASS__, 'ajax_get_and_save_brands']);
        add_action('wp_ajax_alpnav_save_selected_brand', [__CLASS__, 'ajax_save_selected_brand']);
    }
    public static function ajax_save_selected_brand() {
        check_ajax_referer('alpnav_save_selected_brand', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        $brand_id = isset($_POST['brand_id']) ? sanitize_text_field($_POST['brand_id']) : '';
        if (!$brand_id) {
            wp_send_json_error('No brand selected');
        }
        update_option('alpnav_selected_brand_id', $brand_id);
        wp_send_json_success();
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
        // Cleanup: Remove all brands with missing or duplicate brandId meta
        $all_brands = get_posts([
            'post_type' => 'brand',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids'
        ]);
        $brandId_map = [];
        foreach ($all_brands as $bid) {
            $meta = get_post_meta($bid, 'brandId', true);
            if (empty($meta)) {
                wp_delete_post($bid, true);
                continue;
            }
            if (!isset($brandId_map[$meta])) {
                $brandId_map[$meta] = [$bid];
            } else {
                $brandId_map[$meta][] = $bid;
            }
        }
        // Remove duplicates, keep only the first
        foreach ($brandId_map as $meta => $ids) {
            if (count($ids) > 1) {
                foreach (array_slice($ids, 1) as $dup_id) {
                    wp_delete_post($dup_id, true);
                }
            }
        }

        $saved = [];
        $processed_brand_ids = [];
        foreach ($brands as $brand) {
            // Map API keys to CPT keys
            $brand['brandId'] = isset($brand['brandID']) ? $brand['brandID'] : (isset($brand['brandId']) ? $brand['brandId'] : '');
            $brand['telephone'] = isset($brand['telephone']) ? strval($brand['telephone']) : '';
            // Check for required fields
            if (empty($brand['brandId']) || empty($brand['name'])) continue;
            // Prevent duplicate processing in one run
            if (in_array($brand['brandId'], $processed_brand_ids)) continue;
            $processed_brand_ids[] = $brand['brandId'];
            // Find post with this brandId
            $existing = get_posts([
                'post_type' => 'brand',
                'meta_key' => 'brandId',
                'meta_value' => $brand['brandId'],
                'post_status' => 'any',
                'fields' => 'ids',
                'numberposts' => 1
            ]);
            $post_id = null;
            if ($existing && count($existing) > 0) {
                $post_id = $existing[0];
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
                // Set brandId meta immediately to prevent duplicate inserts
                if ($post_id && !is_wp_error($post_id)) {
                    update_post_meta($post_id, 'brandId', sanitize_text_field($brand['brandId']));
                }
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
