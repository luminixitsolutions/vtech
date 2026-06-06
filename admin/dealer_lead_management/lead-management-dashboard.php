<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once '../inc-lead-dashboard-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Lead';
$Page = 'Dealer-Lead-Dashboard';

$row77 = getRecord("SELECT Fname, Lname FROM tbl_users WHERE id='$user_id'");
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
$dash = getDealerLeadDashboardData();

$sourceLabels = array();
$sourceCounts = array();
$sourceColors = array();
foreach ($dash['source_stats'] as $i => $row) {
    $sourceLabels[] = $row['Name'];
    $sourceCounts[] = (int) $row['cnt'];
    $sourceColors[] = leadDashboardSourceChartColor($row['Name'], $i);
}

$statusLabels = array();
$statusCounts = array();
$statusColors = array();
foreach ($dash['status_stats'] as $row) {
    $statusLabels[] = $row['Name'];
    $statusCounts[] = (int) $row['cnt'];
    $statusColors[] = leadDashboardStatusChartColor($row['Name']);
}

$monthlyLabels = array();
$monthlyCounts = array();
foreach ($dash['monthly_stats'] as $row) {
    $monthlyLabels[] = $row['label'];
    $monthlyCounts[] = (int) $row['cnt'];
}

$branchLabels = array();
$branchCounts = array();
foreach ($dash['branch_stats'] as $row) {
    $branchLabels[] = $row['BranchName'];
    $branchCounts[] = (int) $row['cnt'];
}

$social = $dash['social'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Dealer Lead Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <?php echo leadSourceIconsStylesheetTag(); ?>
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/css/lead-management-dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>

            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y lead-dash-page lead-dash-page--dealer">

                        <div class="lead-dash-hero">
                            <h4>Dealer Lead Dashboard</h4>
                            <p>Monitor dealer-sourced leads, pipeline status, assignments, and marketing performance.</p>
                            <div class="lead-dash-hero-meta">
                                <span class="lead-dash-hero-pill"><i class="feather icon-user"></i> <?php echo htmlspecialchars($adminName !== '' ? $adminName : 'Admin'); ?></span>
                                <span class="lead-dash-hero-pill"><i class="feather icon-calendar"></i> <?php echo date('d M Y'); ?></span>
                                <span class="lead-dash-hero-pill"><i class="feather icon-briefcase"></i> <?php echo number_format($dash['total']); ?> dealer leads</span>
                                <span class="lead-dash-hero-pill"><i class="feather icon-plus-circle"></i> <?php echo number_format($dash['added_today']); ?> added today</span>
                            </div>
                        </div>

                        <div class="card lead-dash-shell">
                            <h5 class="card-header lead-dash-header">Dealer Lead Overview</h5>
                            <div class="card-body lead-dash-body">
                                <div class="lead-dash-stat-grid">
                                    <?php
                                    leadDashboardStatCard('Total Dealer Leads', $dash['total'], 'view-leads.php', 'layers', 'All dealer leads', 'total');
                                    leadDashboardStatCard('Added Today', $dash['added_today'], 'view-leads.php', 'sun', 'New leads today', 'today');
                                    leadDashboardStatCard('Added This Month', $dash['added_month'], 'view-leads.php', 'calendar', 'Leads this month', 'month');
                                    leadDashboardStatCard('Assigned Leads', $dash['assigned'], 'view-leads.php', 'user-check', 'Allocated to executives', 'assigned');
                                    leadDashboardStatCard('Unassigned Leads', $dash['unassigned'], 'view-leads.php', 'user-x', 'Awaiting assignment', 'unassigned');
                                    leadDashboardStatCard('Converted to Order', $dash['converted'], 'view-leads.php', 'shopping-cart', 'Opportunity converted', 'converted');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Lead Status Distribution</h6>
                                    <div class="lead-dash-chart-wrap">
                                        <canvas id="statusDoughnutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Lead Source Split</h6>
                                    <div class="lead-dash-chart-wrap lead-dash-chart-wrap--sm">
                                        <canvas id="sourcePieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Monthly Dealer Leads (Last 6 Months)</h6>
                                    <div class="lead-dash-chart-wrap">
                                        <canvas id="monthlyLineChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Status Comparison</h6>
                                    <div class="lead-dash-chart-wrap">
                                        <canvas id="statusBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-7 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Top Lead Sources</h6>
                                    <div class="lead-dash-chart-wrap">
                                        <canvas id="sourceBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 mb-4">
                                <div class="lead-dash-chart-card">
                                    <h6 class="lead-dash-chart-title">Leads by Branch</h6>
                                    <div class="lead-dash-chart-wrap">
                                        <canvas id="branchBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card lead-dash-shell">
                            <h5 class="card-header lead-dash-header">Lead Sources</h5>
                            <div class="card-body lead-dash-body">
                                <div class="lead-dash-source-grid">
                                    <?php foreach ($dash['source_stats'] as $row) {
                                        leadDashboardSourceStatCard($row['Name'], $row['cnt']);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card lead-dash-shell">
                            <h5 class="card-header lead-dash-header">Lead Status Pipeline</h5>
                            <div class="card-body lead-dash-body">
                                <div class="lead-dash-stat-grid">
                                    <?php foreach ($dash['status_stats'] as $row) {
                                        leadDashboardStatusStatCard($row['Name'], $row['cnt']);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card lead-dash-shell">
                            <h5 class="card-header lead-dash-header">Social Media Marketing</h5>
                            <div class="card-body lead-dash-body">
                                <div class="lead-dash-social-grid">
                                    <div class="lead-dash-social-card lead-dash-social-videos">
                                        <div class="lead-dash-social-icon"><i class="feather icon-video"></i></div>
                                        <h5><?php echo number_format((int) $social['Videos']); ?></h5>
                                        <p>Videos Created</p>
                                    </div>
                                    <div class="lead-dash-social-card lead-dash-social-blogs">
                                        <div class="lead-dash-social-icon"><i class="feather icon-file-text"></i></div>
                                        <h5><?php echo number_format((int) $social['Blogs']); ?></h5>
                                        <p>Blogs Created</p>
                                    </div>
                                    <div class="lead-dash-social-card lead-dash-social-influencers">
                                        <div class="lead-dash-social-icon"><i class="feather icon-users"></i></div>
                                        <h5><?php echo number_format((int) $social['Influencers']); ?></h5>
                                        <p>Influencers</p>
                                    </div>
                                    <div class="lead-dash-social-card lead-dash-social-creative">
                                        <div class="lead-dash-social-icon"><i class="feather icon-image"></i></div>
                                        <h5><?php echo number_format((int) $social['Creative']); ?></h5>
                                        <p>Creative Assets</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/material-ripple.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>

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
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($statusCounts); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
                    borderWidth: 0
                }]
            },
            options: Object.assign({}, chartDefaults, {
                cutout: '62%',
                plugins: { legend: { position: 'bottom' } }
            })
        });

        new Chart(document.getElementById('sourcePieChart'), {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($sourceLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($sourceCounts); ?>,
                    backgroundColor: <?php echo json_encode($sourceColors); ?>,
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
                    label: 'Dealer Leads Created',
                    data: <?php echo json_encode($monthlyCounts); ?>,
                    borderColor: '#d97706',
                    backgroundColor: 'rgba(217, 119, 6, 0.15)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#d97706'
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
                labels: <?php echo json_encode($statusLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($statusCounts); ?>,
                    backgroundColor: <?php echo json_encode($statusColors); ?>,
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

        new Chart(document.getElementById('sourceBarChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($sourceLabels); ?>,
                datasets: [{
                    label: 'Leads',
                    data: <?php echo json_encode($sourceCounts); ?>,
                    backgroundColor: <?php echo json_encode($sourceColors); ?>,
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

        new Chart(document.getElementById('branchBarChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($branchLabels); ?>,
                datasets: [{
                    label: 'Leads',
                    data: <?php echo json_encode($branchCounts); ?>,
                    backgroundColor: '#ea580c',
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
