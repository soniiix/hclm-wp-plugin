<?php

function newsletters_list_shortcode() {
    define('NEWSLETTERS_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/hclm/bulletins/');
    define('NEWSLETTERS_URL', home_url('/wp-content/uploads/hclm/bulletins/'));

    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-newsletters-list-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters-list.css');
    wp_enqueue_script('hclm-newsletter-popup-script', plugin_dir_url(__FILE__) . '../../../assets/js/newsletter-popup.js', array(), null, true);
    wp_enqueue_script('pdfjs', 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js', [], null, true);



    $folders = scandir(NEWSLETTERS_FOLDER);
    $cards_html = '<div class="newsletter-cards">';

    foreach ($folders as $folder) {
        if (preg_match('/^B\d+$/', $folder)) {
            $summary_url = NEWSLETTERS_URL . "B0/B0_TableMatieres.pdf";
            $cover_url = NEWSLETTERS_URL . "$folder/$folder" . "_Couverture.png";

            $cards_html .= '
                <div 
                    class="newsletter-card"
                    data-summary-url="' . esc_url($summary_url) . '"
                    data-newsletter-number="' . esc_html(str_replace("B", "", $folder))  .'"
                >
                    <img src="' . esc_url($cover_url) . '" alt="Couverture du bulletin ' . esc_html(str_replace("B", "", $folder)) . '">
                    <h3 class="newsletter-name">Bulletin n°' . esc_html(str_replace("B", "", $folder)) . '</h3>
                </div>
            ';
        }
    }

    $cards_html .= '</div>';

    $cards_html .= '
        <div id="newsletter-popup" class="hidden">
            <div class="popup-overlay"></div>
            <div class="popup-content">
                <button class="popup-close"><i class="fas fa-times"></i></button>

                <div id="newsletter-title" style="margin-bottom: 15px; font-size: 20px; font-weight: 600;">
                    Bulletin n°XX
                </div>
                <div class="popup-description">
                    Voici la table des matières de ce bulletin.
                </div>

                <div id="newsletter-viewer" style="display: flex; gap: 10px; justify-content: center;">
                    <canvas id="pdf-page1" style="max-width: 48%; border: 1px solid #ccc;"></canvas>
                    <canvas id="pdf-page2" style="max-width: 48%; border: 1px solid #ccc;"></canvas>
                </div>


                <div class="newsletter-button-container">
                    <a href="/bulletins" class="newsletter-button">Consulter le bulletin entier</a>
                <div>
            </div>
        </div>
    ';

    return $cards_html;
}

?>