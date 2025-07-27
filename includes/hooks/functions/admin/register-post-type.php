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
        'supports' => ['title', 'editor', 'thumbnail'],
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
            'featured_image' => 'Image de couverture',
            'set_featured_image' => 'Définir l\'image de couverture',
            'remove_featured_image' => 'Supprimer l\'image de couverture',
            'use_featured_image' => 'Utiliser comme image de couverture',
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'bulletins'],
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_ui' => true,
        'show_in_rest' => false,
        'show_in_menu' => 'hclm-manage',
    ]);
}
add_action('init', 'register_newsletter_post_type');

// Add a custom post type for communications
function register_communication_post_type() {
    register_post_type('communication', [
        'labels' => [
            'name' => 'Communications',
            'singular_name' => 'Communication',
            'add_new_item' => 'Ajouter une communication',
            'edit_item' => 'Modifier la communication',
            'search_items' => 'Rechercher des communications',
            'not_found' => 'Aucune communication trouvée',
            'not_found_in_trash' => 'Aucune communication dans la corbeille'
        ],
        'public' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'communications'],
        'supports' => ['title'],
        'show_ui' => true,
        'show_in_rest' => false,
        'show_in_menu' => 'hclm-manage',
    ]);
}
add_action('init', 'register_communication_post_type');

// Remove useless meta boxes
add_action('do_meta_boxes', function () {
    remove_meta_box('astra_settings_meta_box', 'visite_automnale', 'normal');
    remove_meta_box('astra_settings_meta_box', 'bulletin', 'side');
    remove_meta_box('astra_settings_meta_box', 'communication', 'side');
    remove_meta_box('astra_settings_meta_box', 'tribe_events', 'side');
    remove_meta_box('astra_settings_meta_box', 'da_image', 'side');
    remove_meta_box('da_theme_pack', 'da_image', 'side');
    remove_meta_box('wpr-secondary-image', 'visite_automnale', 'side');
    remove_meta_box('wpr-secondary-image', 'bulletin', 'side');
    remove_meta_box('wpr-secondary-image', 'communication', 'side');
    remove_meta_box('wpr-secondary-image', 'tribe_events', 'side');
    remove_meta_box('wpr-secondary-image', 'da_image', 'side');
    remove_meta_box('pms_post_content_restriction', 'visite_automnale', 'normal');
    remove_meta_box('pms_post_content_restriction', 'bulletin', 'normal');
    remove_meta_box('pms_post_content_restriction', 'communication', 'normal');
    remove_meta_box('pms_post_content_restriction', 'tribe_events', 'normal');
    remove_meta_box('pms_post_content_restriction', 'da_image', 'normal');
    remove_meta_box('tribe_events_event_options', 'tribe_events', 'side');
});

?>