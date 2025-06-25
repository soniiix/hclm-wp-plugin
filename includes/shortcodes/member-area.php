<?php

require_once plugin_dir_path(__FILE__) . 'utils/get-pdf-cover.php';

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

    // Retrieve all reports
    $upload_dir = wp_upload_dir();
    $reports_dir = $upload_dir['basedir'] . '/hclm/comptes-rendus/';
    $reports_url = $upload_dir['baseurl'] . '/hclm/comptes-rendus/';

    $files = [];
    if (is_dir($reports_dir)) {
        foreach (glob($reports_dir . 'CR-*.pdf') as $file) {
            // Parse filename: CR-[CA|AG]_JJ-MM-AAAA.pdf
            if (preg_match('/CR\-(CA|AG)_(\d{2})\-(\d{2})\-(\d{4})\.pdf$/', basename($file), $matches)) {
                $files[] = [
                    'type' => $matches[1],
                    'day' => $matches[2],
                    'month' => $matches[3],
                    'year' => $matches[4],
                    'filename' => basename($file),
                    'url' => $reports_url . basename($file),
                    'cover' => hclm_get_pdf_cover($reports_dir . basename($file)),
                ];
            }
        }
        // Sort by date descending
        usort($files, function($a, $b) {
            return strtotime("{$b['year']}-{$b['month']}-{$b['day']}") - strtotime("{$a['year']}-{$a['month']}-{$a['day']}");
        });
    }

    // Filter reports for non-admin users
    if (!current_user_can('administrator')) {
        $files = array_filter($files, function($report) {
            return $report['type'] !== 'CA';
        });
    }

    ob_start();
    ?>
    <div class="hclm-member-area">
        <aside>
            <div class="sidebar">
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
                    <?php if (!current_user_can('administrator')){ ?>
                    <li data-tab="suggestions">
                        <span class="icon"><i class="far fa-comment-dots"></i></i></span>
                        <span class="label">Vos suggestions</span>
                    </li>
                    <?php } ?>
                </ul>
                <div class="logout-container">
                    <a href="<?php echo wp_logout_url(home_url('/connexion')); ?>" class="logout-button" aria-label="Déconnexion">
                        <span>Déconnexion</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            
        </aside>
        <main class="content">
            <section id="dashboard" class="tab-content active">
                <h3>
                    <?php
                    if (in_array('adherent', (array) $user->roles)) {
                        echo 'Bonjour ' . esc_html($user->get('user_firstname') . ' ' . $user->get('user_lastname')) . ',';
                    } else {
                        echo 'Bonjour,';
                    }
                    ?>
                </h3>
                <div class="dashboard-two-columns">
                    <div class="dashboard-col">
                        <div class="tab-card">
                            <p>Bienvenue dans l'espace adhérent. Ici, vous retrouverez toutes les informations importantes liées à votre adhésion.</p>
                            <span>Vous avez également accès à du contenu supplémentaire.</span>
                        </div>
                        <div 
                            class="tab-card tab-hover-card" 
                            title="Voir les événements à venir" 
                            onclick="window.location.href='<?php echo esc_url(tribe_get_events_link()); ?>'"
                        >
                            <h4><i class="fas fa-calendar-alt"></i> Prochain événement</h4>
                            <?php 
                            $next_event = tribe_get_events([
                                'posts_per_page' => 1,
                                'start_date'     => current_time('Y-m-d H:i:s'),
                            ]);
                            ?>
                            <span>
                                <?php if (!empty($next_event)) {
                                    $event = $next_event[0];
                                    echo esc_html($event->post_title) . ' le ' . tribe_get_start_date($event, false, 'j F Y');
                                } else {
                                    echo 'Aucun événement à venir.';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="dashboard-col">
                        <div class="tab-card tab-hover-card card-last-report" onclick="showReports();" title="Voir les comptes rendus">
                            <h4><i class="fas fa-file-alt"></i> Dernier compte rendu</h4>
                            <div class="report-thumbnail">
                                <?php
                                $last_report = !empty($files) ? $files[0] : null;
                                if ($last_report) { ?>
                                <img src="<?php echo $last_report['cover'] ?>" alt="Aperçu du dernier compte rendu">
                            </div>
                            <span>Compte rendu du <?php echo "{$last_report['day']}/{$last_report['month']}/{$last_report['year']}"; ?></span>
                            <?php } else { ?>
                                </div>
                                <span>Aucun compte rendu disponible.</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </section>
            <section id="profile" class="tab-content">
                <?php
                $profile_updated = get_transient('hclm_profile_updated_' . get_current_user_id());
                if ($profile_updated) {
                    delete_transient('hclm_profile_updated_' . get_current_user_id());
                    ?>
                    <div class="update-message">
                        <i class="fas fa-check-circle"></i>
                        Profil mis à jour avec succès !
                    </div>
                <?php } ?>
                <h3>Vos informations</h3>
                <div class="tab-card">
                    <form class="profile-section" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" enctype="multipart/form-data">
                        <?php wp_nonce_field('update_user_profile_nonce', 'update_user_profile_nonce_field'); ?>
                        <input type="hidden" name="action" value="update_user_profile">

                        <div class="profile-header">
                            <input type="file" class="profile-picture-input" name="profile_picture" accept="image/*" />
                            <div class="profile-picture-wrapper">
                                <?php
                                $profile_picture_id = get_user_meta($user->ID, 'profile_picture', true);
                                $profile_picture_url = $profile_picture_id ? wp_get_attachment_url($profile_picture_id) : get_avatar_url($user->get('user_email'));
                                ?>
                                <img class="profile-picture" src="<?php echo esc_url($profile_picture_url); ?>" alt="Photo de profil">
                                <button type="button" class="edit-picture-btn" title="Modifier la photo">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>

                        <div class="profile-form">
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
                        </div>
                    </form>
                </div>
                <?php if (!current_user_can('administrator')){ ?>
                <div class="tab-card membership">
                    <h4><i class="fas fa-address-card"></i> Statut de l'adhésion</h4>
                    <span>Adhérent depuis le : <?php echo date('d/m/Y', strtotime($user->get('user_registered'))) ?></span>
                </div>
                <?php } ?>
            </section>
            <section id="statuses" class="tab-content">
                <h3>Statuts de l'association</h2>
                <div class="tab-card">
                    <p>Les statuts de l'association HCLM sont disponibles ci-dessous.</p>
                    <?php $status_pdf_url = wp_upload_dir()['baseurl'] . '/hclm/documents/statuts-hclm.pdf'; ?>
                    <div class="hclm-status-buttons-row">
                        <a 
                            href="<?php echo esc_url($status_pdf_url); ?>" 
                            class="btn-download btn-download-with-icon"
                            target="_blank" 
                            download
                            aria-label="Télécharger les statuts de l'association HCLM"
                        >
                            Télécharger 
                            <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="20" width="20" xmlns="http://www.w3.org/2000/svg" style="margin-top: 1px;"><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path><path d="M7 11l5 5l5 -5"></path><path d="M12 4l0 12"></path></svg>
                        </a>
                        <div 
                            class="_df_button" 
                            source="<?php echo esc_url($status_pdf_url); ?>"
                        >
                            Consulter le fichier PDF
                        </div>
                    </div>
                </div>
            </section>
            <section id="reports" class="tab-content">
                <h3>Comptes rendus</h3>
                
                <div class="filters">
                    <div class="hclm-reports-search-bar-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input type="text" id="search-input" placeholder="Rechercher un compte rendu..." class="hclm-reports-search-bar"/>
                    </div>

                    <select id="filter-year">
                        <option value="">Toutes les années</option>
                    </select>

                    <?php if (current_user_can('administrator')): ?>
                    <select id="filter-type">
                        <option value="">Tous les types</option>
                        <option value="AG">Assemblée Générale</option>
                        <option value="CA">Conseil d'Administration</option>
                    </select>
                    <?php endif; ?>

                    <select id="sort-date">
                        <option value="desc">Du plus récent au plus ancien</option>
                        <option value="asc">Du plus ancien au plus récent</option>
                    </select>
                </div>

                <div class="reports-list">
                    <?php if (empty($files)): ?>
                        <p>Aucun compte rendu trouvé.</p>
                    <?php else: ?>
                        <?php foreach ($files as $report): ?>
                            <div class="report-card" 
                                data-year="<?php echo esc_attr($report['year']); ?>" 
                                data-type="<?php echo esc_attr($report['type']); ?>" 
                                data-date="<?php echo "{$report['day']}/{$report['month']}/{$report['year']}"; ?>"
                            >
                                <a href="<?php echo esc_url($report['url']); ?>" target="_blank" title="Voir le compte rendu PDF" class="report-link"></a>
                                <img src="<?php echo $report['cover'] ?: esc_url(home_url('/wp-content/uploads/hclm/images/b70.jpg')); ?>" alt="Aperçu PDF">
                                <div class="report-info">
                                    <h4 class="report-title">
                                        <?php if ($report['type'] === 'CA'): ?>
                                            Conseil d'Administration
                                        <?php elseif ($report['type'] === 'AG'): ?>
                                            Assemblée Générale
                                        <?php endif; ?>
                                    </h4>
                                    <p class="report-date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?php echo "{$report['day']}/{$report['month']}/{$report['year']}"; ?>
                                    </p>
                                    <a class="btn-download" href="<?php echo esc_url($report['url']); ?>" target="_blank" download>Télécharger</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <p id="no-results-message" style="display: none; font-style: italic;">Aucun compte rendu trouvé.</p>

            </section>
            <section id="suggestions" class="tab-content">
                <h3>Suggestions / Remarques</h3>
                <div class="tab-card">
                    <div class="mb-5"><span>Pour toute suggestion ou remarque concernant le site, merci de bien vouloir remplir le formulaire ci-dessous.</span></div>
                    <?php echo do_shortcode('[forminator_form id="2040"]') ?>
                </div>
            </section>
        </main>
    </div>
    <?php
    return ob_get_clean();
}
