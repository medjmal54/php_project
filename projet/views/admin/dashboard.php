<?php
// Prepare chart data as JSON for Chart.js
$chart_labels   = [];
$chart_total    = [];
$chart_accepted = [];

// Fill in every day in the range (so days with 0 participations show as 0)
$period_start = new DateTime($date_from);
$period_end   = new DateTime($date_to);
$interval     = new DateInterval('P1D');
$date_range   = new DatePeriod($period_start, $interval, $period_end->modify('+1 day'));

// Index chart data by day
$chart_by_day = [];
foreach ($chart_data as $row) {
    $chart_by_day[$row['day']] = $row;
}

foreach ($date_range as $dt) {
    $key = $dt->format('Y-m-d');
    $chart_labels[]   = $dt->format('d M');
    $chart_total[]    = isset($chart_by_day[$key]) ? (int)$chart_by_day[$key]['total']    : 0;
    $chart_accepted[] = isset($chart_by_day[$key]) ? (int)$chart_by_day[$key]['accepted'] : 0;
}

$chart_labels_json   = json_encode($chart_labels);
$chart_total_json    = json_encode($chart_total);
$chart_accepted_json = json_encode($chart_accepted);

// Preset label for display
$diff_days = (int)(new DateTime($date_from))->diff(new DateTime($date_to))->days;
?>
<div class="admin-layout">

    <?php require_once 'views/layout/admin_sidebar.php'; ?>

    <main class="admin-main custom-scrollbar">
        <div class="admin-content">

            <!-- Header + Filter Bar -->
            <header class="admin-header">
                <div>
                    <h1 class="admin-header-title">Tableau de bord</h1>
                    <p class="admin-header-sub">Analysez vos performances sur la période sélectionnée.</p>
                </div>
                <?php require 'views/layout/admin_header_actions.php'; ?>
            </header>

            <!-- Date Filter Bar -->
            <form method="GET" action="index.php" class="dash-filter-bar">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="action" value="dashboard">

                <!-- Segmented Preset Group -->
                <div class="dash-seg-group">
                    <a href="index.php?page=admin&action=dashboard&date_from=<?php echo date('Y-m-d'); ?>&date_to=<?php echo date('Y-m-d'); ?>"
                       class="dash-seg-btn <?php echo $diff_days === 0 ? 'active' : ''; ?>">Aujourd'hui</a>
                    <a href="index.php?page=admin&action=dashboard&date_from=<?php echo date('Y-m-d', strtotime('monday this week')); ?>&date_to=<?php echo date('Y-m-d'); ?>"
                       class="dash-seg-btn <?php echo ($diff_days >= 1 && $diff_days <= 7) ? 'active' : ''; ?>">Semaine</a>
                    <a href="index.php?page=admin&action=dashboard&date_from=<?php echo date('Y-m-01'); ?>&date_to=<?php echo date('Y-m-d'); ?>"
                       class="dash-seg-btn <?php echo ($diff_days >= 8 && $diff_days <= 31) ? 'active' : ''; ?>">Ce mois</a>
                    <a href="index.php?page=admin&action=dashboard&date_from=<?php echo date('Y-01-01'); ?>&date_to=<?php echo date('Y-m-d'); ?>"
                       class="dash-seg-btn <?php echo ($diff_days >= 90 && $diff_days <= 366) ? 'active' : ''; ?>">Cette année</a>
                    <a href="index.php?page=admin&action=dashboard&date_from=2020-01-01&date_to=<?php echo date('Y-m-d'); ?>"
                       class="dash-seg-btn <?php echo $diff_days > 366 ? 'active' : ''; ?>">Tout</a>
                </div>

                <div class="dash-filter-sep"></div>

                <!-- Compact date range + submit -->
                <div class="dash-date-range">
                    <i data-lucide="calendar" class="dash-cal-icon"></i>
                    <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" class="dash-date-input">
                    <span class="dash-date-sep">—</span>
                    <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" class="dash-date-input">
                    <button type="submit" class="dash-filter-submit">
                        Appliquer
                    </button>
                </div>
            </form>

            <!-- Stats Cards (filtered by date range) -->
            <div class="admin-stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--green"><i data-lucide="mountain"></i></div>
                    </div>
                    <p class="stat-label">Randonnées (total)</p>
                    <h3 class="stat-value"><?php echo number_format($total_randonnees); ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--blue"><i data-lucide="users"></i></div>
                    </div>
                    <p class="stat-label">Utilisateurs (total)</p>
                    <h3 class="stat-value"><?php echo number_format($total_users); ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--teal"><i data-lucide="send"></i></div>
                    </div>
                    <p class="stat-label">Demandes reçues</p>
                    <h3 class="stat-value"><?php echo number_format($total_part); ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--orange"><i data-lucide="banknote"></i></div>
                    </div>
                    <p class="stat-label">Revenu (période)</p>
                    <div class="stat-value-row">
                        <h3 class="stat-value"><?php echo number_format($filtered_revenu, 0, ',', ' '); ?></h3>
                        <span class="stat-unit">TND</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--green"><i data-lucide="check-circle-2"></i></div>
                    </div>
                    <p class="stat-label">Acceptées</p>
                    <div class="stat-value-row">
                        <h3 class="stat-value"><?php echo $acc; ?></h3>
                        <span class="stat-unit"><?php echo $taux_acc; ?>%</span>
                    </div>
                </div>
                <div class="stat-card stat-card--alert">
                    <div class="stat-card-header">
                        <div class="stat-icon stat-icon--red"><i data-lucide="clock"></i></div>
                    </div>
                    <p class="stat-label">En attente</p>
                    <h3 class="stat-value"><?php echo $pending; ?></h3>
                </div>
            </div>

            <!-- Chart Panel -->
            <div class="admin-panel dash-chart-panel">
                <div class="admin-panel-header">
                    <div>
                        <h2 class="admin-panel-title">Évolution des inscriptions</h2>
                        <p class="admin-header-sub" style="margin-top:0.25rem;">
                            Du <?php echo (new DateTime($date_from))->format('d M Y'); ?>
                            au <?php echo (new DateTime($date_to))->format('d M Y'); ?>
                        </p>
                    </div>
                    <div style="display:flex;align-items:center;gap:1rem;font-size:0.8125rem;">
                        <span style="display:flex;align-items:center;gap:0.375rem;color:var(--color-primary);font-weight:600;">
                            <span style="width:0.75rem;height:0.75rem;border-radius:50%;background:#01261f;display:inline-block;"></span>
                            Total
                        </span>
                        <span style="display:flex;align-items:center;gap:0.375rem;color:#059669;font-weight:600;">
                            <span style="width:0.75rem;height:0.75rem;border-radius:50%;background:#059669;display:inline-block;"></span>
                            Acceptées
                        </span>
                    </div>
                </div>
                <div class="dash-chart-wrap">
                    <canvas id="participationChart"></canvas>
                </div>
            </div>

            <div class="admin-bottom-spacer"></div>
        </div>
    </main>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels   = <?php echo $chart_labels_json; ?>;
const total    = <?php echo $chart_total_json; ?>;
const accepted = <?php echo $chart_accepted_json; ?>;

const ctx = document.getElementById('participationChart').getContext('2d');

// Gradient fills
const gradTotal = ctx.createLinearGradient(0, 0, 0, 300);
gradTotal.addColorStop(0, 'rgba(1,38,31,0.18)');
gradTotal.addColorStop(1, 'rgba(1,38,31,0)');

const gradAccepted = ctx.createLinearGradient(0, 0, 0, 300);
gradAccepted.addColorStop(0, 'rgba(5,150,105,0.18)');
gradAccepted.addColorStop(1, 'rgba(5,150,105,0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Total Demandes',
                data: total,
                borderColor: '#01261f',
                backgroundColor: gradTotal,
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#01261f',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.45,
                fill: true,
            },
            {
                label: 'Acceptées',
                data: accepted,
                borderColor: '#059669',
                backgroundColor: gradAccepted,
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#059669',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.45,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#01261f',
                titleColor: '#fff',
                bodyColor: 'rgba(255,255,255,0.8)',
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`,
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { color: '#6b7280', font: { size: 12 }, maxTicksLimit: 12 }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                border: { display: false },
                ticks: {
                    color: '#6b7280',
                    font: { size: 12 },
                    stepSize: 1,
                    precision: 0
                }
            }
        }
    }
});
</script>
