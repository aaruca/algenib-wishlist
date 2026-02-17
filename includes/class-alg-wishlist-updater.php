<?php

/**
 * GitHub Auto Updater
 * 
 * Handles plugin updates directly from GitHub releases.
 *
 * @package    Algenib_Wishlist
 * @subpackage Algenib_Wishlist/includes
 */

class Alg_Wishlist_Updater
{

    private $slug;
    private $plugin_data;
    private $username;
    private $repo;
    private $plugin_file;
    private $github_response;

    public function __construct($plugin_file, $github_username, $github_repo)
    {
        $this->plugin_file = $plugin_file;
        $this->username = $github_username;
        $this->repo = $github_repo;
        $this->slug = plugin_basename($plugin_file);

        add_filter('pre_set_site_transient_update_plugins', array($this, 'modify_transient'), 10, 1);
        add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
        add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
    }

    private function get_repo_release_info()
    {
        if (!empty($this->github_response)) {
            return $this->github_response;
        }

        $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases', $this->username, $this->repo);

        // Add authentication if private repo (via transient or option)
        // For now assuming public repo or token header added via filter if needed.

        $response = wp_remote_get($request_uri);

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            return false;
        }

        $releases = json_decode(wp_remote_retrieve_body($response));
        if (is_array($releases) && !empty($releases)) {
            $this->github_response = $releases[0]; // Get latest
        }

        return $this->github_response;
    }

    public function modify_transient($transient)
    {
        if (property_exists($transient, 'checked') && $transient->checked) {
            $this->get_repo_release_info();

            $out_of_date = version_compare($this->github_response->tag_name, $transient->checked[$this->slug], 'gt');

            if ($out_of_date) {
                $new_files = $this->github_response->assets[0]->browser_download_url ?? $this->github_response->zipball_url;

                $slug = current(explode('/', $this->slug));

                $plugin = array(
                    'url' => $this->plugin_data['PluginURI'] ?? '',
                    'slug' => $slug,
                    'package' => $new_files,
                    'new_version' => $this->github_response->tag_name,
                );

                $transient->response[$this->slug] = (object) $plugin;
            }
        }

        return $transient;
    }

    public function plugin_popup($result, $action, $args)
    {
        if ('plugin_information' !== $action) {
            return $result;
        }

        if ($args->slug !== current(explode('/', $this->slug))) {
            return $result;
        }

        $this->get_repo_release_info();

        $plugin = array(
            'name' => $this->plugin_data['Name'] ?? 'Algenib Wishlist',
            'slug' => $this->slug,
            'version' => $this->github_response->tag_name,
            'author' => $this->plugin_data['AuthorName'] ?? 'Algenib',
            'homepage' => $this->plugin_data['PluginURI'] ?? '',
            'requires' => '5.0',
            'tested' => '6.4',
            'download_link' => $this->github_response->assets[0]->browser_download_url ?? $this->github_response->zipball_url,
            'sections' => array(
                'description' => $this->github_response->body
            )
        );

        return (object) $plugin;
    }

    public function after_install($response, $hook_extra, $result)
    {
        global $wp_filesystem;
        $install_directory = plugin_dir_path($this->plugin_file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;
        return $result;
    }
}
