<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
Author URI: https://cznquentin.vercel.app
*/

defined('ABSPATH') || exit;

// Include files
require_once plugin_dir_path(__FILE__) . 'includes/hooks/filters.php';
require_once plugin_dir_path(__FILE__) . 'includes/hooks/actions.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login-button.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/registration-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/login-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/contact-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/member-area.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/newsletters/page.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/newsletters/newsletters-summaries.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/newsletters/newsletters-list.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/paywall-content.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/visits-list.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/events-slider.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/search-bar.php';

// Save shortcodes
add_shortcode('hclm_login_button', 'login_button_shortcode');
add_shortcode('hclm_registration_form', 'registration_form_shortcode');
add_shortcode('hclm_login_form', 'login_form_shortcode');
add_shortcode('hclm_contact_form', 'contact_form_shortcode');
add_shortcode('hclm_member_area', 'member_area_shortcode');
add_shortcode('hclm_newsletters', 'newsletters_shortcode');
add_shortcode('hclm_newsletter_summaries', 'newsletters_summaries_shortcode');
add_shortcode('hclm_newsletter_list', 'newsletters_list_shortcode');
add_shortcode('hclm_paywall_content', 'paywall_content_shortcode');
add_shortcode('hclm_fall_visits', 'fall_visits_shortcode');
add_shortcode('hclm_events_slider', 'events_slider_shortcode');
add_shortcode('hclm_search-bar', 'search_bar_shortcode');

?>