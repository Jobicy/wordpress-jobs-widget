<?php
/**
Plugin Name: Jobicy Remote Jobs Widget
Description: Display cached remote jobs from the public Jobicy API with the [jobicy_jobs] shortcode.
Version: 0.1.0
Requires at least: 6.0
Requires PHP: 7.4
Author: Jobicy
License: MIT
Text Domain: jobicy-remote-jobs-widget
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/includes/class-jobicy-api-client.php';
require_once __DIR__ . '/includes/class-jobicy-jobs-shortcode.php';

add_action('init', static function (): void {
    $client = new Jobicy_Remote_Jobs_Api_Client();
    $shortcode = new Jobicy_Remote_Jobs_Shortcode($client, plugin_dir_url(__FILE__) . 'assets/style.css');
    $shortcode->register();
});
