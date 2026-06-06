<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

require_once __DIR__ . '/../admin/inc-insurance-site.php';

$status = isset($_GET['status']) ? trim($_GET['status']) : 'pending';
$title = mobileMgmtInsuranceStatusLabel($status);
$where = mobileMgmtInsuranceStatusCondition($status);
$filters = insuranceSiteListFiltersFromRequest();
$sql = insuranceSiteListSelectSql($where, $filters);
$rows = array();
$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
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
    <a href="insurance-management.php"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-list-wrap">
<?php if (empty($rows)) { ?>
    <div class="mob-mgmt-empty">No records found.</div>
<?php } else {
    foreach ($rows as $row) {
        $custName = !empty($row['CustName']) ? $row['CustName'] : '';
        if ($custName === '' && !empty($row['Fname'])) {
            $custName = $row['Fname'];
        }
        $badgeClass = ($status === 'expired') ? 'expired' : (($status === 'pending') ? 'pending' : '');
        ?>
    <div class="mob-mgmt-card">
        <div class="mob-mgmt-card-title"><?php echo htmlspecialchars($custName !== '' ? $custName : 'Customer'); ?></div>
        <div class="mob-mgmt-card-row"><span>Beneficiary ID</span><span><?php echo htmlspecialchars($row['BeneficiaryId'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Contact</span><span><?php echo htmlspecialchars($row['CellNo'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Project</span><span><?php echo mobileMgmtProjectTypeLabel($row['ProjectType'] ?? 0); ?></span></div>
        <div class="mob-mgmt-card-row"><span>District</span><span><?php echo htmlspecialchars($row['District'] ?? '-'); ?></span></div>
        <div class="mob-mgmt-card-row"><span>Village</span><span><?php echo htmlspecialchars($row['Village'] ?? '-'); ?></span></div>
        <?php if (!empty($row['InsuranceAgency'])) { ?>
        <div class="mob-mgmt-card-row"><span>Company</span><span><?php echo htmlspecialchars($row['InsuranceAgency']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['InsuranceNumber'])) { ?>
        <div class="mob-mgmt-card-row"><span>Policy No</span><span><?php echo htmlspecialchars($row['InsuranceNumber']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['InsuranceValidity'])) { ?>
        <div class="mob-mgmt-card-row"><span>Expiry</span><span><?php echo htmlspecialchars($row['InsuranceValidity']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['Inst_Dispatcher_Date'])) { ?>
        <div class="mob-mgmt-card-row"><span>Dispatch Date</span><span><?php echo mobileMgmtFormatDate($row['Inst_Dispatcher_Date']); ?></span></div>
        <?php } ?>
        <span class="mob-mgmt-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($title); ?></span>
    </div>
        <?php
    }
} ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
