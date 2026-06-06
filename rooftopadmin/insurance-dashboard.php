<?php
session_start();
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/auth.php';
include_once __DIR__ . '/inc-insurance-dashboard-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Insurance';
$Page = 'Insurance-Dashboard';

$row77 = getRecord("SELECT Fname, Lname FROM tbl_users WHERE id='$user_id'");
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
$dash = getInsuranceDashboardData();

$districtLabels = array();
$districtCounts = array();
foreach ($dash['district_stats'] as $row) {
    $districtLabels[] = $row['District'];
    $districtCounts[] = (int) $row['cnt'];
}

$companyLabels = array();
$companyCounts = array();
foreach ($dash['company_stats'] as $row) {
    $companyLabels[] = $row['InsuranceCompany'];
    $companyCounts[] = (int) $row['cnt'];
}

$monthlyLabels = array();
$monthlyCounts = array();
foreach ($dash['monthly_stats'] as $row) {
    $monthlyLabels[] = $row['label'];
    $monthlyCounts[] = (int) $row['cnt'];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Insurance Dashboard</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
<link rel="stylesheet" href="css/insurance-dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y ins-dash-page">

<div class="ins-dash-hero">
    <h4>Rooftop Insurance Dashboard</h4>
    <p>Overview of pending, active, renewal, and expired insurance for site-dispatched rooftop customers only.</p>
    <div class="ins-dash-hero-meta">
        <span class="ins-dash-hero-pill"><i class="feather icon-user"></i> <?php echo htmlspecialchars($adminName !== '' ? $adminName : 'Admin'); ?></span>
        <span class="ins-dash-hero-pill"><i class="feather icon-calendar"></i> <?php echo date('d M Y'); ?></span>
        <span class="ins-dash-hero-pill"><i class="feather icon-upload"></i> <?php echo number_format($dash['imported_today']); ?> imported today</span>
    </div>
</div>

<div class="card ins-dash-shell">
    <h5 class="card-header ins-dash-header">Insurance Status Overview</h5>
    <div class="card-body ins-dash-body">
        <div class="ins-dash-stat-grid">
            <?php
            insuranceDashboardStatCard('Pending Insurance', $dash['pending'], 'pending-insurance.php', 'clock', 'Awaiting insurance process', 'pending');
            insuranceDashboardStatCard('Active Completed', $dash['active_completed'], 'completed-insurance.php', 'check-circle', 'Valid & not expiring soon', 'active');
            insuranceDashboardStatCard('Upcoming Renewal', $dash['renewal'], 'renewal-insurance.php', 'refresh-cw', 'Expiring within 1 month', 'renewal');
            insuranceDashboardStatCard('Expired Insurance', $dash['expired'], 'expired-insurance.php', 'alert-circle', 'Past expiry date', 'expired');
            insuranceDashboardStatCard('Site Dispatched', $dash['site_dispatched'], 'pending-insurance.php', 'truck', 'Total dispatched sites', 'dispatch');
            insuranceDashboardStatCard('Total Completed', $dash['total_completed'], 'completed-insurance.php', 'shield', 'All processed insurance', 'total');
            insuranceDashboardStatCard('Imported This Month', $dash['imported_month'], 'completed-insurance.php', 'upload-cloud', 'Excel imports this month', 'import');
            insuranceDashboardStatCard('Renewed Insurance', $dash['renewed'], 'renewed-insurance.php', 'repeat', 'Marked renewed in history', 'renewed');
            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Insurance Status Distribution</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="statusDoughnutChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Renewal Overview</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="renewalOverviewChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Monthly Insurance Completed (Last 6 Months)</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="monthlyLineChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Status Comparison</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="statusBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Top Districts — Completed Insurance</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="districtBarChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-4">
        <div class="ins-dash-chart-card">
            <h6 class="ins-dash-chart-title">Top Insurance Companies</h6>
            <div class="ins-dash-chart-wrap">
                <canvas id="companyBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card ins-dash-shell">
    <h5 class="card-header ins-dash-header">Rooftop Insurance Summary</h5>
    <div class="card-body ins-dash-body">
        <div class="ins-dash-mini-grid">
            <div class="ins-dash-mini-card">
                <h5><?php echo number_format($dash['renewed']); ?></h5>
                <p>Renewed Insurance</p>
            </div>
            <div class="ins-dash-mini-card">
                <h5><?php echo number_format($dash['imported_month']); ?></h5>
                <p>Imported This Month</p>
            </div>
            <div class="ins-dash-mini-card">
                <h5><?php echo number_format($dash['total_completed']); ?></h5>
                <p>Total Completed</p>
            </div>
            <div class="ins-dash-mini-card">
                <h5><?php echo number_format($dash['site_dispatched']); ?></h5>
                <p>Site Dispatched</p>
            </div>
        </div>
    </div>
</div>

</div>

<?php include_once 'footer.php'; ?>

</div>
</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>

<script>
(function() {
    var chartDefaults = {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { boxWidth: 14, font: { size: 12 } }
            }
        }
    };

    new Chart(document.getElementById('statusDoughnutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Active Completed', 'Upcoming Renewal', 'Expired'],
            datasets: [{
                data: [
                    <?php echo (int) $dash['pending']; ?>,
                    <?php echo (int) $dash['active_completed']; ?>,
                    <?php echo (int) $dash['renewal']; ?>,
                    <?php echo (int) $dash['expired']; ?>
                ],
                backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: Object.assign({}, chartDefaults, {
            cutout: '62%',
            plugins: {
                legend: { position: 'bottom' }
            }
        })
    });

    new Chart(document.getElementById('renewalOverviewChart'), {
        type: 'pie',
        data: {
            labels: ['Upcoming Renewal', 'Renewed', 'Expired'],
            datasets: [{
                data: [
                    <?php echo (int) $dash['renewal']; ?>,
                    <?php echo (int) $dash['renewed']; ?>,
                    <?php echo (int) $dash['expired']; ?>
                ],
                backgroundColor: ['#3b82f6', '#14b8a6', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: Object.assign({}, chartDefaults, {
            plugins: { legend: { position: 'bottom' } }
        })
    });

    new Chart(document.getElementById('monthlyLineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthlyLabels); ?>,
            datasets: [{
                label: 'Insurance Completed',
                data: <?php echo json_encode($monthlyCounts); ?>,
                borderColor: '#0891b2',
                backgroundColor: 'rgba(8, 145, 178, 0.15)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointBackgroundColor: '#0891b2'
            }]
        },
        options: Object.assign({}, chartDefaults, {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        })
    });

    new Chart(document.getElementById('statusBarChart'), {
        type: 'bar',
        data: {
            labels: ['Pending', 'Active', 'Renewal', 'Expired', 'Dispatched'],
            datasets: [{
                data: [
                    <?php echo (int) $dash['pending']; ?>,
                    <?php echo (int) $dash['active_completed']; ?>,
                    <?php echo (int) $dash['renewal']; ?>,
                    <?php echo (int) $dash['expired']; ?>,
                    <?php echo (int) $dash['site_dispatched']; ?>
                ],
                backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6'],
                borderRadius: 10
            }]
        },
        options: Object.assign({}, chartDefaults, {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        })
    });

    new Chart(document.getElementById('districtBarChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($districtLabels); ?>,
            datasets: [{
                label: 'Completed',
                data: <?php echo json_encode($districtCounts); ?>,
                backgroundColor: '#2563eb',
                borderRadius: 8
            }]
        },
        options: Object.assign({}, chartDefaults, {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } }
            }
        })
    });

    new Chart(document.getElementById('companyBarChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($companyLabels); ?>,
            datasets: [{
                label: 'Policies',
                data: <?php echo json_encode($companyCounts); ?>,
                backgroundColor: '#0f766e',
                borderRadius: 8
            }]
        },
        options: Object.assign({}, chartDefaults, {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } }
            }
        })
    });
})();
</script>
</body>
</html>
