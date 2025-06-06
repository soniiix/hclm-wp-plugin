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
                    <input type="text" placeholder="Rechercher sur le site" name="s" class="hclm-popup-search-bar"/>
                </div>

                <div class="hclm-popup-advanced-search">
                    <span class="hclm-popup-title">Recherche avancée</span>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label id="label-keywords" for="hclm-keywords-input">Mots-clés</label>
                        <div id="hclm-keywords-tagbox" class="hclm-keywords-tagbox">
                            <input
                                type="text"
                                id="hclm-keywords-input"
                                placeholder="Entrer des mots-clés"
                                autocomplete="off"
                                aria-labelledby="label-keywords"
                                aria-describedby="help-keywords"
                            />
                        </div>
                        <span class="hclm-help-message" id="help-keywords" data-tagbox="hclm-keywords-input">
                            Validez un mot en tapant sur « Entrée » ou « , »
                        </span>
                        <input type="hidden" name="keywords" id="hclm-keywords-hidden" />
                    </div>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label id="label-exclude" for="hclm-aside-exclude-input">Mots à exclure</label>
                        <div id="hclm-aside-exclude-tagbox" class="hclm-keywords-tagbox">
                            <input
                                type="text"
                                id="hclm-aside-exclude-input"
                                placeholder="Entrer des mots à exclure"
                                autocomplete="off"
                                aria-labelledby="label-exclude"
                                aria-describedby="help-exclude"
                            />
                        </div>
                        <span class="hclm-help-message" id="help-exclude" data-tagbox="hclm-aside-exclude-input">
                            Validez un mot en tapant sur « Entrée » ou « , »
                        </span>
                        <input type="hidden" name="exclude" id="hclm-aside-exclude-hidden" />
                    </div>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label for="hclm-advanced-search-filter-input">Type de contenu</label>
                        <select id="hclm-advanced-search-filter-input" name="type">
                            <option value="">Tout</option>
                            <option value="newsletters">Bulletins</option>
                            <option value="pages">Pages</option>                    
                            <option value="events">Évenements</option>
                            <option value="fall-visits">Visites automnales</option>
                            <option value="products">Ouvrages</option>
                        </select>
                    </div>
                    <div class="hclm-popup-advanced-search-filter-group">
                        <label>Période</label>
                        <div class="hclm-date-range-wrapper popup">
                            <input type="date" name="start_date" value="<?php echo esc_attr($_GET['start_date'] ?? '') ?>" />
                            <input type="date" name="end_date" value="<?php echo esc_attr($_GET['end_date'] ?? '') ?>" />
                        </div>
                    </div>
                </div>

                <div class="hclm-popup-action-row">
                <div class="hclm-popup-close-btn">Fermer</div>
                <button type="submit" class="hclm-popup-submit-btn">Lancer la recherche</button>
            </div>
            </form>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}

?>