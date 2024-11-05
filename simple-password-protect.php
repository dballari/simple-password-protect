<?php
/*
Plugin Name: Simple Password Protection
Plugin URI: https://ballarinconsulting.com/plugins
Description: Adds password protection to your WordPress site, blocking access until the correct password is entered, ideal for preventing search engine indexing.
Version: 0.0.3
Requires at least: 6.5
Requires PHP: 7.0
Author: David Ballarin Prunera
Author URI: https://profiles.wordpress.org/dballari/
License: GPL2
Text Domain: simple-password-protect
*/

// Hook to redirect to the custom login page
add_action('template_redirect', 'password_protect_site');

function password_protect_site() {

    // Display login form if session is not granted
    if (!is_user_logged_in() && !isset($_POST['password']) && !isset($_SESSION['site_access_granted'])) {
        wp_die('<form method="post" action="">
            <p>' . esc_html__('Enter the password to access the site:', 'simple-password-protect') . '</p>
            <input type="password" name="password" required />
            ' . wp_nonce_field('password_protect_action', 'password_protect_nonce', true, false) . '
            <input type="submit" value="' . esc_html__('Enter', 'simple-password-protect') . '" />
        </form>', esc_html__('Restricted Access', 'simple-password-protect'));
    }

    // Check the password
    if (isset($_POST['password'])) {

        // Verify the nonce
        if (!isset($_POST['password_protect_nonce']) || !check_admin_referer('password_protect_action', 'password_protect_nonce')) {
            wp_die(esc_html__('Nonce verification failed. Please try again.', 'simple-password-protect'));
        }

        // Default password if MY_PASSWORDS is not defined
        $password = 'micontra';

        // Determine password based on MY_PASSWORDS constant
        if (defined('MY_PASSWORDS')) {
            if (is_multisite() && is_array(MY_PASSWORDS)) {
                $site_id = get_current_blog_id();
                $password = isset(MY_PASSWORDS[$site_id]) ? MY_PASSWORDS[$site_id] : null;
                if (!$password) {
                    wp_die(esc_html__('Password configuration error. Please contact the administrator.', 'simple-password-protect'));
                }
            } else {
                $password = MY_PASSWORDS;
            }
        }

        // Check the password
        if ($_POST['password'] === $password) {
            $_SESSION['site_access_granted'] = true;
            wp_redirect(home_url());
            exit;
        } else {
            wp_die('<form method="post" action="">
                <p>' . esc_html__('Incorrect password. Please try again:', 'simple-password-protect') . '</p>
                ' . wp_nonce_field('password_protect_action', 'password_protect_nonce', true, false) . '
                <input type="password" name="password" required />
                <input type="submit" value="' . esc_html__('Enter', 'simple-password-protect') . '" />
            </form>', esc_html__('Restricted Access', 'simple-password-protect'));
        }
    }
}

// Start the session if it is not active
add_action('init', function() {
    if (!session_id()) session_start();
});
