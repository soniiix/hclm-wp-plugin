<?php

// Redirect to home if user is logged in and visits "Adhérer" page
add_action('template_redirect', function () {
    if (is_user_logged_in() && is_page('adherer')) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
});

?>