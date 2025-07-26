<?php 

function events_slider_shortcode() {
    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-events-slider-style', plugin_dir_url(__FILE__) . '../../assets/css/events-slider.css');
    wp_enqueue_script('hclm-events-slider-script', plugin_dir_url(__FILE__) . '../../assets/js/events-slider.js', [], false, true);

    // Retrieve upcoming events
    $events = tribe_get_events([
        'posts_per_page' => -1,
        'start_date'     => current_time('Y-m-d H:i:s'),
    ]);

    ob_start();
    ?>
    <section class="hclm-events-section">
        <div class="hclm-events-header">
            <h2>Évènements à venir</h2>
            <div class="view-calendar">
                <a href="<?php echo esc_url(tribe_get_events_link()); ?>" target="_blank" rel="noopener noreferer">
                    Voir le calendrier ->
                </a>
            </div>
        </div>
        <div class="slider-wrapper">
            <div class="hclm-events-slider">
                <?php foreach ($events as $event){
                    $venue_id = tribe_get_venue_id($event);
                    $venue_name = $venue_id ? tribe_get_venue($venue_id) : false;
                    $thumb_url = get_the_post_thumbnail_url($event, 'large'); ?>
                    <div class="event-card">
                        <a href="<?php echo esc_url(get_permalink($event)); ?>" class="hclm-event-thumb-container" aria-label="<?php echo esc_attr($event->post_title); ?>">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($event->post_title); ?>" class="hclm-event-thumb" />
                        </a>
                        <div class="event-content">
                            <h3><?php echo esc_html($event->post_title); ?></h3>
                            <p>
                                <span><i class="fas fa-calendar-day"></i><?php echo esc_html(tribe_get_start_date($event, false, 'j F Y')); ?></span>
                                <?php if($venue_name) { ?>
                                    <span><i class="fas fa-map-marker-alt"></i><?php echo esc_html($venue_name); ?></span>
                                <?php } ?>
                            <?php echo wp_trim_words(wp_strip_all_tags(tribe_events_get_the_excerpt($event)), 12, '...'); ?>

                            </p>
                            <a href="<?php echo esc_url(get_permalink($event)); ?>" class="event-link">En savoir plus</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="slider-controls-row">
                <div class="slider-arrow prev-slide" aria-label="Précédent" title="Précédent" tabindex="0" role="button">
                    <!-- SVG left arrow -->
                    <svg fill="#e76f51" viewBox="0 0 24 24" width="30" height="30" style="transform: rotate(180deg);"><polyline points="7.5 3 16.5 12 7.5 21" style="fill: none; stroke: #e76f51; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline></svg>
                </div>
                <div class="slider-bullets"></div>
                <div class="slider-arrow next-slide" aria-label="Suivant" title="Suivant" tabindex="0" role="button">
                    <!-- SVG right arrow -->
                    <svg fill="#e76f51" viewBox="0 0 24 24" width="30" height="30"><polyline points="7.5 3 16.5 12 7.5 21" style="fill: none; stroke: #e76f51; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline></svg>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

?>