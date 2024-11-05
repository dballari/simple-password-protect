<?php
/*
Plugin Name: Simple Password Protection
Plugin URI: https://ballarinconsulting.com/plugins
Description: Initiates a session, checks if the entered password is correct, and redirects to the homepage if it is. This plugin is an AI assisted plugin.
Version: 1.0.0
Requires at least: 6.5
Requires PHP: 7.0
Author: David Ballarin Prunera
Author URI: https://profiles.wordpress.org/dballari/
License: GPL2
Text Domain: simple-password-protect
*/

// Hook para redirigir a la página de login personalizada
add_action('template_redirect', 'password_protect_site');

function password_protect_site() {
    if (!is_user_logged_in() && !isset($_POST['password']) && !isset($_SESSION['site_access_granted'])) {
        wp_die('<form method="post" action="">
            <p>Introduce la contraseña para acceder al sitio:</p>
            <input type="password" name="password" required />
            <input type="submit" value="Acceder" />
        </form>', 'Acceso Restringido');
    }

    // Comprobar la contraseña
    if (isset($_POST['password'])) {
        $password = 'micontra'; // Cambia esto por la contraseña que desees

        if ($_POST['password'] === $password) {
            $_SESSION['site_access_granted'] = true;
            wp_redirect(home_url());
            exit;
        } else {
            wp_die('<form method="post" action="">
            <p>Contraseña incorrecta. Por favor, vuelve a intentarlo:</p>
            <input type="password" name="password" required />
            <input type="submit" value="Acceder" />
        </form>', 'Acceso Restringido');
        }
    }
}

// Iniciar la sesión si no está activa
add_action('init', function() {
    if (!session_id()) session_start();
});
