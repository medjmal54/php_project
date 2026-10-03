<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>L'Échappée Sauvage - Randonnées en Tunisie</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="/projet/public/assets/css/global.css">
    <?php
    global $page;
    $page = $page ?? ($_GET['page'] ?? 'home');
    $action = $_GET['action'] ?? 'index';

    $cssMap = [
        'home'      => ['home.css'],
        'auth'      => ['auth.css'],
        'randonnee' => ($action === 'details') ? ['randonnee-details.css'] : ['admin.css', 'randonnee-list.css'],
        'admin'     => ['admin.css'],
        'user'      => ['admin.css', 'user.css', 'randonnee-list.css'],
    ];
    foreach ($cssMap[$page] ?? [] as $css) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/projet/public/assets/css/' . $css;
        if (file_exists($filePath)) {
            $v = filemtime($filePath);
            echo '<link rel="stylesheet" href="/projet/public/assets/css/' . $css . '?v=' . $v . '">' . "\n    ";
        }
    }
    ?>
</head>
<body>

    <?php if (!in_array($page, ['auth', 'admin', 'user'])): ?>
    <!-- TopNavBar -->
    <nav class="topnav">
        <div style="display:flex;align-items:center;gap:2rem;">
            <a href="index.php" class="topnav-brand">L'Échappée Sauvage</a>
            <div class="topnav-links">
                <a href="index.php?page=home" <?php echo $page == 'home' ? 'class="active"' : ''; ?>>Découvrir</a>
                <a href="index.php?page=randonnee&action=index" <?php echo $page == 'randonnee' ? 'class="active"' : ''; ?>>Aventures</a>
            </div>
        </div>
        <div class="topnav-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="topnav-user">
                    <span class="topnav-username"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="index.php?page=<?php echo $_SESSION['role']; ?>&action=dashboard" class="topnav-icon-btn topnav-icon-btn--user">
                        <i data-lucide="user" style="width:1.125rem;height:1.125rem;"></i>
                    </a>
                    <a href="index.php?page=auth&action=logout" class="topnav-icon-btn topnav-icon-btn--logout">
                        <i data-lucide="log-out" style="width:1.125rem;height:1.125rem;"></i>
                    </a>
                </div>
            <?php else: ?>
                <a href="index.php?page=auth&action=login" class="topnav-btn-login">Connexion</a>
                <a href="index.php?page=auth&action=register" class="topnav-btn-register">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

    <main style="width:100%;">
