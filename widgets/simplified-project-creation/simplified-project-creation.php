<?php

namespace AurynElements\Widgets;

use \Elementor as Elementor;

class SimplifiedProjectCreation extends Elementor\Widget_Base {

    public function get_name() {
        return 'simplified-project-creation';
    }

    public function get_title() {
        return __('Simplified Project Creation', 'auryn-elements');
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
                'label' => $this->get_title(),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            ]
        );

        $isPrincipalWordpress = get_option( 'is_principal_wordpress' );

        $bookSizes = [];
        if ($isPrincipalWordpress != '1') {
            $bookSizes = $this->getBookSizesForCompany(get_option( 'company_domain' ), true);
        }

        $this->add_control(
            'show_book_sizes',
            [
                'label'       => __('Show book sizes', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $bookSizes,
                'default'     => []
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'       => __('Button text', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Diagram', 'auryn-elements' ),
                'placeholder' => __( 'Type button text', 'auryn-elements' ),
            ]
        );

        $this->end_controls_section();
        
        $this->registerStyleTab();

    }

    public function getBookSizesForCompany($companyDomain, $isManager = false) {
        $response = wp_remote_get("https://{$companyDomain}/publicData/getActiveCompaniesDomain", 
            array(
                'methods'  => 'GET'
            )
        );

        if (is_wp_error($response)) {
            throw new \Exception('Request failed. ' . $response->get_error_messages());
        }

        $jsonResponse = json_decode($response['body'], true);

        $bookSizes = [];

        if ($isManager) {
            foreach($jsonResponse as $json) {
                array_push($bookSizes, [[$json['id']] = $json['subdomain']]);
            }
        } else {
            foreach($jsonResponse as $json) {
                array_push($bookSizes, [ 
                    'id' => $json['id'],
                    'name' => $json['subdomain'] 
                ]);
            }
        }

        return $bookSizes;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $isPrincipalWordpress = get_option( 'is_principal_wordpress' );
        $bookSizes = [];
        $activatedBookSizes = [];

        if (array_key_exists('companyDomain', $_GET)) {
            $companyDomain = $_GET['companyDomain'];
            $bookSizes = $this->getBookSizesForCompany($_GET['companyDomain']);
        }

        if ($isPrincipalWordpress != '1') {
            $bookSizes = $this->getBookSizesForCompany(get_option( 'company_domain' ));
            $activatedBookSizes = $settings['show_book_sizes'];
        }
        
        $data = json_encode([
            'bookSizes' => $bookSizes,
            'activatedBookSizes' => $activatedBookSizes
        ]);

        add_action( 'elementor/frontend/before_enqueue_scripts', function() use ( $data ) {
            $this->loadScripts($data);
        });

        add_action( 'elementor/frontend/before_enqueue_styles', function() {
            $this->loadStyles();
        });

		echo "<div class='simplified-project-creation-elementor-widget'>";

        echo "<div class='book-size'>";
            echo "<select name='simplified-project-creation' class='dropdown'>";
                echo "<option value='' selected=''>" . __('Select the size','auryn-elements') . "</option>";
            echo "</select>";
        echo "</div>";

        echo "<div class='action'>";
            echo "<a>{$settings['button_text']}</a>";
        echo "</div>";

        echo '</div>';
        
    }

    function loadScripts($data) {
        wp_enqueue_script('simplified-project-creation-frontend', 
            PLUGIN_PATH . 'js/simplified-project-creation/frontend/main.js', [
                'elementor-frontend'
            ]);

        wp_add_inline_script( 'simplified-project-creation-frontend', 
            "var bookSizesData = {$data};", 'before' );
    }
    
    function loadStyles() {
        wp_enqueue_style('simplified-project-creation', 
            PLUGIN_PATH . 'css/simplified-project-creation/frontend/main.css');
    }

    function registerStyleTab() {
        $this->start_controls_section(
			'style_section',
			[
				'label' => $this->get_title(),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
            'button_color',
            [
                'label'       => __('Button color', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::COLOR,
                'multiple'    => true,
                'scheme'      => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_1
                ],
                'selectors' => [
					'{{WRAPPER}} .action a' => 'background-color: {{VALUE}}',
				]
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label'       => __('Button color on hover', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::COLOR,
                'multiple'    => true,
                'scheme'      => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_2
                ],
                'selectors' => [
					'{{WRAPPER}} .action a:hover' => 'background-color: {{VALUE}}',
				]
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'       => __('Button text color', 'auryn-elements'),
                'type'        => \Elementor\Controls_Manager::COLOR,
                'multiple'    => true,
                'scheme'      => [
                    'type'  => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_3
                ],
                'selectors' => [
					'{{WRAPPER}} .action a' => 'color: {{VALUE}}',
				]
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Button typography', 'auryn-elements' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .action a',
			]
        );
        
        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_shadow',
				'label' => __( 'Button shadow', 'auryn-elements' ),
				'selector' => '{{WRAPPER}} .action a',
			]
		);

		$this->end_controls_section();
    }

}

Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \AurynElements\Widgets\SimplifiedProjectCreation());