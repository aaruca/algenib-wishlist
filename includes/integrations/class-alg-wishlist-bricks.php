<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Bricks Builder Integration
 */
class Alg_Wishlist_Bricks
{

    public static function init()
    {
        add_action('bricks/elements/register', array(__CLASS__, 'register_elements'), 11);
    }

    public static function register_elements()
    {
        // Load Query Integration
        require_once ALG_WISHLIST_PATH . 'includes/integrations/class-alg-wishlist-bricks-query.php';
        Alg_Wishlist_Bricks_Query::init();

        // Register Wishlist Button Element
        \Bricks\Elements::register_element(ALG_WISHLIST_PATH . 'includes/integrations/elements/wishlist-button.php');

        // Register Wishlist Counter Element
        \Bricks\Elements::register_element(ALG_WISHLIST_PATH . 'includes/integrations/elements/wishlist-counter.php');
    }
}
