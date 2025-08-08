<?php 

function events_slider_shortcode() {
    // Load CSS style and JavaScript
    wp_enqueue_style('hclm-events-slider-style', plugin_dir_url(__FILE__) . '../../assets/css/events-slider.css');
    wp_enqueue_script('hclm-events-slider-script', plugin_dir_url(__FILE__) . '../../assets/js/events-slider.js', [], false, true);
    // Swiper CSS & JS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], null, true);


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
            <a href="<?php echo esc_url(tribe_get_events_link()); ?>" class="view-calendar-link" aria-label="Voir le calendrier des évènements">
                Voir le calendrier
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.3153 16.6681C15.9247 17.0587 15.9247 17.6918 16.3153 18.0824C16.7058 18.4729 17.339 18.4729 17.7295 18.0824L22.3951 13.4168C23.1761 12.6357 23.1761 11.3694 22.3951 10.5883L17.7266 5.9199C17.3361 5.52938 16.703 5.52938 16.3124 5.91991C15.9219 6.31043 15.9219 6.9436 16.3124 7.33412L19.9785 11.0002L2 11.0002C1.44772 11.0002 1 11.4479 1 12.0002C1 12.5524 1.44772 13.0002 2 13.0002L19.9832 13.0002L16.3153 16.6681Z" fill="#e76f51"></path> </g></svg>
            </a>
        </div>
        <div class="slider-wrapper">
            <div class="swiper hclm-events-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($events as $event){
                        $venue_id = tribe_get_venue_id($event);
                        $venue_name = $venue_id ? tribe_get_venue($venue_id) : false;
                        $thumb_url = get_the_post_thumbnail_url($event, 'large'); ?>
                        <div class="swiper-slide">
                            <div class="event-card">
                                <a href="<?php echo esc_url(get_permalink($event)); ?>" class="hclm-event-thumb-container" aria-label="<?php echo esc_attr($event->post_title); ?>">
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($event->post_title); ?>" class="hclm-event-thumb" />
                                </a>
                                <div class="event-content">
                                    <a href="<?php echo esc_url(get_permalink($event)); ?>">
                                        <h3><?php echo esc_html($event->post_title); ?></h3>
                                    </a>
                                    <p>
                                        <span><i class="fas fa-calendar-day"></i><?php echo esc_html(tribe_get_start_date($event, false, 'j F Y')); ?></span>
                                        <?php if($venue_name) { ?>
                                            <span><i class="fas fa-map-marker-alt"></i><?php echo esc_html($venue_name); ?></span>
                                        <?php } ?>
                                        <?php echo esc_html(wp_trim_words(wp_strip_all_tags(tribe_events_get_the_excerpt($event)), 16, '...')); ?>
                                    </p>
                                    <a href="<?php echo esc_url(get_permalink($event)); ?>" class="event-link">En savoir plus</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Swiper navigation (on tablet & desktop) -->
            <div class="swiper-buttons-row">
                <div class="swiper-button-prev" role="button" aria-label="Précédent" title="Précédent">
                    <svg fill="#e76f51" viewBox="0 0 24 24" width="24" height="24" style="transform: rotate(180deg);">
                        <polyline points="7.5 3 16.5 12 7.5 21" style="fill: none; stroke: #e76f51; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline>
                    </svg>
                </div>
                <div class="swiper-button-next" role="button" aria-label="Suivant" title="Suivant">
                    <svg fill="#e76f51" viewBox="0 0 24 24" width="24" height="24">
                        <polyline points="7.5 3 16.5 12 7.5 21" style="fill: none; stroke: #e76f51; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></polyline>
                    </svg>
                </div>  
            </div>

            <!-- Swiper pagination (on mobile) -->
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

?>