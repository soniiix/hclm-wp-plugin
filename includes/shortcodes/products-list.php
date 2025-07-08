<?php

function products_list_shortcode() {
    // Enqueue styles and scripts for the products list
    wp_enqueue_style('hclm-products-list-style', plugin_dir_url(__FILE__) . '../../assets/css/products-list.css');
    wp_enqueue_script('hclm-products-list-js', plugin_dir_url(__FILE__) . '../../assets/js/products-list.js', [], false, true);

    // Get sorting option from URL
    $sort = $_GET['sort'] ?? 'date_desc';

    // Set up WP_Query arguments based on sorting
    switch ($sort) {
        case 'price_asc':
            $orderby = 'meta_value_num';
            $order = 'ASC';
            $meta_key = '_price';
            break;
        case 'price_desc':
            $orderby = 'meta_value_num';
            $order = 'DESC';
            $meta_key = '_price';
            break;
        case 'date_asc':
            $orderby = 'date';
            $order = 'ASC';
            $meta_key = '';
            break;
        default:
            $orderby = 'date';
            $order = 'DESC';
            $meta_key = '';
            break;
    }

    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'orderby' => $orderby,
        'order' => $order,
    ];

    if (!empty($meta_key)) {
        $args['meta_key'] = $meta_key;
    }
    
    $query = new WP_Query($args);

    ob_start();
    ?>
    <!-- Search bar and sorting options -->
    <div class="hclm-products_search-row">
        <div class="hclm-products-searchbar-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" placeholder="Rechercher un ouvrage" class="hclm-products-searchbar" />
        </div>
        <form method="get" class="hclm-products_search-row-sort">
            <span>Trier</span>
            <div>
                <select name="sort" onchange="this.form.submit()">
                    <option value="date_desc" <?= ($_GET['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Du plus récent au plus ancien</option>
                    <option value="date_asc" <?= ($_GET['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Du plus ancien au plus récent</option>
                    <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Par prix (croissant)</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Par prix (décroissant)</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Products grid -->
    <?php
    echo '<div class="products-grid">';
    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());
        $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        $link = get_permalink();
        ?>
        <div class="product-card">
            <a href="<?= esc_url($link); ?>" class="product-link" target="_blank" rel="noopener noreferrer">
                <div class="product-image" style="background-image: url('<?= esc_url($img); ?>')"></div>
            </a>
            <div class="product-content">
                <h3 class="product-title"><?= wp_trim_words(get_the_title(), 11); ?></h3>
                <div class="product-subcontent">
                    <span class="product-price"><?php echo $product->get_price(); ?>&nbsp;€</span>
                    <p class="product-stock <?= $product->get_stock_status(); ?>">
                        <?= ($product->get_stock_status() == "outofstock") 
                            ? "<i class='far fa-times-circle'></i>&nbsp;Épuisé" 
                            : "<i class='far fa-check-circle'></i>&nbsp;En stock"; ?>
                    </p>

                    <a href="<?= esc_url($link); ?>" class="product-button">Voir les détails</a>
                </div>
                
            </div>
        </div>
        <?php
    }
    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}
?>