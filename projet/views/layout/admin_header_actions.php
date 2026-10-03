<?php
require_once 'models/ParticipationModel.php';
$recentPending = ParticipationModel::getPendingParticipations(5);
$pendingCount  = count($recentPending);
?>
<div class="admin-header-actions" style="position: relative;">
    <?php echo $headerActionsHtml ?? ''; ?>
    <button class="btn-icon-bell" id="notif-bell-btn" onclick="toggleNotifDropdown(event)">
        <i data-lucide="bell"></i>
        <?php if ($pendingCount > 0): ?>
        <span class="notif-dot"></span>
        <?php endif; ?>
    </button>

    <!-- Dropdown Menu -->
    <div id="notif-dropdown" class="notif-dropdown">
        <div class="notif-dropdown-header">
            <h4>Notifications</h4>
            <span class="notif-count-badge"><?php echo $pendingCount; ?> new</span>
        </div>
        <div class="notif-dropdown-body custom-scrollbar">
            <?php if ($pendingCount === 0): ?>
                <div class="notif-empty">
                    <i data-lucide="check-circle-2" style="width:2rem;height:2rem;color:#059669;opacity:0.5;margin-bottom:0.5rem;"></i>
                    <p>Aucune notification</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentPending as $notifP): ?>
                <a href="index.php?page=admin&action=manage_participations" class="notif-item">
                    <div class="notif-avatar">
                        <img src="<?php echo htmlspecialchars($notifP->getPhotoUrl()); ?>" alt="Avatar">
                    </div>
                    <div class="notif-info">
                        <p class="notif-text">
                            <strong><?php echo htmlspecialchars($notifP->getUserName()); ?></strong> a demandé à rejoindre 
                            <span style="color:var(--color-primary);font-weight:600;"><?php echo htmlspecialchars($notifP->getTitre()); ?></span>
                        </p>
                        <p class="notif-time">
                            <?php 
                                $date = new DateTime($notifP->getDateCreation());
                                echo $date->format('d M, Y \à H:i');
                            ?>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="notif-dropdown-footer">
            <a href="index.php?page=admin&action=manage_participations">Voir toutes les demandes</a>
        </div>
    </div>
</div>

<script>
function toggleNotifDropdown(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('notif-dropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notif-dropdown');
    if (dropdown && dropdown.classList.contains('show') && !e.target.closest('#notif-dropdown') && !e.target.closest('#notif-bell-btn')) {
        dropdown.classList.remove('show');
    }
});
</script>
