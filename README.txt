# Algenib Wishlist

A complete, high-performance Wishlist plugin for WooCommerce with native Bricks Builder integration and Doofinder compatibility.

## Features
- **High Performance**: Uses custom database tables (`wp_alg_wishlists`) instead of heavy Custom Post Types.
- **Bricks Builder Native**: Drag & Drop "Wishlist Button" and "Wishlist Counter" elements directly in Bricks.
- **Doofinder Ready**: Seamlessly injects and syncs wishlist buttons inside Doofinder search layers via JS events.
- **Guest Support**: Guests can add items (saved via Cookie/Session) which are auto-merged when they register/login.
- **Auto-Updates**: Updates automatically from GitHub Releases.

## Requirements
- WordPress 5.0+
- WooCommerce 3.0+
- PHP 7.4+

## Installation
1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. The database tables will be created automatically.

## Usage

### Shortcuts
- `[alg_wishlist_button]` - Renders the Add to Wishlist button.
- `[alg_wishlist_count]` - Renders the items count.
- `[alg_wishlist_page]` - Renders the full wishlist grid.

### Bricks Builder
Locate the elements under the **"WooCommerce"** category in the element panel:
- **Wishlist Button (Algenib)**
- **Wishlist Counter (Algenib)**

### Doofinder Integration
Add the following snippet to your Doofinder "Product Card" template:
```html
<button 
  type="button" 
  class="wbw-doofinder-btn" 
  data-product-id="<%= @item["id"] %>"
  title="Add to Wishlist">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
</button>
```
