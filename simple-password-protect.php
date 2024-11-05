<?php
/*
Plugin Name: Simple Password Protection
Plugin URI: https://ballarinconsulting.com/plugins
Description: Initiates a session, checks if the entered password is correct, and redirects to the homepage if it is. This plugin is an AI assisted plugin.
Version: 0.0.2
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
            <p>' . __('Enter the password to access the site:', 'password-protect-site') . '</p>
            <input type="password" name="password" required />
            <input type="submit" value="' . __('Enter', 'password-protect-site') . '" />
        </form>', __('Restricted Access', 'password-protect-site'));
    }

    // Check the password
    if (isset($_POST['password'])) {

        // Default password if MY_PASSWORDS is not defined
        $password = 'micontra';

        // Determine password based on MY_PASSWORDS constant
        if (defined('MY_PASSWORDS')) {
            if (is_multisite() && is_array(MY_PASSWORDS)) {
                $site_id = get_current_blog_id();
                $password = isset(MY_PASSWORDS[$site_id]) ? MY_PASSWORDS[$site_id] : null;
                if (!$password) {
                    wp_die(__('Password configuration error. Please contact the administrator.', 'password-protect-site'));
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
                <p>' . __('Incorrect password. Please try again:', 'password-protect-site') . '</p>
                <input type="password" name="password" required />
                <input type="submit" value="' . __('Enter', 'password-protect-site') . '" />
            </form>', __('Restricted Access', 'password-protect-site'));
        }
    }
}

// Start the session if it is not active
add_action('init', function() {
    if (!session_id()) session_start();
});
