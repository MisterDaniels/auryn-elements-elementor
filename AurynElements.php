<?php

namespace AurynElements;

use AurynCategories;

/**
 * Plugin Name: Auryn Elements
 * Description: Custom elements for Auryn.
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
        define('PLUGIN_PATH', plugins_url('/assets/', __FILE__ ));

        add_action('init', array($this, 'loadPluginTranslation'));

        $this->loadElementor();
        $this->loadPlugin();
    }

    function loadPluginTranslation() {
        load_plugin_textdomain('auryn-elements', false, 
            basename(dirname(__FILE__)).'/languages');
    }

    function loadElementor() {
        add_action('elementor/elements/categories_registered', array(
            $this, 'AddCustomCategories'));

        add_action('elementor/widgets/widgets_registered', array(
            $this, 'registerAurynWidgets'));

        add_action( 'wp_enqueue_scripts', array($this, 'enqueueAssets') );
    }

    function loadPlugin() {
        add_action('admin_menu', array($this, 'addPluginAdminMenu'));

        add_action('admin_init', array( $this, 'registerAndBuildFields' ));
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

    function registerAurynWidgets() {
        if (defined('ELEMENTOR_PATH') && class_exists('\Elementor\Widget_Base')) {
            $templateFile = plugin_dir_path(__FILE__).'widgets/simplified-project-creation/simplified-project-creation.php';

            if ($templateFile && is_readable($templateFile)) {
                require_once $templateFile;
            }
        }
    }

    function addPluginAdminMenu() {
        $pluginTitle = __('Auryn Elements', 'auryn-elements');
        $capability = 'administrator';
        $pluginSlug = 'auryn-elements';

        add_menu_page( $pluginTitle, $pluginTitle, $capability, $pluginSlug, 
            array( $this, 'displayPluginAdminDashboard' ), 'dashicons-cover-image', 26 );
        
        add_submenu_page( $pluginSlug, 
            __('Auryn Elements Settings', 'auryn-elements'),
            __('Settings', 'auryn-elements'), $capability, 'auryn-elements-settings', 
            array( $this, 'displayPluginAdminSettings' ));

        remove_submenu_page( $pluginSlug, $pluginSlug );
    }

    function displayPluginAdminDashboard() {
		require_once 'partials/auryn-elements-settings.php';
    }

    function displayPluginAdminSettings() {
        if(isset($_GET['error_message'])){
            add_action('admin_notices', array($this,'pluginNameSettingsMessages'));
            do_action('admin_notices', $_GET['error_message']);
        }

        require_once 'partials/auryn-elements-settings.php';
    }

    function pluginNameSettingsMessages($error_message){
        switch ($error_message) {
            case '1':
                $message = __( 'Ops, a wild error appears', 'auryn-elements' );                 
                $errCode = esc_attr( 'auryn_elements_setting' );                 
                $settingField = 'auryn_elements_setting';                 
                break;
        }

        $type = 'error';
        add_settings_error(
               $settingField,
               $errCode,
               $message,
               $type
           );
    }

    function registerAndBuildFields() {
        register_setting(
            'general_settings',
            'company_domain'
        );
        register_setting(
            'general_settings',
            'is_principal_wordpress'
        );
    }

    function enqueueAssets() {
        wp_enqueue_style('simplified-project-creation', 
            PLUGIN_PATH . 'css/simplified-project-creation/frontend/main.css');
    }

}

AurynElements::getInstance()->init();