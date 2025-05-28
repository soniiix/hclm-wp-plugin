<?php

function newsletters_list_shortcode() {
    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-newsletters-list-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters-list.css');
    wp_enqueue_script('hclm-newsletter-popup-script', plugin_dir_url(__FILE__) . '../../../assets/js/newsletter-popup.js', array(), null, true);

    // Retrieve all newsletters
    $args = [
        'post_type' => 'bulletin',
        'posts_per_page' => -1
    ];
    $newsletters = get_posts($args);

    // Handle case when no newsletters are found
    if (empty($newsletters)) return '<p>Aucun bulletin trouvé.</p>';

    // Sort newsletters by title
    usort($newsletters, function ($a, $b) {
        $numA = intval(preg_replace('/\D/', '', $a->post_title));
        $numB = intval(preg_replace('/\D/', '', $b->post_title));
        return $numB <=> $numA;
    });

    $cards_html = '<div class="newsletter-cards">';

    // Loop through each newsletter and generate HTML
    foreach ($newsletters as $post) {
        $bulletin_num = preg_replace('/[^0-9]/', '', $post->post_title); // Exctract the number from the title

        $pdf_url = get_post_meta($post->ID, 'pdf_url', true);
        $cover_url = get_the_post_thumbnail_url($post->ID, 'medium');
        $summary_url = get_post_meta($post->ID, 'summary_url', true);

        if (!$summary_url) continue;
    
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
                        <div class="_df_button" source="' . esc_url($pdf_url) . '">
                            <div class="newsletter-button">Consulter le bulletin entier</div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    return $cards_html . '</div>';
}

?>