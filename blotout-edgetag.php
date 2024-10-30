<?php

/**
 * @link              https://blotout.io/
 * @since             1.0.0
 * @package           Bloutout_EdgeTag
 *
 * @wordpress-plugin
 * Plugin Name:       Blotout EdgeTag
 * Plugin URI:        https://app.edgetag.io
 * Description:       EdgeTag integration plugin for WordPress
 * Version:           1.2.0
 * Author:            Blotout
 * Author URI:        https://blotout.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blotout-edgetag
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) exit;

define('WCBE_VERSION', '1.2.0');

if (!defined('WCBE_PATH')) {
    define('WCBE_PATH', plugins_url('', __FILE__));
}

if (!defined('WCBE_CLASS_PATH')) {
    define('WCBE_CLASS_PATH', plugin_dir_path(__FILE__) . 'includes/');
}

if (!defined('WCBE_PUBLIC_JS_URL')) {
    define('WCBE_PUBLIC_JS_URL', plugins_url('public/js/', __FILE__));
}

function wcbe_activate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wcbe-activator.php';
    WCBE_Activator::activate();
}

function wcbe_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wcbe-deactivator.php';
    WCBE_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'wcbe_activate');
register_deactivation_hook(__FILE__, 'wcbe_deactivate');

require plugin_dir_path(__FILE__) . 'includes/class-wcbe-main.php';

require WCBE_CLASS_PATH . 'class-wcbe-tag.php';

function wcbe_run_plugin()
{
    $plugin = new WCBE();
    $plugin->run();
}
wcbe_run_plugin();
