<?php

function hclm_member_sign_up() {
    if (is_user_logged_in()) {
        return '<p>Vous êtes déjà connecté.</p>';
    }

    wp_enqueue_style('hclm-signup-style', plugin_dir_url(__FILE__) . '../../assets/css/signup-form.css');

    ob_start();
    ?>
    <form method="post" id="sign_up_form">
        <p><label>Nom :<br><input type="text" name="member_lastname" required></label></p>
        <p><label>Prénom :<br><input type="text" name="member_firstname" required></label></p>
        <p><label>Adresse :<br><input type="text" name="member_address" required></label></p>
        <p><label>Téléphone :<br><input type="tel" name="member_phone" required></label></p>
        <p><label>Email :<br><input type="email" name="member_email" required></label></p>
        <p><label>Mot de passe :<br><input type="password" name="member_password" required></label></p>
        <p><input type="submit" name="sign_up_submit" value="Créer mon compte"></p>
    </form>
    <?php
    return ob_get_clean();
}

add_action('init', function () {
    if (isset($_POST['sign_up_submit'])) {
        $email = sanitize_email($_POST['member_email']);
        $password = $_POST['member_password'];
        $lastname = sanitize_text_field($_POST['member_lastname']);
        $firstname = sanitize_text_field($_POST['member_firstname']);
        $address = sanitize_text_field($_POST['member_address']);
        $phone = sanitize_text_field($_POST['member_phone']);

        if (!email_exists($email)) {
            $userdata = [
                'user_login'    => $email,
                'user_email'    => $email,
                'user_pass'     => $password,
                'first_name'    => $firstname,
                'last_name'     => $lastname,
                'display_name'  => $firstname . ' ' . $lastname,
                'role'          => 'adherent',
                'meta_input'    => [
                    'adresse'   => $address,
                    'telephone' => $phone
                ]
            ];

            $user_id = wp_insert_user($userdata);
            if (!is_wp_error($user_id)) {
                wp_set_auth_cookie($user_id);
                wp_redirect(home_url('/accueil'));
                exit;
            }
        }
    }
});
