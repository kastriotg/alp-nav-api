<?php
// Brands admin subpage partial
$brands = get_posts([
    'post_type' => 'brand',
    'post_status' => 'publish',
    'numberposts' => -1
]);
?>
<div class="wrap">
    <h1>Brands <button id="alpnav-get-brands" class="button button-primary" style="margin-left:20px;">Get Brands</button></h1>
    <div id="alpnav-brands-list"></div>

    <h2>Select Brand which will be used on Api calls for Locations</h2>
    <div style="display:flex;align-items:center;gap:10px;">
        <?php $selected_brand_id = get_option('alpnav_selected_brand_id'); ?>
        <select id="alpnav-brandid-selector" style="min-width:250px;">
            <option value="">Select a brand...</option>
            <?php if ($brands): foreach ($brands as $brand): ?>
                <?php $brand_id = get_post_meta($brand->ID, 'brandId', true); ?>
                <option value="<?php echo esc_attr($brand_id); ?>"<?php selected($selected_brand_id, $brand_id); ?>><?php echo esc_html($brand->post_title); ?></option>
            <?php endforeach; endif; ?>
        </select>
        <button id="alpnav-save-brandid" class="button button-secondary">Save</button>
        <span id="alpnav-save-brandid-result" style="margin-left:10px;"></span>
    </div>

    <h2>Saved Brands</h2>
    <table class="widefat fixed" style="max-width:900px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Brand</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($brands): foreach ($brands as $brand): ?>
            <tr>
                <td><?php echo esc_html(get_post_meta($brand->ID, 'brandId', true)); ?></td>
                <td><?php echo esc_html($brand->post_title); ?></td>
                <td><?php echo esc_html(get_post_meta($brand->ID, 'brand', true)); ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="6">No brands found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
