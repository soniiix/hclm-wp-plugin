<?php 

function hclm_admin_menu () {
    add_menu_page(
        'HCLM - Gestion',
        'Gestion HCLM',
        'manage_options',
        'hclm-manage',
        'hclm_index_admin_page',
        'dashicons-database',
        27
    );
}

?>