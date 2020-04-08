<?php

namespace AurynElements;

use AurynCategories;

/**
 * Plugin Name: Auryn Elements
 * Description: Custom elements for Auryn.
 * Plugin URI: https://www.auryn.com.br
 * Version: 0.0.1
 * Author: Auryn
 * Author URI: https://www.auryn.com.br
 * Text Domain: auryn-elements
 * Domain Path: /languages/
 * License: MIT
 */

if (!defined('ABSPATH')) exit;

class AurynElements {

    private static $instance = null;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function init() {
        add_action('init', array($this, 'loadPluginTranslation'));

        add_action('elementor/elements/categories_registered', array(
            $this, 'AddCustomCategories'));

        add_action('elementor/widgets/widgets_registered', array(
            $this, 'registerAurynWidgets'));
    }

    function loadPluginTranslation() {
        load_plugin_textdomain('auryn-elements', false, 
            basename(dirname(__FILE__)).'/languages');
    }

    function AddCustomCategories($elementsManager) {
        $elementsManager->add_category(
            'auryn-category',
            [
                'title' => __('Auryn Elements', 'auryn-elements'),
                'icon' => 'fa fa-plug'
            ]
        );
    }

    public function registerAurynWidgets() {
        if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')) {
            $templateFile = plugin_dir_path(__FILE__).'widgets/title-widget/auryn-title-widget.php';

            if ($templateFile && is_readable($templateFile)) {
                require_once $templateFile;
            }
        }
    }

}

AurynElements::getInstance()->init();