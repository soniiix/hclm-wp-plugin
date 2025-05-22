<?php

// Add a custom post type for fall visits
function register_visit_post_type() {
    register_post_type('visite_automnale', [
        'labels' => [
            'name' => 'Visites automnales',
            'singular_name' => 'Visite automnale',
            'add_new_item' => 'Ajouter une visite automnale',
            'edit_item' => 'Modifier la visite automnale',
            'search_items' => 'Rechercher des visites automnales',
            'not_found' => 'Aucune visite automnale trouvée',
            'not_found_in_trash' => 'Aucune visite automnale dans la corbeille',
        ],
        'public' => true,                  
        'has_archive' => false, 
        'rewrite' => ['slug' => 'visites-automnales'],
        'supports' => ['title', 'thumbnail'],
        'show_ui' => true,
        'show_in_rest' => false,
        'show_in_menu' => 'hclm-manage'
    ]);
}
add_action('init', 'register_visit_post_type');

// Add a custom post type for newsletters
function register_newsletter_post_type() {
    register_post_type('bulletin', [
        'labels' => [
            'name' => 'Bulletins',
            'singular_name' => 'Bulletin',
            'add_new_item' => 'Ajouter un bulletin',
            'edit_item' => 'Modifier le bulletin',
            'search_items' => 'Rechercher des bulletins',
            'not_found' => 'Aucun bulletin trouvé',
            'not_found_in_trash' => 'Aucun bulletin dans la corbeille',
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'bulletins'],
        'supports' => ['title', 'thumbnail'],
        'show_ui' => true,
        'show_in_rest' => false,
        'show_in_menu' => 'hclm-manage',
    ]);
}
add_action('init', 'register_newsletter_post_type');

// Remove astra settings meta box
add_action('do_meta_boxes', function () {
    remove_meta_box('astra_settings_meta_box', 'visite_automnale', 'normal');
});

?>