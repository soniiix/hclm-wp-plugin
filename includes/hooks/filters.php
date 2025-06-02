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

// Rename the product tabs
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	$tabs['description']['title'] = __( 'Sommaire' ); // Rename the description tab
	return $tabs;
}

// Use a custom template for fall visit details page
add_filter('template_include', function($template){
    if (is_singular('visite_automnale')) {
        return plugin_dir_path(__FILE__) . '../../templates/single-fall-visit.php';
    }
    return $template;
});

// Use a custom template for newsletter single page
add_filter('template_include', function($template){
    if (is_singular('bulletin')) {
        return plugin_dir_path(__FILE__) . '../../templates/single-newsletter.php';
    }
    return $template;
});

// Use a custom page for search results
add_filter('template_include', function($template){
    if (is_search()) {
        return plugin_dir_path(__FILE__) . '../../templates/search-results.php';
    }
    return $template;
});

?>