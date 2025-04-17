<?php

/**
 * Displays the HCLM login form.
 *
 * @return string HTML login form.
 */
function hclm_login_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>Vous êtes déjà connecté.</p>';
    }

    $error = '';

    if (isset($_POST['hclm_login_submit'])) {
        $creds = array(
            'user_login'    => sanitize_email($_POST['user_email']),
            'user_password' => $_POST['user_password'],
            'remember'      => isset($_POST['remember_me']),
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            $error = '<p class="hclm-error">Identifiants incorrects. Veuillez réessayer.</p>';
        } else {
            wp_redirect(home_url('/espace-adherent'));
            exit;
        }
    }

    wp_enqueue_style('hclm-login-form-style', plugin_dir_url(__FILE__) . '../assets/css/login_form.css');

    return $error . '
        <form method="post" class="hclm-login-form">
            <p><label>Email :<br><input type="email" name="user_email" required></label></p>
            <p><label>Mot de passe :<br><input type="password" name="user_password" required></label></p>
            <p><label><input type="checkbox" name="remember_me"> Se souvenir de moi</label></p>
            <p><input type="submit" name="hclm_login_submit" value="Me connecter"></p>
        </form>
    ';
}

?>