<?php

if (!defined('ABSPATH')) exit;
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://blotout.io/
 * @since      1.0.0
 *
 * @package    Bloutout_EdgeTag
 * @subpackage Bloutout_EdgeTag/admin/partials
 */
?>

<script>
    var wcbe_base_url = '<?php echo esc_url(home_url()) ?>'
    var wcbe_nonce = '<?php echo esc_html(wp_create_nonce('wcbe_nonce')) ?>'
    var wcbe_assets_url = '<?php echo esc_url(plugin_dir_url(__DIR__) . 'frontend/src/assets/') ?>'
</script>
<div id="wcbe-app"></div>