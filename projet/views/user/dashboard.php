<?php
//longueur
$total_inscriptions = count($participations);
//Tableaux filtrés selon le statut
$accepted = array_filter($participations, function($p) { return strtolower($p->getStatut()) == 'acceptée'; });
$pending  = array_filter($participations, function($p) { return strtolower($p->getStatut()) == 'en attente'; });
$total_spent = 0;
foreach ($accepted as $p) { $total_spent += floatval($p->getPrix()); }
?>
<div class="user-layout">

    <!-- Sidebar -->
    <?php include 'views/layout/user_sidebar.php'; ?>

    <!-- Main -->
    <main class="user-main custom-scrollbar">
        <div class="admin-content">

            <?php if (isset($_GET['success']) && $_GET['success'] == 'participation_envoyee'): ?>
                <div class="user-alert user-alert--success">
                    <i data-lucide="check-circle"></i>
                    Votre demande a été envoyée ! L'administrateur va l'examiner prochainement.
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'deja_inscrit'): ?>
                <div class="user-alert user-alert--error">
                    <i data-lucide="alert-circle"></i>
                    Vous êtes déjà inscrit à cette randonnée.
                </div>
            <?php endif; ?>

            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Mon Tableau de bord</h1>
                    <p class="admin-header-sub">Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> ! Retrouvez ici vos inscriptions et votre historique.</p>
                </div>
                <?php include 'views/layout/user_header_actions.php'; ?>
            </header>

            <!-- Stats -->
            <div class="admin-stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--mint"><i data-lucide="map"></i></div>
                    </div>
                    <p class="stat-label">Total Inscriptions</p>
                    <h3 class="stat-value"><?php echo $total_inscriptions; ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--teal"><i data-lucide="check-circle"></i></div>
                    </div>
                    <p class="stat-label">Acceptées</p>
                    <h3 class="stat-value"><?php echo count($accepted); ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--orange"><i data-lucide="clock"></i></div>
                    </div>
                    <p class="stat-label">En Attente</p>
                    <h3 class="stat-value"><?php echo count($pending); ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--blue"><i data-lucide="wallet"></i></div>
                    </div>
                    <p class="stat-label">Dépensé</p>
                    <div class="stat-value-row">
                        <h3 class="stat-value"><?php echo number_format($total_spent, 0, ',', ' '); ?></h3>
                        <span class="stat-unit">DT</span>
                    </div>
                </div>
            </div>

            <!-- Participations Table -->
            <div class="admin-panel">
                <div class="admin-panel-header">
                    <h2 class="admin-panel-title">Mes Aventures</h2>
                    <div class="panel-search-wrap">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Rechercher..." class="panel-search">
                    </div>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th>Région</th>
                                <th>Date</th>
                                <th>Prix</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($participations)): ?>
                                <tr>
                                    <td colspan="5" class="td-empty">
                                        Vous n'avez pas encore participé à des randonnées.
                                        <a href="index.php?page=randonnee&action=index" style="color:var(--color-secondary);font-weight:700;margin-left:0.5rem;">Découvrir</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($participations as $p): ?>
                                <tr>
                                    <td>
                                        <div class="td-dest">
                                            <div class="td-dest-icon"><i data-lucide="mountain"></i></div>
                                            <div>
                                                <p class="td-dest-name"><?php echo htmlspecialchars($p->getTitre()); ?></p>
                                                <p class="td-dest-sub">Expédition</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="region-badge"><?php echo htmlspecialchars($p->getRegion() ?? 'Tunisie'); ?></span>
                                    </td>
                                    <td>
                                        <p class="td-user-name"><?php echo $p->getDateDebut() ? date('d/m/Y', strtotime($p->getDateDebut())) : '—'; ?></p>
                                    </td>
                                    <td>
                                        <span style="font-size:0.875rem;font-weight:700;color:var(--color-secondary);"><?php echo number_format(floatval($p->getPrix()), 2); ?> DT</span>
                                    </td>
                                    <td>
                                        <?php
                                        $s = strtolower($p->getStatut());
                                        if ($s == 'en attente')  echo '<span class="badge-pending">En Attente</span>';
                                        elseif ($s == 'acceptée') echo '<span class="badge-accepted">Acceptée</span>';
                                        else echo '<span class="badge-refused">Refusée</span>';
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="admin-bottom-spacer"></div>
        </div>
    </main>
</div>
