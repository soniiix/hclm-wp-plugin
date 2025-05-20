<?php

function search_bar_shortcode() {
    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-search-bar-style', plugin_dir_url(__FILE__) . '../../assets/css/search-bar.css');
    wp_enqueue_script('hclm-search-bar-script', plugin_dir_url(__FILE__) . '../../assets/js/search-bar.js', [], false, true);

    ob_start();
    ?>

    <!-- Search bar in header -->
    <div class="hclm-search-bar-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <div class="hclm-search-bar">Rechercher</div>
    </div>

    <!-- Popup with advanced search -->
    <div id="hclm-search-popup" class="hclm-popup-overlay" style="display: none;">
        <div class="hclm-popup-content">

            <form method="GET" action="/">
                <div class="hclm-popup-search-bar-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" placeholder="Rechercher sur le site" name="s" class="hclm-popup-search-bar" required/>
                </div>

                <div class="hclm-popup-advanced-search">
                    <span class="hclm-popup-title">Recherche avancée</span>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label for="hclm-keywords-tagbox">Mots-clés</label>
                        <div id="hclm-keywords-tagbox" class="hclm-keywords-tagbox">
                            <input type="text" id="hclm-keywords-input" placeholder="Entrer un mot-clé" autocomplete="off" />
                        </div>
                        <input type="hidden" name="keywords" id="hclm-keywords-hidden" />
                    </div>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label for="hclm-advanced-search-filter-input">Type de contenu</label>
                        <select id="hclm-advanced-search-filter-input" name="type">
                            <option value="">Tout</option>
                            <option value="post">Bulletins</option>
                            <option value="page">Pages</option>                    
                            <option value="page">Évenements</option>
                            <option value="page">Visites automnales</option>
                        </select>
                    </div>
                </div>
            </form>

            <div class="hclm-popup-action-row">
                <div class="hclm-popup-close-btn">Fermer</div>
                <div></div>
            </div>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}

?>