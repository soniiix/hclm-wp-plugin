<?php

require_once plugin_dir_path(__FILE__) . '/functions/handle-login.php';
require_once plugin_dir_path(__FILE__) . '/functions/handle-registration.php';
require_once plugin_dir_path(__FILE__) . '/functions/hide-page.php';
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
    hide_page('espace-adherent', false, '/connexion'); // If not logged in
    hide_page('adherer', true);          // If logged in
    hide_page('connexion', true);        // If logged in
});

// Add a plugin-specific tab in the wordpress menu
add_action('admin_menu', 'hclm_admin_menu');

// Remove zoom on hover for product images
add_action( 'wp', function () {
    remove_theme_support('wc-product-gallery-zoom');
}, 100 );

// Extend search results
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_search()) {
        // Content type filter
        if (!empty($_GET['type'])) {
            switch ($_GET['type']) {
                case 'newsletters':
                    $query->set('post_type', '');
                    break;
                case 'pages':
                    $query->set('post_type', 'page');
                    break;
                case 'events':
                    $query->set('post_type', 'tribe_events');
                    break;
                case 'fall-visits':
                    $query->set('post_type', 'visite_automnale');
                    break;
                case 'products':
                    $query->set('post_type', 'product');
                    break;
                default:
                    break;
            }
        }
    }
});


?>