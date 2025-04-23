<?php

/**
 * Displays the newsletters page.
 *
 * @return string HTML content.
 */
function newsletters_shortcode() {
    // Load CSS style
    wp_enqueue_style('hclm-signup-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters.css');

    ob_start();
    ?>

    <div class="description-section">
        <h1 class="page-title">Bulletins</h1>
        <span>
            Depuis sa création, HCLM a édité 75 bulletins d'environ 52 pages à raison de deux par an. Ces bulletins sont constitués d'articles écrits par des membres de l'association à partir de documents d'archives.
            <br>
            Chaque bulletin comporte au moins un article sur chacune des huit communes constitutives de l'actuel collège.
            <br><br>
            <?php 
            if (!is_user_logged_in()) {
                echo "L'accès à ces bulletins est réservé aux adhérents. Pour les non adhérents et à titre de démonstration, HCLM vous propose de consulter l'intégralité d'un bulletin ainsi que la table des matières des autres bulletins.";
            } 
            else{
                echo "Vous trouverez ci-dessous tous les bulletins.";
            }
            ?>
        </span>
    </div>
    
    <?php
    if (!is_user_logged_in()) {?>
        <div class="complete-newsletter-section">
            <h2>Bulletin n°68</h2>
            <div class="pdf-container">
                <?php echo do_shortcode('[dflip id="826"][/dflip]'); ?>
            </div>
        </div>

        <div class="shortcode-container">
            <h2>Consulter le sommaire d'un bulletin</h2>
            <?php echo do_shortcode('[hclm_newsletter_summaries]'); ?>
        </div>
    <?php } 
        if (is_user_logged_in()){
            echo do_shortcode('[hclm_newsletter_list]');
        }
    ?>

    <?php
    return ob_get_clean();
}

?>