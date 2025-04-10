<?php
/*
Plugin Name: HCLM
Description: Plugin personnalisé pour intégrer diverses fonctionnalités au site de l'association HCLM.
Version: 1.0
Author: Quentin COUZINET
*/

define('NEWSLETTERS_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/hclm/bulletins/');
define("NEWSLETTERS_URL", home_url('/wp-content/uploads/hclm/bulletins/'));

add_shortcode('newsletter_summaries', function () {
    $newsletter_selected = isset($_GET['newsletter-select']) && !empty($_GET['newsletter-select']);

    $selected_pdf_url = $newsletter_selected 
        ? esc_url($_GET['newsletter-select'])
        : NEWSLETTERS_URL . "B45/B45_Sommaire.pdf";

    $folders = scandir(NEWSLETTERS_FOLDER);

    $options = '';

    foreach ($folders as $folder) {
        if (str_starts_with($folder, "B")){
            $pdf_path = NEWSLETTERS_FOLDER . $folder . '/' . $folder . '_Sommaire.pdf';
            $pdf_url = NEWSLETTERS_URL . $folder . '/' . $folder . '_Sommaire.pdf';

            if (file_exists($pdf_path)) {
                $is_selected = ($selected_pdf_url === esc_url($pdf_url)) ? 'selected' : '';
                $options .= '<option value="' . esc_url($pdf_url) . '" ' . $is_selected . '>Bulletin n°' . esc_html(str_replace("B", "", $folder)) . '</option>';
            }
        }
    }

    $output = '
        <form method="GET" action="">
            <div style="display: flex; flex-direction: row; align-items: center; gap: 15px;">
                <span>Sélectionnez un bulletin ci-contre pour consulter son son sommaire :</span>
                <select style="width: 150px;" name="newsletter-select" onchange="this.form.submit()">
                    ' . $options . '
                </select>
            </div>
        </form>
    ';

    $output .= '
        <div style="margin-top: 30px; height: 600px;">
            <div id="flipbook_container" style="max-height: 600px !important;  scroll-margin: 90px;" class="_df_book" source="' . $selected_pdf_url . '"></div>
        </div>
    ';

    if ($newsletter_selected){
        $output .= '
            <script>
                document.getElementById("flipbook_container").scrollIntoView();
            </script>
        ';
    }

    return $output;

});

?>