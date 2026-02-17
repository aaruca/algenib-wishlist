<?php
// Load WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

global $wpdb;
$table_wishlists = $wpdb->prefix . 'alg_wishlists';
$table_items = $wpdb->prefix . 'alg_wishlist_items';

echo "Checking tables...\n";

if ($wpdb->get_var("SHOW TABLES LIKE '$table_wishlists'") == $table_wishlists) {
    echo "✅ Table $table_wishlists EXISTS.\n";
} else {
    echo "❌ Table $table_wishlists MISSING.\n";
    // Attempt to run activation
    require_once 'includes/class-alg-wishlist-activator.php';
    Alg_Wishlist_Activator::activate();
    echo "🔄 Attempted activation. Re-checking...\n";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_wishlists'") == $table_wishlists) {
        echo "✅ Table created successfully.\n";
    } else {
        echo "❌ Start failed.\n";
        echo "Last Error: " . $wpdb->last_error . "\n";
    }
}

if ($wpdb->get_var("SHOW TABLES LIKE '$table_items'") == $table_items) {
    echo "✅ Table $table_items EXISTS.\n";
} else {
    echo "❌ Table $table_items MISSING.\n";
}
