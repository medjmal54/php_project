<?php
/**
 * User Sidebar Partial
 */
$current_page = $_GET['page'] ?? 'home';
$current_action = $_GET['action'] ?? 'index';
?>
<aside class="user-sidebar admin-sidebar">
    <div class="sidebar-logo">
        <a href="index.php"><i data-lucide="mountain-snow"></i> L'Échappée Sauvage</a>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php?page=user&action=dashboard" class="sidebar-link <?php echo ($current_page == 'user' && $current_action == 'dashboard') ? 'active' : ''; ?>">
            <i data-lucide="layout-dashboard"></i> L'Expérience
        </a>
        <a href="index.php?page=randonnee&action=index" class="sidebar-link <?php echo ($current_page == 'randonnee') ? 'active' : ''; ?>">
            <i data-lucide="compass"></i> Explorer
        </a>
        <?php if ($current_page == 'randonnee' && $current_action == 'index'): ?>
        <a href="javascript:void(0)" class="sidebar-link sidebar-link--toggle" id="filtersToggle" onclick="toggleExplorerFilters()">
            <i data-lucide="sliders-horizontal"></i> Filtres
        </a>
        <?php endif; ?>
        <a href="index.php?page=user&action=favorites" class="sidebar-link <?php echo ($current_page == 'user' && $current_action == 'favorites') ? 'active' : ''; ?>">
            <i data-lucide="heart"></i> Favoris
        </a>
        <a href="index.php?page=user&action=settings" class="sidebar-link sidebar-link--settings <?php echo ($current_page == 'user' && $current_action == 'settings') ? 'active' : ''; ?>">
            <i data-lucide="settings"></i> Paramètres
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <div class="sidebar-avatar">
                <img src="<?php echo htmlspecialchars($_SESSION['photo_url'] ?? '/projet/public/uploads/profiles/default.png'); ?>" alt="User">
            </div>
            <div class="sidebar-profile-info">
                <p class="sidebar-profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Aventurier'); ?></p>
                <p class="sidebar-profile-role">Aventurier</p>
            </div>
        </div>
        <div style="margin-top: 1rem;">
            <a href="index.php?page=auth&action=logout" class="sidebar-logout" style="display:flex; align-items:center; gap:0.75rem; color:var(--text-muted); text-decoration:none; font-size:0.875rem; font-weight:600; padding:0.5rem; border-radius:0.5rem; transition:all 0.2s;">
                <i data-lucide="log-out" style="width:1.25rem;"></i> Déconnexion
            </a>
        </div>
    </div>
</aside>
