<?php
// Locations admin subpage partial
$json_file = dirname(__DIR__, 2) . '/admin/locations-data.json';
$locations = [];
if (file_exists($json_file)) {
    $locations = json_decode(file_get_contents($json_file), true);
}
?>
<div class="wrap">
    <h1>Locations <button id="alpnav-get-locations" class="button button-primary" style="margin-left:20px;">Get Locations</button></h1>
    <div id="alpnav-locations-list"></div>
    <p><strong>Total locations:</strong> <?php echo is_array($locations) ? count($locations) : 0; ?></p>
    <table class="widefat fixed" style="margin-top:20px;">
        <thead>
            <tr>
                <th>Location ID</th>
                <th>Location Code</th>
                <th>Location Name</th>
                <th>Area ID</th>
                <th>Area Name</th>
                <th>Country</th>
                <th>Distance</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($locations): foreach ($locations as $location): ?>
                <tr>
                    <td><?php echo esc_html(isset($location['locationID']) ? $location['locationID'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['location']) ? $location['location'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['name']) ? $location['name'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['areaID']) ? $location['areaID'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['areaName']) ? $location['areaName'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['country']) ? $location['country'] : ''); ?></td>
                    <td><?php echo esc_html(isset($location['distance']) ? $location['distance'] : ''); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7">No locations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
