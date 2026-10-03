<div class="admin-layout">

    <?php require_once 'views/layout/admin_sidebar.php'; ?>

    <!-- Main -->
    <main class="admin-main custom-scrollbar">
        <div class="admin-content">

            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Gestion des Bus</h1>
                    <p class="admin-header-sub">Gérez votre flotte de transport pour les expéditions.</p>
                </div>
                <?php ob_start(); ?>
                <button class="btn-admin-primary" onclick="openAddBusModal()">
                    <i data-lucide="plus"></i> Nouveau Bus
                </button>
                <?php $headerActionsHtml = ob_get_clean(); ?>
                <?php require 'views/layout/admin_header_actions.php'; ?>
            </header>

            <!-- Table Panel -->
            <div class="admin-panel">
                <div class="admin-panel-header">
                    <h2 class="admin-panel-title">Flotte Actuelle</h2>
                    <div class="panel-search-wrap">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Rechercher..." class="panel-search">
                    </div>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Immatriculation</th>
                                <th>Chauffeur & Contact</th>
                                <th>Places</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($buses)): ?>
                                <tr><td colspan="4" class="td-empty">Vous n'avez pas encore configuré de bus.</td></tr>
                            <?php else: ?>
                                <?php foreach ($buses as $b): ?>
                                <tr>
                                    <td>
                                        <div class="td-dest">
                                            <div class="td-dest-icon"><i data-lucide="bus-front"></i></div>
                                            <div>
                                                <p class="td-dest-name"><?php echo htmlspecialchars($b->getImmatBus()); ?></p>
                                                <p class="td-dest-sub">Actif</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="td-user-name"><?php echo htmlspecialchars($b->getChauffeur()); ?></p>
                                        <p class="td-user-sub" style="display:flex;align-items:center;gap:0.25rem;">
                                            <i data-lucide="phone" style="width:0.75rem;height:0.75rem;"></i>
                                            <?php echo htmlspecialchars($b->getTelChauff()); ?>
                                        </p>
                                    </td>
                                    <td>
                                        <span class="region-badge"><?php echo $b->getNbPlaces(); ?> places</span>
                                    </td>
                                    <td>
                                        <div class="td-actions">
                                            <button type="button"
                                                class="btn-icon-action btn-icon-action--edit"
                                                onclick="openEditBusModal(this)"
                                                data-id="<?php echo $b->getId(); ?>"
                                                data-immat_bus="<?php echo htmlspecialchars($b->getImmatBus()); ?>"
                                                data-nb_places="<?php echo $b->getNbPlaces(); ?>"
                                                data-chauffeur="<?php echo htmlspecialchars($b->getChauffeur()); ?>"
                                                data-tel_chauff="<?php echo htmlspecialchars($b->getTelChauff()); ?>"
                                                title="Éditer">
                                                <i data-lucide="pen"></i>
                                            </button>
                                            <a href="index.php?page=admin&action=manage_buses&subaction=delete&id=<?php echo $b->getId(); ?>"
                                               class="btn-icon-action btn-icon-action--delete"
                                               title="Supprimer"
                                               onclick="return confirm('Supprimer ce bus ?')">
                                                <i data-lucide="trash-2"></i>
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

            <div class="admin-bottom-spacer"></div>
        </div>
    </main>
</div>

<!-- Add Bus Modal -->
<div id="add-bus-overlay" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeAddBusModal()"></div>
    <div class="modal-center">
        <div id="add-bus-box" class="modal-box modal-box--md">
            <div class="modal-inner">
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">Ajouter un Bus</h3>
                        <p class="modal-subtitle">Renseignez les détails du nouveau véhicule.</p>
                    </div>
                    <button class="modal-close" onclick="closeAddBusModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form action="index.php?page=admin&action=manage_buses&subaction=add" method="POST" class="modal-form">
                    <div class="modal-form-grid-2">
                        <div class="mf-group">
                            <label class="mf-label">Immatriculation</label>
                            <input type="text" name="immat_bus" required placeholder="Ex: 123 TUN 456" class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Nombre de places</label>
                            <input type="number" name="nb_places" required min="1" placeholder="Ex: 50" class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Nom du Chauffeur</label>
                            <input type="text" name="chauffeur" required placeholder="Nom complet" class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Tél Chauffeur</label>
                            <input type="text" name="tel_chauff" required placeholder="Numéro" class="mf-input">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" onclick="closeAddBusModal()">Annuler</button>
                        <button type="submit" class="btn-modal-submit">
                            <i data-lucide="plus"></i> Enregistrer le Bus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bus Modal -->
<div id="edit-bus-overlay" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeEditBusModal()"></div>
    <div class="modal-center">
        <div id="edit-bus-box" class="modal-box modal-box--md">
            <div class="modal-inner">
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">Modifier Bus</h3>
                        <p class="modal-subtitle">Mettez à jour les informations du véhicule.</p>
                    </div>
                    <button class="modal-close" onclick="closeEditBusModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>
                <form id="edit-bus-form" action="index.php?page=admin&action=manage_buses&subaction=edit" method="POST" class="modal-form">
                    <input type="hidden" name="id_bus">
                    <div class="modal-form-grid-2">
                        <div class="mf-group">
                            <label class="mf-label">Immatriculation</label>
                            <input type="text" name="immat_bus" required class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Nombre de places</label>
                            <input type="number" name="nb_places" required min="1" class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Nom du Chauffeur</label>
                            <input type="text" name="chauffeur" required class="mf-input">
                        </div>
                        <div class="mf-group">
                            <label class="mf-label">Tél Chauffeur</label>
                            <input type="text" name="tel_chauff" required class="mf-input">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" onclick="closeEditBusModal()">Annuler</button>
                        <button type="submit" class="btn-modal-submit">
                            <i data-lucide="save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAddBusModal() {
    const overlay = document.getElementById('add-bus-overlay');
    const box = document.getElementById('add-bus-box');
    overlay.classList.add('open');
    setTimeout(() => box.classList.add('open'), 10);
}
function closeAddBusModal() {
    const box = document.getElementById('add-bus-box');
    const overlay = document.getElementById('add-bus-overlay');
    box.classList.remove('open');
    setTimeout(() => overlay.classList.remove('open'), 300);
}
function openEditBusModal(btn) {
    const form = document.getElementById('edit-bus-form');
    ['id_bus','immat_bus','nb_places','chauffeur','tel_chauff'].forEach(field => {
        const input = form.querySelector('[name="' + field + '"]');
        const attr = field === 'id_bus' ? 'data-id' : 'data-' + field;
        if (input) input.value = btn.getAttribute(attr);
    });
    const overlay = document.getElementById('edit-bus-overlay');
    const box = document.getElementById('edit-bus-box');
    overlay.classList.add('open');
    setTimeout(() => box.classList.add('open'), 10);
}
function closeEditBusModal() {
    const box = document.getElementById('edit-bus-box');
    const overlay = document.getElementById('edit-bus-overlay');
    box.classList.remove('open');
    setTimeout(() => overlay.classList.remove('open'), 300);
}
</script>
