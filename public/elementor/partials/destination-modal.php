<div id="destinationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" style="cursor:pointer;display:inline-flex;align-items:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M15 19L8 12L15 5" stroke="#B3B3B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <h2>DESTINATION</h2>
        </div>
        <div class="search-input-wrapper" style="">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M15.3341 15.335L19.9991 20.001M17.3341 10.507C17.3341 14.278 14.2771 17.335 10.5061 17.335C6.7351 17.335 3.6781 14.278 3.6781 10.507C3.6781 6.736 6.7351 3.679 10.5061 3.679C14.2771 3.679 17.3341 6.736 17.3341 10.507Z" stroke="#B3B3B3" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <input type="text" placeholder="Enter destination" name="search" />
        </div>
        <?php
        $json_file = dirname(__DIR__, 3) . '/admin/locations-data.json';
        $locations = [];
        if (file_exists($json_file)) {
            $locations = json_decode(file_get_contents($json_file), true);
        }
        $countries = [
            'CH' => '<span class="country-flag">🇨🇭</span> <span>Switzerland</span>',
            'FR' => '<span class="country-flag">🇫🇷</span> <span>France</span>',
            'IT' => '<span class="country-flag">🇮🇹</span> <span>Italy</span>',
            'AT' => '<span class="country-flag">🇦🇹</span> <span>Austria</span>',
        ];
        foreach ($countries as $code => $label) {
            echo '<div class="country">';
            echo '<p class="country-label">' . $label . '</p>';
            echo '<ul class="airport-list">';
            if (!empty($locations)) {
                foreach ($locations as $loc) {
                    if (isset($loc['country']) && $loc['country'] === $code) {
                        $location_id = isset($loc['locationID']) ? $loc['locationID'] : '';
                        $airport_name = isset($loc['name']) ? $loc['name'] : '';
                        echo '<li data-locationid="' . esc_attr($location_id) . '">' . esc_html($airport_name) . '</li>';
                    }
                }
            }
            echo '</ul>';
            echo '</div>';
            echo '<div class="line"></div>';
        }
        ?>
        <div id="destination-form">
            <h4>Specify destination address</h4>
            <form>
                <input type="text" placeholder="Hotel or Chalet Name" />
                <input type="text" placeholder="Street Address" />
                <input type="text" placeholder="City or Town" />
                <input type="text" placeholder="Postal Code" />

                <label class="checkbox-container">
                    <input type="checkbox" />
                    <span>Use the same address for the return journey</span>   
                </label>

                <button type="submit" class="confirm-btn">Confirm Destination</button>
            </form>
        </div>
    </div>
</div>