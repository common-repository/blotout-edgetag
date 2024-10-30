<?php

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'WCBE_Api.php';

class WCBE_Router
{
    protected $secure_routes = [
        ['get_edgetag_options', [WCBE_Api::class, 'get_edgetag_options']],
        ['set_edgetag_options', [WCBE_Api::class, 'set_edgetag_options']],
    ];

    protected $unsecure_routes = [];

    public function register_routes()
    {
        //! SECURE
        foreach ($this->secure_routes as $route_data) {
            list($route, $handler) = $route_data;
            $wrapped_handler = self::wrap_handler_with_nonce_check($handler);
            add_action('wp_ajax_' . $route, $wrapped_handler);
            add_action('wp_ajax_nopriv_' . $route, $wrapped_handler);
        }

        //! UNSECURE
        foreach ($this->unsecure_routes as $route_data) {
            list($route, $handler) = $route_data;
            add_action('wp_ajax_' . $route, $handler);
            add_action('wp_ajax_nopriv_' . $route, $handler);
        }
    }

    private static function wrap_handler_with_nonce_check($handler)
    {
        return function () use ($handler) {
            check_ajax_referer('wcbe_nonce', 'nonce') || wp_send_json_error('Invalid nonce', 403);
            call_user_func($handler);
        };
    }
}
