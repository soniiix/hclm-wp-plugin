<?php

function handle_login() {
    if (isset($_POST['hclm_login_submit'])) {
        // Get the login input, which can be either email or username
        $login_input = sanitize_text_field($_POST['user_email']);
        if (is_email($login_input)) {
            $user_obj = get_user_by('email', $login_input);
            $user_login = $user_obj ? $user_obj->user_login : $login_input;
        } else {
            $user_login = $login_input;
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

            if (user_can($user, 'administrator')) {
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