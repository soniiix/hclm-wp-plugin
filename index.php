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

?>