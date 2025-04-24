<?php

/**
 * Displays the HCLM contact form.
 *
 * @return string HTML contact form.
 */
function contact_form_shortcode() {
    $error = '';

    // Load CSS style
    wp_enqueue_style('hclm-signup-style', plugin_dir_url(__FILE__) . '../../assets/css/forms.css');

    return $error . '
        <form method="post" class="hclm_form">
            <p><label>Sujet :<br><input type="text" name="request_subject" required></label></p>
            <p><label>Joindre un fichier :<br><input type="file" name="request_file"></label></p>
            <p><label>Message :<br><textarea name="request_message" rows="4" required></textarea></label></p>
            <p><input type="submit" name="hclm_contact_submit" value="Envoyer le message"></p>
        </form>
    ';
}

?>