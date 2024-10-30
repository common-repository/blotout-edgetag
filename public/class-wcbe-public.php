<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://blotout.io/
 * @since      1.0.0
 *
 * @package    Bloutout_EdgeTag
 * @subpackage Bloutout_EdgeTag/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bloutout_EdgeTag
 * @subpackage Bloutout_EdgeTag/public
 */
require_once plugin_dir_path(__FILE__) . '../includes/utils.php';

class WCBE_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('wp_head', [$this, 'print_edgetag_script']);
    }

    public function print_edgetag_script()
    {
        $allowed_html = array(
            'script' => array()
        );

        $scipt_from_db = get_option('edgetag_script');

        // for compatibility
        $cleared_script = wp_strip_all_tags($scipt_from_db);

        echo decode_value(wp_kses('<script>' . $cleared_script . '</script>', $allowed_html));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WCBE_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WCBE_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WCBE_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WCBE_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
    }
}
