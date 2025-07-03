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

    // Basics WP fields
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

    if (isset($_POST['user_email']) && is_email($_POST['user_email'])) {
        wp_update_user([
            'ID' => $user_id,
            'user_email' => sanitize_email($_POST['user_email']),
        ]);
    }

    // Address and phone fields
    $pms_fields = [];

    if (isset($_POST['user_phone'])) {
        $phone = sanitize_text_field($_POST['user_phone']);
        update_user_meta($user_id, 'billing_phone', $phone);
        $pms_fields['pms_billing_phone'] = $phone;
    }

    if (isset($_POST['user_address'])) {
        $address = sanitize_text_field($_POST['user_address']);
        update_user_meta($user_id, 'pms_billing_address', $address);
        update_user_meta($user_id, 'billing_address_1', $address);
        $pms_fields['pms_billing_address'] = $address;
    }

    if (isset($_POST['user_address_2'])) {
        $address2 = sanitize_text_field($_POST['user_address_2']);
        update_user_meta($user_id, 'billing_address_2', $address2);
        $pms_fields['pms_billing_address_2'] = $address2;
    }

    if (isset($_POST['user_city'])) {
        $city = sanitize_text_field($_POST['user_city']);
        update_user_meta($user_id, 'pms_billing_city', $city);
        update_user_meta($user_id, 'billing_city', $city);
        $pms_fields['pms_billing_city'] = $city;
    }

    if (isset($_POST['user_postal_code'])) {
        $postal_code = sanitize_text_field($_POST['user_postal_code']);
        update_user_meta($user_id, 'pms_billing_zip', $postal_code);
        update_user_meta($user_id, 'billing_postcode', $postal_code);
        $pms_fields['pms_billing_zip'] = $postal_code;
    }

    // Profile picture update
    if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['tmp_name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded = media_handle_upload('profile_picture', 0);
        if (!is_wp_error($uploaded)) {
            update_user_meta($user_id, 'profile_picture', $uploaded);
        }
    }

    // PMS Sync
    if (function_exists('pms_update_user_account_data') && !empty($pms_fields)) {
        pms_update_user_account_data($pms_fields, $user_id, 'custom_form');
    }

    //Redirect back to the profile page with a success message
    set_transient('hclm_profile_updated_' . $user_id, 1, 60);
    wp_redirect(remove_query_arg('profile_updated', $_SERVER['HTTP_REFERER']));
    exit;
}

?>