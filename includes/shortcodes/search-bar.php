<?php

function search_bar_shortcode() {
    wp_enqueue_style('hclm-search-bar-style', plugin_dir_url(__FILE__) . '../../assets/css/search-bar.css');

    ob_start();
    ?>

    <div class="hclm-search-bar-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Rechercher" class="hclm-search-bar" />
    </div>

    <?php
    return ob_get_clean();
}

?>