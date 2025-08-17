<?php
/**
 * Custom Post Type for Locations
 */
class Alp_Nav_Api_CPT_Locations {
    public static function add_meta_boxes() {
        add_meta_box(
            'location_meta_box',
            'Location Details',
            array(__CLASS__, 'render_meta_box'),
            'location',
            'normal',
            'default'
        );
    }

    public static function render_meta_box($post) {
        $fields = [
            'locationID' => 'Location ID',
            'location' => 'Location Code',
            'name' => 'Location Name',
            'areaID' => 'Area ID',
            'areaName' => 'Area Name',
            'country' => 'Country',
            'distance' => 'Distance',
        ];
        wp_nonce_field('location_meta_box', 'location_meta_box_nonce');
        echo '<table class="form-table">';
        foreach ($fields as $key => $label) {
            $value = esc_attr(get_post_meta($post->ID, $key, true));
        $type = ($key === 'locationID' || $key === 'areaID' || $key === 'distance') ? 'number' : 'text';
            echo '<tr><th><label for="' . $key . '">' . $label . '</label></th>';
            echo '<td><input type="' . $type . '" id="' . $key . '" name="' . $key . '" value="' . $value . '" class="regular-text" /></td></tr>';
        }
        echo '</table>';
    }

    public static function save_post($post_id) {
        if (!isset($_POST['location_meta_box_nonce']) || !wp_verify_nonce($_POST['location_meta_box_nonce'], 'location_meta_box')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        $fields = ['locationID', 'location', 'name', 'areaID', 'areaName', 'country', 'distance'];
        foreach ($fields as $key) {
            if (isset($_POST[$key])) {
                update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
            }
        }
    }
    public static function register() {
        $labels = array(
            'name' => 'Locations',
            'singular_name' => 'Location',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Location',
            'edit_item' => 'Edit Location',
            'new_item' => 'New Location',
            'view_item' => 'View Location',
            'search_items' => 'Search Locations',
            'not_found' => 'No Locations found',
            'not_found_in_trash' => 'No Locations found in Trash',
            'menu_name' => 'Locations',
        );
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-location',
            'supports' => array('title'),
        );
        register_post_type('location', $args);

        // Register meta fields
        register_post_meta('location', 'locationID', array('type' => 'integer', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'location', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'name', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'areaID', array('type' => 'integer', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'areaName', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'country', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('location', 'distance', array('type' => 'number', 'single' => true, 'show_in_rest' => true));
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post_location', array(__CLASS__, 'save_post'));
    }
}
