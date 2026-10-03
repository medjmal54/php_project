<div class="auth-page">
    <div class="auth-card auth-card--wide">
        
        <!-- Left Side: Desert Image -->
        <div class="auth-panel-left">
            <img class="auth-panel-left-img" alt="Désert tunisien au coucher du soleil" src="https://images.unsplash.com/photo-1542332213-31f87348057f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80"/>
            <div class="auth-panel-left-img-overlay"></div>
            <div class="auth-brand-content auth-brand-content--bottom">
                <div class="auth-brand-logo">
                    <i data-lucide="mountain"></i>
                    <span>L'Échappée Sauvage</span>
                </div>
                <div>
                    <h1 class="auth-brand-headline">
                        Commencez <br/>votre voyage
                    </h1>
                    <p class="auth-brand-sub">
                        Découvrez la beauté sauvage de la Tunisie, des crêtes de l'Atlas aux sables infinis du Grand Erg Oriental.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="auth-panel-right auth-panel-right--white">
            <div class="auth-form-inner">
                <!-- Mobile Logo -->
                <div class="auth-mobile-logo">
                    <div class="auth-mobile-logo-inner">
                        <i data-lucide="mountain"></i>
                        <span>L'Échappée Sauvage</span>
                    </div>
                </div>

                <div class="auth-heading">
                    <h2>Créer un compte</h2>
                    <p>Rejoignez la communauté.</p>
                </div>

                <!-- Auth Toggle -->
                <div class="auth-toggle">
                    <a href="index.php?page=auth&action=login" class="auth-toggle-link">Se connecter</a>
                    <a href="index.php?page=auth&action=register" class="auth-toggle-link active">S'inscrire</a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="auth-alert-error">
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=auth&action=register" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="nom" class="form-label">Nom complet</label>
                        <div class="form-input-wrap">
                            <i data-lucide="user" class="form-input-icon" style="width:1rem;height:1rem;"></i>
                            <input class="form-input form-input--surface" id="nom" name="nom" placeholder="Elias Ben Amor" required type="text"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="form-input-wrap">
                            <i data-lucide="mail" class="form-input-icon" style="width:1rem;height:1rem;"></i>
                            <input class="form-input form-input--surface" id="email" name="email" placeholder="votre@email.com" required type="email"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <div class="form-input-wrap">
                            <i data-lucide="phone" class="form-input-icon" style="width:1rem;height:1rem;"></i>
                            <input class="form-input form-input--surface" id="telephone" name="telephone" placeholder="216 22 345 678" required type="tel"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <div class="form-input-wrap">
                            <i data-lucide="lock-keyhole" class="form-input-icon" style="width:1rem;height:1rem;"></i>
                            <input class="form-input form-input--surface" id="password" name="password" placeholder="••••••••" required type="password"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                        <div class="form-input-wrap">
                            <i data-lucide="shield-check" class="form-input-icon" style="width:1rem;height:1rem;"></i>
                            <input class="form-input form-input--surface" id="confirm_password" name="confirm_password" placeholder="••••••••" required type="password"/>
                        </div>
                    </div>

                    <div class="form-checkbox-row-top">
                        <input type="checkbox" id="terms" required class="form-checkbox">
                        <label for="terms" class="form-checkbox-label">
                            J'accepte les <a href="#">Conditions</a> et la <a href="#">Politique de confidentialité</a>.
                        </label>
                    </div>

                    <button class="btn-auth-submit" type="submit">
                        S'inscrire <i data-lucide="arrow-right" style="width:1.25rem;height:1.25rem;"></i>
                    </button>
                </form>

                <div class="auth-lang">
                    <div class="auth-lang-inner">
                        <span>Français (FR)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
