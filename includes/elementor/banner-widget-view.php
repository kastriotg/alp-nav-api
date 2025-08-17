<?php
/**
 * View for Banner_Widget Elementor widget
 */
if (!isset($settings)) return;
?>
<section class="alpnav-banner-widget" style="padding:0;">
    <?php
    $mobile_url = !empty($settings['banner_image_mobile']['url']) ? esc_url($settings['banner_image_mobile']['url']) : '';
    $wide_url = !empty($settings['banner_image_wide']['url']) ? esc_url($settings['banner_image_wide']['url']) : '';
    ?>
    <?php if (!empty($settings['banner_title'])): ?>
        <h2><?php echo esc_html($settings['banner_title']); ?></h2>
    <?php endif; 
    if ($mobile_url || $wide_url): ?>
        <picture>
            <div></div>
            <?php if ($mobile_url): ?>
                <source srcset="<?php echo $mobile_url; ?>" media="(max-width: 768px)">
            <?php endif; ?>
            <?php if ($wide_url): ?>
                <source srcset="<?php echo $wide_url; ?>" media="(min-width: 769px)">
            <?php endif; ?>
            <img src="<?php echo $wide_url ? $wide_url : $mobile_url; ?>" alt="Banner" />
        </picture>
    <?php endif; ?>
    <?php if (!empty($settings['banner_title'])): ?>
        <h2><?php echo esc_html($settings['banner_title']); ?></h2>
    <?php endif; ?>
    <?php if (!empty($settings['banner_subtitle'])): ?>
        <h2><?php echo esc_html($settings['banner_subtitle']); ?></h2>
    <?php endif; ?>
    <?php if (!empty($settings['banner_description'])): ?>
        <p><?php echo esc_html($settings['banner_description']); ?></p>
    <?php endif; ?>
</section>