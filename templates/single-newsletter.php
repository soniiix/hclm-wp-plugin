<?php
get_header();

// Check if the user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}

// Load CSS style
wp_enqueue_style('hclm-newsletters-style', plugin_dir_url(__FILE__) . '../assets/css/newsletters.css');

// Retrieve the current newsletter
$title = get_the_title();
$summary_url = get_post_meta(get_the_ID(), 'summary_url', true);
$pdf_url = get_post_meta(get_the_ID(), 'pdf_url', true);
?>

<div class="newsletter_display_section">
    <span class="slug_text"><a href="/bulletins">Bulletins ></a></span>
    <h2 class="page-subtitle"><?php echo $title; ?></h2>
    <div class="_df_book" style="max-height: 600px !important;" source="<?php echo $pdf_url ?>"></div>
</div>

<?php
get_footer();
?>