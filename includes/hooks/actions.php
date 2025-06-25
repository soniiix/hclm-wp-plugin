<?php

require_once plugin_dir_path(__FILE__) . '/functions/handle-login.php';
require_once plugin_dir_path(__FILE__) . '/functions/hide-page.php';
require_once plugin_dir_path(__FILE__) . '/functions/update-user-profile.php';
require_once plugin_dir_path(__FILE__) . '/functions/show-more-user-data.php';
require_once plugin_dir_path(__FILE__) . '/functions/admin/menu.php';
require_once plugin_dir_path(__FILE__) . '/functions/admin/register-post-type.php';

// Handle login form submission
add_action('template_redirect', 'handle_login');

// Display additional user information on the admin page 
add_action( 'show_user_profile', 'show_more_user_data' );
add_action( 'edit_user_profile', 'show_more_user_data' );

// Redirect to the home page depending on whether the user is logged in or not
add_action('template_redirect', function () {
    if (!current_user_can('administrator')) {
        hide_page('espace-adherent', false, '/connexion'); // If not logged in
        hide_page('adherer', true);          // If logged in
        hide_page('connexion', true);        // If logged in
    }
});

// Remove the default WooCommerce shop page
add_action( 'template_redirect', function() {
    if (is_shop()) {
        wp_redirect(home_url('/ouvrages')); // Redirect to the custom shop page
        exit;
    }
});

// Add a plugin-specific tab in the wordpress menu
add_action('admin_menu', 'hclm_admin_menu');

// Remove zoom on hover for product images
add_action( 'wp', function () {
    remove_theme_support('wc-product-gallery-zoom');
}, 100 );

// Update user profile
add_action('admin_post_update_user_profile', 'hclm_update_user_profile');
add_action('admin_post_nopriv_update_user_profile', 'hclm_update_user_profile');

// Display custom fields in the PMS registration form
add_action( 'pms_register_form_after_fields', 'custom_pms_extra_fields' );
function custom_pms_extra_fields() {
    ?>
    <div class="pms-field">
        <label for="user_address">Adresse *</label>
        <input type="text" name="user_address" id="user_address" required>
    </div>
    <div class="pms-field">
        <label for="user_phone">Téléphone *</label>
        <input type="tel" name="user_phone" id="user_phone" required>
    </div>
    <?php
}

// Save custom fields data after user registration in PMS
add_action( 'pms_register_form_after_create_user', 'hclm_save_custom_user_meta', 10, 1 );
function hclm_save_custom_user_meta( $user_data ) {
    if (isset( $user_data['user_id'] ) ) {
        $user_id = $user_data['user_id'];

        if (isset($_POST['user_address']) ) {
            update_user_meta( $user_id, 'billing_address_1', sanitize_text_field($_POST['user_address']) );
            update_user_meta( $user_id, 'shipping_address_1', sanitize_text_field($_POST['user_address']) );
        }

        if (isset($_POST['user_phone']) ) {
            update_user_meta( $user_id, 'billing_phone', sanitize_text_field($_POST['user_phone']) );
            update_user_meta( $user_id, 'shipping_phone', sanitize_text_field($_POST['user_phone']) );
        }

        if ( function_exists('pms_get_member') ) {
            $member = pms_get_member( $user_id );

            if ( $member ) {
                if (isset($_POST['user_address']) ) {
                    update_user_meta( $user_id, 'pms_billing_address_1', sanitize_text_field($_POST['user_address']) );
                }

                if (isset($_POST['user_phone']) ) {
                    update_user_meta( $user_id, 'pms_billing_phone', sanitize_text_field($_POST['user_phone']) );
                }
            }
        }
    }
}

// Make first and last name fields required in the PMS registration form
add_filter( 'pms_register_form_label_first_name', function($attributes){
    return __( 'Prénom *', 'paid-member-subscriptions' );
});
add_filter( 'pms_register_form_label_last_name', function($attributes){
    return __( 'Nom *', 'paid-member-subscriptions' );
});
add_action( 'pms_register_form_validation', function() {
    if(empty( $_POST['first_name']))
		pms_errors()->add( 'first_name', __( 'Veuillez saisir un prénom.', 'paid-member-subscriptions' ) );

	if(empty( $_POST['last_name']))
		pms_errors()->add( 'last_name', __( 'Veuillez saisir un nom.', 'paid-member-subscriptions' ) );
});

// Hide the login field in the PMS registration form and fill it with the email value
add_action( 'wp_footer', function() {
    if (is_page('adherer')) { ?>
        <style>
            .pms-user-login-field { 
                display: none !important; 
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelector('.pms-form-submit')?.addEventListener('click', function () {
                    const email =  document.querySelector('#pms_user_email');
                    if (email && email.value) {
                        const loginField = document.querySelector('.pms-user-login-field input');
                        if (loginField) {
                            loginField.value = email.value;
                        }
                    }
                });
            });
        </script>
    <?php }
});

// Display the last modified date in the appropriate column
add_action('manage_pages_custom_column', function($column_name, $post_id) {
    if ($column_name === 'modified') {
        $modified_date = get_post_modified_time('d/m/Y H:i', false, $post_id);
        echo $modified_date;
    }
}, 10, 2);

// Sort pages by last modified date in the admin area
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->get('post_type') === 'page' && $query->get('orderby') === 'modified') {
        $query->set('orderby', 'modified');
    }
});

// Reduce the width of the "Modifié le" column in the admin area
add_action('admin_head', function() {
    echo '<style>
        .fixed .column-modified {
            width: 10%;
        }
    </style>';
});


?>