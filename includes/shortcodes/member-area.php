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
                    <span class="label">Mon profil</span>
                </li>
                <li data-tab="statuses">
                    <span class="icon"><i class="fas fa-layer-group"></i></span>
                    <span class="label">Statuts HCLM</span>
                </li>
                <li data-tab="reports">
                    <span class="icon"><i class="fas fa-file-alt"></i></span>
                    <span class="label">Comptes rendus</span>
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
                <h3>Bonjour <?php echo esc_html($user->get('user_firstname') . ' ' . $user->get('user_lastname')); ?>,</h3>
                <div class="dashboard-grid">
                    <div class="tab-card span-2">
                        <p>Bienvenue dans l'espace adhérent. Ici, vous retrouverez toutes les informations importantes liées à votre adhésion.</p>
                        <span>Vous avez également accès à du contenu supplémentaire.</span>
                    </div>
                    <div class="tab-card">
                        <h4><i class="fas fa-calendar-alt"></i> Prochain événement</h4>
                        <span>Réunion du CA le 24/04/2025 à 14h</span>
                    </div>
                    <div class="tab-card card-last-report" onclick="showReports();" style="cursor: pointer;">
                        <h4><i class="fas fa-file-alt"></i> Dernier compte rendu</h4>
                        <div class="report-thumbnail">
                            <img src="<?php echo home_url('/wp-content/uploads/hclm/images/b70.jpg') ?>">
                        </div>
                        <span>Compte rendu du 25/03/2025</span>
                    </div>
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
                        <div class="form-group address-group">
                            <label>Adresse</label>
                            <div class="input-with-icon">
                                <input type="text" name="user_address" value="<?php echo esc_attr($user->get('user_address')); ?>" disabled>
                                <i class="fas fa-edit edit-icon"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-save">Enregistrer</button>
                    </form>
                </div>
                <div class="tab-card">
                    <h4><i class="fas fa-address-card"></i> Statut de l'adhésion</h4>
                    <span>Adhérent depuis le : <?php echo date('d/m/Y', strtotime($user->get('user_registered'))) ?></span>
                </div>
            </section>
            <section id="statuses" class="tab-content">
                <h3>Statuts de l'association</h2>
                <p><i>Mettre PDF</i></p>
            </section>
            <section id="reports" class="tab-content">
                <h3>Compte rendus</h3>
                
                <div class="filters">
                    <input type="text" placeholder="Rechercher un compte rendu...">

                    <select>
                        <option value="">Toutes les années</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                    <?php 
                    if (current_user_can('administrator')) {
                    ?>
                    <select>
                        <option value="">Tous les types</option>
                        <option value="AG">Assemblée Générale</option>
                        <option value="CA">Conseil d'Administration</option>
                    </select>
                    <?php } ?>
                </div>

                <div class="reports-list">
                    <div class="report-card">
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/hclm/images/b70.jpg')); ?>" alt="Aperçu PDF">
                        <div class="report-info">
                            <p class="report-meta"><i class="fas fa-calendar-alt"></i> 18/04/2025 — <strong>AG</strong></p>
                            <a class="btn-download" href="#">Télécharger</a>
                        </div>
                    </div>
                    <div class="report-card">
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/hclm/images/b70.jpg')); ?>" alt="Aperçu PDF">
                        <div class="report-info">
                            <p class="report-meta"><i class="fas fa-calendar-alt"></i> 18/04/2025 — <strong>CA</strong></p>
                            <a class="btn-download" href="#">Télécharger</a>
                        </div>
                    </div>
                    <div class="report-card">
                        <img src="<?php echo esc_url(home_url('/wp-content/uploads/hclm/images/b70.jpg')); ?>" alt="Aperçu PDF">
                        <div class="report-info">
                            <p class="report-meta"><i class="fas fa-calendar-alt"></i> 18/04/2025 — <strong>AG</strong></p>
                            <a class="btn-download" href="#">Télécharger</a>
                        </div>
                    </div>

                </div>
            </section>
            <section id="suggestions" class="tab-content">
                <h3>Suggestions / Remarques</h3>
                <div class="tab-card">
                    <?php echo do_shortcode('[hclm_contact_form]') ?>
                </div>
            </section>
        </main>
    </div>
    <?php
    return ob_get_clean();
}
