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
    }
}
