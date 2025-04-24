<?php

function newsletters_list_shortcode() {
    define('NEWSLETTERS_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/hclm/bulletins/');
    define('NEWSLETTERS_URL', home_url('/wp-content/uploads/hclm/bulletins/'));

    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-newsletters-list-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters-list.css');
    wp_enqueue_script('hclm-newsletter-popup-script', plugin_dir_url(__FILE__) . '../../../assets/js/newsletter-popup.js', array(), null, true);

    $folders = scandir(NEWSLETTERS_FOLDER);
    $cards_html = '<div class="newsletter-cards">';

    sort($folders, SORT_NATURAL | SORT_FLAG_CASE);

    foreach ($folders as $folder) {
        if (preg_match('/^B\d+$/', $folder)) {
            if (!file_exists(NEWSLETTERS_FOLDER . "$folder/{$folder}_TableMatieres.pdf")) {
                $summary_url = NEWSLETTERS_URL . "$folder/{$folder}_Sommaire.pdf";
            } else {
                $summary_url = NEWSLETTERS_URL . "$folder/{$folder}_TableMatieres.pdf";
            }
            $cover_url = NEWSLETTERS_URL . "$folder/{$folder}_Couverture.png";
            $bulletin_num = str_replace("B", "", $folder);
    
            $cards_html .= '
                <div 
                    class="newsletter-card" 
                    data-target="#popup-b' . $bulletin_num . '"
                >
                    <img src="' . esc_url($cover_url) . '" alt="Couverture du bulletin n°' . esc_html($bulletin_num) . '">
                    <h3 class="newsletter-name">Bulletin n°' . esc_html($bulletin_num) . '</h3>
                </div>
    
                <div id="popup-b' . $bulletin_num . '" class="newsletter-popup hidden">
                    <div class="popup-overlay"></div>
                    <div class="popup-content">
                        <button class="popup-close"><i class="fas fa-times"></i></button>
    
                        <div class="popup-title">Bulletin n°' . $bulletin_num . '</div>
                        <div class="popup-description">
                            Voici la table des matières de ce bulletin. Pour consulter l\'intégralité du bulletin, cliquez sur le bouton ci-dessous.
                        </div>
    
                        <div class="popup-flipbook">
                            <div class="_df_book" style="max-height: 600px !important;" source="' . esc_url($summary_url) . '"></div>
                        </div>
    
                        <div class="newsletter-button-container">
                            <a href="/bulletins?view=' . esc_attr($folder) . '" class="newsletter-button">Consulter le bulletin entier</a>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    return $cards_html . '</div>';
}
?>