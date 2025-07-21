<?php 

/**
 * Customize the admin menu.
 */
function hclm_admin_menu () {
    // Add a custom menu page for the plugin
    add_menu_page(
        'HCLM - Gestion',
        'Gestion HCLM',
        'manage_options',
        'hclm-manage',
        'hclm_index_admin_page',
        'dashicons-database',
        27
    );

    // Remove unnecessary menu items
    remove_menu_page('woocommerce-marketing');                                  // Remove the WooCommerce marketing menu item
    remove_menu_page('wpr-addons');                                             // Remove the WPR Addons menu item
    remove_menu_page('edit-comments.php');                                      // Remove the comments menu item
    remove_menu_page('filebird-settings');                                      // Remove the  FileBird menu item
    remove_menu_page('edit.php');                                               // Remove the posts menu item
    remove_menu_page('edit.php?post_type=elementor_library');                   // Remove the Elementor library menu item
    remove_menu_page('shopengine-settings');                                    // Remove the ShopEngine menu item
    remove_submenu_page('edit.php?post_type=da_image', 'da_upgrade_to_pro');    // Remove the Draw Attention upgrade submenu item

    // Rename specific menu items
    global $menu;
    foreach ($menu as $key => $item) {
        if ($item[2] === 'edit.php?post_type=da_image') {
            $menu[$key][0] = 'Carte interactive'; // Rename the Draw Attention menu item
        }
        if ($item[2] === 'edit.php?post_type=dflip') {
            $menu[$key][0] = 'DearFlip PDF'; // Rename the DearFlip PDF menu item
        }
        if ($item[2] === 'members') {
            $menu[$key][0] = 'Rôles'; // Rename the Members menu item
        }
    }

    // Call the function to restrict admin menus based on user roles
    hclm_restrict_admin_menus();
}

/**
 * Remove specific admin menu and submenu items based on user roles.
 */
function hclm_restrict_admin_menus() {
    if (!is_user_logged_in()) return;

    // For both 'secretaire' and 'tresorier' roles
    if (hclm_current_user_has_role(['secretaire', 'tresorier'])) {
        remove_menu_page('astra');                                                // Remove Astra menu item
        remove_menu_page('woocommerce');                                          // Remove WooCommerce menu item
        remove_menu_page('admin.php?page=wc-settings&tab=checkout&from=PAYMENTS_MENU_ITEM');
        remove_menu_page('admin.php?page=wc-settings&tab=checkout');              // Remove WooCommerce Payments menu item
        remove_submenu_page('edit.php?post_type=product', 'product-reviews');     // Remove WooCommerce product reviews submenu item
        remove_menu_page('elementor');                                            // Remove Elementor menu item
        remove_menu_page('edit.php?post_type=dflip');                             // Remove DearFlip PDF menu item
        remove_menu_page('themes.php');                                           // Remove Themes menu item
        remove_menu_page('tools.php');                                            // Remove Tools menu item
        remove_menu_page('options-general.php');                                  // Remove Settings menu item
        remove_menu_page('edit.php?post_type=acf-field-group');                   // Remove ACF menu item
        remove_submenu_page('forminator', 'forminator_cross_sell');               // Remove Forminator "More free plugins" submenu item
    }

    // For 'secretaire' role only
    if (hclm_current_user_has_role(['secretaire'])) {
        remove_menu_page('members');                                              // Remove Members menu item
        remove_menu_page('wpie-new-export');                                      // Remove WP Imp Exp menu item
    }

    // For 'tresorier' role only
    if (hclm_current_user_has_role(['tresorier'])) {
        remove_menu_page('edit.php?post_type=da_image');                          // Remove Draw Attention menu item
        remove_menu_page('edit.php?post_type=tribe_events');                      // Remove The Events Calendar menu item
    }

    // For 'editor' role only
    if (hclm_current_user_has_role(['editor'])) {
        remove_menu_page('astra');                                                // Remove Astra menu item
        remove_menu_page('elementor');                                            // Remove Elementor menu item
        remove_menu_page('paid-member-subscriptions');                            // Remove Paid Member Subscriptions menu item
        remove_menu_page('edit.php?post_type=acf-field-group');                   // Remove ACF menu item
        remove_menu_page('members');                                              // Remove Members menu item
    }
}

?>