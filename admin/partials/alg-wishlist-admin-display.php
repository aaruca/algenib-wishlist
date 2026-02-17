<?php
/**
 * Provide a admin area view for the plugin
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/admin/partials
 */

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'docs';
?>

<div class="alg-wrapper">
	<div class="alg-header">
		<h1>Algenib Wishlist</h1>
	</div>

	<div class="alg-nav-tabs">
		<a href="?page=algenib-wishlist&tab=docs"
			class="alg-nav-tab <?php echo $active_tab == 'docs' ? 'active' : ''; ?>">Documentation</a>
		<a href="?page=algenib-wishlist&tab=settings"
			class="alg-nav-tab <?php echo $active_tab == 'settings' ? 'active' : ''; ?>">Settings</a>
	</div>

	<div class="alg-content-container">

		<?php if ($active_tab == 'docs'): ?>

			<div class="alg-card">
				<h2>Getting Started</h2>
				<p>Welcome to <strong>Algenib Wishlist</strong>. This plugin is designed to be lightweight, fast, and fully
					compatible with Bricks Builder and Doofinder.</p>

				<hr>

				<h3>1. Usage with Bricks Builder</h3>
				<p>The easiest way to use this plugin is with Bricks. You will find two new elements in your element panel:
				</p>
				<ul>
					<li><strong>Wishlist Button (Algenib)</strong>: Drag this into your Product Loop or Single Product
						Template.</li>
					<li><strong>Wishlist Counter (Algenib)</strong>: Place this in your Header to show the count of items.
					</li>
				</ul>
				<p><em>Note: When using Bricks, you control the styles (colors, sizes) directly inside the Builder element
						settings.</em></p>

				<hr>

				<h3>2. Usage with Shortcodes</h3>
				<p>If you are not using Bricks, you can use the following shortcodes:</p>
				<table class="widefat fixed striped" style="border:none; box-shadow:none;">
					<thead>
						<tr>
							<th style="font-weight:600;">Shortcode</th>
							<th style="font-weight:600;">Description</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><code>[alg_wishlist_button]</code></td>
							<td>Displays the "Add to Wishlist" heart button. Works automatically inside loops.</td>
						</tr>
						<tr>
							<td><code>[alg_wishlist_count]</code></td>
							<td>Displays the current number of items in the wishlist.</td>
						</tr>
						<tr>
							<td><code>[alg_wishlist_page]</code></td>
							<td>Displays the full grid of products in the wishlist. Create a page (e.g., /wishlist) and add
								this shortcode.</td>
						</tr>
					</tbody>
				</table>

				<hr>

				<h3>3. Doofinder Integration</h3>
				<p>To add the Wishlist button to your Doofinder search results, add this snippet to your <strong>Doofinder
						Layer Template</strong> (Product Card):</p>
				<pre
					style="background: #f3f2f1; padding: 15px; border-radius: 4px; overflow-x: auto; font-family: Consolas;">
			&lt;button 
			type="button" 
			class="wbw-doofinder-btn" 
			data-product-id="&lt;%= @item["id"] %&gt;"
			title="Add to Wishlist"&gt;
			&lt;svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"&gt;&lt;path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"&gt;&lt;/path&gt;&lt;/svg&gt;
			&lt;/button&gt;</pre>

				<p><em>The plugin javascript will automatically detect this button and handle the logic.</em></p>
			</div>

		<?php else: ?>

			<div class="alg-card">
				<h2>Configuration</h2>
				<form action="options.php" method="post">
					<?php
					settings_fields('alg_wishlist_options');
					do_settings_sections('algenib-wishlist');
					submit_button('Save Settings');
					?>
				</form>


				<hr style="margin: 30px 0;">

				<h3>Updates</h3>
				<p>This plugin updates automatically from GitHub. You can force a check manually below.</p>
				<?php if (isset($_GET['update-checked'])): ?>
					<div class="notice notice-success inline">
						<p>Update check initiated! Please refresh the <a href="<?php echo admin_url('plugins.php'); ?>">Plugins
								page</a> to see if a new version is
							available.</p>
					</div>
				<?php endif; ?>

				<form action="<?php echo admin_url('admin-post.php'); ?>" method="post">
					<input type="hidden" name="action" value="alg_check_updates">
					<?php wp_nonce_field('alg_check_updates_action', 'alg_nonce'); ?>
					<?php submit_button('Check for Updates', 'secondary', 'submit', false); ?>
				</form>
			</div>

		<?php endif; ?>

		<div class="alg-card" style="margin-top: 20px;">
			<h3>Need Help?</h3>
			<p>This plugin is maintained on GitHub. Please check the repository for updates.</p>
		</div>

	</div>
</div>