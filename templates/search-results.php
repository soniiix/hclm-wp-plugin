<?php get_header();

require_once plugin_dir_path(__FILE__) . 'utils/get-highlighted-excerpt.php';

// Load CSS style and JavaScript
wp_enqueue_style('hclm-search-results-style', plugin_dir_url(__FILE__) . '../assets/css/search-results.css');
wp_enqueue_script('hclm-search-results-script', plugin_dir_url(__FILE__) . '../assets/js/search-results.js', [], false, true);
?>

<div class="hclm-search-results">
    <h1>Résultats de recherche pour "<?php echo esc_html(get_search_query()); ?>"</h1>
    <span id="hclm-search-results-count">0 résultat</span>

    <!-- Retrieve all search results in the website content -->
    <?php if (have_posts()) {
        $site_results = [];
        while (have_posts()) {
            the_post();
            $site_results[] = [
                'post_type' => get_post_type(),
                'url' => get_the_permalink(),
                'title' => get_the_title(),
                'thumbnail' => get_the_post_thumbnail(get_the_ID(), 'thumbnail'),
                'excerpt' => get_highlighted_excerpt(get_the_excerpt(), get_search_query())
            ];
        }

        // Group posts by their type and display them
        $post_types = array_unique(array_column($site_results, 'post_type'));
        foreach ($post_types as $post_type) {
            ?>
            <div class="hclm-search-category-wrapper">
                <!-- Display the correct label (category) for the post type -->
                <h2 class="hclm-search-category-title">
                    <?php
                    switch ($post_type) {
                        case 'page': echo 'Pages'; break;
                        case 'tribe_events': echo 'Événements'; break;
                        case 'visite_automnale': echo 'Visites automnales'; break;
                        case 'product': echo 'Ouvrages'; break;
                        default: echo 'Autres';
                    } 
                    ?>
                </h2>
                <!-- Display post data -->
                <ul class="hclm-search-category-list">
                    <?php foreach ($site_results as $result) {
                        if ($result['post_type'] === $post_type) { ?>
                            <li class="hclm-search-result-row">
                                <a href="<?php echo esc_url($result['url']); ?>">
                                    <?php if ($result['thumbnail']) { ?>
                                        <div class="hclm-result-thumbnail">
                                            <?php echo $result['thumbnail']; ?>
                                        </div>
                                    <?php } ?>
                                    <div class="hclm-result-content">
                                        <h3 class="hclm-result-title"><?php echo esc_html($result['title']); ?></h3>
                                        <p class="hclm-result-excerpt"><?php echo $result['excerpt']; ?></p>
                                    </div>
                                    <div class="hclm-result-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="24" width="24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </div>
                                </a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
            <?php
        }
    } ?>

    <!-- Retrieve all newsletters that contain the search -->
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
            $newsletter = basename($txt_file, '.txt');
            $folder = basename(dirname($txt_file));
            
            $excerpt = get_highlighted_excerpt($content, $query);

            $results[] = [
                'title' => 'Bulletin n°' . str_replace("B", "", $newsletter),
                "image" => $base_url . '/' . $folder . '/' . $newsletter . '_Couverture.png',
                'url'   => $base_url . '/' . $folder . '/' . $newsletter . '.pdf',
                'excerpt' => $excerpt
            ];
        }
    }

    // Display results : a list of each newsletter with excerpt
    if (!empty($results)) { ?>
        <div class="hclm-search-category-wrapper">
            <h2 class="hclm-search-category-title">Bulletins</h2>
            <ul class="hclm-search-category-list">
                <?php foreach ($results as $item) { ?>
                    <li class="hclm-search-result-row">
                        <a href="<?php echo esc_url($item['url']); ?>" target="_blank">
                            <div class="hclm-result-thumbnail">
                                <img src="<?php echo $item['image'] ?>" class="newsletter-thumbnail">
                            </div>
                            <div class="hclm-result-content">
                                <h3 class="hclm-result-title"><?php echo esc_html($item['title']); ?></h3>
                                <p class="hclm-result-excerpt"><?php echo $item['excerpt']; ?></p>
                            </div>
                            <div class="hclm-result-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="24" width="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>

<?php get_footer(); ?>
