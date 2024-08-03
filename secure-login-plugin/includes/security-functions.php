<?php
// Set security headers
function set_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    header('Referrer-Policy: no-referrer');
}

// Handle failed login attempts
function handle_failed_login($username) {
    // Track failed login attempts and block user after 2 failed attempts
    // Send notification email to admin if necessary
}

// Log security-related errors
function log_security_error($message) {
    $log_file = plugin_dir_path(__FILE__) . 'security.log';
    error_log($message . "\n", 3, $log_file);
}
