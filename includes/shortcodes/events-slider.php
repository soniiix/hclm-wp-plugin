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
            <div class="separator"></div>
            <div class="slider-nav">
                <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
                <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>

        <div class="hclm-events-slider">
            <?php foreach ($events as $event){
                $venue_id = tribe_get_venue_id($event);
                $venue_name = $venue_id ? tribe_get_venue($venue_id) : false; ?>
                <div class="event-card">
                    <h3><?php echo esc_html($event->post_title); ?></h3>
                    <p>
                        <span><i class="fas fa-calendar-day"></i><?php echo tribe_get_start_date($event, false, 'j F Y'); ?></span>
                        <?php if($venue_name) { ?>
                            <span><i class="fas fa-map-marker-alt"></i><?php echo $venue_name; ?></span>
                        <?php } ?>
                    </p>
                    <a href="<?php echo esc_url(get_permalink($event)); ?>" class="event-link">En savoir plus</a>
                </div>
            <?php } ?>
        </div>

        <div class="view-calendar">
            <a href="<?php echo esc_url(tribe_get_events_link()); ?>" target="_blank" rel="noopener noreferer">
                Voir le calendrier ➜
            </a>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

?>