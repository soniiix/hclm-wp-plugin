<?php get_header(); 

// Load CSS style and JavaScript
wp_enqueue_style('hclm-search-results-style', plugin_dir_url(__FILE__) . '../assets/css/search-results.css');
wp_enqueue_script('hclm-search-results-script', plugin_dir_url(__FILE__) . '../assets/js/search-results.js', [], false, true);
?>

<div class="search-results">
    <h1>Résultats de recherche pour "<?php echo get_search_query(); ?>"</h1>
    <span id="hclm-search-results-count">0 résultat</span>

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
                            <div class="hclm-result-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="24" width="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </div>
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
            $newsletter = basename($txt_file, '.txt');
            $folder = basename(dirname($txt_file));
            $pos = stripos($content, $query); // Find position of first word occurence in the newsletter
            $excerpt = '';
            if ($pos !== false) {
                // Extract 50 words around the occurrence to make an excerpt
                $start = max(0, $pos - 50);
                $length = strlen($query) + 100;
                $snippet = substr($content, $start, $length);
                
                $safe_snippet = htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8');

                // Highlight the word
                $highlighted = preg_replace(
                    '/' . preg_quote($query, '/') . '/i',
                    '<mark>$0</mark>',
                    $safe_snippet
                );

                $excerpt = '... ' . $highlighted . ' ...'; // Concatenate the highlighted word and the excerpt where it's present
            }

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
    <?php } else { ?>
        <p>Aucun résultat trouvé dans les bulletins.</p>
    <?php } ?>
</div>

<?php get_footer(); ?>
