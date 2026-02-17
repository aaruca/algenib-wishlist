<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Bricks Wishlist Counter Element
 */
class Alg_Wishlist_Counter_Element extends \Bricks\Element
{

    public $category = 'woocommerce';
    public $name = 'alg-wishlist-counter';
    public $icon = 'ti-heart';
    public $css_selector = '.alg-wishlist-count';

    public function get_label()
    {
        return esc_html__('Wishlist Counter (Algenib)', 'algenib-wishlist');
    }

    public function set_controls()
    {
        $this->controls['color'] = [
            'tab' => 'style',
            'label' => esc_html__('Background Color', 'algenib-wishlist'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'background-color',
                    'selector' => '.alg-wishlist-count',
                ],
            ],
        ];

        $this->controls['textColor'] = [
            'tab' => 'style',
            'label' => esc_html__('Text Color', 'algenib-wishlist'),
            'type' => 'color',
            'css' => [
                [
                    'property' => 'color',
                    'selector' => '.alg-wishlist-count',
                ],
            ],
        ];
    }

    public function render()
    {
        echo do_shortcode('[alg_wishlist_count]');
    }
}
