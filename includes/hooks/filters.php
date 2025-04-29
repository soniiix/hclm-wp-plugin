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

// Change the product button text
add_filter('woocommerce_product_add_to_cart_text', function ($text) {
    if ($text == 'Lire la suite' || 'Ajouter au panier') {
        return 'Voir les détails';
    } else {
        return $text;
    }
});

add_filter('ywctm_modify_woocommerce_after_shop_loop_item', '__return_false');

?>