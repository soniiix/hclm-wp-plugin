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
    <div class="pms-field pms-field-address">
        <div class="pms-field-streetaddress">
            <label for="user_address">Adresse *</label>
            <input type="text" name="user_address" id="user_address" autocomplete="address-line1" required>
        </div>
        <div>
            <label for="user_city">Ville *</label>
            <input type="text" name="user_city" id="user_city" autocomplete="address-level2" required>
        </div>
        <div>
            <label for="user_postal_code">Code postal *</label>
            <input type="text" name="user_postal_code" id="user_postal_code" autocomplete="postal-code" required>
        </div>
    </div>
    <div class="pms-field">
        <label for="user_address_2">Complément d'adresse</label>
        <input type="text" name="user_address_2" id="user_address_2" autocomplete="address-line2">
    </div>
    <div class="pms-field">
        <label for="user_phone">Téléphone *</label>
        <input type="tel" name="user_phone" id="user_phone" autocomplete="tel" required>
    </div>
    <?php
}

// Save custom fields data after user registration in PMS
add_action( 'pms_register_form_after_create_user', 'hclm_save_custom_user_meta', 10, 1 );
function hclm_save_custom_user_meta( $user_data ) {
    if ( isset( $user_data['user_id'] ) ) {
        $user_id = $user_data['user_id'];

        $pms_fields = [];

        if ( isset($_POST['user_address']) ) {
            $pms_fields['pms_billing_address'] = sanitize_text_field($_POST['user_address']);
        }

        // Ensure the address 2 field is saved in both PMS and WooCommerce
        if ( isset($_POST['user_address_2']) ) {
            $pms_fields['pms_billing_address_2'] = sanitize_text_field($_POST['user_address_2']);
            update_user_meta( $user_id, 'billing_address_2', sanitize_text_field($_POST['user_address_2']) );
        }

        if ( isset($_POST['user_city']) ) {
            $pms_fields['pms_billing_city'] = sanitize_text_field($_POST['user_city']);
        }

        if ( isset($_POST['user_postal_code']) ) {
            $pms_fields['pms_billing_zip'] = sanitize_text_field($_POST['user_postal_code']);
        }

        // Ensure the phone number is saved in both PMS and WooCommerce
        if ( isset($_POST['user_phone']) ) {
            $pms_fields['pms_billing_phone'] = sanitize_text_field($_POST['user_phone']);
            update_user_meta( $user_id, 'billing_phone', sanitize_text_field($_POST['user_phone']) );
        }

        // Use the native PMS method to handle WooCommerce sync
        if ( function_exists('pms_update_user_account_data') ) {
            pms_update_user_account_data( $pms_fields, $user_id, 'pms_form' );
        }

        // Manually update user meta for PMS fields
        foreach ( $pms_fields as $key => $value ) {
            update_user_meta( $user_id, $key, $value );
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

add_action('admin_menu', function() {
    remove_menu_page('woocommerce-marketing'); // Remove the WooCommerce marketing menu item
    remove_menu_page('wpr-addons'); // Remove the WPR Addons menu item
    remove_menu_page('edit-comments.php'); // Remove the comments menu item
}, 99);

// Remove the comments tab from the admin bar
add_action( 'wp_before_admin_bar_render', function(){
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
});

// Initialize the session if it is not already started. It is necessary for the login error handling.
add_action('init', function () {
    if (!session_id()) {
        session_start();
    }
});

// Hide attachment pages and redirect to the home page
add_action('template_redirect', function() {
    if (is_attachment()) {
        wp_redirect(home_url(), 301);
        exit;
    }
});

// Show a success message after login
add_action('wp_footer', function() {
    if (isset($_GET['login_success']) && $_GET['login_success'] == '1') {
        echo '
        <div class="hclm-success-message"">
            Connexion réussie ! Vous pouvez maintenant accéder à votre&nbsp;
            <a href="/espace-adherent">
                espace adhérent
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.3153 16.6681C15.9247 17.0587 15.9247 17.6918 16.3153 18.0824C16.7058 18.4729 17.339 18.4729 17.7295 18.0824L22.3951 13.4168C23.1761 12.6357 23.1761 11.3694 22.3951 10.5883L17.7266 5.9199C17.3361 5.52938 16.703 5.52938 16.3124 5.91991C15.9219 6.31043 15.9219 6.9436 16.3124 7.33412L19.9785 11.0002L2 11.0002C1.44772 11.0002 1 11.4479 1 12.0002C1 12.5524 1.44772 13.0002 2 13.0002L19.9832 13.0002L16.3153 16.6681Z" fill="#0e5c25"></path> </g></svg>
            </a> 
        </div>

        <style>
            .hclm-success-message {
                background-color: #e0fce6;
                color: #0e5c25;
                border: 1px solid #a4e5b3;
                padding: 1rem 1.25rem;
                border-radius: 12px;
                font-weight: 500;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
                display: flex;
                align-items: center;
                margin-bottom: 20px;
                font-size: 16px;
                animation: fadeInSlide 0.4s ease-out;
                position:fixed;
                bottom:20px;
                right:20px;
                z-index:9999;
            }
            .hclm-success-message a {
                display: flex;
                align-items: center;
                color: #0e5c25 !important;
                font-weight: 500 !important;
                gap: 5px;
                box-shadow: inset 0 -1px 0 0 #0e5c25;
            }
            .hclm-success-message a:hover {
                color: #0e5c25 !important;
            }
            .hclm-success-message a svg {
                height: 17px !important;
            }

            @media (max-width: 600px) {
                .hclm-success-message {
                    left: 10px;
                    right: 10px;
                    bottom: 10px;
                    max-width: calc(100% - 20px);
                    width: auto;
                    border-radius: 10px;
                    align-items: flex-start;
                    flex-direction: row;
                    flex-wrap: wrap;
                    font-size: 15px;
                }

                .hclm-success-message a {
                    display: inline-flex;
                    margin-top: 0;
                    gap: 6px;
                }
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const params = new URLSearchParams(window.location.search);
                if (params.has("login_success")) {
                    params.delete("login_success");
                    const newUrl = window.location.pathname + (params.toString() ? "?" + params.toString() : "");
                    window.history.replaceState({}, "", newUrl);
                }
            });
        </script>
        ';
    }
});

?>