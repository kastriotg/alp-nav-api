<?php
namespace AlpNavApi\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Banner_Widget extends Widget_Base {
    public function get_name() {
        return 'alpnav_banner';
    }
    public function get_title() {
        return __( 'AlpNav Banner', 'alp-nav-api' );
    }
    public function get_icon() {
        return 'eicon-banner';
    }
    public function get_categories() {
        return [ 'anchorzup' ];
    }
    protected function _register_controls() {
        $this->start_controls_section(
            'section_banner',
            [
                'label' => __( 'Banner', 'alp-nav-api' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'banner_title',
            [
                'label' => __( 'Title', 'alp-nav-api' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Welcome to AlpNav!', 'alp-nav-api' ),
            ]
        );
        $this->add_control(
            'banner_subtitle',
            [
                'label' => __( 'Subtitle', 'alp-nav-api' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Welcome to AlpNav!', 'alp-nav-api' ),
            ]
        );
        $this->add_control(
            'banner_image_mobile',
            [
                'label' => __( 'Image Mobile', 'alp-nav-api' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'banner_image_wide',
            [
                'label' => __( 'Image Wide', 'alp-nav-api' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'banner_description',
            [
                'label' => __( 'Description', 'alp-nav-api' ),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        include plugin_dir_path(__FILE__) . 'banner-widget-view.php';
    }
}
