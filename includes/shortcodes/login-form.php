<?php

/**
 * Displays the HCLM login form.
 *
 * @return string HTML login form.
 */
function login_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>Vous êtes déjà connecté.</p>';
    }

    $error = '';

    if (!empty($_GET['login_error'])) {
        $error = '<p class="hclm_error">Identifiants incorrects. Veuillez réessayer.</p>';
    }

    // Load CSS style
    wp_enqueue_style('hclm-signup-style', plugin_dir_url(__FILE__) . '../../assets/css/forms.css');

    return $error . '
        <form method="post" class="hclm_form">
            <input type="hidden" name="redirect_to" value="' . esc_url($_GET['redirect_to'] ?? home_url('/accueil')) . '">
            <p><label>Email :<br><input type="email" name="user_email" required></label></p>
            <p><label>Mot de passe :<br><input type="password" name="user_password" required></label></p>
            <p class="checkbox"><input type="checkbox" id="remember_me" name="remember_me" class="hclm_checkbox">
                <label for="remember_me"> Se souvenir de moi</label>
            </p>
            <p><input type="submit" name="hclm_login_submit" value="Me connecter"></p>
        </form>
    ';
}

?>