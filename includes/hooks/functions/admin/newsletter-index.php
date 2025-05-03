<?php

function hclm_index_admin_page() {
    if (isset($_POST['hclm_index_text_nonce']) && wp_verify_nonce($_POST['hclm_index_text_nonce'], 'hclm_index_text')) {
        hclm_index_txt_files();
        echo '<div class="notice notice-success"><p>Indexation terminée.</p></div>';
    }

    ?>
    <div class="wrap">
        <h1>Indexer les bulletins</h1>
        <form method="post">
            <?php wp_nonce_field('hclm_index_text', 'hclm_index_text_nonce'); ?>
            <p>Cliquez ci-dessous pour indexer tous les fichiers .txt relatifs aux bulletins PDF.</p>
            <p><input type="submit" class="button button-primary" value="Indexer les fichiers texte"></p>
        </form>
    </div>
    <?php
}

function hclm_index_txt_files() {
    $upload_dir = wp_upload_dir();
    $bulletin_dir = $upload_dir['basedir'] . '/hclm/bulletins';

    $txt_files = glob($bulletin_dir . '/**/*.txt');

    foreach ($txt_files as $txt_path) {
        $filename = basename($txt_path, '.txt');
        $pdf_path = dirname($txt_path) . '/' . $filename . '.pdf';

        $attachment_id = hclm_fake_attachment_id($pdf_path);

        $content = file_get_contents($txt_path);
        if ($content) {
            update_post_meta($attachment_id, 'hclm_pdf_text', $content);
        }
    }
}

function hclm_fake_attachment_id($path) {
    $hash = md5($path);
    $existing = get_posts([
        'post_type' => 'attachment',
        'meta_key' => '_hclm_pdf_hash',
        'meta_value' => $hash,
        'fields' => 'ids',
        'posts_per_page' => 1
    ]);

    if (!empty($existing)) {
        return $existing[0];
    }

    $post_id = wp_insert_post([
        'post_type' => 'attachment',
        'post_title' => basename($path),
        'post_status' => 'inherit'
    ]);

    update_post_meta($post_id, '_hclm_pdf_hash', $hash);
    return $post_id;
}


?>