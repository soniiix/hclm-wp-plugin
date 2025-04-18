<?php

function handle_registration() {
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
                    'user_address'   => $address,
                    'user_phone' => $phone
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
}

?>