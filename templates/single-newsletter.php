<?php
get_header();

// Check if the user is logged in
if (!is_user_logged_in()) {
    $current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));
    
    // Redirect to the login page with the current URL as a parameter
    $login_url = home_url('/connexion');
    $redirect_url = add_query_arg('redirect_to', urlencode($current_url), $login_url);

    wp_redirect($redirect_url);
    exit;
}

// If the user does not have an active membership, redirect to the member area that displays the warning message
if (!hclm_is_membership_active() && !hclm_current_user_has_role(['administrator', 'tresorier', 'secretaire', 'editor'])) {
    wp_redirect('/espace-adherent');
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
    <h2 class="page-subtitle"><?php echo esc_html($title); ?></h2>
    <div class="_df_book" style="max-height: 600px !important;" source="<?php echo esc_url($pdf_url) ?>"></div>
</div>

<?php
get_footer();
?>