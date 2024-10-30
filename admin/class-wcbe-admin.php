<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blotout.io/
 * @since      1.0.0
 *
 * @package    Bloutout_EdgeTag
 * @subpackage Bloutout_EdgeTag/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bloutout_EdgeTag
 * @subpackage Bloutout_EdgeTag/admin
 */
class WCBE_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->set_settings_defaults();

        add_action('admin_menu', [$this, 'register_admin_menu']);
    }


    public function admin_page_contents()
    {
        include 'partials/wcbe-admin-display.php';
    }


    public function register_admin_menu()
    {
        $custom_svg_icon = '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 34">
            <style>
              .cls-1 {
                fill: #fff;
                fill-rule: evenodd;
              }
            </style>
          <path class="cls-1" d="m2.35,5.88c-.05.09-.05.21-.05.44v21.09c0,.23,0,.35.05.44.04.08.1.14.18.18.09.05.21.05.44.05h1.55c.23,0,.35,0,.44-.05.08-.04.14-.1.18-.18.05-.09.05-.21.05-.44v-.74c1.32,1.05,2.99,1.69,4.81,1.69,4.25,0,7.69-3.44,7.69-7.69s-3.44-7.69-7.69-7.69c-1.82,0-3.49.63-4.81,1.69V6.32c0-.23,0-.35-.05-.44-.04-.08-.1-.14-.18-.18-.09-.05-.21-.05-.44-.05h-1.55c-.23,0-.35,0-.44.05-.08.04-.14.1-.18.18Zm2.84,14.78c0,2.65,2.15,4.8,4.81,4.8s4.81-2.15,4.81-4.8-2.15-4.8-4.81-4.8-4.81,2.15-4.81,4.8Z"/>
        </svg>';

        $page_hook = add_menu_page(
            __('EdgeTag', 'blotout-edgetag'),
            __('EdgeTag', 'blotout-edgetag'),
            'manage_options',
            'blotout-edgetag',
            [$this, 'admin_page_contents'],
            'data:image/svg+xml;base64,' . base64_encode($custom_svg_icon),
            150
        );
    }

    /**
     * Register the stylesheets for the admin area.
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
     * Register the JavaScript for the admin area.
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

        //* DEV
        // wp_enqueue_script($this->plugin_name . '-react', 'http://localhost:3000/' . 'static/js/bundle.js', array(), false, true);

        //* PRODUCTION
        wp_enqueue_script($this->plugin_name . '-react', plugin_dir_url(__FILE__) . '/frontend/build/static/js/main.js', array(), false, true);
        wp_enqueue_style($this->plugin_name . '-react', plugin_dir_url(__FILE__) . '/frontend/build/static/css/main.css');
    }


    public function set_settings_defaults()
    {
        if (!get_option('edgetag_init')) {
            add_option('edgetag_init', 1);

            if (!get_option('edgetag_url'))
                add_option('edgetag_url', null);

            if (!get_option('edgetag_selectors'))
                add_option('edgetag_selectors', null);

            if (!get_option('edgetag_script'))
                add_option('edgetag_script', null);

            if (!get_option('edgetag_newsletter_event_name'))
                add_option('edgetag_newsletter_event_name', 'Lead');
        }
    }
}
