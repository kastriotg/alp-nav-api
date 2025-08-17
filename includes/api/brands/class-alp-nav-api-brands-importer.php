<?php
/**
 * Service class to import brands from API response into CPT.
 * Follows SRP and can be reused/tested independently.
 */
class Alp_Nav_Api_Brands_Importer {
	/**
	 * Import brands from API response data.
	 * @param array $brandsData The decoded 'data' array from API
	 * @return array [ 'created' => int, 'updated' => int ]
	 */
	public static function import($brandsData) {
		$created = 0;
		$updated = 0;
		if (!is_array($brandsData)) return [ 'created' => 0, 'updated' => 0 ];
		foreach ($brandsData as $brand) {
			if (empty($brand['brandID'])) continue;
			$existing = get_posts([
				'post_type' => 'brand',
				'meta_key' => 'brandId',
				'meta_value' => $brand['brandID'],
				'post_status' => 'any',
				'numberposts' => 1,
				'fields' => 'ids',
			]);
			$postarr = [
				'post_type' => 'brand',
				'post_status' => 'publish',
				'post_title' => $brand['name'] ?? $brand['brand'] ?? 'Brand',
			];
			if ($existing) {
				$postarr['ID'] = $existing[0];
				wp_update_post($postarr);
				$updated++;
				$post_id = $existing[0];
			} else {
				$post_id = wp_insert_post($postarr);
				if ($post_id && !is_wp_error($post_id)) $created++;
			}
			if ($post_id && !is_wp_error($post_id)) {
				update_post_meta($post_id, 'brandId', $brand['brandID']);
				update_post_meta($post_id, 'brand', $brand['brand'] ?? '');
				update_post_meta($post_id, 'name', $brand['name'] ?? '');
				update_post_meta($post_id, 'website', $brand['website'] ?? '');
				update_post_meta($post_id, 'email', $brand['email'] ?? '');
				update_post_meta($post_id, 'telephone', $brand['telephone'] ?? '');
			}
		}
		return [ 'created' => $created, 'updated' => $updated ];
	}
}
