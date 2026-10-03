<?php
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;
require_once 'models/FavoriteModel.php';
?>

<?php if ($isLoggedIn): ?>
<div class="admin-layout">
    <div class="admin-main custom-scrollbar">
        <div class="admin-content">
            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Explorez la Tunisie</h1>
                    <p class="admin-header-sub">Trouvez votre prochaine aventure parmi nos expéditions sélectionnées.</p>
                </div>
                <?php include 'views/layout/user_header_actions.php'; ?>
            </header>
<?php endif; ?>

<div class="rando-page <?php echo !$isLoggedIn ? 'page-with-nav' : ''; ?>">
    
    <?php if (!$isLoggedIn): ?>
    <div class="rando-page-hero">
        <h1>Explorez la Tunisie</h1>
        <p>Découvrez des sentiers secrets entre mer et désert, guidés par la passion de l'aventure authentique.</p>
    </div>
    <?php endif; ?>

    <div class="rando-layout" id="randoLayout" style="<?php echo $isLoggedIn ? 'gap: 2rem;' : ''; ?>">
        
        <!-- Main Content (First in DOM for better order when filters are on right) -->
        <div class="rando-main">
            <?php if (empty($randonnees)): ?>
                <div class="rando-empty">
                    <div class="rando-empty-icon"><i data-lucide="mountain-snow"></i></div>
                    <h3>Aucune aventure trouvée</h3>
                    <p>Modifier vos filtres pour explorer d'autres destinations.</p>
                </div>
            <?php else: ?>
                <div class="rando-grid">
                    <?php foreach ($randonnees as $r): ?>
                        <?php 
                            $isFav = $isLoggedIn ? FavoriteModel::isFavorite($userId, $r->getId()) : false; 
                            
                            // Safe Duration Logic
                            $duration = '1 J'; // Default
                            try {
                                $dateStartValue = $r->getDateDebut();
                                $dateEndValue = $r->getDateFin();
                                if ($dateStartValue && $dateEndValue) {
                                    $start = new DateTime($dateStartValue);
                                    $end = new DateTime($dateEndValue);
                                    $diff = $start->diff($end);
                                    if ($diff->days > 0) {
                                        $duration = $diff->days . ' Jours';
                                    } else {
                                        $duration = ($diff->h ?: 8) . 'h';
                                    }
                                }
                            } catch (Exception $e) {
                                $duration = 'Expédition';
                            }
                        ?>
                        <div class="rando-card-v2">
                            <div class="rando-card-media" onclick="window.location.href='index.php?page=randonnee&action=details&id=<?php echo $r->getId(); ?>'">
                                <img src="<?php echo $r->getImage() ?: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b'; ?>" alt="">
                                
                                <div class="media-badges">
                                    <span class="m-badge m-badge--loc"><?php echo htmlspecialchars(strtoupper($r->getRegion())); ?></span>
                                    <span class="m-badge m-badge--diff"><?php echo htmlspecialchars(strtoupper($r->getDifficulte())); ?></span>
                                </div>

                                <button class="m-fav-btn <?php echo $isFav ? 'active' : ''; ?>" onclick="toggleFav(event, <?php echo $r->getId(); ?>)">
                                    <i data-lucide="heart" <?php echo $isFav ? 'fill="currentColor"' : ''; ?>></i>
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
                <!-- Pagination removed as requested -->
            <?php endif; ?>
        </div>

        <!-- Sidebar Filters (Moved to Right) -->
        <aside class="rando-sidebar">
            <form action="index.php" method="GET" class="filter-form">
                <input type="hidden" name="page" value="randonnee">
                <input type="hidden" name="action" value="index">
                
                <div class="filter-group">
                    <h3 class="filter-label">Recherche</h3>
                    <div class="filter-search-wrap">
                        <input type="text" name="region" value="<?php echo htmlspecialchars($filters['region'] ?? ''); ?>" placeholder="Djebel, Oasis, Canyon..." class="filter-input">
                        <i data-lucide="search" class="filter-search-icon"></i>
                    </div>
                </div>
                
                <div class="filter-group">
                    <h3 class="filter-label">Catégories</h3>
                    <div class="filter-pills">
                        <?php
                        $cats = ['', 'Montagne', 'Désert', 'Côte', 'Forêt'];
                        $catLabels = ['Tous', 'Montagne', 'Désert', 'Côte', 'Forêt'];
                        foreach ($cats as $i => $cat):
                            $isSelected = ($filters['categorie'] ?? '') == $cat;
                        ?>
                        <label class="filter-pill <?php echo $isSelected ? 'active' : ''; ?>">
                            <input type="radio" name="categorie" value="<?php echo $cat; ?>" <?php echo $isSelected ? 'checked' : ''; ?>>
                            <span><?php echo $catLabels[$i]; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="filter-group">
                    <h3 class="filter-label">Difficulté</h3>
                    <div class="filter-radios">
                        <?php
                        $diffs = ['' => 'Toutes', 'Facile' => 'Débutant', 'Moyen' => 'Intermédiaire', 'Difficile' => 'Expert'];
                        foreach ($diffs as $val => $label):
                            $isSelected = ($filters['difficulte'] ?? '') == $val;
                        ?>
                        <label class="filter-radio-row">
                            <input type="radio" name="difficulte" value="<?php echo $val; ?>" <?php echo $isSelected ? 'checked' : ''; ?>>
                            <span class="custom-radio">
                                <i data-lucide="check"></i>
                            </span>
                            <span class="radio-label"><?php echo $label; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h3 class="filter-label">Régions</h3>
                    <div class="filter-select-wrap">
                        <select name="region_filter" class="filter-select">
                            <option value="">Toutes les régions</option>
                            <option value="Zaghouan" <?php echo ($filters['region_filter'] ?? '') == 'Zaghouan' ? 'selected' : ''; ?>>Zaghouan</option>
                            <option value="Douz" <?php echo ($filters['region_filter'] ?? '') == 'Douz' ? 'selected' : ''; ?>>Douz</option>
                            <option value="Ain Draham" <?php echo ($filters['region_filter'] ?? '') == 'Ain Draham' ? 'selected' : ''; ?>>Ain Draham</option>
                        </select>
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <div class="filter-budget-header">
                        <h3 class="filter-label">Budget (DT)</h3>
                        <div class="filter-budget-val">
                            <span id="minVal"><?php echo htmlspecialchars($filters['prix_min'] ?? '0'); ?></span>
                            <span> - </span>
                            <span id="maxVal"><?php echo htmlspecialchars($filters['prix_max'] ?? '300'); ?></span>
                        </div>
                    </div>
                    <div class="dual-range-slider" id="budgetSlider">
                        <div class="slider-track"></div>
                        <input type="range" name="prix_min" min="0" max="300" value="<?php echo htmlspecialchars($filters['prix_min'] ?? '0'); ?>" class="range-input min-range">
                        <input type="range" name="prix_max" min="0" max="300" value="<?php echo htmlspecialchars($filters['prix_max'] ?? '300'); ?>" class="range-input max-range">
                    </div>
                </div>

                <button type="submit" class="filter-apply-btn">
                    <span>Appliquer les filtres</span>
                    <i data-lucide="arrow-right"></i>
                </button>
            </form>
        </aside>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('budgetSlider');
    const minInput = slider.querySelector('.min-range');
    const maxInput = slider.querySelector('.max-range');
    const track = slider.querySelector('.slider-track');
    const minDisplay = document.getElementById('minVal');
    const maxDisplay = document.getElementById('maxVal');

    function updateSlider() {
        const min = parseInt(minInput.value);
        const max = parseInt(maxInput.value);

        if (min > max - 10) {
            if (event?.target === minInput) minInput.value = max - 10;
            else maxInput.value = min + 10;
        }

        const percent1 = (minInput.value / minInput.max) * 100;
        const percent2 = (maxInput.value / maxInput.max) * 100;

        track.style.left = percent1 + '%';
        track.style.width = (percent2 - percent1) + '%';

        minDisplay.textContent = minInput.value;
        maxDisplay.textContent = maxInput.value;
    }

    minInput.addEventListener('input', updateSlider);
    maxInput.addEventListener('input', updateSlider);
    updateSlider(); // Init
});
</script>

<?php if ($isLoggedIn): ?>
            <div class="admin-bottom-spacer"></div>
        </div>
    </div>
</div>
<?php endif; ?>

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
.rando-card-fav.active {
    color: #ef4444;
}
</style>
