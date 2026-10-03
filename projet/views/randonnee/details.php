<div class="details-page page-with-nav" style="max-width:75rem;margin:0 auto;padding:0 1.5rem 3rem;">
    <div class="details-banner" style="background-image: url('<?php echo $randonnee->getImage() ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'; ?>');">
        <div class="details-overlay">
            <?php 
                $isLoggedIn = isset($_SESSION['user_id']);
                if ($isLoggedIn) {
                    require_once 'models/FavoriteModel.php';
                    $isFav = FavoriteModel::isFavorite($_SESSION['user_id'], $randonnee->getId());
                }
            ?>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <span class="r-badge"><?php echo htmlspecialchars($randonnee->getCategorie()); ?></span>
                <?php if ($isLoggedIn): ?>
                <button class="rando-card-fav <?php echo $isFav ? 'active' : ''; ?>" onclick="toggleFav(event, <?php echo $randonnee->getId(); ?>)" style="position:static; padding:0.75rem; background:rgba(255,255,255,0.1); backdrop-filter:blur(10px); color:white; border-radius:50%; border:1px solid rgba(255,255,255,0.2);">
                    <i data-lucide="heart" <?php echo $isFav ? 'fill="currentColor"' : ''; ?>></i>
                </button>
                <?php endif; ?>
            </div>
            <h1><?php echo htmlspecialchars($randonnee->getTitre()); ?></h1>
            <p>📍 <?php echo htmlspecialchars($randonnee->getRegion()); ?> | 🗓️ Du <?php echo date('d/m/Y H:i', strtotime($randonnee->getDateDebut())); ?> au <?php echo date('d/m/Y H:i', strtotime($randonnee->getDateFin())); ?></p>
        </div>
    </div>
    <script>
    function toggleFav(e, randoId) {
        e.stopPropagation();
        const btn = e.currentTarget;
        const icon = btn.querySelector('i');
        
        fetch(`index.php?page=user&action=toggle_favorite&id=${randoId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'added') {
                    btn.classList.add('active');
                    icon.setAttribute('fill', 'currentColor');
                } else {
                    btn.classList.remove('active');
                    icon.setAttribute('fill', 'none');
                }
                lucide.createIcons();
            });
    }
    </script>
    <style>
    .rando-card-fav.active { color: #ef4444; }
    </style>

    <div class="details-grid">
        <div>
            <div class="details-card">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($randonnee->getDescription())); ?></p>
            </div>
            
            <div class="details-card">
                <h3>Informations Complémentaires</h3>
                <ul class="features-list">
                    <li><i data-lucide="mountain"></i> <strong>Difficulté:</strong> <?php echo htmlspecialchars($randonnee->getDifficulte()); ?></li>
                    <li><i data-lucide="map"></i> <strong>Distance:</strong> <?php echo $randonnee->getDistance(); ?> km</li>
                    <li><i data-lucide="users"></i> <strong>Capacité:</strong> <?php echo $count; ?> / <?php echo $randonnee->getCapacite(); ?> places</li>
                    <li><i data-lucide="tag"></i> <strong>Prix:</strong> <?php echo number_format($randonnee->getPrix(), 2); ?> DT</li>
                </ul>
            </div>
        </div>

        <aside>
            <div class="booking-card">
                <h3>Participer</h3>
                <p class="price-big"><?php echo number_format($randonnee->getPrix(), 2); ?> <span>DT</span></p>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert-danger">
                        <?php 
                            if ($_GET['error'] == 'complet') echo "Désolé, cette randonnée est complète.";
                            elseif ($_GET['error'] == 'conflit_date') echo "Vous avez déjà une randonnée prévue à ces dates.";
                        ?>
                    </div>
                <?php endif; ?>

                <?php 
                $userRole = $_SESSION['role'] ?? 'guest';
                
                if ($userRole === 'admin'): ?>
                    <div class="status-box admin-view">
                        <i data-lucide="shield-check"></i>
                        Mode Organisateur
                    </div>
                    <a href="index.php?page=admin&action=manage_randonnees" class="btn-book" style="background: var(--color-secondary);">Gérer cette randonnée</a>
                <?php elseif ($alreadyParticipated): ?>
                    <div class="status-box <?php echo $alreadyParticipated->getStatut() == 'acceptée' ? 'accepted' : ($alreadyParticipated->getStatut() == 'refusée' ? 'refused' : 'pending'); ?>">
                        Statut: <strong><?php echo ucfirst($alreadyParticipated->getStatut()); ?></strong>
                    </div>
                <?php else: ?>
                    <a href="index.php?page=randonnee&action=participate&id=<?php echo $randonnee->getId(); ?>" class="btn-book">Réserver ma place</a>
                <?php endif; ?>
                
                <p class="booking-note">Paiement à effectuer lors du départ.</p>
            </div>
        </aside>

        <style>
        .status-box.admin-view {
            background: #f1f5f3;
            color: var(--color-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            border: 1px dashed rgba(1, 38, 31, 0.2);
        }
        .status-box.admin-view i { width: 1.25rem; height: 1.25rem; color: var(--color-secondary); }
        </style>
    </div>
</div>
