<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
*/

defined('ABSPATH') || exit;

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/hooks/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks/actions.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login-button.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/registration-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/newsletters-summaries.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/member-area.php';

// Save shortcodes
add_shortcode('hclm_login_button', 'login_button_shortcode');
add_shortcode('hclm_registration_form', 'registration_form_shortcode');
add_shortcode('hclm_newsletter_summaries', 'newsletters_summaries_shortcode');
add_shortcode('hclm_login_form', 'login_form_shortcode');
add_shortcode('hclm_member_area', 'member_area_shortcode');

?>