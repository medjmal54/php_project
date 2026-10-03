<?php
// Split participations into pending and treated
$pending   = array_filter($participations, fn($p) => strtolower($p->getStatut()) === 'en attente');
$treated   = array_filter($participations, fn($p) => strtolower($p->getStatut()) !== 'en attente');

// Build a lookup: id_user => all their participations (for the user modal)
$userHistory = [];
foreach ($participations as $p) {
    $uid = $p->getIdUser();
    if (!isset($userHistory[$uid])) {
        $userHistory[$uid] = [
            'name'     => $p->getUserName(),
            'email'    => $p->getEmail(),
            'photo'    => $p->getPhotoUrl(),
            'history'  => []
        ];
    }
    $userHistory[$uid]['history'][] = $p;
}
?>
<div class="admin-layout">

    <?php require_once 'views/layout/admin_sidebar.php'; ?>

    <main class="admin-main custom-scrollbar">
        <div class="admin-content">

            <?php if (isset($_GET['error']) && $_GET['error'] == 'complet'): ?>
                <div class="admin-alert admin-alert--error">
                    <i data-lucide="alert-circle"></i>
                    Impossible d'accepter : La randonnée est déjà complète.
                </div>
            <?php endif; ?>

            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Gestion des Participations</h1>
                    <p class="admin-header-sub">Examinez et validez les demandes d'inscription reçues.</p>
                <?php require 'views/layout/admin_header_actions.php'; ?>
            </header>

            <!-- ===== SECTION 1: PENDING DEPOT ===== -->
            <div class="admin-section-label admin-section-label--pending">
                <i data-lucide="inbox"></i>
                Demandes en attente
                <span class="count-badge count-badge--urgent"><?php echo count($pending); ?></span>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-header-count">
                        <h2 class="admin-panel-title">À traiter</h2>
                    </div>
                    <div class="panel-search-wrap">
                        <i data-lucide="search"></i>
                        <input type="text" id="search-pending" placeholder="Rechercher un explorateur..." class="panel-search" oninput="filterTable('pending-tbody', this.value)">
                    </div>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Explorateur</th>
                                <th>Destination</th>
                                <th>Date demande</th>
                                <th>Décision</th>
                            </tr>
                        </thead>
                        <tbody id="pending-tbody">
                            <?php if (empty($pending)): ?>
                                <tr><td colspan="4" class="td-empty">
                                    <i data-lucide="check-circle-2" style="width:1.25rem;height:1.25rem;color:var(--color-secondary);"></i>
                                    Aucune demande en attente. Tout est traité !
                                </td></tr>
                            <?php else: ?>
                                <?php foreach ($pending as $p): ?>
                                <tr>
                                    <td>
                                        <div class="td-user" onclick="openUserModal(<?php echo $p->getIdUser(); ?>)" style="cursor:pointer;" title="Voir le profil">
                                            <div class="td-avatar"><img src="<?php echo htmlspecialchars($p->getPhotoUrl()); ?>" alt="Avatar"></div>
                                            <div>
                                                <p class="td-user-name td-user-name--link"><?php echo htmlspecialchars($p->getUserName()); ?></p>
                                                <p class="td-user-sub"><?php echo htmlspecialchars($p->getEmail()); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-dest">
                                            <div class="td-dest-icon"><i data-lucide="map-pin"></i></div>
                                            <p class="td-dest-name"><?php echo htmlspecialchars($p->getTitre()); ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <?php $d = new DateTime($p->getDateCreation()); ?>
                                        <p style="font-size:0.875rem;font-weight:500;color:var(--color-primary);"><?php echo $d->format('d M, Y'); ?></p>
                                        <p class="td-user-sub"><?php echo $d->format('h:i A'); ?></p>
                                    </td>
                                    <td>
                                        <div class="td-decision">
                                            <a href="index.php?page=admin&action=update_participation_status&id=<?php echo $p->getId(); ?>&status=refusée"
                                               class="btn-decision btn-decision--refuse">
                                                <i data-lucide="x"></i> Refuser
                                            </a>
                                            <a href="index.php?page=admin&action=update_participation_status&id=<?php echo $p->getId(); ?>&status=acceptée"
                                               class="btn-decision btn-decision--approve">
                                                <i data-lucide="check"></i> Accepter
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== SECTION 2: HISTORY ===== -->
            <div class="admin-section-label" style="margin-top:2rem;">
                <i data-lucide="history"></i>
                Historique des demandes traitées
                <span class="count-badge"><?php echo count($treated); ?></span>
            </div>

            <div class="admin-panel admin-panel--muted">
                <div class="admin-panel-header">
                    <div class="admin-panel-header-count">
                        <h2 class="admin-panel-title">Traitées</h2>
                    </div>
                    <div class="panel-search-wrap">
                        <i data-lucide="search"></i>
                        <input type="text" id="search-treated" placeholder="Rechercher..." class="panel-search" oninput="filterTable('treated-tbody', this.value)">
                    </div>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table admin-table--muted">
                        <thead>
                            <tr>
                                <th>Explorateur</th>
                                <th>Destination</th>
                                <th>Statut</th>
                                <th>Date demande</th>
                            </tr>
                        </thead>
                        <tbody id="treated-tbody">
                            <?php if (empty($treated)): ?>
                                <tr><td colspan="4" class="td-empty">Aucune demande traitée pour l'instant.</td></tr>
                            <?php else: ?>
                                <?php foreach ($treated as $p): ?>
                                <tr>
                                    <td>
                                        <div class="td-user" onclick="openUserModal(<?php echo $p->getIdUser(); ?>)" style="cursor:pointer;" title="Voir le profil">
                                            <div class="td-avatar"><img src="<?php echo htmlspecialchars($p->getPhotoUrl()); ?>" alt="Avatar"></div>
                                            <div>
                                                <p class="td-user-name td-user-name--link"><?php echo htmlspecialchars($p->getUserName()); ?></p>
                                                <p class="td-user-sub"><?php echo htmlspecialchars($p->getEmail()); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-dest">
                                            <div class="td-dest-icon"><i data-lucide="map-pin"></i></div>
                                            <p class="td-dest-name"><?php echo htmlspecialchars($p->getTitre()); ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statut = strtolower($p->getStatut());
                                        if ($statut === 'acceptée') echo '<span class="badge-accepted">Confirmé</span>';
                                        else echo '<span class="badge-refused">Refusé</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php $d = new DateTime($p->getDateCreation()); ?>
                                        <p style="font-size:0.875rem;color:var(--color-text-muted);"><?php echo $d->format('d M, Y'); ?></p>
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

<!-- ===== USER PROFILE MODAL ===== -->
<div id="user-modal-overlay" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeUserModal()"></div>
    <div class="modal-center">
        <div id="user-modal-box" class="modal-box modal-box--lg">
            <div class="modal-inner">
                <div class="modal-header">
                    <div id="user-modal-header-info" style="display:flex;align-items:center;gap:1rem;">
                        <div class="td-avatar td-avatar--lg">
                            <img id="user-modal-photo" src="" alt="Photo">
                        </div>
                        <div>
                            <h3 class="modal-title" id="user-modal-name">—</h3>
                            <p class="modal-subtitle" id="user-modal-email">—</p>
                        </div>
                    </div>
                    <button class="modal-close" onclick="closeUserModal()"><i data-lucide="x"></i></button>
                </div>

                <div style="padding: 1.5rem;">
                    <h4 style="font-size:0.875rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--color-text-muted);margin-bottom:1rem;">
                        Historique des participations
                    </h4>
                    <div id="user-modal-history"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Embed user data as JSON for JS modal -->
<script>
const userHistoryData = <?php echo json_encode(array_map(function($uid, $u) {
    return [
        'id'    => $uid,
        'name'  => $u['name'],
        'email' => $u['email'],
        'photo' => $u['photo'],
        'history' => array_map(fn($p) => [
            'titre'   => $p->getTitre(),
            'statut'  => $p->getStatut(),
            'date'    => $p->getDateCreation(),
        ], array_values($u['history']))
    ];
}, array_keys($userHistory), array_values($userHistory))); ?>;

function openUserModal(userId) {
    const user = userHistoryData.find(u => u.id == userId);
    if (!user) return;

    document.getElementById('user-modal-photo').src = user.photo;
    document.getElementById('user-modal-name').textContent = user.name;
    document.getElementById('user-modal-email').textContent = user.email;

    const container = document.getElementById('user-modal-history');
    if (!user.history.length) {
        container.innerHTML = '<p style="color:var(--color-text-muted);font-size:0.875rem;">Aucune participation trouvée.</p>';
    } else {
        container.innerHTML = user.history.map(h => {
            const statut = h.statut.toLowerCase();
            let badgeClass = 'badge-pending';
            if (statut === 'acceptée') badgeClass = 'badge-accepted';
            else if (statut === 'refusée') badgeClass = 'badge-refused';
            const d = new Date(h.date);
            const dateStr = d.toLocaleDateString('fr-FR', { day:'2-digit', month:'short', year:'numeric' });
            return `
            <div class="user-history-row">
                <div class="user-history-dot"></div>
                <div class="user-history-info">
                    <p class="user-history-titre">${h.titre}</p>
                    <p class="user-history-date">${dateStr}</p>
                </div>
                <span class="${badgeClass}">${h.statut}</span>
            </div>`;
        }).join('');
    }

    const overlay = document.getElementById('user-modal-overlay');
    const box     = document.getElementById('user-modal-box');
    overlay.classList.add('open');
    setTimeout(() => box.classList.add('open'), 10);
    lucide.createIcons();
}

function closeUserModal() {
    const overlay = document.getElementById('user-modal-overlay');
    const box     = document.getElementById('user-modal-box');
    box.classList.remove('open');
    setTimeout(() => overlay.classList.remove('open'), 300);
}

function filterTable(tbodyId, query) {
    const rows = document.getElementById(tbodyId).querySelectorAll('tr');
    const q = query.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
