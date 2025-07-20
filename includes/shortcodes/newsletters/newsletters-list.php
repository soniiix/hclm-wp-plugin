<?php

require_once plugin_dir_path(__FILE__) . '../utils/is-membership-active.php';

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
        $cover_url = get_the_post_thumbnail_url($post->ID, 'large');
        $summary_url = get_post_meta($post->ID, 'summary_url', true);

        if (!$summary_url) continue;
    
        $cards_html .= '
            <div 
                class="newsletter-card" 
                data-target="#popup-b' . $bulletin_num . '"
                tabindex="0"
                role="button"
                aria-haspopup="dialog"
                aria-controls="popup-b' . $bulletin_num .'"
                title="Ouvrir le bulletin n°' . $bulletin_num .'"
            >
                <img src="' . esc_url($cover_url) . '" alt="Couverture du bulletin n°' . esc_html($bulletin_num) . '">
                <h3 class="newsletter-name">Bulletin n°' . esc_html($bulletin_num) . '</h3>
            </div>

            <div 
                id="popup-b' . $bulletin_num . '" 
                class="newsletter-popup hidden"
                role="dialog"
                aria-modal="true"
                aria-labelledby="popup-title-' . $bulletin_num .'"
                aria-describedby="popup-desc-' . $bulletin_num . '"
                tabindex="-1"
            >
                <div class="popup-overlay"></div>
                <div class="popup-content">
                    <button class="popup-close" title="Femer le popup" aria-label="Fermer le popup">
                        <svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#FFF"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#FFF" d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z"></path></g></svg>
                    </button>

                    <div class="popup-title" id="popup-title-' . $bulletin_num . '">Bulletin n°' . $bulletin_num . '</div>
                    <div class="popup-description" id="popup-desc-' . $bulletin_num . '">
                        Voici la table des matières de ce bulletin. Pour consulter l\'intégralité du bulletin, cliquez sur le bouton ci-dessous.
                    </div>

                    <div class="popup-flipbook">
                        <div class="_df_book" style="max-height: 600px !important;" source="' . esc_url($summary_url) . '"></div>
                    </div>';

                    // Display conditional button based on user login and membership status. If the user is not logged in, redirect them to the login page.
                    $current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
                    $login_url = home_url('/connexion');
                    $redirect_url = esc_url(add_query_arg('redirect_to', urlencode($current_url), $login_url));

                    if (is_user_logged_in()) {
                        if (!hclm_is_membership_active() && !hclm_current_user_has_role(['administrator', 'tresorier', 'secretaire'])) {
                            // Connected user but membership is inactive
                            $cards_html .= '
                                <div class="newsletter-button-container">
                                    <a href="/espace-adherent" class="newsletter-button newsletter-inactive">
                                        Consulter le bulletin entier
                                    </a>
                                </div>';
                        } else {
                            // Connected user with active membership
                            $cards_html .= '
                                <div class="newsletter-button-container">
                                    <div class="_df_button" source="' . esc_url($pdf_url) . '">
                                        <div class="newsletter-button">Consulter le bulletin entier</div>
                                    </div>
                                </div>';
                        }
                    } else {
                        // Not connected user
                        $cards_html .= '
                            <div class="newsletter-button-container">
                                <a href="' . $redirect_url . '" class="newsletter-button">
                                    Consulter le bulletin entier
                                </a>
                            </div>';
                    }
                $cards_html .= '
                </div>
            </div> 
        ';
    }

    return $cards_html . '</div>';
}

?>