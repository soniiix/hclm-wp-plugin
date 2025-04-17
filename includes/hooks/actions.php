<?php

require_once plugin_dir_path(__FILE__) . '/functions/handle-login.php';
require_once plugin_dir_path(__FILE__) . '/functions/handle-registration.php';

// Handle login form submission
add_action('template_redirect', 'handle_login');

// Handle registration form submission
add_action('init', 'handle_registration');

// Redirect to home if user is logged in and visits "Adhérer" page
add_action('template_redirect', function () {
    if (is_user_logged_in() && is_page('adherer')) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
});

// Redirect to home if user is logged in and visits login page
add_action('template_redirect', function () {
    if (is_user_logged_in() && is_page('connexion')) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
});

?>