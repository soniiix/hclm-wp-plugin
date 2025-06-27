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
        $error = '<p class="hclm-login-form-error">Identifiants incorrects. Veuillez réessayer.</p>';
    }

    // Load CSS style
    wp_enqueue_style('hclm-login-form-style', plugin_dir_url(__FILE__) . '../../assets/css/login-form.css');

    return $error . '
        <form method="post" class="hclm-login-form">
            <input type="hidden" name="redirect_to" value="' . esc_url($_GET['redirect_to'] ?? home_url('/accueil')) . '">
            <p>
                <label for="email">Email</label>
                <br>
                <input id="email" type="text" name="user_email" required>
            </p>
            <p class="hclm-login-form-password">
                <label for="password">Mot de passe</label>
                <br>
                <input id="password" type="password" name="user_password" required>
                <a href="' . esc_url(home_url('/contact')) . '">Mot de passe oublié ?</a>
            </p>
            <input type="submit" name="hclm_login_submit" value="Me connecter">
            <div class="hclm-login-form-signup-action">
                Pas encore de compte ?&nbsp;
                <a href="' . esc_url(home_url('/adherer')) . '">
                    Devenez adhérent
                </a>
            </div>
        </form>
    ';
}

?>