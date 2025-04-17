<?php 

function hide_page($page_name, $logged_in) {
    $condition = $logged_in ? is_user_logged_in() : !is_user_logged_in();
    if ($condition && is_page($page_name)) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
}

?>