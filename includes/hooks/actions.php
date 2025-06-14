<?php

require_once plugin_dir_path(__FILE__) . '/functions/handle-login.php';
require_once plugin_dir_path(__FILE__) . '/functions/handle-registration.php';
require_once plugin_dir_path(__FILE__) . '/functions/hide-page.php';
require_once plugin_dir_path(__FILE__) . '/functions/update-user-profile.php';
require_once plugin_dir_path(__FILE__) . '/functions/show-more-user-data.php';
require_once plugin_dir_path(__FILE__) . '/functions/admin/menu.php';
require_once plugin_dir_path(__FILE__) . '/functions/admin/register-post-type.php';

// Handle login form submission
add_action('template_redirect', 'handle_login');

// Handle registration form submission
add_action('init', 'handle_registration');

// Display additional user information on the admin page 
add_action( 'show_user_profile', 'show_more_user_data' );
add_action( 'edit_user_profile', 'show_more_user_data' );

// Redirect to the home page depending on whether the user is logged in or not
add_action('template_redirect', function () {
    if (!current_user_can('administrator')) {
        hide_page('espace-adherent', false, '/connexion'); // If not logged in
        hide_page('adherer', true);          // If logged in
        hide_page('connexion', true);        // If logged in
    }
});

// Remove the default WooCommerce shop page
add_action( 'template_redirect', function() {
    if (is_shop()) {
        wp_redirect(home_url('/ouvrages')); // Redirect to the custom shop page
        exit;
    }
});

// Add a plugin-specific tab in the wordpress menu
add_action('admin_menu', 'hclm_admin_menu');

// Remove zoom on hover for product images
add_action( 'wp', function () {
    remove_theme_support('wc-product-gallery-zoom');
}, 100 );

// Update user profile
add_action('admin_post_update_user_profile', 'hclm_update_user_profile');
add_action('admin_post_nopriv_update_user_profile', 'hclm_update_user_profile');

?>