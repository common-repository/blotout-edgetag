<?php

if (!defined('ABSPATH')) exit;
require_once plugin_dir_path(__FILE__) . 'classes/WCBE_Product.php';
require_once plugin_dir_path(__FILE__) . 'classes/WCBE_Order.php';
require_once plugin_dir_path(__FILE__) . 'utils.php';

class WCBE_Tag
{
    protected $url;

    protected $quantitySelector;

    protected $currency;

    protected $siteData;

    protected $customSubscribeSelectors;

    protected $sendPurchaseSubtotal;

    protected $woocommerceInstalled;

    protected $customNewsletterEventName;

    /*
     *  Enqueue initialization tag
     */
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'initialize'));
    }

    public function initialize()
    {
        $this->url = get_option('edgetag_url');

        $this->customSubscribeSelectors = decode_value(get_option('edgetag_selectors'));

        $this->sendPurchaseSubtotal = get_option('edgetag_purchase_subtotal_only');

        $this->customNewsletterEventName = decode_value(get_option('edgetag_newsletter_event_name'));

        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $this->woocommerceInstalled = true;
        } else {
            $this->woocommerceInstalled = false;
        }

        if (!$this->url) {
            return;
        }

        if (strpos($this->url, 'https://') !== 0 && strpos($this->url, 'http://') !== 0) {
            $this->url = 'https://' . $this->url;
        } elseif (strpos($this->url, 'http://') === 0) {
            $this->url = 'https://' . substr($this->url, strlen('http://'));
        }

        if (!isset($_COOKIE['truid'])) {
            $expire = time() + 60 * 60 * 24 * 365 * 100;

            $parsedUrl = wp_parse_url($this->url);

            $host = $parsedUrl['host'];

            $explodedHost = explode('.', $host);
            array_shift($explodedHost);

            $domain = implode('.', $explodedHost);

            setcookie('truid', $this->guidv4(), $expire, '/', '.' . $domain, false, false);
        }

        if (substr($this->url, -1) === '/') {
            $this->url = substr($this->url, 0, -1);
        }

        $this->quantitySelector = '.qty';

        if ($this->woocommerceInstalled) {
            $this->set_currency();

            $this->siteData = array(
                'url' => $this->url,
                'quantity_selector' => $this->quantitySelector,
                'send_purchase_subtotal' => $this->sendPurchaseSubtotal,
                'currency' => $this->currency(),
                'price_decimal' => get_option('woocommerce_price_num_decimals', 2),
            );
        } else {
            $this->siteData = array(
                'url' => $this->url,
                'send_purchase_subtotal' => $this->sendPurchaseSubtotal,
            );
        }

        $this->filter_tags();

        add_action('wp_enqueue_scripts', array($this, 'insert_tags'));

        $this->action_tags();
    }

    public function set_currency()
    {
        $this->currency = get_woocommerce_currency();
    }

    public function currency()
    {
        return $this->currency;
    }

    public function insert_tags()
    {
        wp_enqueue_script('wcbe-init-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-init-tag.js', array(), null, false);
        wp_localize_script('wcbe-init-script', 'siteData', $this->siteData);

        wp_register_script('wcbe-init-external-script', $this->siteData['url'] . '/load', array(), null, false);
        wp_enqueue_script('wcbe-init-external-script');

        if ($this->woocommerceInstalled) {
            if (is_single() && is_product()) {
                $this->content_view_tag();
            }
            $this->edit_address_tag();
            $this->login_tag();
            $this->site_register_tag();
        }

        $this->subscribe_tag();
    }

    public function action_tags()
    {
        if ($this->woocommerceInstalled) {
            add_action('woocommerce_before_checkout_form', array($this, 'initiate_checkout_tag'), 10, 6);
            // This action is added for Blocks theme, block theme doesn't trigger woocommerce_before_checkout_form anymore
            add_action('woocommerce_blocks_enqueue_checkout_block_scripts_after', array($this, 'initiate_checkout_tag'), 10, 6);
            add_action('woocommerce_thankyou', array($this, 'purchase_tag'), 10, 1);
            add_action('woocommerce_thankyou', array($this, 'checkout_register_tag'), 10, 1);
            add_action('woocommerce_add_to_cart', array($this, 'add_to_cart_tag'), 10, 6);
        }
    }

    public function filter_tags()
    {
        if ($this->woocommerceInstalled) {
            add_filter('woocommerce_login_redirect', array($this, 'login_redirect_filter'), 10, 2);
        }
    }

    public function login_redirect_filter($redirect, $user)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['edgetag_login_flag'] = true;

        return $redirect;
    }

    public function content_view_tag()
    {
        $product = new WCBE_Product(wc_get_product(get_the_ID()));

        $productData = array(
            'id' => $product->id,
            'sku' => $product->sku,
            'item_price' => $product->price,
            'title' => $product->title,
            'category' => $product->category,
            'image' => $product->image,
            'url' => $product->url,
            'siteData' => $this->siteData,
            'variation' => $product->is_variation,
            'variation_prices' => $product->variation_prices,
            'default_variation_id' => $product->default_variation_id,
        );

        wp_enqueue_script('wcbe-content-view-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-content-view-tag.js', array('jquery'), null, true);
        wp_localize_script('wcbe-content-view-script', 'productData', $productData);
    }

    public function add_to_cart_tag($cart_id,  $product_id, $request_quantity, $variation_id, $variation,  $cart_item_data)
    {
        $product = new WCBE_Product(wc_get_product($product_id));
        $addedProduct = array(
            'id' => $product->id,
            'sku' => $product->sku,
            'quantity' => $request_quantity,
            'item_price' => $product->price,
            'title' => $product->title,
            'category' => $product->category,
            'image' => $product->image,
            'url' => $product->url,
            'variation' => $product->is_variation,
            'variantId' => $variation_id,
            'variation_prices' => $product->variation_prices,
            'siteData' => $this->siteData
        );

        wp_enqueue_script('wcbe-add-to-cart-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-add-to-cart-tag.js', array(), null, true);
        wp_localize_script('wcbe-add-to-cart-script', 'addedProduct', $addedProduct);
    }

    public function initiate_checkout_tag()
    {
        if (is_admin()) {
            return false;
        }

        $cart_items = WC()->cart->get_cart();
        $currency = get_woocommerce_currency();
        $value = WC()->cart->subtotal;

        $checkoutProductData = array();

        foreach ($cart_items as $cart_item_key => $cart_item) {
            $product = new WCBE_Product($cart_item['data']);
            $checkoutProductData[] = array(
                'id' => $product->get_parent_id(),
                'variantId' => $product->id,
                'sku' => $product->sku,
                'quantity' => $cart_item['quantity'],
                'price' => $product->price,
                'title' => $product->title,
                'category' => $product->category,
                'image' => $product->image,
                'url' => $product->url,
            );
        }

        if (count($checkoutProductData) == 0) {
          return;
        }

        $checkoutData = array(
            'currency' => $currency,
            'value' => $value,
            'checkoutProductData' => $checkoutProductData,
            'price_decimals' => get_option('woocommerce_price_num_decimals', 2),
            'checkout_url' => wc_get_checkout_url()
        );

        wp_enqueue_script('wcbe-initiate-checkout-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-initiate-checkout-tag.js', array('jquery'), null, true);
        wp_localize_script('wcbe-initiate-checkout-script', 'checkoutData', $checkoutData);
    }

    public function purchase_tag($order_id)
    {
        $order = new WCBE_Order($order_id);

        $purchaseUserData = array(
            'email' => $order->email,
            'phone' => $order->phone,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'city' => $order->city,
            'state' => $order->state,
            'country' => $order->country
        );

        $purchaseProductData = array();
        foreach ($order->items as $item_id => $item) {
            $purchaseProductData[] = $order->get_product($item);
        }

        $purchaseData = array(
            'currency' => $order->currency,
            'value' => $order->total,
            'orderId' => $order_id,
            'userData' => $purchaseUserData,
            'productData' => $purchaseProductData,
            'price_decimals' => get_option('woocommerce_price_num_decimals', 2),
            /** subtotal:  Gets order subtotal. Order subtotal is the price of all items excluding taxes, fees, shipping cost, and coupon discounts.*/
            'subtotal' => $order->subtotal
        );

        wp_enqueue_script('wcbe-purchase-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-purchase-tag.js', array('jquery'), null, true);
        wp_localize_script('wcbe-purchase-script', 'purchaseData', $purchaseData);
    }

    public function login_tag()
    {
        if (!session_id()) {
            session_start();
        }

        if (isset($_SESSION['edgetag_login_flag']) && $_SESSION['edgetag_login_flag'] === true) {
            $current_user = wp_get_current_user();
            $user_email = $current_user->user_email;

            $loginData = array(
                'userEmail' => $user_email
            );

            wp_enqueue_script('wcbe-login-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-login-tag.js', array('jquery'), null, true);
            wp_localize_script('wcbe-login-script', 'loginData', $loginData);

            unset($_SESSION['edgetag_login_flag']);
        }
    }

    public function checkout_register_tag($order_id)
    {
        if (!$order_id) {
            return;
        }

        $order = new WCBE_Order($order_id);

        if (!$order->user_id) {
            return;
        }

        $user_data = get_userdata($order->user_id);
        $order_date = get_the_date('Y-m-d H:i:s', $order_id);
        $user_date = $user_data->user_registered;
        $difference = abs(strtotime($order_date) - strtotime($user_date));
        $threshold = 60;

        if ($difference <= $threshold) {
            $checkoutRegisterData = array(
                "email" => $order->email,
                "firstName" => $order->first_name,
                "lastName" => $order->last_name,
                "phone" => $order->phone
            );

            wp_enqueue_script('wcbe-checkout-register-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-checkout-register-tag.js', array('jquery'), null, true);
            wp_localize_script('wcbe-checkout-register-script', 'checkoutRegisterData', $checkoutRegisterData);
        }
    }

    public function site_register_tag()
    {
        wp_enqueue_script('wcbe-site-register-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-site-register-tag.js', array('jquery'), null, true);
    }

    public function edit_address_tag()
    {
        $currentAddressPage = array(
            'page' => ''
        );

        if (is_wc_endpoint_url('edit-address')) {
            global $wp;
            if (isset($wp->query_vars['edit-address']) && 'billing' === $wp->query_vars['edit-address']) {
                $currentAddressPage['page'] = 'billing';
            } else if (isset($wp->query_vars['edit-address']) && 'shipping' === $wp->query_vars['edit-address']) {
                $currentAddressPage['page'] = 'shipping';
            }
        } else if (is_checkout()) {
            $currentAddressPage['page'] = 'checkout';
        }

        wp_enqueue_script('wcbe-address-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-address-tag.js', array('jquery'), null, true);
        wp_localize_script('wcbe-address-script', 'currentAddressPage', $currentAddressPage);
    }

    public function subscribe_tag()
    {
        $subscribeSelectors = array();
        $customNewsletterEventName = $this->customNewsletterEventName;

        if ($this->customSubscribeSelectors) {
            $customSubscribeSelectorsNoSpaces = str_replace(' ', '', $this->customSubscribeSelectors);
            $customSelectors = explode(',', $customSubscribeSelectorsNoSpaces);
            $subscribeSelectors = array_merge($subscribeSelectors, $customSelectors);
        }

        $subscribeData = array(
            'subscribeSelectors' => $subscribeSelectors,
            'customNewsletterEventName' => $customNewsletterEventName,
        );

        wp_enqueue_script('wcbe-subscribe-script', WCBE_PUBLIC_JS_URL . 'tags/wcbe-subscribe-tag.js', array('jquery'), null, true);
        wp_localize_script('wcbe-subscribe-script', 'subscribeData', $subscribeData);

    }


    public function guidv4($data = null)
    {
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

new WCBE_Tag();
