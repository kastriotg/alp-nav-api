<?php

require_once __DIR__ . '/class-alp-nav-api-token-manager.php';

/**
 * General API client for AlpNav Plugin.
 */
class Alp_Nav_Api_Client {
    /**
     * The base API URL (up to /v1/).
     * @var string
     */
    private static $base_url = 'https://api.globaltransfersgroup.com/v1/';

    /**
     * Make a GET request to the API.
     * @param string $endpoint The endpoint after /v1/, e.g. 'brands'
     * @param array $args      Optional WP_Http args (query params, etc)
     * @return array|WP_Error  Decoded response array or WP_Error
     */
    public static function get($endpoint, $args = array()) {
        $token = Alp_Nav_Api_Token_Manager::get_token();
        if (!$token) {
            return new WP_Error('no_token', 'Could not get a valid API token');
        }
        $url = self::$base_url . ltrim($endpoint, '/');
        $headers = array(
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        );
        $request_args = array(
            'headers' => $headers,
            'timeout' => 15,
        );
        if (!empty($args['query'])) {
            $url = add_query_arg($args['query'], $url);
        }
        $response = wp_remote_get($url, $request_args);
        if (is_wp_error($response)) {
            return $response;
        }
        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        if ($code === 200) {
            return $data;
        } else {
            return new WP_Error('api_error', 'API error: ' . $code, $data);
        }
    }

    // You can add POST, PUT, DELETE methods here as needed.
}
