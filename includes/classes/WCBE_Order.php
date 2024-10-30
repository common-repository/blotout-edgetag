<?php
require_once plugin_dir_path(__FILE__) . 'WCBE_Product.php';

class WCBE_Order
{
    private $order;

    public $user_id;
    public $email;
    public $phone;
    public $first_name;
    public $last_name;
    public $city;
    public $state;
    public $country;

    public $total;
    public $subtotal;
    public $currency;
    public $items;

    public function __construct($order_id)
    {
        if ($this->is_new_version()) {
            $this->order = wc_get_order($order_id);
            $this->get_new_order();
        } else {
            $this->order = new WC_Order($order_id);
            $this->get_old_order();
        }
    }

    public function get_product($item)
    {
        if ($this->is_new_version()) {
            $product = new WCBE_Product($item->get_product());

            return array(
                'id' => $product->get_parent_id(),
                'sku' => $product->sku,
                'quantity' => $item->get_quantity(),
                'price' => $product->price,
                'title' => $product->title,
                'categories' => $product->category,
                'featured_image_url' => $product->image,
                'product_url' => $product->url,
                'variation_id' => $product->id
            );
        } else {
            $product =  $this->order->get_product_from_item($item);

            return array(
                'id' => $item['product_id'],
                'sku' => $product->get_sku(),
                'quantity' => $item['qty'],
                'price' => wc_format_decimal($this->order->get_item_total($item), 2),
                'title' => $item['name'],
                'categories' => $product->get_categories(),
                'featured_image_url' => wp_get_attachment_url($product->get_image_id()),
                'product_url' => get_permalink($item['product_id']),
                'variation_id' => $item['variation_id']
            );
        }
    }

    private function is_new_version()
    {
        return version_compare(WC()->version, '3.0', '>');
    }

    private function get_new_order()
    {
        $this->user_id = $this->order->get_user_id();
        $this->email = $this->order->get_billing_email();
        $this->phone = $this->order->get_billing_phone();
        $this->first_name = $this->order->get_billing_first_name();
        $this->last_name = $this->order->get_billing_last_name();
        $this->city = $this->order->get_billing_city();
        $this->state = $this->order->get_billing_state();
        $this->country = $this->order->get_billing_country();
        $this->total = $this->order->get_total();
        $this->currency = $this->order->get_currency();
        $this->items = $this->order->get_items();
        $this->subtotal = $this->order->get_subtotal();
    }

    private function get_old_order()
    {
        $this->user_id = $this->order->customer_user;
        $this->email = $this->order->billing_email;
        $this->phone = $this->order->billing_phone;
        $this->first_name = $this->order->billing_first_name;
        $this->last_name = $this->order->billing_last_name;
        $this->city = $this->order->billing_city;
        $this->state = $this->order->billing_state;
        $this->country = $this->order->billing_country;
        $this->total = $this->order->order_total;
        $this->currency = $this->order->get_order_currency();
        $this->items = $this->order->get_items();
        $this->subtotal = $this->order->order_subtotal;
    }
}
