<?php get_header();

require_once plugin_dir_path(__FILE__) . 'utils/get-highlighted-excerpt.php';

// Load CSS style and JavaScript
wp_enqueue_style('hclm-search-results-style', plugin_dir_url(__FILE__) . '../assets/css/search-results.css');
wp_enqueue_script('hclm-search-results-script', plugin_dir_url(__FILE__) . '../assets/js/search-results.js', [], false, true);

$keywords = isset($_GET['keywords']) ? explode(',', $_GET['keywords']) : [];
$exclude = isset($_GET['exclude']) ? explode(',', $_GET['exclude']) : [];
$type = $_GET['type'] ?? '';
$orderby = $_GET['orderby'] ?? 'relevance';
// Retrieve the search term or if empty, use the keywords
$search_term = trim(get_search_query());
if (empty($search_term) && !empty($keywords)) {
    $search_term = implode(' ', $keywords);
} else if (!empty($search_term) && !empty($keywords)) {
    $search_term = $search_term . ' ' . implode(' ', $keywords);
}

$args = [
    'post_type' => 'any',
    'posts_per_page' => -1,
];

// Set sorting arguments based on the selected order
switch ($orderby) {
    case 'date_asc':
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
        break;
    case 'date_desc':
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
        break;
    case 'title_asc':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
    case 'title_desc':
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
        break;
    case 'relevance':
    default:
        if (!empty($search_term)) {
            $args['orderby'] = 'relevance';
            $args['order'] = 'DESC';
        } else {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        }
        break;
}

// Filter results by date if start or end date is provided
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
$end_date   = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';

if ($start_date || $end_date) {
    $date_query = [];

    if ($start_date) {
        $date_query['after'] = $start_date;
    }
    if ($end_date) {
        $date_query['before'] = $end_date;
    }

    $date_query['inclusive'] = true;
    $args['date_query'] = [$date_query];
}

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
                            <label for="hclm-exclude-input">Termes à exclure</label>
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
                                <input type="text" id="hclm-exclude-input" placeholder="Entrer des termes à exclure" autocomplete="off" />
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
                        <div class="hclm-popup-advanced-search-filter-group">
                            <label>Période</label>
                            <div class="hclm-date-range-wrapper">
                                <input type="date" name="start_date" value="<?php echo esc_attr($_GET['start_date'] ?? '') ?>" />
                                <input type="date" name="end_date" value="<?php echo esc_attr($_GET['end_date'] ?? '') ?>" />
                            </div>
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
                <!-- Hidden fields to keep the search term and filters -->
                <input type="hidden" name="s" value="<?php echo esc_attr($search_term); ?>">
                <input type="hidden" name="keywords" value="<?php echo esc_attr(implode(',', $keywords)); ?>">
                <input type="hidden" name="exclude" value="<?php echo esc_attr(implode(',', $exclude)); ?>">
                <input type="hidden" name="type" value="<?php echo esc_attr($type); ?>">
                <input type="hidden" name="start_date" value="<?php echo esc_attr($start_date); ?>">
                <input type="hidden" name="end_date" value="<?php echo esc_attr($end_date); ?>">

                <span>Trier&nbsp;par&nbsp;:</span>
                <select name="orderby" id="orderby" onchange="this.form.submit()">
                    <option value="relevance" <?php selected($orderby, 'relevance'); ?>>Pertinence</option>
                    <option value="date_desc" <?php selected($orderby, 'date_desc'); ?>>Date (décroissant)</option>
                    <option value="date_asc" <?php selected($orderby, 'date_asc'); ?>>Date (croissant)</option>
                    <option value="title_asc" <?php selected($orderby, 'title_asc'); ?>>Titre (A-Z)</option>
                    <option value="title_desc" <?php selected($orderby, 'title_desc'); ?>>Titre (Z-A)</option>
                </select>
            </form>
        </div>

        <!-- Retrieve all search results in the website content -->
        <?php 
        $site_results = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $title   = get_the_title();
                $content = get_the_content();
                $haystack = strtolower($title . ' ' . $content);

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

                $slug = get_post_field('post_name', get_post());
                // Exclude specific pages from the results
                $slugs_to_exclude = ['connexion', 'en-construction', 'mentions-legales', 'politique-de-confidentialite'];
                if (in_array($slug, $slugs_to_exclude)) {
                    continue;
                }
                // Do not display the excerpt for specific pages
                $excerpt_exclude = false;
                $exlude_excerpt_slugs = ['contact', 'bulletins', 'espace-adherent'];
                if (in_array($slug, $exlude_excerpt_slugs)) {
                    $excerpt_exclude = true;
                }

                $site_results[] = [
                    'post_type' => get_post_type(),
                    'url'       => get_the_permalink(),
                    'title'     => $title,
                    'thumbnail' => get_the_post_thumbnail(
                                    get_the_ID(),
                                    get_post_type() === 'bulletin' ? 'full' : 'thumbnail',
                                    ['class' => get_post_type() === 'bulletin' ? 'newsletter-thumbnail' : '']
                                ),
                    'excerpt'   => $excerpt_exclude ? '' : get_highlighted_excerpt($content, $search_term),
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

        <?php if (empty($site_results)): ?>
            <div class="hclm-search-no-results">
                <svg viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>search-times</title> <path d="M30.885 29.115l-10.132-10.132c1.555-1.9 2.497-4.355 2.497-7.029 0-3.092-1.26-5.89-3.294-7.909l-0.001-0.001h-0.002c-2.036-2.040-4.851-3.301-7.961-3.301-6.213 0-11.249 5.036-11.249 11.249 0 3.11 1.262 5.924 3.301 7.961l0 0c2.019 2.036 4.817 3.297 7.91 3.297 2.674 0 5.128-0.942 7.048-2.513l-0.020 0.016 10.132 10.132c0.226 0.226 0.539 0.366 0.884 0.366 0.691 0 1.251-0.56 1.251-1.251 0-0.345-0.14-0.658-0.366-0.884l0 0zM5.813 18.186c-1.583-1.583-2.563-3.771-2.563-6.187 0-4.832 3.917-8.749 8.749-8.749 2.416 0 4.603 0.979 6.187 2.563h0.002c1.583 1.583 2.563 3.77 2.563 6.186s-0.979 4.602-2.561 6.185l0-0-0.004 0.002-0.003 0.004c-1.583 1.582-3.769 2.56-6.183 2.56-2.417 0-4.604-0.98-6.187-2.564l-0-0zM13.768 12l1.944-1.944c0.226-0.226 0.366-0.539 0.366-0.884 0-0.69-0.56-1.25-1.25-1.25-0.345 0-0.658 0.14-0.884 0.366l-1.944 1.944-1.944-1.944c-0.226-0.226-0.539-0.366-0.884-0.366-0.69 0-1.25 0.56-1.25 1.25 0 0.345 0.14 0.658 0.366 0.884v0l1.944 1.944-1.944 1.944c-0.226 0.226-0.366 0.539-0.366 0.884 0 0.69 0.56 1.25 1.25 1.25 0.345 0 0.658-0.14 0.884-0.366v0l1.944-1.944 1.944 1.944c0.226 0.226 0.539 0.366 0.884 0.366 0.69 0 1.25-0.56 1.25-1.25 0-0.345-0.14-0.658-0.366-0.884v0z"></path> </g></svg>
                <p>
                    Aucun résultat trouvé pour votre recherche.
                    <br>
                    Vérifiez l'orthographe des mots saisis ou désactivez les filtres actifs.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
