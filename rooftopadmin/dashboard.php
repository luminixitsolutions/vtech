<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once __DIR__ . '/inc-rooftop-dashboard-data.php';

$MainPage = 'Dashboard';
$Page = 'Dashboard';
$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);

if ($Roll === 26) {
    echo "<script>window.location.href='dispatch-dashboard.php';</script>";
    exit();
}
if ($Roll !== 1) {
    echo "<script>window.location.href='emp-dashboard.php';</script>";
    exit();
}

$today = date('Y-m-d');
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));

$counts = [
    'po_total' => (int) getRow("SELECT id FROM tbl_rooftop_purchase_order"),
    'po_today' => (int) getRow("SELECT id FROM tbl_rooftop_purchase_order WHERE InvoiceDate='$today'"),
    'challan_total' => (int) getRow("SELECT id FROM tbl_rooftop_sell"),
    'challan_today' => (int) getRow("SELECT id FROM tbl_rooftop_sell WHERE InvoiceDate='$today'"),
    'quotation_total' => (int) getRow("SELECT id FROM tbl_rooftop_quotation"),
    'quotation_today' => (int) getRow("SELECT id FROM tbl_rooftop_quotation WHERE InvoiceDate='$today'"),
    'work_order_total' => (int) getRow("SELECT id FROM tbl_rooftop_work_order"),
    'complaint_total' => (int) getRow("SELECT id FROM tbl_rooftop_service_complaint"),
    'complaint_today' => (int) getRow("SELECT id FROM tbl_rooftop_service_complaint WHERE CreatedDate='$today'"),
    'insurance_total' => (int) getRow("SELECT id FROM tbl_rooftop_service_complaint WHERE ServiceType='Insurance'"),
    'insurance_today' => (int) getRow("SELECT id FROM tbl_rooftop_service_complaint WHERE ServiceType='Insurance' AND CreatedDate='$today'"),
    'products' => (int) getRow("SELECT id FROM tbl_rooftop_products"),
    'customers' => (int) getRow("SELECT id FROM tbl_users WHERE Roll=5 AND ProjectType=2"),
    'manufacturers' => (int) getRow("SELECT id FROM tbl_users WHERE Roll=3"),
    'employees' => (int) getRow("SELECT id FROM tbl_users WHERE Roll=8"),
    'dealers' => (int) getRow("SELECT id FROM tbl_users WHERE Roll=9"),
];

$poMonthly = rooftopDashboardMonthlyCounts('tbl_rooftop_purchase_order', 'InvoiceDate', 6);
$challanMonthly = rooftopDashboardMonthlyCounts('tbl_rooftop_sell', 'InvoiceDate', 6);
$complaintMonthly = rooftopDashboardMonthlyCounts('tbl_rooftop_service_complaint', 'CreatedDate', 6);
$claimStatusChart = rooftopDashboardClaimStatusChart();

$claimStatusStats = [];
foreach (getList("SELECT Name FROM tbl_common_master WHERE Status='1' AND Roll=6 ORDER BY Name ASC") as $claimRow) {
    $esc = mysqli_real_escape_string($conn, $claimRow['Name']);
    $claimStatusStats[] = [
        $claimRow['Name'] . ' Complaints',
        (int) getRow("SELECT id FROM tbl_rooftop_service_complaint WHERE ClainStatus='$esc'"),
        'view-service-module.php',
        ['ClainStatus' => $claimRow['Name']],
        'alert-circle',
        'Status',
        'amber',
    ];
}

function rooftopDashLink($path, $params = [])
{
    if (empty($params)) {
        return $path;
    }
    return $path . '?' . http_build_query($params);
}

function rooftopDashStatCard($label, $count, $href = '#', $icon = 'bar-chart-2', $badge = 'Total', $tone = 'slate')
{
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $badge = htmlspecialchars($badge, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="ad-stat-link ad-stat-link-compact">
        <div class="ad-stat-card ad-stat-card-compact ad-tone-<?php echo $tone; ?>">
            <h6 class="ad-stat-label"><?php echo $label; ?></h6>
            <div class="ad-stat-meta">
                <span class="ad-stat-count"><?php echo number_format((int) $count); ?></span>
                <span class="ad-stat-badge">
                    <i class="feather icon-<?php echo $icon; ?>" aria-hidden="true"></i>
                    <?php echo $badge; ?>
                </span>
            </div>
        </div>
    </a>
    <?php
}

function rooftopDashRenderStats($items)
{
    foreach ($items as $stat) {
        $href = rooftopDashLink($stat[2], isset($stat[3]) && is_array($stat[3]) ? $stat[3] : []);
        rooftopDashStatCard($stat[0], $stat[1], $href, $stat[4] ?? 'bar-chart-2', $stat[5] ?? 'Total', $stat[6] ?? 'slate');
    }
}

$operationsStats = [
    ['Total Purchase Orders', $counts['po_total'], 'view-purchase-order.php', [], 'shopping-cart', 'Total', 'blue'],
    ['Today Purchase Orders', $counts['po_today'], 'view-purchase-order.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1], 'calendar', 'Today', 'blue'],
    ['Total Delivery Challan', $counts['challan_total'], 'view-sells.php', [], 'truck', 'Total', 'green'],
    ['Today Delivery Challan', $counts['challan_today'], 'view-sells.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1], 'calendar', 'Today', 'green'],
    ['Total Quotations', $counts['quotation_total'], 'view-quotation.php', [], 'file-text', 'Total', 'amber'],
    ['Today Quotations', $counts['quotation_today'], 'view-quotation.php', ['val' => 'today'], 'calendar', 'Today', 'amber'],
    ['Total Work Orders', $counts['work_order_total'], 'view-work-order.php', [], 'clipboard', 'Total', 'purple'],
];

$serviceStats = [
    ['Total Service Complaints', $counts['complaint_total'], 'view-service-module.php', [], 'alert-circle', 'Total', 'red'],
    ['Today Service Complaints', $counts['complaint_today'], 'view-service-module.php', ['val' => 'today'], 'calendar', 'Today', 'red'],
    ['Total Insurance Claims', $counts['insurance_total'], 'view-service-module.php', ['ServiceType' => 'Insurance'], 'shield', 'Total', 'teal'],
    ['Today Insurance Claims', $counts['insurance_today'], 'view-service-module.php', ['ServiceType' => 'Insurance', 'val' => 'today'], 'calendar', 'Today', 'teal'],
];

$accountStats = [
    ['Total Products', $counts['products'], 'product_management/view-products.php', [], 'package', 'Catalog', 'slate'],
    ['Total Customers', $counts['customers'], 'user_management/view-customers.php', [], 'users', 'Accounts', 'green'],
    ['Total Manufacturers', $counts['manufacturers'], 'user_management/view-manufacture.php', [], 'briefcase', 'Accounts', 'blue'],
    ['Total Employees', $counts['employees'], 'user_management/view-employee.php', [], 'user-check', 'Accounts', 'purple'],
    ['Total Dealers', $counts['dealers'], 'user_management/view-dealer.php', [], 'user-plus', 'Accounts', 'amber'],
];

$overviewChart = [
    'labels' => ['Purchase Orders', 'Delivery Challan', 'Quotations', 'Work Orders', 'Complaints'],
    'counts' => [
        $counts['po_total'],
        $counts['challan_total'],
        $counts['quotation_total'],
        $counts['work_order_total'],
        $counts['complaint_total'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <link rel="stylesheet" href="css/rooftop-dashboard.css">
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
                                <h4>Rooftop Admin Dashboard</h4>
                                <p>Welcome<?php echo $adminName !== '' ? ', ' . htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') : ''; ?> — live overview of rooftop operations, service, and accounts.</p>
                                <div class="ad-hero-meta">
                                    <span class="ad-hero-pill"><i class="feather icon-calendar"></i> <?php echo date('l, d M Y'); ?></span>
                                    <a href="view-purchase-order.php" class="ad-hero-pill"><i class="feather icon-shopping-cart"></i> Purchase Orders</a>
                                    <a href="view-service-module.php" class="ad-hero-pill"><i class="feather icon-alert-circle"></i> Service Module</a>
                                </div>
                            </div>
                        </div>

                        <div class="ad-kpi-strip">
                            <a href="view-purchase-order.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon blue"><i class="feather icon-shopping-cart"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['po_total']); ?></div>
                                <div class="ad-kpi-label">Purchase Orders</div>
                            </a>
                            <a href="view-sells.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon green"><i class="feather icon-truck"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['challan_total']); ?></div>
                                <div class="ad-kpi-label">Delivery Challan</div>
                            </a>
                            <a href="view-quotation.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon amber"><i class="feather icon-file-text"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['quotation_total']); ?></div>
                                <div class="ad-kpi-label">Quotations</div>
                            </a>
                            <a href="view-service-module.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon red"><i class="feather icon-alert-circle"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['complaint_total']); ?></div>
                                <div class="ad-kpi-label">Service Complaints</div>
                            </a>
                            <a href="user_management/view-customers.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon teal"><i class="feather icon-users"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['customers']); ?></div>
                                <div class="ad-kpi-label">Customers</div>
                            </a>
                            <a href="product_management/view-products.php" class="ad-kpi-card">
                                <div class="ad-kpi-icon purple"><i class="feather icon-package"></i></div>
                                <div class="ad-kpi-value"><?php echo number_format($counts['products']); ?></div>
                                <div class="ad-kpi-label">Products</div>
                            </a>
                        </div>

                        <div class="ad-three-col-charts">
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Operations Overview</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <canvas id="overviewChart"></canvas>
                                </div>
                            </div>
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Purchase Orders — Last 6 Months</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <?php if (!empty($poMonthly['labels'])) { ?>
                                    <canvas id="poMonthlyChart"></canvas>
                                    <?php } else { ?>
                                    <div class="ad-chart-empty">No purchase order data yet</div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Delivery Challan — Last 6 Months</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <?php if (!empty($challanMonthly['labels'])) { ?>
                                    <canvas id="challanMonthlyChart"></canvas>
                                    <?php } else { ?>
                                    <div class="ad-chart-empty">No challan data yet</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="ad-two-col-sections">
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Service Complaints — Last 6 Months</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <?php if (!empty($complaintMonthly['labels'])) { ?>
                                    <canvas id="complaintMonthlyChart"></canvas>
                                    <?php } else { ?>
                                    <div class="ad-chart-empty">No complaint data yet</div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="ad-chart-card">
                                <h6 class="ad-chart-title">Complaints by Status</h6>
                                <div class="ad-chart-wrap ad-chart-sm">
                                    <?php if (!empty($claimStatusChart['labels'])) { ?>
                                    <canvas id="claimStatusChart"></canvas>
                                    <?php } else { ?>
                                    <div class="ad-chart-empty">No status breakdown available</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>Operations</span>
                                <a href="view-purchase-order.php" class="ad-header-link">View purchase orders →</a>
                            </h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php rooftopDashRenderStats($operationsStats); ?>
                                </div>
                            </div>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>Service &amp; Complaints</span>
                                <a href="view-service-module.php" class="ad-header-link">View all →</a>
                            </h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php rooftopDashRenderStats($serviceStats); ?>
                                    <?php rooftopDashRenderStats($claimStatusStats); ?>
                                </div>
                            </div>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">
                                <span>Accounts &amp; Catalog</span>
                                <a href="user_management/view-customers.php" class="ad-header-link">View customers →</a>
                            </h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid ad-stat-grid-compact">
                                    <?php rooftopDashRenderStats($accountStats); ?>
                                </div>
                            </div>
                        </div>

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
        var palette = ['#0b7a43', '#12a05c', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];

        new Chart(document.getElementById('overviewChart'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($overviewChart['labels']); ?>,
                datasets: [{
                    data: <?php echo json_encode($overviewChart['counts']); ?>,
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                }
            })
        });

        <?php if (!empty($poMonthly['labels'])) { ?>
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

        <?php if (!empty($challanMonthly['labels'])) { ?>
        new Chart(document.getElementById('challanMonthlyChart'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($challanMonthly['labels']); ?>,
                datasets: [{
                    label: 'Delivery Challan',
                    data: <?php echo json_encode($challanMonthly['counts']); ?>,
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.12)',
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

        <?php if (!empty($complaintMonthly['labels'])) { ?>
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

        <?php if (!empty($claimStatusChart['labels'])) { ?>
        new Chart(document.getElementById('claimStatusChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($claimStatusChart['labels']); ?>,
                datasets: [{
                    data: <?php echo json_encode($claimStatusChart['counts']); ?>,
                    backgroundColor: '#f97316',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: Object.assign({}, chartDefaults, {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxRotation: 45, minRotation: 20, font: { size: 10 } } }
                }
            })
        });
        <?php } ?>
    })();
    </script>
</body>
</html>
