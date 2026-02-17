<?php

/**
 * Core Plugin Loader
 * 
 * Orchestrates the loading of dependencies and registering hooks.
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes
 */

class Alg_Wishlist_Loader
{

    protected $loader;

    public function __construct()
    {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_integrations();
    }

    private function load_dependencies()
    {
        // Core Logic
        require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-core.php';

        // Frontend Assets
        require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-assets.php';

        // Shortcodes
        require_once ALG_WISHLIST_PATH . 'includes/shortcodes/class-alg-wishlist-shortcodes.php';

        // AJAX Handler
        require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-ajax.php';
    }

    private function define_admin_hooks()
    {
        if (is_admin()) {
            require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-admin.php';
            $admin = new Alg_Wishlist_Admin();
            add_action('admin_menu', array($admin, 'add_plugin_admin_menu'));
            add_action('admin_init', array($admin, 'register_settings'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));

            // DB Health Check
            add_action('admin_init', array($this, 'verify_database_tables'));
        }
    }

    private function define_public_hooks()
    {
        $assets = new Alg_Wishlist_Assets();
        add_action('wp_enqueue_scripts', array($assets, 'enqueue_scripts'));

        $shortcodes = new Alg_Wishlist_Shortcodes();
        add_action('init', array($shortcodes, 'register_shortcodes'));

        $ajax = new Alg_Wishlist_Ajax();
        add_action('wp_ajax_alg_add_to_wishlist', array($ajax, 'add_to_wishlist'));
        add_action('wp_ajax_nopriv_alg_add_to_wishlist', array($ajax, 'add_to_wishlist'));

        // Session Logic
        add_action('init', array('Alg_Wishlist_Core', 'init_session'));

        // Guest Migration Hook
        add_action('wp_login', array('Alg_Wishlist_Core', 'merge_guest_wishlist'));
        add_action('user_register', array('Alg_Wishlist_Core', 'merge_guest_wishlist'));

        // Initialize Updater (Admin only)
        if (is_admin()) {
            require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-updater.php';
            // Change 'algenib-io' to your actual GitHub Org/User
            new Alg_Wishlist_Updater(ALG_WISHLIST_FILE, 'aaruca', 'algenib-wishlist', ALG_WISHLIST_VERSION);
        }
    }

    private function define_integrations()
    {
        add_action('plugins_loaded', array($this, 'load_integrations'), 20);
    }

    public function load_integrations()
    {
        // Bricks Integration
        require_once ALG_WISHLIST_PATH . 'includes/integrations/class-alg-wishlist-bricks.php';
        // Initialize Bricks elements registration
        Alg_Wishlist_Bricks::init();

        // Doofinder Integration
        require_once ALG_WISHLIST_PATH . 'includes/integrations/class-alg-wishlist-doofinder.php';
        $doofinder = new Alg_Wishlist_Doofinder();
        add_action('wp_enqueue_scripts', array($doofinder, 'enqueue_compatibility_script'));
    }

    public function run()
    {
        // Run logic
    }

    /**
     * Self-healing: Ensure tables exist if missing
     */
    public function verify_database_tables()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'alg_wishlists';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once ALG_WISHLIST_PATH . 'includes/class-alg-wishlist-activator.php';
            Alg_Wishlist_Activator::activate();
        }
    }

}
