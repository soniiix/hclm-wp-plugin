<?php get_header(); ?>

<div class="search-results">
    <h1>Résultats de recherche pour "<?php echo get_search_query(); ?>"</h1>

    <?php if (have_posts()) { ?>
        <h2>Pages</h2>
        <ul>
            <?php while (have_posts()) : the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <div><?php the_excerpt(); ?></div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php } else { ?>
        <p>Aucun résultat dans les articles/pages.</p>
    <?php } ?>

    <?php
    $query = get_search_query();
    $upload_dir = wp_upload_dir();
    $base = $upload_dir['basedir'] . '/hclm/bulletins';
    $baseurl = $upload_dir['baseurl'] . '/hclm/bulletins';

    $results = [];
    foreach (glob($base . '/B*/B*.txt') as $txt_file) {
        $content = file_get_contents($txt_file);
        if (stripos($content, $query) !== false) {
            $bulletin = basename($txt_file, '.txt');
            $folder = basename(dirname($txt_file));
            $results[] = [
                'title' => 'Bulletin ' . $bulletin,
                'url'   => $baseurl . '/' . $folder . '/' . $bulletin . '.pdf'
            ];
        }
    }

    if (!empty($results)) { ?>
        <h2>Résultats dans les bulletins</h2>
        <ul>
            <?php foreach ($results as $item) { ?>
                <li><a href="<?php echo esc_url($item['url']); ?>" target="_blank"><?php echo esc_html($item['title']); ?></a></li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>Aucun résultat trouvé dans les bulletins.</p>
    <?php } ?>
</div>

<?php get_footer(); ?>
