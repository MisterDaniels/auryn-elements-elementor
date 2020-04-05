<?php

namespace AurynElements\Widgets;

use \Elementor as Elementor;

class AurynTitleWidget extends Elementor\Widget_Base {

    public function get_name() {
        return 'auryn-title-widget';
    }

    public function get_title() {
        return __('Auryn Title', 'auryn-elements');
    }

    public function get_icon() {
        return 'fa fa-code';
    }

    public function get_categories() {
        return ['auryn-category'];
    }

    public function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'auryn-elements'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'url',
            [
                'label'       => __('URL to embed', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'input_type'  => 'url',
                'placeholder' => __('https://your-link.com', 'auryn-elements')
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

		$html = wp_oembed_get( $settings['url'] );

		echo '<div class="oembed-elementor-widget">';

		echo ( $html ) ? $html : $settings['url'];

        echo '</div>';
        
    }

    protected function _content_template() {

    }

}

Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \AurynElements\Widgets\AurynTitleWidget());