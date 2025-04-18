<?php

/**
 * Displays the HCLM member area.
 *
 * @return string HTML page.
 */
function member_area_shortcode() {
    $user = wp_get_current_user();

    // Load CSS Style and JavaScript
    wp_enqueue_style('hclm-member-area-style', plugin_dir_url(__FILE__) . '../../assets/css/member-area.css');
    wp_enqueue_script('hclm-member-area-js', plugin_dir_url(__FILE__) . '../../assets/js/member-area.js', [], false, true);

    ob_start();
    ?>
    <div class="hclm-member-area">
        <aside class="sidebar">
            <ul>
                <li data-tab="dashboard" class="active">
                    <span class="icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="label">Tableau de bord</span>
                </li>
                <li data-tab="profile">
                    <span class="icon"><i class="fas fa-user-circle"></i></span>
                    <span class="label">Profil</span>
                </li>
                <li data-tab="statuses">
                    <span class="icon"><i class="fas fa-layer-group"></i></span>
                    <span class="label">Statuts</span>
                </li>
                <li data-tab="reports">
                    <span class="icon"><i class="fas fa-file-alt"></i></span>
                    <span class="label">Compte rendus</span>
                </li>
                <li data-tab="suggestions">
                    <span class="icon"><i class="far fa-comment-dots"></i></i></span>
                    <span class="label">Suggestions</span>
                </li>
            </ul>
            <div class="logout-container">
                <a href="<?php echo wp_logout_url(home_url('/connexion')); ?>" class="logout-button">
                    <span>Déconnexion</span>
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
            
        </aside>
        <main class="content">
            <section id="dashboard" class="tab-content active">
                <h3>Bonjour <?php echo esc_html($user->get('user_firstname')); ?>&nbsp;!</h3>
                <div class="tab-card">
                    <p>Bienvenue dans l'espace adhérent ...</p>
                </div>
            </section>
            <section id="profile" class="tab-content">
                <h3>Vos informations</h3>
                <div class="tab-card profile-section">
                    <div class="profile-header">
                        <div class="profile-picture-wrapper">
                            <img class="profile-picture" src="https://www.transparentpng.com/download/user/gray-user-profile-icon-png-fP8Q1P.png" alt="Photo de profil">
                            <button type="button" class="edit-photo-btn" title="Modifier la photo">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <form class="profile-form">
                        <div class="form-group">
                            <label>Nom</label>
                            <div class="input-with-icon">
                                <input type="text" name="user_lastname" value="<?php echo esc_attr($user->get('user_lastname')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Prénom</label>
                            <div class="input-with-icon">
                                <input type="text" name="user_firstname" value="<?php echo esc_attr($user->get('user_firstname')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <div class="input-with-icon">
                                <input type="email" name="user_email" value="<?php echo esc_attr($user->get('user_email')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <div class="input-with-icon">
                                <input type="tel" name="user_phone" value="<?php echo esc_attr($user->get('user_phone')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Adresse</label>
                            <div class="input-with-icon">
                                <input type="text" name="user_address" value="<?php echo esc_attr($user->get('user_address')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-save">Enregistrer</button>
                    </form>
                </div>
            </section>
            <section id="statuses" class="tab-content">
                <h3>Statuts</h2>
                <p>PDF, infos, etc.</p>
            </section>
            <section id="reports" class="tab-content">
                <h3>Compte rendus</h3>
                <p>Liste de documents, etc.</p>
            </section>
            <section id="suggestions" class="tab-content">
                <h3>Suggestions / Remarques</h3>
                <p>Liste de documents, etc.</p>
            </section>
        </main>
    </div>
    <?php
    return ob_get_clean();
}
