<?php
get_header();

wp_enqueue_style('hclm-fall-visit-style', plugin_dir_url(__FILE__) . '../assets/css/fall-visit.css');

if (have_posts()) :
    while (have_posts()) : the_post(); ?>
        <main class="content">
            <span class="hclm-post-date"><?php echo get_field('date'); ?></span>
            <h3 class="hclm-post-title">Visite à <?php the_title(); ?></h3>

            <?php if (is_user_logged_in()) : ?>
                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <?php
                $pdf_url = get_field('pdf_url');
                if ($pdf_url) :
                    ?>
                    <div class="_df_book dfbook_fall_visit" style="max-height: 600px !important;" source="<?php echo $pdf_url ?>"></div>
                <?php endif; ?>

            <?php else : ?>
                <div class="post-excerpt">
                    <?php
                    $extrait = get_field('extrait');
                    if ($extrait) {
                        echo do_shortcode('[hclm_paywall_content]' . esc_html($extrait) . '[/hclm_paywall_content]');
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