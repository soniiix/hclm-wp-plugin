<?php

function hclm_update_user_profile() {

    if (!is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }

    if (!isset($_POST['update_user_profile_nonce_field']) || 
        !wp_verify_nonce($_POST['update_user_profile_nonce_field'], 'update_user_profile_nonce')) {
        wp_die('Sécurité non valide');
    }

    $user_id = get_current_user_id();

    // Sanitize and update user information
    if (isset($_POST['user_firstname'])) {
        wp_update_user([
            'ID' => $user_id,
            'first_name' => sanitize_text_field($_POST['user_firstname']),
        ]);
    }

    if (isset($_POST['user_lastname'])) {
        wp_update_user([
            'ID' => $user_id,
            'last_name' => sanitize_text_field($_POST['user_lastname']),
        ]);
    }

    if (isset($_POST['user_phone'])) {
        update_user_meta($user_id, 'user_phone', sanitize_text_field($_POST['user_phone']));
    }

    if (isset($_POST['user_address'])) {
        update_user_meta($user_id, 'user_address', sanitize_text_field($_POST['user_address']));
    }

    if (isset($_POST['user_email']) && is_email($_POST['user_email'])) {
        wp_update_user([
            'ID' => $user_id,
            'user_email' => sanitize_email($_POST['user_email']),
        ]);
    }

    if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['tmp_name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded = media_handle_upload('profile_picture', 0);
        if (!is_wp_error($uploaded)) {
            update_user_meta($user_id, 'profile_picture', $uploaded);
        }
    }

    // Redirect back to the profile page with a success message
    set_transient('hclm_profile_updated_' . get_current_user_id(), 1, 60);
    wp_redirect(remove_query_arg('profile_updated', $_SERVER['HTTP_REFERER']));
    exit;
}

?>