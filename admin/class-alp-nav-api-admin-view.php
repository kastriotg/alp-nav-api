<?php
/**
 * Responsible for rendering the AlpNav Plugin admin settings page HTML.
 */
class Alp_Nav_Api_Admin_View {
    public static function render_settings_page($creds_set, $client_id, $client_secret) {
        echo '<form method="post">';
        wp_nonce_field( 'alpnav_plugin_save_settings', 'alpnav_plugin_settings_nonce' );
        echo '<table class="form-table">';
        if ($creds_set) {
            echo '<tr><th scope="row">API Credentials</th><td><span style="color:green;">Credentials are set.</span></td></tr>';
        } else {
            echo '<tr><th scope="row"><label for="alpnav_client_id">Client ID</label></th><td><input name="alpnav_client_id" id="alpnav_client_id" type="text" value="" class="regular-text" required></td></tr>';
            echo '<tr><th scope="row"><label for="alpnav_client_secret">Client Secret</label></th><td><input name="alpnav_client_secret" id="alpnav_client_secret" type="password" value="" class="regular-text" required></td></tr>';
        }
        echo '</table>';
        echo '<p class="submit">';
        if (!$creds_set) {
            echo '<input type="submit" class="button-primary" value="Save Changes"> ';
            echo '<button type="button" class="button" id="alpnav-test-auth">Test auth</button> ';
        }
        if ($creds_set) {
            wp_nonce_field( 'alpnav_plugin_clear_credentials', 'alpnav_plugin_clear_nonce' );
            echo '<button type="submit" name="alpnav_clear_credentials" value="1" class="button button-secondary" onclick="return confirm(\'Are you sure you want to clear the credentials?\');">Clear Credentials</button>';
        }
        echo '</p>';
        echo '<div id="alpnav-auth-result"></div>';
        echo '</form>';
    }
}
