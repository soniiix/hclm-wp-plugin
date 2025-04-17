<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
*/

defined('ABSPATH') || exit;

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks/actions.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login-button.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/member-sign-up.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/newsletters-summaries.php';

// Save shortcodes
add_shortcode('hclm_login_button', 'hclm_login_button_shortcode');
add_shortcode('member_sign_up', 'hclm_member_sign_up');
add_shortcode('newsletter_summaries', 'hclm_newsletters_summaries');

?>