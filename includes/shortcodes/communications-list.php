<?php 

// Shortcode to display the list of communications
function hclm_communications_shortcode() {
    // Load CSS stylesheet
    wp_enqueue_style('hclm-communications-style', plugin_dir_url(__FILE__) . '../../assets/css/communications-list.css');

    // Query to get all communications
    $args = [
        'post_type' => 'communication',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];
    $query = new WP_Query($args);
    $posts = $query->posts;

    ob_start(); ?>

    <!-- Communications List -->
    <div class="hclm-communications-wrapper">
        <?php foreach ($posts as $post) { 
            $fields = get_fields($post->ID);
            ?>
            <div class="hclm-communication-card">
                <div class="hclm-communication-header">
                    <span class="hclm-communication-year">
                        <?php echo esc_html(get_the_date('Y', $post->ID)); ?>
                    </span>
                    <h3 class="hclm-communication-title">
                        <?php echo esc_html(get_the_title($post->ID)); ?>
                    </h3>
                </div>

                <?php if ($fields && is_array($fields)) { ?>
                    <div class="hclm-communication-select-wrapper">
                        <!-- Dropdown to select a document. Once selected, it opens in a new tab -->
                        <select onchange="if(this.value) window.open(this.value, '_blank');">
                            <option value="" disabled selected>Sélectionnez un document à consulter</option>
                            <?php foreach ($fields as $field) {
                                if (
                                    isset($field['file']) && !empty($field['file']) &&
                                    isset($field['title']) && !empty($field['title'])
                                ) { ?>
                                    <option value="<?php echo esc_url($field['file']); ?>">
                                        <?php echo esc_html($field['title']); ?>
                                        <?php if (!empty($field['authors'])) echo ' - ' . esc_html($field['authors']); ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <?php
    return ob_get_clean();
}
