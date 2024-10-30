<?php
require_once plugin_dir_path(__FILE__) . '../utils.php';

class WCBE_Api
{
    public static function get_edgetag_options()
    {
        $data = [
            ['edgetag_url' => esc_url(get_option('edgetag_url'))],
            ['edgetag_selectors' => decode_value(get_option('edgetag_selectors'))],
            ['edgetag_script' => decode_value(get_option('edgetag_script'))],
            ['edgetag_purchase_subtotal_only' => get_option('edgetag_purchase_subtotal_only')],
            ['edgetag_newsletter_event_name' => decode_value(get_option('edgetag_newsletter_event_name'))]
        ];
        return wp_send_json($data);
    }

    public static function set_edgetag_options()
    {
        if (!isset($_POST['data'])) {
            return wp_send_json_error('Please check values', 400);
        }

        $wcbe_url = esc_url_raw($_POST['data']['edgetag_url']);

        $wcbe_selectors = self::kses_unslash($_POST['data']['edgetag_selectors']);
        $wcbe_newsletter_event_name = self::kses_unslash($_POST['data']['edgetag_newsletter_event_name']);
        $wcbe_script = self::kses_unslash($_POST['data']['edgetag_script']);

        $wcbe_purchase_subtotal_only = sanitize_text_field($_POST['data']['edgetag_purchase_subtotal_only']);

        if (!self::validate_form($wcbe_selectors, $wcbe_url)) {
            update_option('edgetag_selectors', $wcbe_selectors);
            update_option('edgetag_url', $wcbe_url);
            update_option('edgetag_script', $wcbe_script);
            update_option('edgetag_purchase_subtotal_only', $wcbe_purchase_subtotal_only);
            update_option('edgetag_newsletter_event_name', $wcbe_newsletter_event_name);

            return wp_send_json_success('Options updated');
        } else {
            return wp_send_json_error('Please check values', 400);
        }
    }

    private static function validate_form($url, $selectors)
    {
        $is_selectors_valid = is_string($selectors) && !empty(trim($selectors));
        $is_url_valid = filter_var($url, FILTER_VALIDATE_URL);

        return $is_selectors_valid && $is_url_valid;
    }

    private static function kses_unslash($value) {
      return wp_kses(wp_unslash($value), []);
    }
}
