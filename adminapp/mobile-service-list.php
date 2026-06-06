<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';
$isAbstract = isset($_GET['abstract']) && $_GET['abstract'] === '1';
$today = mobileMgmtToday();

$labels = array(
    'all' => 'All Complaints',
    'today' => 'Today Complaints',
    'pending' => 'Pending Complaints',
    'closed' => 'Closed Complaints',
    'insurance' => 'Insurance Claims',
    'maintenance' => 'Maintenance Complaints',
);

$abstractProjid = isset($_REQUEST['projid']) ? (int) $_REQUEST['projid'] : 0;
$abstractSubheadid = isset($_REQUEST['subheadid']) ? (int) $_REQUEST['subheadid'] : 0;
$abstractDistrict = isset($_REQUEST['District']) ? trim($_REQUEST['District']) : '';
$abstractFilter = '';

if ($isAbstract) {
    if (!empty($_REQUEST['ClainStatus']) && $_REQUEST['ClainStatus'] === 'Close') {
        $abstractFilter = 'closed';
    } elseif (!empty($_REQUEST['val']) && $_REQUEST['val'] === 'today') {
        $abstractFilter = 'today';
    } elseif (!empty($_REQUEST['ClainStatus']) && $_REQUEST['ClainStatus'] === 'In Process') {
        $abstractFilter = 'material';
    } elseif (!empty($_REQUEST['Status']) && $_REQUEST['Status'] === 'Pending') {
        $abstractFilter = 'pending';
    }

    $rowLabel = $abstractDistrict;
    if ($abstractSubheadid > 0) {
        $subRow = getRecord("SELECT Name FROM tbl_project_sub_head WHERE id = '$abstractSubheadid'");
        $rowLabel = $subRow['Name'] ?? $rowLabel;
    } elseif ($abstractProjid > 0) {
        $projRow = getRecord("SELECT Name FROM tbl_common_master WHERE id = '$abstractProjid'");
        $rowLabel = $projRow['Name'] ?? $rowLabel;
    }

    $title = mobileServiceAbstractListTitle(array(), $abstractFilter, $rowLabel);
} else {
    $title = isset($labels[$filter]) ? $labels[$filter] : 'Service List';
}

$sql = "SELECT tp.*, tc.Name AS IssueName, tb.Name AS BranchName
    FROM tbl_service_complaint tp";

if ($isAbstract) {
    $sql .= " INNER JOIN tbl_users tu ON tu.id = tp.CustId
        LEFT JOIN tbl_issues tc ON tc.id = tp.Issue
        LEFT JOIN tbl_branch tb ON tp.BranchId = tb.id
        WHERE tu.ProjectType = 1";

    if ($abstractSubheadid > 0) {
        $sql .= " AND tu.ProjectSubHeadId = '$abstractSubheadid'";
    } elseif ($abstractProjid > 0) {
        $sql .= " AND tu.ProjectId = '$abstractProjid'";
    }

    if ($abstractDistrict !== '' && $abstractDistrict !== 'TOTAL') {
        if ($abstractDistrict === 'NASHIK (MALEGAON)') {
            $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) IN ('NASHIK','MALEGAON')";
        } elseif ($abstractDistrict === 'AHMEDNAGAR') {
            $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) IN ('AHMEDNAGAR','AHMEDNAAGAR')";
        } else {
            $distEsc = $conn->real_escape_string(strtoupper($abstractDistrict));
            $sql .= " AND UPPER(TRIM(COALESCE(NULLIF(tp.District,''), tu.District,''))) = '".$distEsc."'";
        }
    }

    if (!empty($_REQUEST['Status']) && $_REQUEST['Status'] === 'Pending') {
        $sql .= " AND tp.ClainStatus <> 'Close'";
    }
    if (!empty($_REQUEST['ClainStatus'])) {
        $sql .= " AND tp.ClainStatus = '".$conn->real_escape_string($_REQUEST['ClainStatus'])."'";
    }
    if (!empty($_REQUEST['val']) && $_REQUEST['val'] === 'today') {
        $sql .= " AND tp.CreatedDate = '$today'";
    }
} else {
    $sql .= " LEFT JOIN tbl_issues tc ON tc.id = tp.Issue
        LEFT JOIN tbl_branch tb ON tp.BranchId = tb.id
        WHERE 1";

    switch ($filter) {
        case 'today':
            $sql .= " AND tp.CreatedDate='$today'";
            break;
        case 'pending':
            $sql .= " AND tp.ClainStatus<>'Close'";
            break;
        case 'closed':
            $sql .= " AND tp.ClainStatus='Close'";
            break;
        case 'insurance':
            $sql .= " AND tp.ServiceType='Insurance'";
            break;
        case 'maintenance':
            $sql .= " AND (tp.ServiceType<>'Insurance' OR tp.ServiceType IS NULL OR tp.ServiceType='')";
            break;
    }
}

$sql .= " ORDER BY tp.CreatedDate DESC, tp.id DESC LIMIT 500";

$rows = array();
$list = getList($sql);
if (is_array($list)) {
    $rows = $list;
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($title); ?></title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="service-management.php"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-list-wrap">
<?php if (empty($rows)) { ?>
    <div class="mob-mgmt-empty">No records found.</div>
<?php } else {
    foreach ($rows as $row) {
        $isClosed = ($row['ClainStatus'] === 'Close');
        $ticketLabel = $row['TicketNo'] ?: ('#' . (int) $row['id']);
        ?>
    <div class="mob-mgmt-card">
        <div class="mob-mgmt-card-title"><?php echo htmlspecialchars($ticketLabel); ?></div>
        <div class="mob-mgmt-card-row"><span>Customer</span><span><?php echo htmlspecialchars($row['CustName'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Contact</span><span><?php echo htmlspecialchars($row['CellNo'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Service Type</span><span><?php echo htmlspecialchars($row['ServiceType'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Issue</span><span><?php echo htmlspecialchars($row['IssueName'] ?? ($row['RelatedIssue'] ?? '-')); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Branch</span><span><?php echo htmlspecialchars($row['BranchName'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Complaint Date</span><span><?php echo mobileMgmtFormatDate($row['ComplaintDate'] ?? ''); ?></span></div>
        <span class="mob-mgmt-badge <?php echo $isClosed ? 'closed' : 'pending'; ?>"><?php echo htmlspecialchars($row['ClainStatus'] ?? 'Pending'); ?></span>
    </div>
        <?php
    }
} ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
