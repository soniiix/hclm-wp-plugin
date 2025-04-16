<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
*/

include 'shortcodes/newsletters_summaries.php';

add_shortcode('newsletter_summaries', 'newsletters_summaries');

?>