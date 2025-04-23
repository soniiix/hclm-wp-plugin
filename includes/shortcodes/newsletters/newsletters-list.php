<?php

function hclm_newsletter_cards_shortcode() {
    define('NEWSLETTERS_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/hclm/bulletins/');
    define('NEWSLETTERS_URL', home_url('/wp-content/uploads/hclm/bulletins/'));

    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-newsletters-list-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters-list.css');
    wp_enqueue_script('hclm-newsletter-popup-script', plugin_dir_url(__FILE__) . '../../../assets/js/newsletter-popup.js', array(), null, true);


    $folders = scandir(NEWSLETTERS_FOLDER);
    $cards_html = '<div class="newsletter-cards">';

    foreach ($folders as $folder) {
        if (preg_match('/^B\d+$/', $folder)) {
            $pdf_url = NEWSLETTERS_URL . "$folder/" . $folder . "_TableMatieres.pdf";
            $cover_url = NEWSLETTERS_URL . "$folder/$folder" . "_Couverture.png";

            $cards_html .= '
                <div class="newsletter-card" data-pdf="' . esc_url($pdf_url) . '" data-bulletin="' . esc_html(str_replace("B", "", $folder))  .'">
                    <img src="' . esc_url($cover_url) . '" alt="Couverture Bulletin ' . esc_html(str_replace("B", "", $folder)) . '">
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
                <button class="popup-close">x</button>

                <div id="newsletter-title" style="margin-bottom: 15px; font-size: 20px; font-weight: bold;">
                    Bulletin n°XX - Table des matières ci-dessous
                </div>

                <iframe id="newsletter-viewer" width="100%" height="600px" frameborder="0"></iframe>
            </div>
        </div>
    ';

    return $cards_html;
}

?>