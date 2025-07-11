<?php

require_once plugin_dir_path(__FILE__) . 'utils/is-membership-active.php';

function paywall_content_shortcode($atts, $content = null) {
    wp_enqueue_style('hclm-paywall-content-style', plugin_dir_url(__FILE__) . '../../assets/css/paywall-content.css');
    
    // Retrieve post thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail = get_the_post_thumbnail(null, 'medium_large');
    }

    if (is_user_logged_in()) {
        if (!hclm_is_membership_active()) {
            return '
                <div class="article-preview">
                    <div class="article-fadeout">
                        ' . $thumbnail . do_shortcode($content) . '
                    </div>
                    <div class="paywall-box">
                        <div class="paywall-header">
                            Votre adhésion est inactive
                        </div>
                        <div class="paywall-content">
                            <span class="paywall-title"><i class="fas fa-lock"></i>&nbsp;Envie de lire la suite ?</span>
                            <p>Renouvelez votre adhésion et accédez à l\'ensemble du contenu.</p>
                            <a href="/espace-adherent" class="paywall-button">Renouveler mon adhésion</a>
                        </div>
                    </div>
                </div>
            ';
        }
        return '<div class="article-full">' . do_shortcode($content) . '</div>';
    } else {
        return '
            <div class="article-preview">
                <div class="article-fadeout">
                    ' . $thumbnail . do_shortcode($content) . '
                </div>
                <div class="paywall-box">
                    <div class="paywall-header">
                        Déjà adhérent ? <a href="' . esc_url( home_url('/connexion?redirect_to=' . urlencode(get_permalink())) ) . '">Connectez-vous</a>
                    </div>
                    <div class="paywall-content">
                        <span class="paywall-title"><i class="fas fa-lock"></i>&nbsp;Envie de lire la suite ?</span>
                        <p>Rejoignez l\'association et accédez à l\'ensemble du contenu.</p>
                        <a href="/adherer" class="paywall-button">J\'adhère</a>
                    </div>
                </div>
            </div>
        ';
    }
}

?>