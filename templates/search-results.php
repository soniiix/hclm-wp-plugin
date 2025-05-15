<?php get_header(); 

// Load CSS style
wp_enqueue_style('hclm-search-results-style', plugin_dir_url(__FILE__) . '../assets/css/search-results.css');
?>

<div class="search-results">
    <h1>Résultats de recherche pour "<?php echo get_search_query(); ?>"</h1>
    <span>18 résultats</span>

    <!-- Search if any pages match the query -->
    <?php if (have_posts()) { ?>
        <div class="hclm-search-category-wrapper">
            <h2 class="hclm-search-category-title">Pages</h2>
            <ul class="hclm-search-category-list">
                <?php while (have_posts()) : the_post(); ?>
                    <li class="hclm-search-result-row">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="hclm-result-thumbnail">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="hclm-result-content">
                                <h3 class="hclm-result-title"><?php the_title(); ?></h3>
                                <p class="hclm-result-excerpt">Lorem ipsum dolor sit amet consectetur adipisicing elit.<?php //the_excerpt(); ?></p>
                            </div>
                            <div class="hclm-result-arrow">→</div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php } else { ?>
        <p>Aucun résultat dans les pages.</p>
    <?php } ?>

    <!-- Search if any newsletter match the query -->
    <?php
    $query = get_search_query();
    $upload_dir = wp_upload_dir();
    $base = $upload_dir['basedir'] . '/hclm/bulletins';
    $base_url = $upload_dir['baseurl'] . '/hclm/bulletins';

    $results = [];
    // Read all txt files and check if the term is present
    foreach (glob($base . '/B*/B*.txt') as $txt_file) {
        $content = file_get_contents($txt_file);
        if (stripos($content, $query) !== false) {
            $bulletin = basename($txt_file, '.txt');
            $folder = basename(dirname($txt_file));
            $results[] = [
                'title' => 'Bulletin n°' . str_replace("B", "", $bulletin),
                "image" => $base_url . '/' . $folder . '/' . $bulletin . '_Couverture.png',
                'url'   => $base_url . '/' . $folder . '/' . $bulletin . '.pdf'
            ];
        }
    }

    // Display results : a list of each newsletter
    if (!empty($results)) { ?>
    <div class="hclm-search-category-wrapper">
        <h2 class="hclm-search-category-title">Bulletins</h2>
        <ul class="hclm-search-category-list">
            <?php foreach ($results as $item) { ?>
                <li class="hclm-search-result-row">
                    <a href="<?php echo esc_url($item['url']); ?>" target="_blank">
                        <div class="hclm-result-thumbnail">
                            <img src="<?php echo $item['image'] ?>">
                        </div>
                        <div class="hclm-result-content">
                            <h3 class="hclm-result-title"><?php echo esc_html($item['title']); ?></h3>
                            <p class="hclm-result-excerpt">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                        </div>
                        <div class="hclm-result-arrow">→</div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <?php } else { ?>
        <p>Aucun résultat trouvé dans les bulletins.</p>
    <?php } ?>
</div>

<?php get_footer(); ?>
