<?php

// Add a custom post type for fall visits

function register_visit_post_type() {
    register_post_type('visite_automnale', [
        'labels' => [
            'name' => 'Visites automnales',
            'singular_name' => 'Visite automnale',
            'add_new_item' => 'Ajouter une visite automnale',
            'edit_item' => 'Modifier la visite',
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

// Remove astra settings meta box
add_action('do_meta_boxes', function () {
    remove_meta_box('astra_settings_meta_box', 'visite_automnale', 'normal');
});

?>