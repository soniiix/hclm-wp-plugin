<?php

/**
 * Displays the login button or access to the member area if the user is logged in.
 *
 * @return string HTLM button.
 */
function login_button_shortcode() {
    wp_enqueue_style('hclm-login-button-style', plugin_dir_url(__FILE__) . '../../assets/css/login-button.css');
    
    $label = is_user_logged_in() ? 'Espace Adhérent' : 'Me connecter';
    $url = is_user_logged_in() ? '/espace-adherent' : '/connexion';

    return '<a href="' . esc_url($url) . '" class="hclm-login-button">' . esc_html($label) . '</a>';
}

?>