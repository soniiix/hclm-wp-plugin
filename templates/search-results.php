<?php get_header();

require_once plugin_dir_path(__FILE__) . 'utils/get-highlighted-excerpt.php';

// Load CSS style and JavaScript
wp_enqueue_style('hclm-search-results-style', plugin_dir_url(__FILE__) . '../assets/css/search-results.css');
wp_enqueue_script('hclm-search-results-script', plugin_dir_url(__FILE__) . '../assets/js/search-results.js', [], false, true);

$keywords = isset($_GET['keywords']) ? explode(',', $_GET['keywords']) : [];
$exclude = isset($_GET['exclude']) ? explode(',', $_GET['exclude']) : [];
$type = $_GET['type'] ?? '';
$order = $_GET['order'] ?? 'desc';
$search_term = get_search_query();

$args = [
    'post_type' => 'any',
    'posts_per_page' => 50,
    'orderby' => 'date',
    'order' => ($order === 'asc') ? 'ASC' : 'DESC',
];

// If a specific content type is selected, it's set it in the query
if ($type) {
    $post_type_map = [
        'newsletters' => 'bulletin',
        'pages' => 'page',
        'events' => 'tribe_events',
        'fall-visits' => 'visite_automnale',
        'products' => 'product',
    ];
    if (isset($post_type_map[$type])) {
        $args['post_type'] = $post_type_map[$type];
    }
}

// If a search term is provided, it's added to the query
if (!empty($search_term)) {
    $args['s'] = $search_term;
}

// Launch the query
$query = new WP_Query($args);
?>

<div class="hclm-search-results">
    <!-- Display a sidebar with the search bar and filters. Fields are filled if the user has already searched -->
    <aside class="hclm-search-results-aside">
        <form method="GET" action="/">
            <div class="hclm-search-results-aside-search-bar-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" placeholder="Rechercher sur le site" name="s" value="<?php echo esc_html(get_search_query()); ?>" class="hclm-search-results-aside-search-bar" />
            </div>
            <div class="hclm-search-results-aside-content">
                <span class="hclm-search-results-aside-title">Recherche avancée</span>
                <div class="hclm-search-results-aside-filters">
                    <div class="hclm-popup-advanced-search">
                        <div class="hclm-popup-advanced-search-filter-group">
                            <label for="hclm-keywords-input">Mots-clés</label>
                            <div id="hclm-aside-keywords-tagbox" class="hclm-keywords-tagbox">
                                <?php
                                foreach ($keywords as $tag) {
                                    $tag = trim($tag);
                                    if ($tag !== '') { ?>
                                        <span class="hclm-keywords-tag">
                                            <?php echo esc_html($tag); ?>
                                            <span class="hclm-keywords-remove-tag">×</span>
                                        </span>
                                <?php }} ?>
                                <input type="text" id="hclm-aside-keywords-input" placeholder="Entrer des mots-clés" autocomplete="off" />
                            </div>
                            <input type="hidden" name="keywords" id="hclm-aside-keywords-hidden" />
                        </div>
                        <div class="hclm-popup-advanced-search-filter-group">
                            <label for="hclm-exclude-input">Mots à exclure</label>
                            <div id="hclm-exclude-tagbox" class="hclm-keywords-tagbox">
                                <?php
                                foreach ($exclude as $tag) {
                                    $tag = trim($tag);
                                    if ($tag !== '') { ?>
                                        <span class="hclm-keywords-tag">
                                            <?php echo esc_html($tag); ?>
                                            <span class="hclm-keywords-remove-tag">×</span>
                                        </span>
                                <?php }} ?>
                                <input type="text" id="hclm-exclude-input" placeholder="Entrer des mots à exclure" autocomplete="off" />
                            </div>
                            <input type="hidden" name="exclude" id="hclm-exclude-hidden" />
                        </div>
                        <div class="hclm-popup-advanced-search-filter-group">
                            <label for="hclm-advanced-search-filter-input">Type de contenu</label>
                            <select id="hclm-advanced-search-filter-input" name="type">
                                <option value="">Tout</option>
                                <option value="newsletters" <?= ($_GET['type'] ?? '') === 'newsletters' ? 'selected' : '' ?>>Bulletins</option>
                                <option value="pages" <?= ($_GET['type'] ?? '') === 'pages' ? 'selected' : '' ?>>Pages</option>
                                <option value="events" <?= ($_GET['type'] ?? '') === 'events' ? 'selected' : '' ?>>Événements</option>
                                <option value="fall-visits" <?= ($_GET['type'] ?? '') === 'fall-visits' ? 'selected' : '' ?>>Visites automnales</option>
                                <option value="products" <?= ($_GET['type'] ?? '') === 'products' ? 'selected' : '' ?>>Ouvrages</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="hclm-popup-submit-btn" style="margin-top: 5px;">Lancer la recherche</button>
            </div>
        </form>
    </aside>

    <div class="hclm-search-results-content">
        <div class="hclm-search-results-header">
            <div class="hclm-search-results-title">Résultats de recherche (<span id="hclm-search-results-count">0</span>)</div>
            <form method="get" class="hclm-search-results-sort">
                <span>Trier :</span>
                <div>
                    <select name="order" onchange="this.form.submit()">
                        <option value="desc" <?= ($_GET['order'] ?? '') === 'desc' ? 'selected' : '' ?>>Du plus récent au plus ancien</option>
                        <option value="asc" <?= ($_GET['order'] ?? '') === 'asc' ? 'selected' : '' ?>>Du plus ancien au plus récent</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Retrieve all search results in the website content -->
        <?php if ($query->have_posts()) {
            $site_results = [];

            while ($query->have_posts()) {
                $query->the_post();

                $title   = get_the_title();
                $excerpt = get_the_excerpt();
                $haystack = strtolower($title . ' ' . $excerpt);

                // Keywords filtering
                $ok_keywords = true;
                foreach ($keywords as $kw) {
                    $kw = strtolower($kw);
                    if ($kw !== '' && strpos($haystack, $kw) === false) {
                        $ok_keywords = false;
                        break;
                    }
                }
                if (!$ok_keywords) continue;

                // Words exclusion filtering
                $has_exclude = false;
                foreach ($exclude as $ex) {
                    $ex = strtolower($ex);
                    if ($ex !== '' && strpos($haystack, $ex) !== false) {
                        $has_exclude = true;
                        break;
                    }
                }
                if ($has_exclude) continue;

                $site_results[] = [
                    'post_type' => get_post_type(),
                    'url'       => get_the_permalink(),
                    'title'     => $title,
                    'thumbnail' => get_the_post_thumbnail(
                                    get_the_ID(),
                                    get_post_type() === 'bulletin' ? 'full' : 'thumbnail',
                                    ['class' => get_post_type() === 'bulletin' ? 'newsletter-thumbnail' : '']
                                ),
                    'excerpt'   => get_highlighted_excerpt($excerpt, $search_term),
                    'date'      => get_post_type() === 'tribe_events'
                                    ? tribe_get_start_date(null, false, 'j F Y')
                                    : null,
                ];
            }
        }
        wp_reset_postdata();

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
                        case 'bulletin': echo 'Bulletins'; break;
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
                                        <p class="hclm-result-excerpt">
                                            <?php 
                                            if ($result['date']) {
                                                echo '<span class="hclm-result-event-date">' . esc_html($result['date']) . '&nbsp;•&nbsp;</span>';
                                            }
                                            echo $result['excerpt']; ?>
                                        </p>
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
        ?>
    </div>
</div>

<?php get_footer(); ?>
