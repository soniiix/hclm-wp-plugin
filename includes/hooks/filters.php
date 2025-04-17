<?php

// Hide the admin bar for members
add_filter('show_admin_bar', function ($show) {
    return current_user_can('administrator') ? $show : false;
});

// Hide the "Adhérer" menu link if the user is logged in
add_filter('wp_nav_menu_objects', function ($items, $args) {
    foreach ($items as $key => $item) {
        if ($item->title === 'Adhérer' && is_user_logged_in()) {
            unset($items[$key]);
        }
    }
    return $items;
}, 10, 2);

?>