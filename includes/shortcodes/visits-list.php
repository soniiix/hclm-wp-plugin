<?php

function fall_visits_shortcode() {
    wp_enqueue_style('hclm-fall-visits-style', plugin_dir_url(__FILE__) . '../../assets/css/fall-visit.css');
    wp_enqueue_script('hclm-member-area-js', plugin_dir_url(__FILE__) . '../../assets/js/fall-visits.js', [], false, true);

    $order = isset($_GET['order']) && $_GET['order'] === 'asc' ? 'ASC' : 'DESC';

    $args = [
        'post_type' => 'visite_automnale',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'date',
        'order' => $order,
    ];
    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="hclm-visits_search-row">
        <div class="hclm-visits-searchbar-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" placeholder="Rechercher une visite" class="hclm-visits-searchbar" />
        </div>
        <form method="get" class="hclm-visits_search-row-sort">
            <span>Trier</span>
            <div>
                <select name="order" onchange="this.form.submit()">
                    <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Du plus récent au plus ancien</option>
                    <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Du plus ancien au plus récent</option>
                </select>
            </div>
        </form>
    </div>
    <?php
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