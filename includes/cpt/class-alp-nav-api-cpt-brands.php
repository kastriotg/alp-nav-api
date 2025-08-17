<?php

class Alp_Nav_Api_CPT_Brands {
    public static function add_meta_boxes() {
        add_meta_box(
            'brand_meta_box',
            'Brand Details',
            array(__CLASS__, 'render_meta_box'),
            'brand',
            'normal',
            'default'
        );
    }

    public static function render_meta_box($post) {
        $fields = [
            'brandId' => 'Brand ID',
            'brand' => 'Brand',
            'name' => 'Name',
            'website' => 'Website',
            'email' => 'Email',
            'telephone' => 'Telephone',
        ];
        wp_nonce_field('brand_meta_box', 'brand_meta_box_nonce');
        echo '<table class="form-table">';
        foreach ($fields as $key => $label) {
            $value = esc_attr(get_post_meta($post->ID, $key, true));
            $type = ($key === 'brandId' || $key === 'telephone') ? 'number' : 'text';
            if ($key === 'email') $type = 'email';
            if ($key === 'website') $type = 'url';
            echo '<tr><th><label for="' . $key . '">' . $label . '</label></th>';
            echo '<td><input type="' . $type . '" id="' . $key . '" name="' . $key . '" value="' . $value . '" class="regular-text" /></td></tr>';
        }
        echo '</table>';
    }

    public static function save_post($post_id) {
        if (!isset($_POST['brand_meta_box_nonce']) || !wp_verify_nonce($_POST['brand_meta_box_nonce'], 'brand_meta_box')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        $fields = ['brandId', 'brand', 'name', 'website', 'email', 'telephone'];
        foreach ($fields as $key) {
            if (isset($_POST[$key])) {
                update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
            }
        }
    }
    public static function register() {
        $labels = array(
            'name' => 'Brands',
            'singular_name' => 'Brand',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Brand',
            'edit_item' => 'Edit Brand',
            'new_item' => 'New Brand',
            'view_item' => 'View Brand',
            'search_items' => 'Search Brands',
            'not_found' => 'No Brands found',
            'not_found_in_trash' => 'No Brands found in Trash',
            'menu_name' => 'Brands',
        );
        $args = array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true, // Show in main admin menu
            'menu_icon' => 'dashicons-store',
            'supports' => array('title'),
        );
        register_post_type('brand', $args);

        // Register meta fields
        register_post_meta('brand', 'brandId', array('type' => 'integer', 'single' => true, 'show_in_rest' => true));
        register_post_meta('brand', 'brand', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('brand', 'name', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('brand', 'website', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('brand', 'email', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        register_post_meta('brand', 'telephone', array('type' => 'string', 'single' => true, 'show_in_rest' => true));
        add_action('add_meta_boxes', array(__CLASS__, 'add_meta_boxes'));
        add_action('save_post_brand', array(__CLASS__, 'save_post'));
    }
}
