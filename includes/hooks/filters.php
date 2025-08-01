<?php

// Hide the admin bar for members
add_filter('show_admin_bar', function ($show) {
    return hclm_current_user_has_role(['administrator', 'tresorier', 'secretaire', 'editor']) ? $show : false;
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
	$tabs['description']['title'] = 'Sommaire'; // Rename the description tab
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

// Customize the users list in the admin area
add_filter('manage_users_columns', function($columns) {
    unset($columns['posts']);                   // Remove the "Posts" column
    $columns['num_adherent'] = 'N° Adhérent';   // Add a custom column for member number
    return $columns;
});

// Remove Yoast SEO columns from the admin area
add_filter('manage_edit-tribe_events_columns', 'hclm_remove_yoast_seo_columns', 10, 1);           // The Events Calendar
add_filter('manage_edit-bulletin_columns', 'hclm_remove_yoast_seo_columns', 10, 1);               // Newsletters
add_filter('manage_edit-visite_automnale_columns', 'hclm_remove_yoast_seo_columns', 10, 1);       // Fall Visits
add_filter('manage_edit-communication_columns', 'hclm_remove_yoast_seo_columns', 10, 1);          // Communications
add_filter('manage_edit-product_columns', 'hclm_remove_yoast_seo_columns', 10, 1);                // Woo Commerce Products
add_filter('manage_edit-post_columns', 'hclm_remove_yoast_seo_columns', 10, 1 );
add_filter('manage_edit-page_columns', 'hclm_remove_yoast_seo_columns', 10, 1 );
function hclm_remove_yoast_seo_columns( $columns ) {
	unset($columns['wpseo-score']); // Remove Yoast SEO score column
 	return $columns;
}

// Display the member number in the custom column
add_filter('manage_users_custom_column', function($value, $column_name, $user_id) {
    if ($column_name === 'num_adherent') {
        return get_user_meta($user_id, 'num_adherent', true);
    }
    return $value;
}, 10, 3);

// Remove the "Comments" column from the pages list in the admin area
// Add a "Modifié le" column
add_filter('manage_pages_columns', function($columns) {
    unset($columns['comments']);
    $columns['modified'] = 'Modifié le';
    return $columns;
});

// Make the "Modifié le" column sortable in the admin area
add_filter('manage_edit-page_sortable_columns', function($columns) {
    $columns['modified'] = 'modified';
    return $columns;
});

// Add a custom user avatar based on user meta
// This function replaces the default avatar with a custom profile picture if it exists
add_filter('get_avatar', 'hclm_custom_user_avatar', 10, 5);
function hclm_custom_user_avatar($avatar, $id_or_email, $size, $default, $alt) {
    $user = false;

    if (is_numeric($id_or_email)) {
        $user = get_user_by('id', (int) $id_or_email);
    } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
        $user = get_user_by('id', (int) $id_or_email->user_id);
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
    }

    if ($user) {
        $profile_picture_id = get_user_meta($user->ID, 'profile_picture', true);
        if ($profile_picture_id) {
            $profile_picture_url = wp_get_attachment_url($profile_picture_id);
            if ($profile_picture_url) {
                $avatar = '<img alt="' . esc_attr($alt) . '" src="' . esc_url($profile_picture_url) . '" class="avatar avatar-' . esc_attr($size) . ' photo" height="' . esc_attr($size) . '" width="' . esc_attr($size) . '">';
            }
        }
    }
    return $avatar;
}

// Add custom styling for Stripe payment form
add_filter( 'pms_stripe_connect_elements_styling', function($args) {
    $args['variables'] = [
        'colorPrimary' => '#e76f51',
        'borderRadius' => '10px',
        'fontSizeBase' => '16px'
    ];
    return $args;
});

// Set a custom tablet breakpoint
add_filter( 'astra_tablet_breakpoint', function() {
    return 1220;
});

// Change Wordpress default mail sender email address
add_filter('wp_mail_from', function ($old) {
	return 'noreply@hclm49.fr';
});

// Change Wordpress default mail sender name
add_filter('wp_mail_from_name', function ($old) {
    return 'HCLM';
});

?>