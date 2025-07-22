<?php

/**
 * Displays the newsletters page.
 *
 * @return string HTML content.
 */
function newsletters_shortcode() {
    // Load CSS style
    wp_enqueue_style('hclm-newsletters-style', plugin_dir_url(__FILE__) . '../../../assets/css/newsletters.css');
    ob_start();
    ?>
        <div class="description-section">
            <h1 class="page-title">Bulletins</h1>
            <span>
            Depuis sa création, HCLM a édité 75 bulletins d'environ 52 pages à raison de deux par an. Ces bulletins sont constitués d'articles écrits par des membres de l'association à partir de documents d'archives.
                <br>
                Chaque bulletin comporte au moins un article sur chacune des huit communes constitutives de l'actuel collège.
                <br><br>
                Vous trouverez ci-dessous tous les bulletins.
                <?php if(!is_user_logged_in()) { ?>
                    Pour les non adhérents, HCLM vous propose de consulter la table des matières de chaque bulletin. Pour consulter l'intégralité d'un bulletin, vous devez être adhérent et vous connecter. À titre de démonstration, le <u style="text-decoration-thickness: 2px; text-underline-offset: 3px;">bulletin n°72</u> est accessible en version complète.
                <?php } ?>
            </span>
        </div>
        
        <!-- Display the newsletter list -->
        <?php echo do_shortcode('[hclm_newsletter_list]');

    return ob_get_clean();
}
?>