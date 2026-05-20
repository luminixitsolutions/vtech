<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$MainPage = 'Dashboard';
$Page = 'Dashboard';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];

if ($Roll == 26) {
    echo "<script>window.location.href='dispatch-dashboard.php';</script>";
    exit();
}
if ($Roll != 1) {
    echo "<script>window.location.href='emp-dashboard.php';</script>";
    exit();
}

$today = date('Y-m-d');
$adminName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));

function adminDashboardLink($path, $params = []) {
    if (empty($params)) {
        return $path;
    }
    return $path . '?' . http_build_query($params);
}

function adminDashboardStatCard($label, $count, $href = '#', $icon = 'bar-chart-2', $badge = 'Total', $tone = 'slate') {
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $count = (int) $count;
    $href = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
    $icon = htmlspecialchars($icon, ENT_QUOTES, 'UTF-8');
    $badge = htmlspecialchars($badge, ENT_QUOTES, 'UTF-8');
    $tone = preg_replace('/[^a-z]/', '', $tone);
    ?>
    <a href="<?php echo $href; ?>" class="ad-stat-link">
        <div class="ad-stat-card ad-tone-<?php echo $tone; ?>">
            <h6 class="ad-stat-label"><?php echo $label; ?></h6>
            <div class="ad-stat-meta">
                <span class="ad-stat-count"><?php echo $count; ?></span>
                <span class="ad-stat-badge">
                    <i class="feather icon-<?php echo $icon; ?>" aria-hidden="true"></i>
                    <?php echo $badge; ?>
                </span>
            </div>
        </div>
    </a>
    <?php
}

$operationsStats = [
    ['Total Purchase Orders', getRow("SELECT id FROM tbl_purchase_order"), adminDashboardLink('view-purchase-order.php'), 'shopping-cart', 'Total', 'blue'],
    ['Today Purchase Orders', getRow("SELECT id FROM tbl_purchase_order WHERE InvoiceDate='$today'"), adminDashboardLink('view-purchase-order.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1]), 'calendar', 'Today', 'blue'],
    ['Total Delivery Challan', getRow("SELECT id FROM tbl_sell"), adminDashboardLink('view-sells.php'), 'truck', 'Total', 'green'],
    ['Today Delivery Challan', getRow("SELECT id FROM tbl_sell WHERE InvoiceDate='$today'"), adminDashboardLink('view-sells.php', ['FromDate' => $today, 'ToDate' => $today, 'Search' => 1]), 'calendar', 'Today', 'green'],
    ['Total Quotations', getRow("SELECT id FROM tbl_quotation"), adminDashboardLink('view-quotation.php'), 'file-text', 'Total', 'amber'],
    ['Today Quotations', getRow("SELECT id FROM tbl_quotation WHERE InvoiceDate='$today'"), adminDashboardLink('view-quotation.php', ['val' => 'today']), 'calendar', 'Today', 'amber'],
    ['Total Work Orders', getRow("SELECT id FROM tbl_work_order"), adminDashboardLink('view-work-order.php'), 'clipboard', 'Total', 'purple'],
];

$serviceStats = [
    ['Total Service Complaints', getRow("SELECT id FROM tbl_service_complaint"), adminDashboardLink('view-service-module.php'), 'alert-circle', 'Total', 'red'],
    ['Today Service Complaints', getRow("SELECT id FROM tbl_service_complaint WHERE CreatedDate='$today'"), adminDashboardLink('view-service-module.php', ['val' => 'today']), 'calendar', 'Today', 'red'],
    ['Total Insurance Claims', getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance'"), adminDashboardLink('view-service-module.php', ['ServiceType' => 'Insurance']), 'shield', 'Total', 'teal'],
    ['Today Insurance Claims', getRow("SELECT id FROM tbl_service_complaint WHERE ServiceType='Insurance' AND CreatedDate='$today'"), adminDashboardLink('view-service-module.php', ['ServiceType' => 'Insurance', 'val' => 'today']), 'calendar', 'Today', 'teal'],
];

$masterStats = [
    ['Total Products', getRow("SELECT id FROM tbl_products"), adminDashboardLink('product_management/view-products.php'), 'package', 'Total', 'slate'],
    ['Total Customers', getRow("SELECT id FROM tbl_users WHERE Roll=5"), adminDashboardLink('user_management/pump-customers.php'), 'users', 'Total', 'blue'],
    ['Total Manufacturers', getRow("SELECT id FROM tbl_users WHERE Roll=3"), adminDashboardLink('user_management/view-manufacture.php'), 'briefcase', 'Total', 'green'],
    ['Total Employees', getRow("SELECT id FROM tbl_users WHERE Roll=8"), adminDashboardLink('user_management/view-employee.php'), 'user', 'Total', 'purple'],
    ['Total Dealers', getRow("SELECT id FROM tbl_users WHERE Roll=9"), adminDashboardLink('user_management/view-dealer.php'), 'user-check', 'Total', 'amber'],
];

$claimStatusStats = [];
$sqlClaimStatus = "SELECT Name FROM tbl_common_master WHERE Status='1' AND Roll=6 ORDER BY Name ASC";
foreach (getList($sqlClaimStatus) as $claimStatus) {
    $statusName = $claimStatus['Name'];
    $claimStatusStats[] = [
        $statusName . ' Complaints',
        getRow("SELECT id FROM tbl_service_complaint WHERE ClainStatus='" . mysqli_real_escape_string($GLOBALS['conn'], $statusName) . "'"),
        adminDashboardLink('view-service-module.php', ['ClainStatus' => $statusName]),
        'flag',
        'Status',
        'red',
    ];
}

$schemeStats = [];
$sqlScheme = "SELECT id, Name FROM tbl_scheme WHERE Status='1' ORDER BY Name ASC";
foreach (getList($sqlScheme) as $scheme) {
    $schemeStats[] = [
        $scheme['Name'],
        getRow("SELECT id FROM tbl_users WHERE SchemeId='" . (int) $scheme['id'] . "'"),
        adminDashboardLink('user_management/pump-customers.php', ['SchemeId' => (int) $scheme['id']]),
        'layers',
        'Beneficiaries',
        'teal',
    ];
}

$projectStats = [];
$sqlProject = "SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
foreach (getList($sqlProject) as $project) {
    $projectStats[] = [
        $project['Name'],
        getRow("SELECT id FROM tbl_users WHERE ProjectId='" . (int) $project['id'] . "'"),
        adminDashboardLink('installation-project-sub-head-dashboard.php', ['id' => (int) $project['id'], 'name' => $project['Name']]),
        'folder',
        'Records',
        'blue',
    ];
}

$subHeadStats = [];
$sqlSubHead = "SELECT id, Name, UnderBy FROM tbl_project_sub_head WHERE Status='1' ORDER BY Name ASC";
foreach (getList($sqlSubHead) as $subHead) {
    $subHeadStats[] = [
        $subHead['Name'],
        getRow("SELECT id FROM tbl_users WHERE ProjectSubHeadId='" . (int) $subHead['id'] . "'"),
        adminDashboardLink('installation-project-dashboard-2.php', [
            'prjid' => (int) $subHead['UnderBy'],
            'id' => (int) $subHead['id'],
            'name' => $subHead['Name'],
        ]),
        'git-branch',
        'Records',
        'purple',
    ];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
    <link rel="stylesheet" href="css/admin-dashboard.css">
</head>
<body>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <?php include_once 'header.php'; ?>
            <div class="layout-container">
                <?php include_once 'top_header.php'; ?>
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y ad-page">

                        <div class="ad-welcome">
                            <h4>Welcome<?php echo $adminName !== '' ? ', ' . htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') : ''; ?></h4>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Operations</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($operationsStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Service &amp; Complaints</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($serviceStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                    <?php foreach ($claimStatusStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Master Data</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($masterStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($schemeStats)) { ?>
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Scheme / Yojna</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($schemeStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!empty($projectStats)) { ?>
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Project</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($projectStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if (!empty($subHeadStats)) { ?>
                        <div class="card ad-shell">
                            <h5 class="card-header ad-header">Project Sub Head</h5>
                            <div class="card-body ad-body">
                                <div class="ad-stat-grid">
                                    <?php foreach ($subHeadStats as $stat) {
                                        adminDashboardStatCard($stat[0], $stat[1], $stat[2], $stat[3], $stat[4], $stat[5]);
                                    } ?>
                                </div>
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
</body>
</html>
