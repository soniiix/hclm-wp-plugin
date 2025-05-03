<?php

function paywall_content_shortcode($atts, $content = null) {
    wp_enqueue_style('hclm-signup-style', plugin_dir_url(__FILE__) . '../../assets/css/paywall-content.css');
    
    if (is_user_logged_in()) {
        return '<div class="article-full">' . do_shortcode($content) . '</div>';
    } else {
        return '
            <div class="article-preview">
                <div class="article-fadeout">
                    ' . do_shortcode($content) . '
                </div>
                <div class="paywall-box">
                    <div class="paywall-header">
                        Déjà membre ? <a href="' . esc_url( home_url('/connexion?redirect_to=' . urlencode(get_permalink())) ) . '">Connectez-vous</a>
                    </div>
                    <div class="paywall-content">
                        <span class="paywall-title"><i class="fas fa-lock"></i>&nbsp;Envie de lire la suite ?</span>
                        <p>Devenez membre de l\'association et accédez à l\'ensemble du contenu.</p>
                        <a href="/adherer" class="paywall-button">J\'adhère</a>
                    </div>
                </div>
            </div>
        ';
    }
}

?>