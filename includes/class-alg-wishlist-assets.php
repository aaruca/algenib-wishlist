<?php

/**
 * Assets Handler
 * 
 * Enqueues Frontend JS and CSS.
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes
 */

class Alg_Wishlist_Assets
{

    public function enqueue_scripts()
    {
        // CSS
        wp_enqueue_style('alg-wishlist-css', ALG_WISHLIST_URL . 'assets/css/algenib-wishlist.css', array(), ALG_WISHLIST_VERSION);

        // JS
        wp_enqueue_script('alg-wishlist-js', ALG_WISHLIST_URL . 'assets/js/algenib-wishlist.js', array(), ALG_WISHLIST_VERSION, true);

        // Localize
        $items = Alg_Wishlist_Core::get_wishlist_items();

        wp_localize_script('alg-wishlist-js', 'AlgWishlistSettings', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alg_wishlist_nonce'),
            'initial_items' => $items, // Pass PHP state to JS on load
            'i18n' => array(
                'added' => __('Added to Wishlist', 'algenib-wishlist'),
                'removed' => __('Removed from Wishlist', 'algenib-wishlist')
            )
        ));

        // Inject Custom Styles
        $options = get_option('alg_wishlist_settings');
        $primary = isset($options['alg_wishlist_color_primary']) ? $options['alg_wishlist_color_primary'] : '#ff4b4b';
        $active = isset($options['alg_wishlist_color_active']) ? $options['alg_wishlist_color_active'] : '#cc0000';
        $custom = isset($options['alg_wishlist_custom_css']) ? $options['alg_wishlist_custom_css'] : '';

        $custom_css = "
            :root {
                --alg-wishlist-primary: {$primary};
                --alg-wishlist-active: {$active};
            }
            .alg-wishlist-btn svg { stroke: var(--alg-wishlist-primary); }
            .alg-wishlist-btn.active svg { fill: var(--alg-wishlist-active); stroke: var(--alg-wishlist-active); }
            {$custom}
            
            /* Toast Notification */
            .alg-wishlist-toast {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: #333;
                color: #fff;
                padding: 12px 24px;
                border-radius: 4px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 9999;
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.3s, transform 0.3s;
                font-size: 14px;
            }
            .alg-wishlist-toast.show {
                opacity: 1;
                transform: translateY(0);
            }
        ";

        wp_add_inline_style('alg-wishlist-css', $custom_css);
    }
}
