<?php

/**
 * 
 * Description: A WordPress plugin that updates itself from a GitHub repository.
 * 
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// GitHub Updater Class
class GitHubUpdater
{
    private $plugin_slug;
    private $plugin_file;
    private $github_repo;
    private $github_api;

    public function __construct($plugin_file, $github_repo)
    {
        $this->plugin_slug = plugin_basename($plugin_file);
        $this->plugin_file = $plugin_file;
        $this->github_repo = $github_repo;
        $this->github_api = "https://api.github.com/repos/{$github_repo}/releases/latest";

        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
        add_filter('auto_update_plugin', [$this, 'enable_auto_update'], 10, 2); // Enable auto-update
        add_filter('plugin_auto_update_enabled', '__return_true'); // Hide "Enable auto-updates" option
    }

    public function check_for_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $response = wp_remote_get($this->github_api);
        if (is_wp_error($response)) {
            return $transient;
        }

        $release = json_decode(wp_remote_retrieve_body($response));
        if (!isset($release->tag_name)) {
            return $transient;
        }

        $new_version = $release->tag_name;
        $current_version = get_plugin_data(WP_PLUGIN_DIR . '/' . $this->plugin_slug)['Version'];

        if (version_compare($current_version, $new_version, '<')) {
            $transient->response[$this->plugin_slug] = (object) [
                'new_version' => $new_version,
                'package' => $release->assets[0]->browser_download_url,
                'slug' => $this->plugin_slug,
                'url' => $release->html_url,
            ];
        }

        return $transient;
    }

    public function plugin_info($res, $action, $args)
    {
        if ($action !== 'plugin_information' || $args->slug !== $this->plugin_slug) {
            return $res;
        }

        $response = wp_remote_get($this->github_api);
        if (is_wp_error($response)) {
            return $res;
        }

        $release = json_decode(wp_remote_retrieve_body($response));
        if (!isset($release->tag_name)) {
            return $res;
        }

        $res = (object) [
            'name' => 'Unicode to Bijoy Converter',
            'slug' => $this->plugin_slug,
            'version' => $release->tag_name,
            'download_link' => $release->assets[0]->browser_download_url,
            'sections' => ['description' => $release->body],
            'tested' => '6.4',
            'requires' => '5.0',
            'author' => '<a href="https://github.com/subrata6630">Subrata Debnath</a>',
            'author_profile' => 'https://github.com/subrata6630',
        ];

        return $res;
    }

    // This function enables auto-updates for this plugin
    public function enable_auto_update($update, $plugin)
    {
        if ($plugin === $this->plugin_slug) {
            return true;  // Enable auto-update for this plugin
        }

        return $update;  // Keep default behavior for other plugins
    }
}
