<?php

function fall_visits_shortcode() {
    wp_enqueue_style('hclm-fall-visits-style', plugin_dir_url(__FILE__) . '../../assets/css/fall-visit.css');

    $args = [
        'post_type' => 'visite_automnale',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'date',
        'order' => 'DESC',
    ];
    $query = new WP_Query($args);

    ob_start();
    echo '<div class="visits-grid">';
while ($query->have_posts()) {
    $query->the_post();
    $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
    $link = get_permalink();
    ?>
    <div class="visit-card">
        <div class="visit-image" style="background-image: url('<?= esc_url($img); ?>')"></div>
        <div class="visit-content">
            <h3 class="visit-title"><?= get_the_title(); ?></h3>
            <p class="visit-date"><?= esc_html(get_field('date')); ?></p>
            <a href="<?= esc_url($link); ?>" class="visit-button">Voir les détails</a>
        </div>
    </div>
    <?php
}
echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
?>