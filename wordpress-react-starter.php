<?php
/*
 * Plugin Name: WordPress React Starter
 * Plugin URI:
 * Description:
 * Version:     0.1
 * Author:      Reynold Ren
 * Author URI:  https://reynold-ren.dev
 *
 * Text Domain: wordpress-react-starter
 * Domain Path: /languages
 *
 * License:     GPL
 * ==============================================================================
 * Copyright 2021 Reynold  (Email : chasel1020@gmail.com)
 *
 * Requirements:
 * ==============================================================================
 * This plugin requires WordPress >= 5.0 and tested with PHP Interpreter >= 7.2
 */

if (!defined('ABSPATH')): exit();endif;

/**
 * 定義外掛相關設定
 */

define('WRS_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('WRS_URL', trailingslashit(plugins_url('/', __FILE__)));

/**
 * 載入必要 Scripts
 */

add_action('admin_enqueue_scripts', 'load_scripts');
function load_scripts()
{

    $plugin_app_dir_url = plugin_dir_url(__FILE__) . 'react/';
    $react_app_build = $plugin_app_dir_url . 'build/';
    $manifest_url = $react_app_build . 'asset-manifest.json';

    $request = file_get_contents($manifest_url);

    if (!$request) {
        return false;
    }

    $files_data = json_decode($request);
    if ($files_data === null) {
        return;
    }

    if (!property_exists($files_data, 'entrypoints')) {
        return false;
    }

    $assets_files = $files_data->entrypoints;

    $js_files = array_filter($assets_files, 'js');
    $css_files = array_filter($assets_files, 'css');

    foreach ($css_files as $index => $css_file) {
        wp_enqueue_style('react-plugin-' . $index, $react_app_build . $css_file);
    }

    foreach ($js_files as $index => $js_file) {
        wp_enqueue_script('react-plugin-' . $index, $react_app_build . $js_file, array(), 1, true);
    }

    wp_localize_script('react-plugin-0', 'appLocalizer', [
        'appSelector' => 'wrs-admin-app',
        'apiUrl' => home_url('/wp-json'),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);
}

function js($file_string)
{
    return pathinfo($file_string, PATHINFO_EXTENSION) === 'js';
}

function css($file_string)
{
    return pathinfo($file_string, PATHINFO_EXTENSION) === 'css';
}

require_once WRS_PATH . 'classes/class-create-admin-menu.php';
