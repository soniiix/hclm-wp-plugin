<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
*/

include 'shortcodes/newsletters_summaries.php';
include 'shortcodes/member_sign_up.php';

add_shortcode('newsletter_summaries', 'newsletters_summaries');
add_shortcode('member_sign_up', 'member_sign_up');

add_filter('show_admin_bar', function ($show) {
    if (!current_user_can('administrator')) {
        return false;
    }
    return $show;
});

add_action('template_redirect', function () {
    if (is_user_logged_in() && is_page('adherer')) {
        wp_redirect(home_url('/accueil'));
        exit;
    }
});

add_filter('wp_nav_menu_objects', function($items, $args) {
    foreach ($items as $key => $item) {
        if ($item->title === 'Adhérer' && is_user_logged_in()) {
            unset($items[$key]);
        }
    }
    return $items;
}, 10, 2);

function hclm_login_button_shortcode() {
    if (is_user_logged_in()) {
        return '<a href="/espace-adherent" style="padding: 15px 25px 15px 25px; color: #FFF; background-color: #e76f51; border-radius: 10px; font-size: 16px; font-weight: 500;">Espace Adhérent</a>';
    } else {
        return '<a href="/connexion" style="padding: 15px 25px 15px 25px; color: #FFF; background-color: #e76f51; border-radius: 10px; font-size: 16px; font-weight: 500;">Me connecter</a>';
    }
}
add_shortcode('hclm_login_button', 'hclm_login_button_shortcode');

?>