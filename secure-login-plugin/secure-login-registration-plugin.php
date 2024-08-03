<?php
/*
Plugin Name: Secure Login and Registration Plugin
Description: Enhances WordPress login and registration security with OTP verification, role management, and advanced security features.
Version: 1.0
Author: Majdi M. S. Awad
*/

defined('ABSPATH') or die('No script kiddies please!');

// Include necessary files
include(plugin_dir_path(__FILE__) . 'includes/security-functions.php');
include(plugin_dir_path(__FILE__) . 'includes/otp-functions.php');

// Register hooks
add_action('wp_login', 'verify_otp_on_login', 10, 2);
add_action('wp_login_failed', 'handle_failed_login');
add_action('wp_head', 'set_security_headers');

// Enqueue plugin styles
function secure_login_plugin_enqueue_scripts() {
    wp_enqueue_style('secure-login-style', plugin_dir_url(__FILE__) . 'styles.css');
}
add_action('wp_enqueue_scripts', 'secure_login_plugin_enqueue_scripts');

// Initialize plugin
function secure_login_plugin_init() {
    // Plugin initialization code here
}
add_action('init', 'secure_login_plugin_init');
