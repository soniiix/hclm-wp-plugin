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

    if (!empty($_GET['login_error']) && !empty($_SESSION['hclm_login_failed'])) {
        $error = '
        <p class="hclm-login-form-error" role="alert">
            <i class="far fa-times-circle"></i>
            Identifiants incorrects. Veuillez réessayer.
        </p>';
        unset($_SESSION['hclm_login_failed']);
    }

    // Load CSS style
    wp_enqueue_style('hclm-login-form-style', plugin_dir_url(__FILE__) . '../../assets/css/login-form.css');

    return $error . '
        <form method="post" class="hclm-login-form" aria-labelledby="form-title">
            <span id="form-title" class="sr-only">Connexion à l\'espace adhérent</span>
            <input type="hidden" name="redirect_to" value="' . esc_url($_GET['redirect_to'] ?? home_url('/accueil')) . '">
            <p>
                <label for="email">Email</label>
                <br>
                <input id="email" type="text" name="user_email" autocomplete="email" required>
            </p>
            <p class="hclm-login-form-password">
                <label for="password">Mot de passe</label>
                <br>
                <input id="password" type="password" name="user_password" required autocomplete="current-password">
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

        <script>
        // Remove the login_error parameter from the URL after the page loads
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            if (params.has("login_error")) {
                params.delete("login_error");
                const newUrl = window.location.pathname + (params.toString() ? "?" + params.toString() : "");
                window.history.replaceState({}, "", newUrl);
            }
        });
        </script>
    ';
}

?>