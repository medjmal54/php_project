<div class="user-layout">

    <!-- Sidebar -->
    <?php include 'views/layout/user_sidebar.php'; ?>

    <!-- Main -->
    <main class="user-main custom-scrollbar">
        <div class="admin-content">

            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Mes Préférences</h1>
                    <p class="admin-header-sub">Gérez vos informations de compte et votre profil public.</p>
                </div>
                <?php include 'views/layout/user_header_actions.php'; ?>
            </header>

            <?php if (isset($_GET['success'])): ?>
                <div class="user-alert user-alert--success">
                    <i data-lucide="check-circle-2"></i>
                    <?php
                        if ($_GET['success'] == 'profile') echo "Profil mis à jour avec succès.";
                        elseif ($_GET['success'] == 'password') echo "Mot de passe modifié avec succès.";
                        elseif ($_GET['success'] == 'photo') echo "Photo de profil modifiée avec succès.";
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="user-alert user-alert--error">
                    <i data-lucide="alert-circle"></i>
                    <?php
                        if ($_GET['error'] == 'password') echo "Mot de passe actuel incorrect ou nouveaux mots de passe non identiques.";
                        elseif ($_GET['error'] == 'photo') echo "Erreur lors de l'upload de la photo. Vérifiez le format (JPG, PNG, GIF).";
                    ?>
                </div>
            <?php endif; ?>

            <div class="settings-grid">

                <!-- Left Column -->
                <div style="display:flex;flex-direction:column;gap:2rem;">

                    <!-- Avatar Card -->
                    <div class="settings-card">
                        <div class="settings-card-center">
                            <div class="settings-avatar-wrap" onclick="document.getElementById('photo-upload').click();">
                                <img src="<?php echo htmlspecialchars($user->getPhotoUrl()); ?>" alt="Profile">
                                <div class="settings-avatar-overlay">
                                    <i data-lucide="camera"></i>
                                </div>
                            </div>
                            <p class="settings-card-name"><?php echo htmlspecialchars($user->getNom()); ?></p>
                            <p class="settings-card-role">Aventurier</p>

                            <form action="index.php?page=user&action=settings" method="POST" enctype="multipart/form-data" id="photo-form" style="width:100%">
                                <input type="hidden" name="action_type" value="update_photo">
                                <input type="file" name="photo" id="photo-upload" style="display:none" accept="image/*"
                                    onchange="document.getElementById('photo-form').submit();">
                                <button type="button" class="btn-avatar-change" onclick="document.getElementById('photo-upload').click();">
                                    <i data-lucide="upload"></i> Changer l'Avatar
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Security Card -->
                    <div class="settings-card">
                        <h3 class="settings-section-title">
                            <i data-lucide="lock"></i> Sécurité
                        </h3>
                        <form action="index.php?page=user&action=settings" method="POST" class="settings-form">
                            <input type="hidden" name="action_type" value="update_password">
                            <div class="settings-form-field">
                                <label>Mot de passe actuel</label>
                                <input type="password" name="current_password" required class="settings-input settings-input--simple">
                            </div>
                            <div class="settings-form-field">
                                <label>Nouveau mot de passe</label>
                                <input type="password" name="new_password" required minlength="6" class="settings-input settings-input--simple">
                            </div>
                            <div class="settings-form-field">
                                <label>Confirmer mot de passe</label>
                                <input type="password" name="confirm_password" required minlength="6" class="settings-input settings-input--simple">
                            </div>
                            <hr class="settings-divider">
                            <button type="submit" class="btn-settings-security">
                                <i data-lucide="key"></i> Modifier
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Profile Info -->
                <div class="settings-card settings-card--wide">
                    <h3 class="settings-section-title settings-section-title--lg">
                        <i data-lucide="user-cog"></i> Informations Personnelles
                    </h3>
                    <form action="index.php?page=user&action=settings" method="POST" class="settings-form">
                        <input type="hidden" name="action_type" value="update_profile">
                        <div class="settings-form-field">
                            <label>Nom complet</label>
                            <div class="settings-input-wrap">
                                <i data-lucide="user"></i>
                                <input type="text" name="nom" value="<?php echo htmlspecialchars($user->getNom()); ?>" required class="settings-input">
                            </div>
                        </div>
                        <div class="settings-form-field">
                            <label>Adresse Email</label>
                            <div class="settings-input-wrap">
                                <i data-lucide="mail"></i>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required class="settings-input">
                            </div>
                        </div>
                        <div class="settings-form-field">
                            <label>Téléphone</label>
                            <div class="settings-input-wrap">
                                <i data-lucide="phone"></i>
                                <input type="tel" name="telephone" value="<?php echo htmlspecialchars($user->getTelephone()); ?>" class="settings-input">
                            </div>
                        </div>
                        <hr class="settings-divider">
                        <div class="settings-form-footer">
                            <button type="submit" class="btn-settings-save">
                                <i data-lucide="save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-bottom-spacer"></div>
        </div>
    </main>
</div>
