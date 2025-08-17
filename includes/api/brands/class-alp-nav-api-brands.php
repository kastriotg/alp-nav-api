<?php
/**
 * Brands API client for AlpNav Plugin.
 * Extends the general API client.
 */
class Alp_Nav_Api_Brands extends Alp_Nav_Api_Client {
	/**
	 * Get all brands from the API.
	 * @param array $args Optional query args
	 * @return array|WP_Error
	 */
    public static function get($endpoint = 'brands', $args = array()) {
        // Calls parent::get with 'brands' endpoint by default
        return parent::get($endpoint, $args);
    }



    public static function import_from_api() {
		$response = self::get();
		if (!is_wp_error($response) && !empty($response['data'])) {
			if (!class_exists('Alp_Nav_Api_Brands_Importer')) {
				require_once __DIR__ . '/class-alp-nav-api-brands-importer.php';
			}
			$result = Alp_Nav_Api_Brands_Importer::import($response['data']);
			return [
				'created' => $result['created'],
				'updated' => $result['updated'],
				'error' => null
			];
		} else {
			$msg = is_wp_error($response) ? $response->get_error_message() : 'No data returned from API.';
			return [ 'created' => 0, 'updated' => 0, 'error' => $msg ];
		}
	}
}
