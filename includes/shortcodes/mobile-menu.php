<?php 

/**
 * Shortcode to display a mobile menu
 * @return string HTML output of the mobile menu
 */
function hclm_mobile_menu_shortcode() {
    // Load CSS stylesheet
    wp_enqueue_style('hclm-mobile-menu-style', plugin_dir_url(__FILE__) . '../../assets/css/mobile-menu.css');
    wp_enqueue_script('hclm-mobile-menu-script', plugin_dir_url(__FILE__) . '../../assets/js/mobile-menu.js', array(), null, true);


    ob_start();
    ?>
    <nav class="hclm-mobile-menu">
        <div role="button" class="hclm-mobile-menu-btn" aria-label="Ouvrir/Fermer le menu">
            <ul class="buns">
                <li class="bun"></li>
                <li class="bun"></li>
            </ul>
        </div>
        <div class="hclm-mobile-menu-content">
            <?php
            // Get and display the primary menu
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'hclm-mobile-menu-list',
                'fallback_cb' => false,
            ));
            ?>

            <div class="hclm-mobile-menu-buttons">
                <div class="hclm-mobile-menu-btn-container">
                    <?php echo do_shortcode('[hclm_login_button]'); // Include the login button shortcode ?>
                </div>

                <?php if (is_user_logged_in()) { ?>
                    <div class="hclm-mobile-menu-btn-container">
                        <a href="<?php echo esc_url(wp_logout_url(home_url('/connexion'))); ?>" class="logout-button" aria-label="Déconnexion">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </nav>
    <?php
    return ob_get_clean();
}