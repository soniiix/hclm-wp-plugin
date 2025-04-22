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
            $pdf_url = NEWSLETTERS_URL . "$folder/$folder.pdf";
            $cover_url = NEWSLETTERS_URL . "B0/B0_Couverture.png";

            $cards_html .= '
                <div class="newsletter-card" data-pdf="' . esc_url($pdf_url) . '">
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
                <span class="popup-close">&times;</span>
                <div id="newsletter-viewer" class="_df_book" source=""></div>
            </div>
        </div>
    ';

    return $cards_html;
}

?>