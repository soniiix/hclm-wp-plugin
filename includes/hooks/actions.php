<?php

require_once plugin_dir_path(__FILE__) . '/functions/handle-login.php';
require_once plugin_dir_path(__FILE__) . '/functions/handle-registration.php';
require_once plugin_dir_path(__FILE__) . '/functions/hide-page.php';

// Handle login form submission
add_action('template_redirect', 'handle_login');

// Handle registration form submission
add_action('init', 'handle_registration');

// Redirect to the home page depending on whether the user is logged in or not
add_action('template_redirect', function () {
    hide_page('espace-adherent', false); // If not logged in
    hide_page('adherer', true);          // If logged in
    hide_page('connexion', true);        // If logged in
});

?>