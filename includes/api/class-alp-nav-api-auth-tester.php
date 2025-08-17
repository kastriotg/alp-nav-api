<?php
/**
 * Handles authentication test logic for AlpNav Plugin.
 */
class Alp_Nav_Api_Auth_Tester {
	public static function test_auth( $client_id, $client_secret ) {
		$body = array(
			'grant_type' => 'client_credentials',
			'client_id' => $client_id,
			'client_secret' => $client_secret
		);
		$response = wp_remote_post('https://api.globaltransfersgroup.com/v1/auth/token', array(
			'headers' => array('Content-Type' => 'application/json'),
			'body' => json_encode($body),
			'timeout' => 15
		));
		if ( is_wp_error( $response ) ) {
			return array( 'success' => false, 'message' => $response->get_error_message() );
		}
		   $code = wp_remote_retrieve_response_code( $response );
		   $body = wp_remote_retrieve_body( $response );
		   $data = json_decode( $body, true );
		   if ( $code === 200 && !empty($data['access_token']) ) {
			   return array(
				   'success' => true,
				   'token' => $data['access_token'],
				   'expires_in' => isset($data['expires_in']) ? intval($data['expires_in']) : 3600
			   );
		   } else {
			   $msg = isset($data['error_description']) ? $data['error_description'] : 'Auth failed (HTTP ' . $code . ')';
			   return array( 'success' => false, 'message' => $msg );
		   }
	}
}
