<?php

function member_sign_up() {
    if (is_user_logged_in()) {
        return '<p>Vous êtes déjà connecté.</p>';
    }

    return '
        <form method="post" id="sign_up_form">
            <p><label>Nom :<br><input type="text" name="member_lastname" required style="width: 100%;"></label><p>
            <p><label>Prénom :<br><input type="text" name="member_firstname" required style="width: 100%;"></label></p>
            <p><label>Adresse :<br><input type="text" name="member_address" required style="width: 100%;"></label></p>
            <p><label>Téléphone :<br><input type="tel" name="member_phone" required style="width: 100%;"></label></p>
            <p><label>Email :<br><input type="email" name="member_email" required style="width: 100%;"></label></p>
            <p><label>Mot de passe :<br><input type="password" name="member_password" required style="width: 100%;"></label></p>
            <p><input type="submit" id="sign_up_form_submit" name="sign_up_submit" value="Créer mon compte"></p>
        </form>
        <style>
            #sign_up_form input:focus{
                border: 1px solid #e76f51 !important;
            }
            #sign_up_form input{
                border-radius: 10px !important;
            }
        </style>
    ';
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
            } else {
                error_log($user_id->get_error_message());
            }
        } else {
            //@TODO
        }
    }
});


?>