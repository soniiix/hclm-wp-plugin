<?php 

/**
 * Redirects the user to the home page if he tries to access a specific page, based on his login status.
 * 
 * @param string $page_name The slug of the page to restrict.
 * @param bool $logged_in Whether the user should be logged in to access the page.
 */
function hide_page($page_name, $logged_in) {
    $condition = $logged_in ? is_user_logged_in() : !is_user_logged_in();
    if ($condition && is_page($page_name)) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
}

?>