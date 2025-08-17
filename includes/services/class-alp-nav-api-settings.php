<?php
/**
 * Handles AlpNav Plugin settings (Client ID, Client Secret) using Singleton pattern.
 */
class Alp_Nav_Api_Settings {
    private static $instance = null;
    private $option_client_id = 'alpnav_client_id';
    private $option_client_secret = 'alpnav_client_secret';

    private function __construct() {}

    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_client_id() {
        return get_option( $this->option_client_id, '' );
    }
    public function get_client_secret() {
        return get_option( $this->option_client_secret, '' );
    }
    public function save( $client_id, $client_secret ) {
        update_option( $this->option_client_id, sanitize_text_field( $client_id ) );
        update_option( $this->option_client_secret, sanitize_text_field( $client_secret ) );
    }
}
