<?php

/**
 * Fired during plugin deactivation
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes
 */

class Alg_Wishlist_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        // Flush rewrite rules if needed
        // Do not delete data on deactivation, only on uninstall
    }

}
