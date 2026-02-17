<?php

/**
 * The Admin Dashboard functionality
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes
 */

class Alg_Wishlist_Admin
{

    /**
     * Register the menu page.
     */
    public function add_plugin_admin_menu()
    {
        add_menu_page(
            'Algenib Wishlist',
            'Wishlist',
            'manage_options',
            'algenib-wishlist',
            array($this, 'display_plugin_admin_page'),
            'dashicons-heart',
            56
        );
    }

    /**
     * Render the admin page.
     */
    public function display_plugin_admin_page()
    {
        require_once ALG_WISHLIST_PATH . 'admin/partials/alg-wishlist-admin-display.php';
    }
}
