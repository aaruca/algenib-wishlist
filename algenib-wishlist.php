<?php
/**
 * Plugin Name: Algenib Wishlist
 * Plugin URI:  https://algenib.io
 * Description: A complete, high-performance Wishlist plugin with native Bricks Builder integration and Doofinder compatibility.
 * Version:     1.0.4
 * Author:      Algenib
 * Author URI:  https://algenib.io
 * Text Domain: algenib-wishlist
 * Domain Path: /languages
 *
 * @package Algenib_Wishlist
 */

if (!defined('ABSPATH')) {
	exit;
}

// Define Constants
define('ALG_WISHLIST_VERSION', '1.0.4');
define('ALG_WISHLIST_FILE', __FILE__);
define('ALG_WISHLIST_PATH', plugin_dir_path(__FILE__));
define('ALG_WISHLIST_URL', plugin_dir_url(__FILE__));
define('ALG_WISHLIST_BASENAME', plugin_basename(__FILE__));

/**
 * The Switchboard Class
 */
require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-loader.php';

/**
 * Activation Hook
 */
function activate_algenib_wishlist()
{
	require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-activator.php';
	Alg_Wishlist_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_algenib_wishlist');

/**
 * Deactivation Hook
 */
function deactivate_algenib_wishlist()
{
	require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-deactivator.php';
	Alg_Wishlist_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_algenib_wishlist');

/**
 * Begin Execution
 */
function run_algenib_wishlist()
{
	$plugin = new Alg_Wishlist_Loader();
	$plugin->run();
}
run_algenib_wishlist();
