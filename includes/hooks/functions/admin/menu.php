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
    remove_menu_page('woocommerce-marketing');                  // Remove the WooCommerce marketing menu item
    remove_menu_page('wpr-addons');                             // Remove the WPR Addons menu item
    remove_menu_page('edit-comments.php');                      // Remove the comments menu item
    remove_menu_page('filebird-settings');                      // Remove the  FileBird menu item
    remove_menu_page('edit.php');                               // Remove the posts menu item
    remove_menu_page('edit.php?post_type=elementor_library');   // Remove the Elementor library menu item
    remove_menu_page('shopengine-settings');                    // Remove the ShopEngine menu item

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
}

?>