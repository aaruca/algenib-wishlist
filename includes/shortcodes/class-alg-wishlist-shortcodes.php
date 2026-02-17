<?php

/**
 * Shortcodes Handler
 * 
 * Registers [alg_wishlist_button] and [alg_wishlist_count].
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes/shortcodes
 */

class Alg_Wishlist_Shortcodes
{

    public function register_shortcodes()
    {
        add_shortcode('alg_wishlist_button', array($this, 'render_button'));
        add_shortcode('alg_wishlist_count', array($this, 'render_count'));
        add_shortcode('alg_wishlist_page', array($this, 'render_page'));
    }

    public function render_button($atts)
    {
        $atts = shortcode_atts(array(
            'product_id' => get_the_ID(),
            'class' => '',
            'text' => '' // Optional text
        ), $atts);

        $id = intval($atts['product_id']);
        if (!$id)
            return '';

        // Check status logic is handled by JS on load, 
        // but we can add 'active' class via PHP for initial render if cached? 
        // For Better Caching compatibility, we rely on JS to add .active class.
        // However, we can use the 'initial_items' localized script to do it instantly.

        ob_start();
        ?>
        <button type="button" class="alg-add-to-wishlist <?php echo esc_attr($atts['class']); ?>"
            data-product-id="<?php echo esc_attr($id); ?>"
            aria-label="<?php esc_attr_e('Add to Wishlist', 'algenib-wishlist'); ?>">

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
            <?php if (!empty($atts['text'])): ?>
                <span class="alg-btn-text">
                                <?php echo esc_html($atts['text']); ?>
                </span>
            <?php endif; ?>

        </button>
        <?php
        return ob_get_clean();
    }

    public function render_count($atts)
    {
        ob_start();
        ?>
        <span class="alg-wishlist-count hidden">0</span>
        <?php
        return ob_get_clean();
    }

    public function render_page($atts)
    {
        $items = Alg_Wishlist_Core::get_wishlist_items();

        if (empty($items)) {
            return '<p class="alg-wishlist-empty">' . esc_html__('Your wishlist is empty.', 'algenib-wishlist') . '</p>';
        }

        ob_start();
        ?>
                <div class="alg-wishlist-grid">
                    <?php foreach ($items as $product_id):
                        $product = wc_get_product($product_id);
                        if (!$product)
                            continue;
                        ?>
                            <div class="alg-wishlist-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                <div class="alg-wishlist-item-thumb">
                                    <?php echo $product->get_image(); ?>
                                </div>
                                <div class="alg-wishlist-item-details">
                                    <h3><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo $product->get_name(); ?></a></h3>
                                    <div class="alg-wishlist-item-price">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    <div class="alg-wishlist-item-actions">
                                        <a href="?add-to-cart=<?php echo esc_attr($product_id); ?>" class="button alt"><?php esc_html_e('Add to Cart', 'algenib-wishlist'); ?></a>
                                        <button type="button" class="alg-remove-from-wishlist" data-product-id="<?php echo esc_attr($product_id); ?>"><?php esc_html_e('Remove', 'algenib-wishlist'); ?></button>
                                    </div>
                                </div>
                            </div>
                    <?php endforeach; ?>
                </div>
                <?php
                return ob_get_clean();
    }

}
