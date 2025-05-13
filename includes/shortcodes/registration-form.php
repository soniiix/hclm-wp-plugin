<?php

/**
 * Displays the registration form for members.
 *
 * @return string HTLM registration form.
 */
function registration_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>Vous êtes déjà connecté.</p>';
    }

    // Load CSS style
    wp_enqueue_style('hclm-registration-form-style', plugin_dir_url(__FILE__) . '../../assets/css/forms.css');

    ob_start();
    ?>
    <form method="post" class="hclm_form">
        <p><label>Nom :</label><br><input type="text" name="member_lastname" autocomplete="family-name" required></p>
        <p><label>Prénom :</label><br><input type="text" name="member_firstname" autocomplete="given-name" required></p>
        <p><label>Adresse :</label><br><textarea rows="2" type="text" name="member_address" required></textarea></p>
        <p><label>Téléphone :</label><br><input type="tel" name="member_phone" autocomplete="tel" required></p>
        <p><label>Email :</label><br><input type="email" name="member_email" autocomplete="email" required></p>
        <p><label>Mot de passe :</label><br><input type="password" name="member_password" autocomplete="new-password" required></p>
        <p><input type="submit" name="sign_up_submit" value="Créer mon compte"></p>
    </form>
    <?php
    return ob_get_clean();
}

?>