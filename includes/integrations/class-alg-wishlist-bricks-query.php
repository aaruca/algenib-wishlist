<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Bricks Wishlist Query
 */
class Alg_Wishlist_Bricks_Query
{
    public static function init()
    {
        add_filter('bricks/setup/control_options', [__CLASS__, 'register_query_type']);
        add_filter('bricks/query/run', [__CLASS__, 'run_query'], 10, 2);
        add_filter('bricks/query/loop_object', [__CLASS__, 'get_loop_object'], 10, 3);
        add_filter('bricks/query/loop_object_id', [__CLASS__, 'get_loop_object_id'], 10, 3);
    }

    /**
     * Register "Algenib Wishlist" in the Query Type dropdown
     */
    public static function register_query_type($options)
    {
        $options['queryTypes']['alg_wishlist'] = esc_html__('Algenib Wishlist', 'algenib-wishlist');
        return $options;
    }

    /**
     * Run the query: Fetch Product IDs from Wishlist
     */
    public static function run_query($results, $query_obj)
    {
        if ($query_obj->object_type !== 'alg_wishlist') {
            return $results;
        }

        // Get Wishlist Items (IDs)
        $items = Alg_Wishlist_Core::get_wishlist_items();

        if (empty($items)) {
            return [];
        }

        // We fetch WP_Post objects to be safe, though Bricks can handle IDs sometimes.
        // It's safer to return the actual objects expected by a loop.
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'post__in' => $items,
            'posts_per_page' => -1,
            'orderby' => 'post__in', // Keep order of addition if possible, or use 'post__in'
        ];

        $query = new WP_Query($args);

        return $query->posts;
    }

    /**
     * Setup the loop object (Global Post & Product)
     */
    public static function get_loop_object($loop_object, $loop_key, $query_obj)
    {
        if ($query_obj->object_type !== 'alg_wishlist') {
            return $loop_object;
        }

        global $post;
        $post = $loop_object;
        setup_postdata($post);

        // Ensure WooCommerce global product is set
        if (function_exists('wc_get_product')) {
            $GLOBALS['product'] = wc_get_product($loop_object->ID);
        }

        return $loop_object;
    }

    /**
     * Return the valid object ID for dynamic data
     */
    public static function get_loop_object_id($object_id, $object, $query_obj)
    {
        if ($query_obj->object_type !== 'alg_wishlist') {
            return $object_id;
        }

        return $object->ID;
    }
}
