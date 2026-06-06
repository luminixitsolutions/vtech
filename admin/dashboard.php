<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once __DIR__ . '/inc-admin-dashboard-data.php';

$MainPage = 'Dashboard';
$Page = 'Dashboard';
$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$userOptions = array_filter(explode(',', (string) ($row77['Options'] ?? '')));
$dashFullAccess = adminDashboardHasFullAccess($Roll);

if ($Roll === 26) {
    echo "<script>window.location.href='dispatch-dashboard.php';</script>";
    exit();
}

$today = date('Y-m-d');
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
$dashboardTitle = $dashFullAccess ? 'Admin Dashboard' : 'Dashboard';
$dashboardIntro = $dashFullAccess
    ? 'Welcome' . ($adminName !== '' ? ', ' . htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') : '') . ' — overview without duplicate counts; each section shows data once.'
    : 'Welcome' . ($adminName !== '' ? ', ' . htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') : '') . ' — counts and charts for menus you can access.';

$accountStats = adminDashboardFilterAccountStats(getAdminDashboardAccountStats(), $userOptions, $Roll);
$showAccounts = $dashFullAccess || adminDashboardCanSeeMenuGroup($userOptions, 'User Accounts', $Roll);
$showProjects = $dashFullAccess || adminDashboardCanSeeOptions($userOptions, [68], $Roll);
$showOperations = $dashFullAccess || adminDashboardCanSeeOptions($userOptions, [25, 26, 117, 140], $Roll);
$showService = $dashFullAccess || adminDashboardCanSeeOptions($userOptions, [28, 135, 136, 137, 164], $Roll);
$showPoChart = $dashFullAccess || adminDashboardCanSeeOptions($userOptions, [25], $Roll);
$showComplaintChart = $dashFullAccess || adminDashboardCanSeeOptions($userOptions, [28], $Roll);
$showProjectsLink = adminDashboardCanSeeOptions($userOptions, [68], $Roll);
$showAccountsLink = $dashFullAccess || adminDashboardCanSeeMenuGroup($userOptions, 'User Accounts', $Roll);
$projectChart = getAdminDashboardProjectHeadChartData(12);
$projectHeadBoxes = getAdminDashboardProjectHeadStatCards();
$subHeadChart = getAdminDashboardSubHeadChartData(10);
$subHeadBoxes = getAdminDashboardSubHeadStatCards();
$schemeChart = getAdminDashboardSchemeChartData(8);
$poMonthly = getAdminDashboardMonthlyCounts('tbl_purchase_order', 'InvoiceDate', 6);
$complaintMonthly = getAdminDashboardMonthlyCounts('tbl_service_complaint', 'CreatedDate', 6);

function adminDashboardLink($path, $params = []) {
    if (empty($params)) {
        return $path;
    }
    return $path . '?' . http_build_query($params);
}

function adminDashboardStatCard($label, $count, $href = '#', $icon = 'bar-chart-2', $badge = 'Total', $tone = 'slate', $compact = true) {
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $badge = htmlspecialchars($badge, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="ad-stat-link ad-stat-link-compact">
        <div class="ad-stat-card ad-stat-card-compact ad-tone-<?php echo $tone; ?>">
            <h6 class="ad-stat-label"><?php echo $label; ?></h6>
            <div class="ad-stat-meta">
                <span class="ad-stat-count"><?php echo number_format($count); ?></span>
                <span class="ad-stat-badge">
                    <i class="feather icon-<?php echo $icon; ?>" aria-hidden="true"></i>
                    <?php echo $badge; ?>
                </span>
            </div>
        </div>
    </a>
    <?php
}

function adminDashboardRenderStatCards($items, $defaultBadge = 'Total') {
    foreach ($items as $stat) {
        $href = adminDashboardLink($stat[2], isset($stat[3]) && is_array($stat[3]) ? $stat[3] : []);
        $icon = $stat[4] ?? 'bar-chart-2';
        $badge = $stat[5] ?? $defaultBadge;
        $tone = $stat[6] ?? 'slate';
        adminDashboardStatCard($stat[0], $stat[1], $href, $icon, $badge, $tone, true);
    }
}

$operationsStats = adminDashboardFilterOperationsStats([
    ['Total Purchase Orders', getRow("SELECT id FROM tbl_purchase_order"), 'view-purchase-order.php', [], 'shopping-cart', 'Total', 'blue'],
    ['Today Purchase Orders', getRow("SELECT id FROM tbl_purchase_order WHERE InvoiceDate='$today'"), 'view-purchase-order.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1], 'calendar', 'Today', 'blue'],
    ['Total Delivery Challan', getRow("SELECT id FROM tbl_sell"), 'view-sells.php', [], 'truck', 'Total', 'green'],
    ['Today Delivery Challan', getRow("SELECT id FROM tbl_sell WHERE InvoiceDate='$today'"), 'view-sells.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1], 'calendar', 'Today', 'green'],
    ['Total Quotations', getRow("SELECT id FROM tbl_quotation"), 'view-quotation.php', [], 'file-text', 'Total', 'amber'],
    ['Today Quotations', getRow("SELECT id FROM tbl_quotation WHERE InvoiceDate='$today'"), 'view-quotation.php', ['val' => 'today'], 'calendar', 'Today', 'amber'],
    ['Total Work Orders', getRow("SELECT id FROM tbl_work_order"), 'view-work-order.php', [], 'clipboard', 'Total', 'purple'],
], $userOptions, $Roll);

$serviceStats = adminDashboardFilterServiceStats([
    ['Total Service Complaints', getRow("SELECT id FROM tbl_service_complaint"), 'view-service-module.php', [], 'alert-circle', 'Total', 'red'],
    ['Today Service Complaints', getRow("SELECT id FROM tbl_service_complaint WHERE CreatedDate='$today'"), 'view-service-module.php', ['val' => 'today'], 'calendar', 'Today', 'red'],
    ['Total Insurance Claims', getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance'"), 'view-service-module.php', ['ServiceType' => 'Insurance'], 'shield', 'Total', 'teal'],
    ['Today Insurance Claims', getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance' AND CreatedDate='$today'"), 'view-service-module.php', ['ServiceType' => 'Insurance', 'val' => 'today'], 'calendar', 'Today', 'teal'],
], $userOptions, $Roll);

$showOperations = $showOperations && !empty($operationsStats);
$showService = $showService && !empty($serviceStats);
$showAccounts = $showAccounts && !empty($accountStats);

$hasDashboardContent = $showAccounts || $showProjects || $showOperations || $showService || $showPoChart || $showComplaintChart;

$claimStatusChart = ['labels' => [], 'counts' => []];
if ($showComplaintChart) {
    foreach (getList("SELECT Name FROM tbl_common_master WHERE Status='1' AND Roll=6 ORDER BY Name ASC") as $claimStatus) {
        $statusName = $claimStatus['Name'];
        $cnt = getRow("SELECT id FROM tbl_service_complaint WHERE ClainStatus='" . mysqli_real_escape_string($GLOBALS['conn'], $statusName) . "'");
        if ($cnt > 0) {
            $claimStatusChart['labels'][] = $statusName;
            $claimStatusChart['counts'][] = $cnt;
        }
    }
}

$hasProjectChart = $showProjects && !empty($projectChart['labels']);
$hasSubHeadChart = $showProjects && !empty($subHeadChart['labels']);
$hasSchemeChart = $showProjects && !empty($schemeChart['labels']);
if (!$showProjects) {
    $projectHeadBoxes = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <link rel="stylesheet" href="css/admin-dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <?php include_once 'header.php'; ?>
            <div class="layout-container">
                <?php include_once 'top_header.php'; ?>
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y ad-page">

                        <div class="ad-hero">
                            <div class="ad-hero-inner">
                                <h4><?php echo htmlspecialchars($dashboardTitle, ENT_QUOTES, 'UTF-8'); ?></h4>
                                <p><?php echo $dashboardIntro; ?></p>
                                <div class="ad-hero-meta">
                                    <span class="ad-hero-pill"><i class="feather icon-calendar"></i> <?php echo date('l, d M Y'); ?></span>
                                    <?php if ($showProjectsLink) { ?>
                                    <a href="installation-project-dashboard.php" class="ad-hero-pill" style="color:#fff;"><i class="feather icon-layers"></i> All Projects</a>
                                    <?php } ?>
                                    <?php if ($showAccountsLink) { ?>
                                    <a href="user_management/account-managment-dashboard.php" class="ad-hero-pill" style="color:#fff;"><i class="feather icon-users"></i> Employee Management</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!$hasDashboardContent) { ?>
                        <div class="card ad-shell">
                            <div class="card-body ad-body">
                                <p class="mb-0 text-muted">No dashboard widgets match your menu access. Use the sidebar to open allowed screens, or ask an administrator to assign <strong>View</strong> permissions.</p>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($showAccounts) { ?>
                        <!-- 1. User accounts — only place for account totals -->
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>User Accounts</span>
                                <?php if ($showAccountsLink) { ?>
                                <a href="user_management/account-managment-dashboard.php" class="ad-header-link">Open account dashboard →</a>
                                <?php } ?>
                            </h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php foreach ($accountStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], adminDashboardLink($stat[2]), $stat[3], 'Accounts', $stat[4], true);
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($showProjects) { ?>
                        <!-- 2. Projects: chart + count boxes (no duplicate elsewhere) -->
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>Projects &amp; Beneficiaries</span>
                                <?php if ($showProjectsLink) { ?>
                                <a href="installation-project-dashboard.php" class="ad-header-link">View all projects →</a>
                                <?php } ?>
                            </h5>
                            <div class="card-body ad-body">

                                <div class="ad-section-block">
                                    <h6 class="ad-chart-title mb-2">Project Head Wise</h6>
                                    <?php if ($hasProjectChart) { ?>
                                    <div class="ad-chart-card mb-0" style="box-shadow:none;border:1px solid #e2e8f0;padding:0.85rem;">
                                        <div class="ad-chart-wrap ad-chart-sm">
                                            <canvas id="projectHeadChart"></canvas>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if (!empty($projectHeadBoxes)) { ?>
                                    <div class="ad-boxes-below-chart">
                                        <div class="ad-stat-grid ad-stat-grid-compact<?php echo count($projectHeadBoxes) > 12 ? ' ad-stat-grid-scroll' : ''; ?>">
                                            <?php adminDashboardRenderStatCards($projectHeadBoxes, 'Beneficiaries'); ?>
                                        </div>
                                    </div>
                                    <?php } elseif (!$hasProjectChart) { ?>
                                    <div class="ad-chart-empty">No project heads configured</div>
                                    <?php } ?>
                                </div>

                                <div class="ad-section-block">
                                    <h6 class="ad-chart-title mb-2">Project Sub Head Wise</h6>
                                    <?php if ($hasSubHeadChart) { ?>
                                    <div class="ad-chart-card mb-0" style="box-shadow:none;border:1px solid #e2e8f0;padding:0.85rem;">
                                        <div class="ad-chart-wrap ad-chart-sm">
                                            <canvas id="subHeadChart"></canvas>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if (!empty($subHeadBoxes)) { ?>
                                    <div class="ad-boxes-below-chart">
                                        <div class="ad-stat-grid ad-stat-grid-compact ad-stat-grid-scroll">
                                            <?php adminDashboardRenderStatCards($subHeadBoxes, 'Beneficiaries'); ?>
                                        </div>
                                    </div>
                                    <?php } elseif (!$hasSubHeadChart) { ?>
                                    <div class="ad-chart-empty">No project sub heads configured</div>
                                    <?php } ?>
                                </div>

                                <?php if ($hasSchemeChart) { ?>
                                <div class="ad-section-block">
                                    <h6 class="ad-chart-title mb-2">Scheme / Yojna (chart only)</h6>
                                    <div class="ad-chart-card mb-0" style="box-shadow:none;border:1px solid #e2e8f0;padding:0.85rem;">
                                        <div class="ad-chart-wrap ad-chart-sm">
                                            <canvas id="schemeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($showOperations) { ?>
                        <!-- 3. Operations — boxes only -->
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Operations</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php adminDashboardRenderStatCards($operationsStats); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($showService) { ?>
                        <!-- 4. Service — boxes only -->
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>Service &amp; Complaints</span>
                                <?php if (adminDashboardCanSeeOptions($userOptions, [28], $Roll)) { ?>
                                <a href="view-service-module.php" class="ad-header-link">View all →</a>
                                <?php } ?>
                            </h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php adminDashboardRenderStatCards($serviceStats); ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($showPoChart || $showComplaintChart) { ?>
                        <!-- 5. Trends & status charts (no count boxes — avoids repeat) -->
                        <div class="ad-two-col-sections">
                            <?php if ($showPoChart) { ?>
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Purchase Orders — Last 6 Months</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <canvas id="poMonthlyChart"></canvas>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($showComplaintChart) { ?>
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Service Complaints — Last 6 Months</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <canvas id="complaintMonthlyChart"></canvas>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                        <?php if ($showComplaintChart && !empty($claimStatusChart['labels'])) { ?>
                        <div class="ad-chart-card">
                            <h6 class="ad-chart-title">Complaints by Status</h6>
                            <div class="ad-chart-wrap ad-chart-sm">
                                <canvas id="claimStatusChart"></canvas>
                            </div>
                        </div>
                        <?php } ?>

                    </div>
                    <?php include_once 'footer.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-sidenav-toggle"></div>
    <?php include_once 'footer_script.php'; ?>
    <script>
    (function() {
        var chartDefaults = {
            maintainAspectRatio: false,
            plugins: { legend: { labels: { boxWidth: 12, font: { size: 11 } } } }
        };
        var palette = ['#4f46e5', '#3b82f6', '#06b6d4', '#10b981', '#84cc16', '#eab308', '#f97316', '#ef4444', '#ec4899', '#8b5cf6', '#6366f1', '#14b8a6'];

        <?php if ($hasProjectChart) { ?>
        new Chart(document.getElementById('projectHeadChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($projectChart['labels']); ?>,
                datasets: [{
                    label: 'Beneficiaries',
                    data: <?php echo json_encode($projectChart['counts']); ?>,
                    backgroundColor: palette,
                    borderRadius: 8,
                    maxBarThickness: 36
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxRotation: 40, minRotation: 20, font: { size: 10 } } }
                }
            })
        });
        <?php } ?>

        <?php if ($hasSubHeadChart) { ?>
        new Chart(document.getElementById('subHeadChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($subHeadChart['labels']); ?>,
                datasets: [{
                    label: 'Beneficiaries',
                    data: <?php echo json_encode($subHeadChart['counts']); ?>,
                    backgroundColor: '#6366f1',
                    borderRadius: 8
                }]
            },
            options: Object.assign({}, chartDefaults, {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            })
        });
        <?php } ?>

        <?php if ($hasSchemeChart) { ?>
        new Chart(document.getElementById('schemeChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($schemeChart['labels']); ?>,
                datasets: [{
                    label: 'Beneficiaries',
                    data: <?php echo json_encode($schemeChart['counts']); ?>,
                    backgroundColor: '#0d9488',
                    borderRadius: 8
                }]
            },
            options: Object.assign({}, chartDefaults, {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            })
        });
        <?php } ?>

        <?php if ($showPoChart) { ?>
        new Chart(document.getElementById('poMonthlyChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($poMonthly['labels']); ?>,
                datasets: [{
                    label: 'Purchase Orders',
                    data: <?php echo json_encode($poMonthly['counts']); ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            })
        });
        <?php } ?>

        <?php if ($showComplaintChart) { ?>
        new Chart(document.getElementById('complaintMonthlyChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($complaintMonthly['labels']); ?>,
                datasets: [{
                    label: 'Complaints',
                    data: <?php echo json_encode($complaintMonthly['counts']); ?>,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            })
        });
        <?php } ?>

        <?php if ($showComplaintChart && !empty($claimStatusChart['labels'])) { ?>
        new Chart(document.getElementById('claimStatusChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($claimStatusChart['labels']); ?>,
                datasets: [{
                    data: <?php echo json_encode($claimStatusChart['counts']); ?>,
                    backgroundColor: '#f97316',
                    borderRadius: 8
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            })
        });
        <?php } ?>
    })();
    </script>
</body>
</html>
