<?php

/**
 * Generates a cover image for a PDF file using Imagick.
 *
 * @param string $pdf_path Path to the PDF file.
 * @return string|null URL of the generated cover image or null if an error occurs.
 * @throws Exception If Imagick is not installed or the PDF file does not exist.
 */
function hclm_get_pdf_cover($pdf_path) {
    if (!extension_loaded('imagick') || !file_exists($pdf_path)) {
        return null;
    }

    $upload_dir = wp_upload_dir();
    $covers_dir = $upload_dir['basedir'] . '/hclm/comptes-rendus/couvertures/';
    $covers_url = $upload_dir['baseurl'] . '/hclm/comptes-rendus/couvertures/';

    // Create the directory if it doesn't exist
    if (!file_exists($covers_dir)) {
        wp_mkdir_p($covers_dir);
    }

    // Set the image name from the PDF file name
    $pdf_filename = basename($pdf_path);
    $cover_filename = pathinfo($pdf_filename, PATHINFO_FILENAME) . '.jpg';
    $cover_path = $covers_dir . $cover_filename;
    $cover_url = $covers_url . $cover_filename;

    // Return the cover URL if it already exists
    if (file_exists($cover_path)) {
        return $cover_url;
    }

    // Else, generate the cover image from the first page of the PDF
    try {
        $imagick = new Imagick();
        $imagick->setResolution(150, 150);
        $imagick->readImage($pdf_path . '[0]');
        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompressionQuality(85);
        $imagick->writeImage($cover_path);

        $imagick->clear();
        $imagick->destroy();

        return $cover_url;
    } catch (Exception $e) {
        error_log('Erreur de génération de la couverture PDF : ' . $e->getMessage());
        return null;
    }
}

?>