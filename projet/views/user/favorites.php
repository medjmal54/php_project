<div class="user-layout">
    <?php include 'views/layout/user_sidebar.php'; ?>

    <main class="user-main custom-scrollbar">
        <div class="admin-content">
            <header class="admin-header">
                <div class="fav-header-content">
                    <div class="fav-icon-badge">
                        <i data-lucide="heart"></i>
                    </div>
                    <div>
                        <h1 class="admin-header-title">Mes Favoris</h1>
                        <p class="admin-header-sub">Vivez à nouveau vos coups de cœur et planifiez votre prochaine escapade.</p>
                    </div>
                </div>
                <?php include 'views/layout/user_header_actions.php'; ?>
            </header>

            <?php if (empty($favorites)): ?>
                <div class="fav-empty-state">
                    <div class="fav-empty-illustration">
                        <div class="illustration-circle"></div>
                        <i data-lucide="heart-off"></i>
                    </div>
                    <h2>Votre liste est vide</h2>
                    <p>On dirait que vous n'avez pas encore trouvé l'aventure qui fera battre votre cœur.</p>
                    <a href="index.php?page=randonnee&action=index" class="btn-explore-fav">
                        <span>Découvrir la Tunisie</span>
                        <i data-lucide="compass"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="modern-grid-favorites">
                    <?php foreach ($favorites as $r): ?>
                        <?php 
                            // Duration Logic
                            $duration = '1 J';
                            try {
                                if ($r->getDateDebut() && $r->getDateFin()) {
                                    $start = new DateTime($r->getDateDebut());
                                    $end = new DateTime($r->getDateFin());
                                    $diff = $start->diff($end);
                                    $duration = ($diff->days > 0) ? ($diff->days . ' Jours') : (($diff->h ?: 8) . 'h');
                                }
                            } catch (Exception $e) { $duration = 'Expédition'; }
                        ?>
                        <div class="rando-card-v2" id="fav-card-<?php echo $r->getId(); ?>">
                            <div class="rando-card-media" onclick="window.location.href='index.php?page=randonnee&action=details&id=<?php echo $r->getId(); ?>'">
                                <img src="<?php echo $r->getImage(); ?>">
                                
                                <div class="media-badges">
                                    <span class="m-badge m-badge--loc"><?php echo htmlspecialchars(strtoupper($r->getRegion())); ?></span>
                                    <span class="m-badge m-badge--diff"><?php echo htmlspecialchars(strtoupper($r->getDifficulte())); ?></span>
                                </div>

                                <button class="m-fav-btn active" onclick="toggleFav(event, <?php echo $r->getId(); ?>)">
                                    <i data-lucide="heart" fill="currentColor"></i>
                                </button>
                            </div>
                            
                            <div class="rando-card-info">
                                <div class="info-header">
                                    <h3 class="info-title"><?php echo htmlspecialchars($r->getTitre()); ?></h3>
                                    <div class="info-price">
                                        <span class="p-val"><?php echo number_format($r->getPrix(), 0); ?></span>
                                        <span class="p-unit">DT</span>
                                    </div>
                                </div>
                                
                                <p class="info-desc"><?php echo htmlspecialchars(substr($r->getDescription(), 0, 90) . '...'); ?></p>
                                
                                <div class="info-footer">
                                    <div class="footer-meta">
                                        <div class="meta-item">
                                            <i data-lucide="clock"></i>
                                            <span><?php echo $duration; ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i data-lucide="mountain"></i>
                                            <span><?php echo $r->getDistance(); ?>km</span>
                                        </div>
                                    </div>
                                    <a href="index.php?page=randonnee&action=details&id=<?php echo $r->getId(); ?>" class="info-details-link">
                                        Détails <i data-lucide="arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="admin-bottom-spacer"></div>
        </div>
    </main>
</div>

<script>
function toggleFav(e, randoId) {
    e.stopPropagation();
    const card = document.getElementById(`fav-card-${randoId}`);
    
    // Disable interaction immediately to prevent double-clicks
    card.style.pointerEvents = 'none';
    
    fetch(`index.php?page=user&action=toggle_favorite&id=${randoId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                // Smooth scale-out and fade-out since we're on the favorites page
                card.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8) translateY(20px)';
                card.style.filter = 'blur(10px)';
                
                setTimeout(() => {
                    card.remove();
                    // If no cards left, check and show empty state
                    const remaining = document.querySelectorAll('.rando-card-v2');
                    if (remaining.length === 0) {
                        location.reload(); 
                    }
                }, 500);
            }
        });
}
</script>

<style>
.rando-card-fav.active {
    color: #ef4444;
}
</style>
