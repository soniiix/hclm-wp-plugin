<?php

// Redirect to home if user is logged in and visits "Adhérer" page
add_action('template_redirect', function () {
    if (is_user_logged_in() && is_page('adherer')) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
});

// Handle login form submission
add_action('template_redirect', 'hclm_handle_login_form');

function hclm_handle_login_form() {
    if (isset($_POST['hclm_login_submit'])) {
        $creds = array(
            'user_login'    => sanitize_email($_POST['user_email']),
            'user_password' => $_POST['user_password'],
            'remember'      => isset($_POST['remember_me']),
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            wp_redirect(add_query_arg('login_error', '1', wp_get_referer()));
            exit;
        } else {
            wp_redirect(home_url('/accueil'));
            exit;
        }
    }
}

?>