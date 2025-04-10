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
    $folders = scandir(NEWSLETTERS_FOLDER);

    $options = '';

    foreach ($folders as $folder) {
        if (str_starts_with($folder, "B")){
            $pdf_path = NEWSLETTERS_FOLDER . $folder . '/' . $folder . '.pdf';
            $pdf_url = NEWSLETTERS_URL . $folder . '/' . $folder . '.pdf';

            if (file_exists($pdf_path)) {
                $options .= '<option value="' . esc_url($pdf_url) . '">Bulletin n°' . esc_html(str_replace("B", "", $folder)) . '</option>';
            }
        }
    }

    $output = '
        <form id="form-bulletins" onsubmit="event.preventDefault(); window.open(document.getElementById(\'select-bulletins\').value, \'_blank\');">
            <div style="display: flex; flex-direction: row; align-items: center; gap: 15px;">
                <span>Sélectionnez un bulletin en particulier ci-contre pour consulter son sommaire :</span>
                <select style="width: 150px;" id="select-bulletins" name="bulletin">
                    ' . $options . '
                </select>
            </div>
            <button style="margin-top: 5px;" type="submit">Voir</button>
        </form>
    ';

    return $output;
});


?>