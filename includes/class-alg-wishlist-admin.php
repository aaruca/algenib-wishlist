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
        add_action('admin_post_alg_check_updates', array($this, 'handle_update_check'));
    }

    /**
     * Enqueue Admin Styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('alg-wishlist-admin-css', ALG_WISHLIST_URL . 'admin/css/alg-wishlist-admin.css', array(), ALG_WISHLIST_VERSION, 'all');
    }

    /**
     * Register Plugin Settings
     */
    public function register_settings()
    {
        register_setting('alg_wishlist_options', 'alg_wishlist_settings');

        add_settings_section(
            'alg_wishlist_style_section',
            'Global Styles',
            null,
            'algenib-wishlist'
        );

        add_settings_field(
            'alg_wishlist_color_primary',
            'Primary Color (Heart)',
            array($this, 'render_color_picker'),
            'algenib-wishlist',
            'alg_wishlist_style_section',
            array('label_for' => 'alg_wishlist_color_primary', 'default' => '#ff4b4b')
        );

        add_settings_field(
            'alg_wishlist_color_hover',
            'Hover Color',
            array($this, 'render_color_picker'),
            'algenib-wishlist',
            'alg_wishlist_style_section',
            array('label_for' => 'alg_wishlist_color_hover', 'default' => '#ff0000')
        );

        add_settings_field(
            'alg_wishlist_color_active',
            'Active Color (Filled)',
            array($this, 'render_color_picker'),
            'algenib-wishlist',
            'alg_wishlist_style_section',
            array('label_for' => 'alg_wishlist_color_active', 'default' => '#cc0000')
        );

        add_settings_field(
            'alg_wishlist_icon_svg',
            'Custom Icon SVG',
            array($this, 'render_textarea'),
            'algenib-wishlist',
            'alg_wishlist_style_section',
            array('label_for' => 'alg_wishlist_icon_svg', 'description' => 'Paste your custom raw SVG code here to replace the default heart.')
        );

        add_settings_section(
            'alg_wishlist_general_section',
            'General Settings',
            null,
            'algenib-wishlist'
        );

        add_settings_field(
            'alg_wishlist_page_id',
            'Wishlist Page',
            array($this, 'render_page_selector'),
            'algenib-wishlist',
            'alg_wishlist_general_section',
            array('label_for' => 'alg_wishlist_page_id')
        );

        add_settings_field(
            'alg_wishlist_custom_css',
            'Custom CSS',
            array($this, 'render_textarea'),
            'algenib-wishlist',
            'alg_wishlist_style_section',
            array('label_for' => 'alg_wishlist_custom_css')
        );
    }

    public function render_page_selector($args)
    {
        $options = get_option('alg_wishlist_settings');
        $selected = isset($options[$args['label_for']]) ? $options[$args['label_for']] : 0;

        wp_dropdown_pages(array(
            'name' => 'alg_wishlist_settings[' . esc_attr($args['label_for']) . ']',
            'selected' => $selected,
            'show_option_none' => '-- Select Page --',
            'option_none_value' => '0',
        ));
    }

    public function render_color_picker($args)
    {
        $options = get_option('alg_wishlist_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : $args['default'];
        echo '<input type="color" id="' . esc_attr($args['label_for']) . '" name="alg_wishlist_settings[' . esc_attr($args['label_for']) . ']" value="' . esc_attr($value) . '">';
    }

    public function render_textarea($args)
    {
        $options = get_option('alg_wishlist_settings');
        $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
        echo '<textarea id="' . esc_attr($args['label_for']) . '" name="alg_wishlist_settings[' . esc_attr($args['label_for']) . ']" rows="5" cols="50" class="large-text code">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">Add your own CSS overrides here.</p>';
    }

    /**
     * Render the admin page.
     */
    public function display_plugin_admin_page()
    {
        require_once ALG_WISHLIST_PATH . 'admin/partials/alg-wishlist-admin-display.php';
    }

    /**
     * Handle Manual Update Check
     * Deletes the 'update_plugins' transient to force a refresh.
     */
    public function handle_update_check()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        check_admin_referer('alg_check_updates_action', 'alg_nonce');

        delete_site_transient('update_plugins');
        delete_transient('alg_wishlist_gh_release'); // If you used any custom cache in Updater

        wp_redirect(admin_url('admin.php?page=algenib-wishlist&tab=settings&update-checked=1'));
        exit;
    }
}
