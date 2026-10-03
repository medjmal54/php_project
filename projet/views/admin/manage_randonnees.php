<?php $currentAction = 'manage_randonnees'; ?>
<div class="admin-layout">

    <?php require_once 'views/layout/admin_sidebar.php'; ?>

    <!-- Main -->
    <main class="admin-main custom-scrollbar">
        <div class="admin-content">

            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Gérer Randonnées</h1>
                    <p class="admin-header-sub">Créez et organisez vos prochaines expéditions.</p>
                </div>
                <?php ob_start(); ?>
                <button class="btn-admin-primary" onclick="openAddModal()">
                    <i data-lucide="plus"></i> Nouvelle Expédition
                </button>
                <?php $headerActionsHtml = ob_get_clean(); ?>
                <?php require 'views/layout/admin_header_actions.php'; ?>
            </header>

            <!-- Table Panel -->
            <div class="admin-panel">
                <div class="admin-panel-header">
                    <h2 class="admin-panel-title">Toutes les Randonnées</h2>
                    <div class="panel-search-wrap">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Rechercher une destination..." class="panel-search">
                    </div>
                </div>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Région</th>
                                <th>Date</th>
                                <th>Capacité & Prix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($randonnees)): ?>
                                <tr><td colspan="5" class="td-empty">Vous n'avez pas encore configuré de randonnées.</td></tr>
                            <?php else: ?>
                                <?php foreach ($randonnees as $r): ?>
                                <tr>
                                    <td>
                                        <div class="td-dest">
                                            <div class="td-dest-thumb">
                                                <?php if ($r->getImage()): ?>
                                                    <img src="<?php echo htmlspecialchars($r->getImage()); ?>" alt="">
                                                <?php else: ?>
                                                    <div class="td-dest-icon"><i data-lucide="compass"></i></div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="td-dest-name"><?php echo htmlspecialchars($r->getTitre()); ?></p>
                                                <p class="td-dest-sub">Expédition</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display:flex;align-items:center;gap:0.375rem;font-size:0.875rem;font-weight:700;color:var(--color-primary);">
                                            <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem;color:var(--color-secondary);"></i>
                                            <?php echo htmlspecialchars($r->getRegion()); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <p class="td-user-name"><?php echo date('d/m/Y', strtotime($r->getDateDebut())); ?></p>
                                        <p class="td-user-sub"><?php echo date('H:i', strtotime($r->getDateDebut())); ?></p>
                                    </td>
                                    <td>
                                        <span class="region-badge"><?php echo $r->getCapacite(); ?> pl.</span>
                                        <span style="font-size:0.875rem;font-weight:700;color:var(--color-secondary);margin-left:0.5rem;"><?php echo number_format($r->getPrix(), 2); ?> DT</span>
                                    </td>
                                    <td>
                                        <div class="td-actions">
                                            <button type="button" class="btn-icon-action btn-icon-action--edit"
                                                onclick="openEditModal(this)"
                                                data-id="<?php echo $r->getId(); ?>"
                                                data-titre="<?php echo htmlspecialchars($r->getTitre() ?? '', ENT_QUOTES); ?>"
                                                data-categorie="<?php echo htmlspecialchars($r->getCategorie() ?? '', ENT_QUOTES); ?>"
                                                data-region="<?php echo htmlspecialchars($r->getRegion() ?? '', ENT_QUOTES); ?>"
                                                data-difficulte="<?php echo htmlspecialchars($r->getDifficulte() ?? '', ENT_QUOTES); ?>"
                                                data-description="<?php echo htmlspecialchars($r->getDescription() ?? '', ENT_QUOTES); ?>"
                                                data-date_debut="<?php echo $r->getDateDebut() ? date('Y-m-d\TH:i', strtotime($r->getDateDebut())) : ''; ?>"
                                                data-date_fin="<?php echo $r->getDateFin() ? date('Y-m-d\TH:i', strtotime($r->getDateFin())) : ''; ?>"
                                                data-distance="<?php echo htmlspecialchars($r->getDistance() ?? '', ENT_QUOTES); ?>"
                                                data-prix="<?php echo htmlspecialchars($r->getPrix() ?? '', ENT_QUOTES); ?>"
                                                data-capacite="<?php echo htmlspecialchars($r->getCapacite() ?? '', ENT_QUOTES); ?>"
                                                data-id_bus="<?php echo htmlspecialchars($r->getIdBus() ?? '', ENT_QUOTES); ?>"
                                                data-image="<?php echo htmlspecialchars($r->getImage() ?? '', ENT_QUOTES); ?>"
                                                title="Éditer">
                                                <i data-lucide="pen"></i>
                                            </button>
                                            <a href="index.php?page=admin&action=delete_randonnee&id=<?php echo $r->getId(); ?>"
                                               class="btn-icon-action btn-icon-action--delete"
                                               title="Supprimer"
                                               onclick="return confirm('Confirmer la suppression ?')">
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

<!-- Add Randonnée Modal -->
<div id="add-rando-overlay" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeAddModal()"></div>
    <div class="modal-center">
        <div id="add-rando-box" class="modal-box modal-box--lg">
            <div class="modal-inner">
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">Nouvelle Randonnée</h3>
                        <p class="modal-subtitle">Configurez les étapes, dates et détails.</p>
                    </div>
                    <button class="modal-close" onclick="closeAddModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <form action="index.php?page=admin&action=manage_randonnees&subaction=add" method="POST" enctype="multipart/form-data" class="modal-form">
                    <div class="modal-form-cols">
                        <!-- Col 1 -->
                        <div class="modal-form">
                            <div class="mf-group">
                                <label class="mf-label">Titre</label>
                                <input type="text" name="titre" required placeholder="Ex: Randonnée au Djebel Zaghouan" class="mf-input">
                            </div>
                            <div class="modal-form-grid-2">
                                <div class="mf-group">
                                    <label class="mf-label">Catégorie</label>
                                    <input type="text" name="categorie" required placeholder="Montagne, Désert..." class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Région</label>
                                    <input type="text" name="region" required placeholder="Zaghouan" class="mf-input">
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Difficulté</label>
                                <div class="mf-select-wrap">
                                    <select name="difficulte" class="mf-select">
                                        <option value="Facile">Facile</option>
                                        <option value="Moyen">Moyen</option>
                                        <option value="Difficile">Difficile</option>
                                    </select>
                                    <i data-lucide="chevron-down"></i>
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Description</label>
                                <textarea name="description" rows="4" class="mf-textarea" placeholder="Décrivez l'itinéraire..."></textarea>
                            </div>
                        </div>
                        <!-- Col 2 -->
                        <div class="modal-form">
                            <div class="modal-form-grid-2">
                                <div class="mf-group">
                                    <label class="mf-label">Date Début</label>
                                    <input type="datetime-local" name="date_debut" required class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Date Fin</label>
                                    <input type="datetime-local" name="date_fin" required class="mf-input">
                                </div>
                            </div>
                            <div class="modal-form-grid-3">
                                <div class="mf-group">
                                    <label class="mf-label">Dist. (km)</label>
                                    <input type="number" step="0.1" name="distance" required placeholder="10" class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Prix (DT)</label>
                                    <input type="number" step="0.5" name="prix" required placeholder="45" class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Places</label>
                                    <input type="number" name="capacite" required placeholder="30" class="mf-input">
                                </div>
                            </div>
                            <div class="mf-transport-box">
                                <div class="mf-transport-label">
                                    <i data-lucide="bus"></i> Transport Assigné
                                </div>
                                <div class="mf-select-wrap">
                                    <select name="id_bus" class="mf-select">
                                        <?php if (empty($buses)): ?>
                                            <option disabled>Aucun bus disponible</option>
                                        <?php else: ?>
                                            <?php foreach ($buses as $b): ?>
                                                <option value="<?php echo $b->getId(); ?>"><?php echo htmlspecialchars($b->getImmatBus()); ?> (<?php echo $b->getNbPlaces(); ?> pl.)</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <i data-lucide="chevron-down"></i>
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Photo de la Destination</label>
                                <div class="mf-dropzone">
                                    <input type="file" name="image" accept="image/*">
                                    <div class="mf-dropzone-inner">
                                        <div class="mf-dropzone-icon"><i data-lucide="image-plus"></i></div>
                                        <p>Cliquez pour ajouter une photo</p>
                                        <small>JPG, PNG ou WEBP (Max 5MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" onclick="closeAddModal()">Annuler</button>
                        <button type="submit" class="btn-modal-submit">
                            <i data-lucide="check-circle-2"></i> Créer la Randonnée
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Randonnée Modal -->
<div id="edit-rando-overlay" class="modal-overlay">
    <div class="modal-backdrop" onclick="closeEditModal()"></div>
    <div class="modal-center">
        <div id="edit-rando-box" class="modal-box modal-box--lg">
            <div class="modal-inner">
                <div class="modal-header">
                    <div>
                        <h3 class="modal-title">Modifier Randonnée</h3>
                        <p class="modal-subtitle">Mettez à jour les détails de l'expédition.</p>
                    </div>
                    <button class="modal-close" onclick="closeEditModal()">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <form id="edit-rando-form" action="index.php?page=admin&action=manage_randonnees&subaction=edit" method="POST" enctype="multipart/form-data" class="modal-form">
                    <input type="hidden" name="id_randonnee">
                    <div class="modal-form-cols">
                        <div class="modal-form">
                            <div class="mf-group">
                                <label class="mf-label">Titre</label>
                                <input type="text" name="titre" required class="mf-input">
                            </div>
                            <div class="modal-form-grid-2">
                                <div class="mf-group">
                                    <label class="mf-label">Catégorie</label>
                                    <input type="text" name="categorie" required class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Région</label>
                                    <input type="text" name="region" required class="mf-input">
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Difficulté</label>
                                <div class="mf-select-wrap">
                                    <select name="difficulte" class="mf-select">
                                        <option value="Facile">Facile</option>
                                        <option value="Moyen">Moyen</option>
                                        <option value="Difficile">Difficile</option>
                                    </select>
                                    <i data-lucide="chevron-down"></i>
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Description</label>
                                <textarea name="description" rows="4" class="mf-textarea"></textarea>
                            </div>
                        </div>
                        <div class="modal-form">
                            <div class="modal-form-grid-2">
                                <div class="mf-group">
                                    <label class="mf-label">Date Début</label>
                                    <input type="datetime-local" name="date_debut" required class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Date Fin</label>
                                    <input type="datetime-local" name="date_fin" required class="mf-input">
                                </div>
                            </div>
                            <div class="modal-form-grid-3">
                                <div class="mf-group">
                                    <label class="mf-label">Dist. (km)</label>
                                    <input type="number" step="0.1" name="distance" required class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Prix (DT)</label>
                                    <input type="number" step="0.5" name="prix" required class="mf-input">
                                </div>
                                <div class="mf-group">
                                    <label class="mf-label">Places</label>
                                    <input type="number" name="capacite" required class="mf-input">
                                </div>
                            </div>
                            <div class="mf-transport-box">
                                <div class="mf-transport-label">
                                    <i data-lucide="bus"></i> Transport Assigné
                                </div>
                                <div class="mf-select-wrap">
                                    <select name="id_bus" class="mf-select">
                                        <?php if (empty($buses)): ?>
                                            <option disabled>Aucun bus disponible</option>
                                        <?php else: ?>
                                            <?php foreach ($buses as $b): ?>
                                                <option value="<?php echo $b->getId(); ?>"><?php echo htmlspecialchars($b->getImmatBus()); ?> (<?php echo $b->getNbPlaces(); ?> pl.)</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <i data-lucide="chevron-down"></i>
                                </div>
                            </div>
                            <div class="mf-group">
                                <label class="mf-label">Modifier la Photo</label>
                                <div class="mf-dropzone">
                                    <input type="file" name="image" accept="image/*"
                                        onchange="previewEditImg(this)">
                                    <div class="mf-dropzone-inner">
                                        <div class="mf-img-preview" id="edit-img-preview">
                                            <img id="edit-img-preview-img" src="" alt="Preview">
                                        </div>
                                        <div class="mf-dropzone-icon"><i data-lucide="image-plus"></i></div>
                                        <p>Cliquez pour modifier la photo</p>
                                        <small>Laissez vide pour conserver l'actuelle</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Annuler</button>
                        <button type="submit" class="btn-modal-submit">
                            <i data-lucide="save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openAddModal() {
    const overlay = document.getElementById('add-rando-overlay');
    const box = document.getElementById('add-rando-box');
    overlay.classList.add('open');
    setTimeout(() => box.classList.add('open'), 10);
}
function closeAddModal() {
    const overlay = document.getElementById('add-rando-overlay');
    const box = document.getElementById('add-rando-box');
    box.classList.remove('open');
    setTimeout(() => overlay.classList.remove('open'), 300);
}

function openEditModal(btn) {
    const form = document.getElementById('edit-rando-form');
    ['id_randonnee','titre','categorie','region','difficulte','description',
     'date_debut','date_fin','distance','prix','capacite','id_bus'].forEach(field => {
        const input = form.querySelector('[name="' + field + '"]');
        const attr = field === 'id_randonnee' ? 'data-id' : 'data-' + field;
        if (input) input.value = btn.getAttribute(attr);
    });
    const imgUrl = btn.getAttribute('data-image');
    const previewWrap = document.getElementById('edit-img-preview');
    const previewImg = document.getElementById('edit-img-preview-img');
    if (imgUrl) { previewImg.src = imgUrl; previewWrap.style.display = 'block'; }
    else { previewWrap.style.display = 'none'; }

    const overlay = document.getElementById('edit-rando-overlay');
    const box = document.getElementById('edit-rando-box');
    overlay.classList.add('open');
    setTimeout(() => box.classList.add('open'), 10);
}
function closeEditModal() {
    const overlay = document.getElementById('edit-rando-overlay');
    const box = document.getElementById('edit-rando-box');
    box.classList.remove('open');
    setTimeout(() => overlay.classList.remove('open'), 300);
}
function previewEditImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.getElementById('edit-img-preview');
            const img  = document.getElementById('edit-img-preview-img');
            img.src = e.target.result;
            wrap.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
