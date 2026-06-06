<?php
require_once __DIR__ . '/_init.php';
require_once __DIR__ . '/../inc-msedcl-smart-dashboard-data.php';

$Page = 'MSEDCL-Smart-Dashboard';
msedclSmartRequireOption(MSEDCL_SMART_OPT_DASHBOARD);

$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
$dash = getMsedclSmartDashboardData();

$districtLabels = [];
$districtCounts = [];
foreach ($dash['district_stats'] as $row) {
    $districtLabels[] = $row['District'];
    $districtCounts[] = (int) $row['cnt'];
}

$abstractLabels = [];
$abstractPmsgy = [];
$abstractMahadiscom = [];
$abstractPayment = [];
$abstractSurvey = [];
foreach ($dash['abstract_rows'] as $row) {
    $abstractLabels[] = $row['District'];
    $abstractPmsgy[] = (int) $row['pmsgy_cnt'];
    $abstractMahadiscom[] = (int) $row['mahadiscom_cnt'];
    $abstractPayment[] = (int) $row['payment_cnt'];
    $abstractSurvey[] = (int) $row['survey_cnt'];
}

$monthlyLabels = [];
$monthlyCounts = [];
foreach ($dash['monthly_stats'] as $row) {
    $monthlyLabels[] = $row['label'];
    $monthlyCounts[] = (int) $row['cnt'];
}

$capacityLabels = [];
$capacityCounts = [];
foreach ($dash['capacity_stats'] as $row) {
    $capacityLabels[] = $row['label'];
    $capacityCounts[] = (int) $row['cnt'];
}

$hasCharts = ($dash['total'] > 0);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | MSEDCL SMART PROJECT Dashboard</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once __DIR__ . '/../header_script.php'; ?>
<link rel="stylesheet" href="css/msedcl-smart-dashboard.css">
<style>.layout-wrapper .layout-content { background: #ffffff !important; }</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once __DIR__ . '/msedcl-smart-sidebar.php'; ?>
<div class="layout-container">
<?php include_once __DIR__ . '/../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 msedcl-dash-page">

<div class="msedcl-dash-top">
    <h4>MSEDCL SMART PROJECT Dashboard</h4>
    <p>Visual overview of PMSGY → Mahadiscom → Payment → Survey workflow</p>
    <div class="msedcl-dash-top-meta">
        <span class="msedcl-dash-top-pill"><i class="feather icon-user"></i> <?php echo htmlspecialchars($adminName !== '' ? $adminName : 'Admin'); ?></span>
        <span class="msedcl-dash-top-pill"><i class="feather icon-calendar"></i> <?php echo date('d M Y'); ?></span>
        <span class="msedcl-dash-top-pill"><i class="feather icon-users"></i> <?php echo number_format($dash['total']); ?> total customers</span>
        <span class="msedcl-dash-top-pill"><i class="feather icon-upload"></i> <?php echo number_format($dash['imported_today']); ?> new records today</span>
    </div>
</div>

<div class="card msedcl-dash-shell border-0">
    <h5 class="card-header msedcl-dash-header">Status Overview</h5>
    <div class="card-body">
        <div class="msedcl-dash-stat-grid">
            <?php
            msedclSmartDashboardStatCard('Total Customers', $dash['total'], 'abstract.php', 'users', 'All uploaded records', 'total');
            msedclSmartDashboardStatCard('PMSGY Portal', $dash['pmsgy'], 'pmsgy.php', 'upload-cloud', 'Awaiting Mahadiscom', 'pmsgy');
            msedclSmartDashboardStatCard('Mahadiscom Portal', $dash['mahadiscom'], 'mahadiscom.php', 'file-text', 'Awaiting payment', 'mahadiscom');
            msedclSmartDashboardStatCard('Payment Done', $dash['payment_done'], 'payment.php', 'check-circle', 'Paid — pending forward', 'payment');
            msedclSmartDashboardStatCard('Survey Pending', $dash['survey_pending'], 'survey-pending.php', 'clock', 'Ready for survey', 'survey');
            msedclSmartDashboardStatCard('Survey Done', $dash['survey_done'], 'abstract.php', 'map', 'Completed surveys', 'total');
            ?>
        </div>
    </div>
</div>

<?php if (!$hasCharts) { ?>
<div class="card msedcl-dash-shell">
    <div class="card-body text-center text-muted py-5">
        <i class="feather icon-bar-chart-2" style="font-size:2.5rem;opacity:.4;"></i>
        <p class="mt-3 mb-2">No customer data yet.</p>
        <a href="pmsgy.php" class="btn btn-primary btn-sm">Upload PMSGY Excel</a>
    </div>
</div>
<?php } else { ?>

<div class="msedcl-analytics-section">
    <h5 class="msedcl-analytics-heading">Analytics &amp; Charts</h5>

    <div class="msedcl-charts-grid msedcl-charts-grid-row1 mb-3">
        <div class="msedcl-chart-panel">
            <div class="msedcl-chart-panel-head"><h6>Workflow Stage Distribution</h6></div>
            <div class="msedcl-chart-panel-body">
                <div class="msedcl-chart-canvas-box"><canvas id="msedclStageDoughnut"></canvas></div>
            </div>
        </div>
        <div class="msedcl-chart-panel">
            <div class="msedcl-chart-panel-head"><h6>Process Funnel (% of total customers)</h6></div>
            <div class="msedcl-chart-panel-body">
                <?php msedclSmartDashboardRenderFunnel($dash['funnel']); ?>
            </div>
        </div>
        <div class="msedcl-chart-panel msedcl-quick-links-panel">
            <div class="msedcl-chart-panel-head"><h6>Quick Links</h6></div>
            <div class="msedcl-chart-panel-body">
                <a href="pmsgy.php" class="btn btn-outline-secondary btn-block btn-sm">Upload PMSGY Excel</a>
                <a href="mahadiscom.php" class="btn btn-outline-secondary btn-block btn-sm">Mahadiscom Applications</a>
                <a href="payment.php" class="btn btn-outline-secondary btn-block btn-sm">Payment Done</a>
                <a href="abstract.php" class="btn btn-outline-secondary btn-block btn-sm">View Abstract</a>
            </div>
        </div>
    </div>

    <div class="msedcl-charts-grid msedcl-charts-grid-row2 mb-3">
        <div class="msedcl-chart-panel">
            <div class="msedcl-chart-panel-head"><h6>District-wise Stage Comparison</h6></div>
            <div class="msedcl-chart-panel-body">
                <?php if (empty($abstractLabels)) { ?>
                <div class="msedcl-empty-chart">No district data available</div>
                <?php } else { ?>
                <div class="msedcl-chart-canvas-box"><canvas id="msedclDistrictStackChart"></canvas></div>
                <?php } ?>
            </div>
        </div>
        <div class="msedcl-chart-panel">
            <div class="msedcl-chart-panel-head"><h6>Stage Count Comparison</h6></div>
            <div class="msedcl-chart-panel-body">
                <div class="msedcl-chart-canvas-box"><canvas id="msedclStageBarChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="msedcl-charts-grid msedcl-charts-grid-row3">
        <div class="msedcl-chart-panel msedcl-chart-panel-sm">
            <div class="msedcl-chart-panel-head"><h6>PMSGY Uploads — Last 6 Months</h6></div>
            <div class="msedcl-chart-panel-body">
                <?php if (empty($monthlyLabels)) { ?>
                <div class="msedcl-empty-chart">No upload history in last 6 months</div>
                <?php } else { ?>
                <div class="msedcl-chart-canvas-box"><canvas id="msedclMonthlyLineChart"></canvas></div>
                <?php } ?>
            </div>
        </div>
        <div class="msedcl-chart-panel msedcl-chart-panel-sm">
            <div class="msedcl-chart-panel-head"><h6>Top Districts (Total Customers)</h6></div>
            <div class="msedcl-chart-panel-body">
                <?php if (empty($districtLabels)) { ?>
                <div class="msedcl-empty-chart">No district data</div>
                <?php } else { ?>
                <div class="msedcl-chart-canvas-box"><canvas id="msedclDistrictBarChart"></canvas></div>
                <?php } ?>
            </div>
        </div>
        <div class="msedcl-chart-panel msedcl-chart-panel-sm">
            <div class="msedcl-chart-panel-head"><h6>Rooftop Capacity Mix</h6></div>
            <div class="msedcl-chart-panel-body">
                <?php if (empty($capacityLabels)) { ?>
                <div class="msedcl-empty-chart">No capacity data</div>
                <?php } else { ?>
                <div class="msedcl-chart-canvas-box"><canvas id="msedclCapacityPieChart"></canvas></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php } ?>

</div>
</div>
<?php include_once __DIR__ . '/../footer_script.php'; ?>
<?php if ($hasCharts) { ?>
<script>
(function () {
    var chartFont = { size: 12, family: "'Segoe UI', system-ui, sans-serif" };
    var gridColor = '#e5e7eb';
    var chartDefaults = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: { labels: { boxWidth: 14, padding: 14, font: chartFont, color: '#374151' } }
        }
    };
    var scaleDefaults = {
        grid: { color: gridColor, drawBorder: false },
        ticks: { font: chartFont, color: '#6b7280', padding: 6 }
    };

    new Chart(document.getElementById('msedclStageDoughnut'), {
        type: 'doughnut',
        data: {
            labels: ['PMSGY Portal', 'Mahadiscom Portal', 'Survey Pending', 'Survey Done'],
            datasets: [{
                data: [
                    <?php echo (int) $dash['pmsgy']; ?>,
                    <?php echo (int) $dash['mahadiscom']; ?>,
                    <?php echo (int) $dash['survey_pending']; ?>,
                    <?php echo (int) $dash['survey_done']; ?>
                ],
                backgroundColor: ['#2563eb', '#7c3aed', '#d97706', '#64748b'],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: Object.assign({}, chartDefaults, {
            cutout: '62%',
            layout: { padding: 8 },
            plugins: {
                legend: { position: 'bottom', labels: { font: chartFont, color: '#374151', padding: 12 } }
            }
        })
    });

    new Chart(document.getElementById('msedclStageBarChart'), {
        type: 'bar',
        data: {
            labels: ['PMSGY', 'Mahadiscom', 'Payment Done', 'Survey Pending', 'Survey Done'],
            datasets: [{
                label: 'Customers',
                data: [
                    <?php echo (int) $dash['pmsgy']; ?>,
                    <?php echo (int) $dash['mahadiscom']; ?>,
                    <?php echo (int) $dash['payment_done']; ?>,
                    <?php echo (int) $dash['survey_pending']; ?>,
                    <?php echo (int) $dash['survey_done']; ?>
                ],
                backgroundColor: ['#2563eb', '#7c3aed', '#059669', '#d97706', '#64748b'],
                borderRadius: 6,
                barThickness: 36,
                maxBarThickness: 44
            }]
        },
        options: Object.assign({}, chartDefaults, {
            plugins: { legend: { display: false } },
            layout: { padding: { top: 4, right: 8, bottom: 0, left: 4 } },
            scales: {
                y: Object.assign({}, scaleDefaults, { beginAtZero: true, ticks: Object.assign({}, scaleDefaults.ticks, { precision: 0 }) }),
                x: Object.assign({}, scaleDefaults, { grid: { display: false } })
            }
        })
    });

    <?php if (!empty($abstractLabels)) { ?>
    new Chart(document.getElementById('msedclDistrictStackChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($abstractLabels); ?>,
            datasets: [
                { label: 'PMSGY', data: <?php echo json_encode($abstractPmsgy); ?>, backgroundColor: '#2563eb', borderRadius: 2 },
                { label: 'Mahadiscom', data: <?php echo json_encode($abstractMahadiscom); ?>, backgroundColor: '#7c3aed', borderRadius: 2 },
                { label: 'Payment Done', data: <?php echo json_encode($abstractPayment); ?>, backgroundColor: '#059669', borderRadius: 2 },
                { label: 'Survey Done', data: <?php echo json_encode($abstractSurvey); ?>, backgroundColor: '#64748b', borderRadius: 2 }
            ]
        },
        options: Object.assign({}, chartDefaults, {
            layout: { padding: 8 },
            scales: {
                x: Object.assign({}, scaleDefaults, { stacked: true, grid: { display: false } }),
                y: Object.assign({}, scaleDefaults, { stacked: true, beginAtZero: true, ticks: Object.assign({}, scaleDefaults.ticks, { precision: 0 }) })
            }
        })
    });
    <?php } ?>

    <?php if (!empty($monthlyLabels)) { ?>
    new Chart(document.getElementById('msedclMonthlyLineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthlyLabels); ?>,
            datasets: [{
                label: 'PMSGY uploads',
                data: <?php echo json_encode($monthlyCounts); ?>,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 2
            }]
        },
        options: Object.assign({}, chartDefaults, {
            plugins: { legend: { display: false } },
            layout: { padding: 8 },
            scales: {
                y: Object.assign({}, scaleDefaults, { beginAtZero: true, ticks: Object.assign({}, scaleDefaults.ticks, { precision: 0 }) }),
                x: Object.assign({}, scaleDefaults, { grid: { display: false } })
            }
        })
    });
    <?php } ?>

    <?php if (!empty($districtLabels)) { ?>
    new Chart(document.getElementById('msedclDistrictBarChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($districtLabels); ?>,
            datasets: [{
                label: 'Customers',
                data: <?php echo json_encode($districtCounts); ?>,
                backgroundColor: '#2563eb',
                borderRadius: 4,
                barThickness: 22
            }]
        },
        options: Object.assign({}, chartDefaults, {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            layout: { padding: 8 },
            scales: {
                x: Object.assign({}, scaleDefaults, { beginAtZero: true, ticks: Object.assign({}, scaleDefaults.ticks, { precision: 0 }) }),
                y: Object.assign({}, scaleDefaults, { grid: { display: false } })
            }
        })
    });
    <?php } ?>

    <?php if (!empty($capacityLabels)) { ?>
    new Chart(document.getElementById('msedclCapacityPieChart'), {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($capacityLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($capacityCounts); ?>,
                backgroundColor: ['#2563eb', '#7c3aed', '#059669', '#d97706', '#64748b', '#dc2626', '#0891b2', '#4f46e5'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: Object.assign({}, chartDefaults, {
            layout: { padding: 6 },
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, color: '#374151', padding: 10 } }
            }
        })
    });
    <?php } ?>
})();
</script>
<?php } ?>
</div>
</div>
</body>
</html>
