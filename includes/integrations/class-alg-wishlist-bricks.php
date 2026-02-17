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
        // Load Query Integration immediately to catch setup/control_options hooks early
        require_once ALG_WISHLIST_PATH . 'includes/integrations/class-alg-wishlist-bricks-query.php';
        Alg_Wishlist_Bricks_Query::init();

        // Register Elements
        add_action('bricks/elements/register', array(__CLASS__, 'register_elements'), 11);
    }

    public static function register_elements()
    {
        // Register Wishlist Button Element
        \Bricks\Elements::register_element(ALG_WISHLIST_PATH . 'includes/integrations/elements/wishlist-button.php');

        // Register Wishlist Counter Element
        \Bricks\Elements::register_element(ALG_WISHLIST_PATH . 'includes/integrations/elements/wishlist-counter.php');
    }
}
