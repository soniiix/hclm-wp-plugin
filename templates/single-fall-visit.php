<?php
get_header();

require_once plugin_dir_path(__FILE__) . '../includes/shortcodes/utils/is-membership-active.php';

wp_enqueue_style('hclm-fall-visit-style', plugin_dir_url(__FILE__) . '../assets/css/fall-visit.css');

if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <main class="content">
            <span class="hclm-post-date"><?php echo get_the_date('d/m/Y'); ?></span>
            <h3 class="hclm-post-title">Visite à <?php the_title(); ?></h3>

            <?php if (is_user_logged_in() && (hclm_is_membership_active() || hclm_current_user_has_role(['administrator', 'tresorier', 'secretaire', 'editor']))):
                $base_url = wp_upload_dir()['baseurl'] . '/hclm/visites-automnales/'; // Base URL for fall visits
                $pdf_url = $base_url . get_field('pdf_name'); // Construct the PDF URL
                if ($pdf_url) :
                    ?>
                    <div class="_df_book dfbook_fall_visit" style="max-height: 600px !important;" source="<?php echo esc_url($pdf_url) ?>"></div>
                <?php endif; ?>

            <?php else : ?>
                <div class="post-excerpt">
                    <?php
                    $extract = get_the_content();
                    if ($extract) {
                        echo do_shortcode('[hclm_paywall_content]' . wpautop($extract) . '[/hclm_paywall_content]');
                    } else {
                        echo '<p>Contenu réservé aux adhérents.</p>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </main>
    <?php endwhile;
endif;

get_footer();

?>