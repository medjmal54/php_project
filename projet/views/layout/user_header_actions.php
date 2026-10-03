<?php
require_once 'models/ParticipationModel.php';
$id_user = $_SESSION['user_id'];
$notifications = ParticipationModel::getLatestStatusUpdates($id_user, 5);
$unseenCount   = ParticipationModel::getUnseenCount($id_user);
?>

<div class="admin-header-actions" style="position: relative;">
    <button class="btn-icon-bell" id="user-notif-bell" onclick="toggleUserNotif(event)">
        <i data-lucide="bell"></i>
        <?php if ($unseenCount > 0): ?>
            <span class="notif-dot"></span>
        <?php endif; ?>
    </button>

    <div id="user-notif-dropdown" class="notif-dropdown">
        <div class="notif-dropdown-header">
            <h4>Vos Notifications</h4>
            <span class="notif-count-badge"><?php echo $unseenCount; ?> nouvelles</span>
        </div>
        <div class="notif-dropdown-body custom-scrollbar">
            <?php if (empty($notifications)): ?>
                <div class="notif-empty">
                    <i data-lucide="bell-off" style="width:2rem;height:2rem;opacity:0.2;margin-bottom:0.5rem;"></i>
                    <p>Aucune notification pour le moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                    <a href="index.php?page=user&action=dashboard" class="notif-item <?php echo !$n->getIsSeen() ? 'notif-item--unseen' : ''; ?>">
                        <div class="notif-avatar">
                            <img src="<?php echo $n->getPhotoUrl(); ?>" alt="Rando">
                        </div>
                        <div class="notif-info">
                            <p class="notif-text">
                                Votre demande pour <strong><?php echo htmlspecialchars($n->getTitre()); ?></strong> a été 
                                <span class="status-badge status-<?php echo strtolower($n->getStatut()); ?>">
                                    <?php echo strtolower($n->getStatut()); ?>
                                </span>
                            </p>
                            <p class="notif-time"><?php echo date('d M, H:i', strtotime($n->getDateCreation())); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="notif-dropdown-footer">
            <a href="index.php?page=user&action=dashboard">Voir toutes mes aventures</a>
        </div>
    </div>
</div>

<script>
function toggleUserNotif(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('user-notif-dropdown');
    dropdown.classList.toggle('show');
    
    // Mark as read when opening (Optional improvement: send AJAX)
    if (dropdown.classList.contains('show')) {
        fetch('index.php?page=user&action=mark_read')
            .then(() => {
                const dot = document.querySelector('#user-notif-bell .notif-dot');
                if (dot) dot.style.display = 'none';
            });
    }
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('user-notif-dropdown');
    if (dropdown && dropdown.classList.contains('show') && !e.target.closest('#user-notif-dropdown') && !e.target.closest('#user-notif-bell')) {
        dropdown.classList.remove('show');
    }
});
</script>

<style>
.notif-item--unseen {
    background: rgba(1, 38, 31, 0.03);
    border-left: 3px solid var(--color-primary);
}
.status-badge {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
}
.status-acceptée { color: #059669; }
.status-refusée { color: #dc2626; }
</style>
