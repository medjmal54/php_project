<div class="auth-page">
    <div class="auth-card">
        
        <!-- Left Side: Immersive Brand Panel -->
        <div class="auth-panel-left">
            <div class="auth-panel-left-bg">
                <i data-lucide="mountain"></i>
            </div>
            <div class="auth-brand-content">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <i data-lucide="mountain" style="width:1.5rem;height:1.5rem;"></i>
                    <span style="font-family:var(--font-headline);font-size:1.25rem;font-weight:800;letter-spacing:-0.04em;text-transform:uppercase;">L'Échappée Sauvage</span>
                </div>
                <div style="margin:2.5rem 0;">
                    <h1 class="auth-brand-headline">
                        Redécouvrez <br/>la Tunisie <br/>à travers <br/>ses sentiers.
                    </h1>
                    <p class="auth-brand-sub">
                        Rejoignez la plus grande communauté de randonneurs et explorez des paysages préservés, du Djebel Bargou aux oasis du sud.
                    </p>
                </div>
                <div class="auth-brand-footer">Expert en Aventure Tunisienne</div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-panel-right">
            <div class="auth-form-inner">
                <!-- Mobile Logo -->
                <div class="auth-mobile-logo">
                    <div class="auth-mobile-logo-inner">
                        <i data-lucide="mountain"></i>
                        <span>L'Échappée Sauvage</span>
                    </div>
                </div>

                <div class="auth-heading">
                    <h2>Bon retour</h2>
                    <p>Accédez à votre espace randonneur.</p>
                </div>

                <!-- Auth Toggle -->
                <div class="auth-toggle">
                    <a href="index.php?page=auth&action=login" class="auth-toggle-link active">Se connecter</a>
                    <a href="index.php?page=auth&action=register" class="auth-toggle-link">S'inscrire</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="auth-alert-error">
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
                    <div class="auth-alert-success">
                        <p>Compte créé avec succès ! Connectez-vous.</p>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=auth&action=login" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email" class="form-label">Adresse e-mail</label>
                        <div class="form-input-wrap">
                            <i data-lucide="mail" class="form-input-icon"></i>
                            <input class="form-input" id="email" name="email" placeholder="nom@exemple.com" required type="email"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label for="password" class="form-label">Mot de passe</label>
                            <a href="#" class="form-forgot">Oublié ?</a>
                        </div>
                        <div class="form-input-wrap">
                            <i data-lucide="lock" class="form-input-icon"></i>
                            <input class="form-input" id="password" name="password" placeholder="••••••••" required type="password"/>
                            <button type="button" class="form-input-btn-end">
                                <i data-lucide="eye" style="width:1.25rem;height:1.25rem;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-checkbox-row">
                        <input type="checkbox" id="remember" class="form-checkbox">
                        <label for="remember" class="form-checkbox-label">Rester connecté</label>
                    </div>

                    <button type="submit" class="btn-auth-submit">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
