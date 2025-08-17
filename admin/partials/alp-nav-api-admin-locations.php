<?php
// Locations admin subpage partial
$locations = get_posts([
    'post_type' => 'location',
    'numberposts' => -1,
    'post_status' => 'publish',
]);
?>
<div class="wrap">
    <h1>Locations <button id="alpnav-get-locations" class="button button-primary" style="margin-left:20px;">Get Locations</button></h1>
    <div id="alpnav-locations-list"></div>
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
                    <td><?php echo esc_html(get_post_meta($location->ID, 'locationID', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'location', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'name', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'areaID', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'areaName', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'country', true)); ?></td>
                    <td><?php echo esc_html(get_post_meta($location->ID, 'distance', true)); ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="7">No locations found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
