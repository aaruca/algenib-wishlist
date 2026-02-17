<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Bricks Wishlist Button Element
 */
class Alg_Wishlist_Button_Element extends \Bricks\Element
{

    public $category = 'woocommerce';
    public $name = 'alg-wishlist-button';
    public $icon = 'ti-heart'; // Themify Icon
    public $css_selector = '.alg-add-to-wishlist';

    public function get_label()
    {
        return esc_html__('Wishlist Button (Algenib)', 'algenib-wishlist');
    }

    public function set_controls()
    {
        $this->controls['text'] = [
            'tab' => 'content',
            'label' => esc_html__('Button Text', 'algenib-wishlist'),
            'type' => 'text',
            'default' => '',
        ];

        $this->controls['color'] = [
            'tab' => 'style',
            'label' => esc_html__('Color', 'algenib-wishlist'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'color',
                    'selector' => '.alg-add-to-wishlist',
                ],
            ],
        ];

        $this->controls['activeColor'] = [
            'tab' => 'style',
            'label' => esc_html__('Active Color', 'algenib-wishlist'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'color', // or fill/stroke
                    'selector' => '.alg-add-to-wishlist.active',
                ],
                [
                    'property' => 'fill',
                    'selector' => '.alg-add-to-wishlist.active svg',
                ],
                [
                    'property' => 'stroke',
                    'selector' => '.alg-add-to-wishlist.active svg',
                ],
            ],
        ];
    }

    public function render()
    {
        // Use the Shortcode logic to render, ensuring DRY
        // We can call the shortcode handler or replicate simple logic here.
        // Since we need context (Loop ID), Bricks usually handles loop context inside render().

        $product_id = \Bricks\Query::get_loop_object_id();
        if (!$product_id) {
            $product_id = get_the_ID();
        }

        // Check if we have attributes
        $settings = $this->settings;
        $text = !empty($settings['text']) ? $settings['text'] : '';

        echo do_shortcode('[alg_wishlist_button product_id="' . $product_id . '" text="' . $text . '"]');
    }
}
