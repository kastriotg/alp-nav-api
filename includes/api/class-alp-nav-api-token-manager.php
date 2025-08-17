<?php
/**
 * Manages API token lifecycle for AlpNav Plugin.
 */
class Alp_Nav_Api_Token_Manager {
    /**
     * Get a valid access token, refreshing if expired or missing.
     * @return string|false The access token or false on failure.
     */
    public static function get_token() {
        $token = get_option('alpnav_api_token');
        $expires_at = get_option('alpnav_api_token_expires_at');
        $client_id = get_option('alpnav_client_id');
        $client_secret = get_option('alpnav_client_secret');
        $now = time();
        // If token is missing or expired (with 60s buffer), refresh
        if (!$token || !$expires_at || $now >= ($expires_at - 60)) {
            $result = Alp_Nav_Api_Auth_Tester::test_auth($client_id, $client_secret);
            if ($result['success'] && !empty($result['token'])) {
                $token = $result['token'];
                $expires_in = !empty($result['expires_in']) ? intval($result['expires_in']) : 3600;
                update_option('alpnav_api_token', $token);
                update_option('alpnav_api_token_expires_at', $now + $expires_in);
                return $token;
            } else {
                return false;
            }
        }
        return $token;
    }
}
