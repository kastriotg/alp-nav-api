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
        // Cleanup: Remove all locations with missing or duplicate locationID meta
        $all_locations = get_posts([
            'post_type' => 'location',
            'post_status' => 'any',
            'numberposts' => -1,
            'fields' => 'ids'
        ]);
        $locationID_map = [];
        foreach ($all_locations as $lid) {
            $meta = get_post_meta($lid, 'locationID', true);
            if (empty($meta)) {
                wp_delete_post($lid, true);
                continue;
            }
            if (!isset($locationID_map[$meta])) {
                $locationID_map[$meta] = [$lid];
            } else {
                $locationID_map[$meta][] = $lid;
            }
        }
        // Remove duplicates, keep only the first
        foreach ($locationID_map as $meta => $ids) {
            if (count($ids) > 1) {
                foreach (array_slice($ids, 1) as $dup_id) {
                    wp_delete_post($dup_id, true);
                }
            }
        }
        $saved = [];
        $processed_location_ids = [];
        foreach ($locations as $location) {
            $locationID = isset($location['locationID']) ? $location['locationID'] : '';
            $locationCode = isset($location['location']) ? $location['location'] : '';
            $name = isset($location['name']) ? $location['name'] : '';
            $areaID = isset($location['area']['areaID']) ? $location['area']['areaID'] : '';
            $areaName = isset($location['area']['name']) ? $location['area']['name'] : '';
            $country = isset($location['country']) ? $location['country'] : '';
            $distance = isset($location['distance']) ? $location['distance'] : '';
            if (empty($locationID) || empty($name)) continue;
            if (in_array($locationID, $processed_location_ids)) continue;
            $processed_location_ids[] = $locationID;
            $existing = get_posts([
                'post_type' => 'location',
                'meta_key' => 'locationID',
                'meta_value' => $locationID,
                'post_status' => 'any',
                'fields' => 'ids',
                'numberposts' => 1
            ]);
            $post_id = null;
            if ($existing && count($existing) > 0) {
                $post_id = $existing[0];
                wp_update_post([
                    'ID' => $post_id,
                    'post_title' => $name,
                ]);
            } else {
                $post_id = wp_insert_post([
                    'post_type' => 'location',
                    'post_title' => $name,
                    'post_status' => 'publish',
                ]);
                if ($post_id && !is_wp_error($post_id)) {
                    update_post_meta($post_id, 'locationID', sanitize_text_field($locationID));
                }
            }
            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, 'location', sanitize_text_field($locationCode));
                update_post_meta($post_id, 'name', sanitize_text_field($name));
                update_post_meta($post_id, 'areaID', sanitize_text_field($areaID));
                update_post_meta($post_id, 'areaName', sanitize_text_field($areaName));
                update_post_meta($post_id, 'country', sanitize_text_field($country));
                update_post_meta($post_id, 'distance', sanitize_text_field($distance));
                $saved[] = $post_id;
            }
        }
        wp_send_json_success(['saved' => $saved, 'count' => count($saved)]);
    }
}
