<?php

class WCBE_Product
{
    private $product;
    public $id;
    public $sku;
    public $title;
    public $price;
    public $image;
    public $url;
    public $category = '';
    public $is_variation = false;
    public $variation_prices = array();
    public $default_variation_id = '';

    public function __construct(WC_Product $product)
    {
        $this->product = $product;

        if (version_compare(WC()->version, '3.0', '>=')) {
            $this->get_new_product();
        } else {
            $this->get_old_product();
        }

        $this->title = esc_js($this->title);
        $this->category = esc_js($this->category);
        $this->image = esc_url($this->image);
        $this->url = esc_url($this->url);
    }

    private function get_old_product()
    {
        $this->id = $this->product->id;
        $this->sku = $this->product->get_sku();
        $this->title = $this->product->get_title();
        $this->price = $this->product->get_price();
        $this->image = wp_get_attachment_url($this->product->get_image_id());
        $this->url = get_permalink($this->id);
        $this->category = $this->product->get_categories();

        $this->is_variation = $this->product->is_type('variable');
        if ($this->is_variation) {
            $variations = $this->product->get_children();
            // Get default variation ID from product attributes
            $default_attributes = $this->remove_attribute_prefixes($this->product->get_variation_default_attributes());


            $this->default_variation_id = 0;
            foreach ($variations as $variation_id) {
                $variation_product = wc_get_product($variation_id);
                $variation_attributes = $this->remove_attribute_prefixes($variation_product->get_variation_attributes());

                if ($variation_attributes == $default_attributes) {
                    $this->default_variation_id = $variation_id;
                }

                $this->variation_prices[$variation_id] = $variation_product->get_price();
            }
        }
    }

    private function get_new_product()
    {
        $this->id = $this->product->get_id();
        $this->sku = $this->product->get_sku();
        $this->title = $this->product->get_title();
        $this->price = $this->product->get_price();
        $this->image = wp_get_attachment_url($this->product->get_image_id());
        $this->url = get_permalink($this->id);

        $categories = get_the_terms($this->id, 'product_cat');
        if (empty($categories)) {
            $categories = wp_get_post_terms($this->id, 'product_cat');
        }

        if (!empty($categories)) {
            $category_names = array();
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }
            $this->category = implode(', ', $category_names);
        }

        $this->is_variation = $this->product->is_type('variable');
        if ($this->is_variation) {
            $available_variations = $this->product->get_available_variations();
            $default_attributes = $this->remove_attribute_prefixes($this->product->get_default_attributes());

            foreach ($available_variations as $variation) {
                $variation_id = $variation['variation_id'];
                $variation_product = wc_get_product($variation_id);
                $this->variation_prices[$variation_id] = $variation_product->get_price();

                $variation_attributes = $this->remove_attribute_prefixes($variation['attributes']);
                if ($default_attributes == $variation_attributes) {
                    $this->default_variation_id = $variation_id;
                }
            }
        }
    }

    private function remove_attribute_prefixes($attributes, $prefixes = ['pa_', 'attribute_'])
    {
        $normalized_attributes = [];
        // escapes special chars for each prefix
        $escapedPrefixes = array_map(fn ($prefix) => preg_quote($prefix, '/'), $prefixes);
        // creates a PCRE to detect all prefixes listed
        $pattern = '/^(' . implode('|', $escapedPrefixes) . ')/';
        foreach ($attributes as $key => $value) {
            // replaces any prefix with an empty string
            $normalizedKey = preg_replace($pattern, '', $key);
            $normalized_attributes[$normalizedKey] = $value;
        }

        return $normalized_attributes;
    }

    public function get_parent_id()
    {
        if (version_compare(WC()->version, '3.0', '>=')) {
            $is_variation = $this->product->is_type('variation');
            $parent_id = $is_variation ? $this->product->get_parent_id() : $this->id;
        } else {
            $parent_id = $this->product->parent ? $this->product->parent->id : $this->id;
        }

        return $parent_id;
    }
}
