<?php

function handle_login() {
    if (isset($_POST['hclm_login_submit'])) {
        // Get the login input, which can be email, username, or member number
        $login_input = sanitize_text_field($_POST['user_login']);
        $user_login = $login_input;

        if (is_email($login_input)) {
            $user_obj = get_user_by('email', $login_input);
            if ($user_obj) {
                $user_login = $user_obj->user_login;
            }
        } elseif (is_numeric($login_input)) {
            // Assuming the input is a member number, we search for the user by meta key
            $users = get_users([
                'meta_key'   => 'num_adherent',
                'meta_value' => $login_input,
                'number'     => 1,
                'fields'     => ['ID']
            ]);
            if (!empty($users)) {
                $user_obj = get_user_by('id', $users[0]->ID);
                if ($user_obj) {
                    $user_login = $user_obj->user_login;
                }
            }
        }

        $creds = array(
            'user_login'    => $user_login,
            'user_password' => $_POST['user_password']
        );

        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            set_transient('hclm_login_error_' . md5($_SERVER['REMOTE_ADDR']), true, 120); // Store the transient for 2 minutes
            wp_redirect(add_query_arg('login_error', '1', wp_get_referer()));
            exit;
        } else {
            // Manually set the current user and auth cookie
            // This is necessary to ensure the user is logged in correctly and if he is an admin, redirect to the admin dashboard
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            if (hclm_current_user_has_role(['administrator', 'tresorier', 'secretaire', 'editor'])) {
                wp_redirect(admin_url());
            } else {
                $redirect = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : home_url('/accueil');
                if (!empty($_POST['redirect_to']) && $_POST['redirect_to'] !== home_url('/accueil')) {
                    $redirect = add_query_arg('redirected', '1', $redirect);
                }
                $redirect = add_query_arg('login_success', '1', $redirect);
                wp_redirect($redirect);
            }
            exit;
        }
    }
}

?>