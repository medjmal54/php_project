<?php
/*
 * Admin Sidebar Partial - views/layout/admin_sidebar.php
 * Included in every admin view to avoid code duplication.
 */
$currentAction = $_GET['action'] ?? 'dashboard';

$navLinks = [
    ['url' => 'index.php?page=admin&action=dashboard',            'icon' => 'layout-dashboard', 'label' => 'Tableau de bord',  'action' => 'dashboard'],
    ['url' => 'index.php?page=admin&action=manage_randonnees',    'icon' => 'map',              'label' => 'Gérer Randonnées', 'action' => 'manage_randonnees'],
    ['url' => 'index.php?page=admin&action=manage_participations','icon' => 'users',            'label' => 'Participations',   'action' => 'manage_participations'],
    ['url' => 'index.php?page=admin&action=manage_buses',         'icon' => 'bus',              'label' => 'Gérer Bus',        'action' => 'manage_buses'],
    ['url' => 'index.php?page=admin&action=settings',             'icon' => 'settings',         'label' => 'Paramètres',       'action' => 'settings'],
];
?>
<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <a href="index.php">
            <i data-lucide="mountain-snow"></i> L'Échappée Sauvage
        </a>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($navLinks as $link): ?>
        <a href="<?php echo $link['url']; ?>"
           class="sidebar-link <?php echo $currentAction === $link['action'] ? 'active' : ''; ?>">
            <i data-lucide="<?php echo $link['icon']; ?>"></i>
            <?php echo $link['label']; ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <div class="sidebar-avatar">
                <img src="<?php echo htmlspecialchars($_SESSION['photo_url'] ?? '/projet/public/uploads/profiles/default.png'); ?>" alt="Admin">
            </div>
            <div class="sidebar-profile-info">
                <p class="sidebar-profile-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
                <p class="sidebar-profile-role">Administrateur</p>
            </div>
        </div>
        <a href="index.php?page=auth&action=logout" class="sidebar-logout">
            <i data-lucide="log-out"></i> Déconnexion
        </a>
    </div>
</aside>
