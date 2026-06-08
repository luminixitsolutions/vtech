<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';
require_once __DIR__ . '/../admin/report_management/inc-contractor-payment.php';

$PageName = 'Contractor Billing Details';
contractorPaymentEnsureTable($conn);

$contractorId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$projectId = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;
$subheadId = isset($_GET['subhead_id']) ? (int) $_GET['subhead_id'] : 0;
$contractor = contractorPaymentGetContractor($conn, $contractorId);

if (!$contractor) {
    header('Location: mobile-contractor-billing.php');
    exit;
}

$projectName = $projectId > 0 ? contractorBillingGetProjectName($conn, $projectId) : '';
$subheadName = $subheadId > 0 ? contractorBillingGetSubHeadName($conn, $subheadId) : '';
$isScoped = ($projectId > 0 && $subheadId > 0 && $projectName !== '' && $subheadName !== '');

$contractorName = trim((string) ($contractor['Fname'] ?? '') . ' ' . (string) ($contractor['Lname'] ?? ''));
$pivot = contractorCommissionPivotByCustomer(
    $conn,
    $contractorId,
    $isScoped ? $projectId : 0,
    $isScoped ? $subheadId : 0
);
$rows = $pivot['rows'];
$scopeColumns = $pivot['scopes'];
$scopeTotals = $pivot['scopeTotals'];
$grandTotal = $pivot['grandTotal'];

$backUrl = mobileContractorBillingUrl($projectId, $subheadId);
if (!$isScoped) {
    $backUrl = 'mobile-contractor-billing.php';
}

$pageHeading = $contractorName . ' — Scope Wise Billing';
if ($isScoped) {
    $pageHeading = $contractorName . ' — ' . $projectName . ' / ' . $subheadName;
}

$colspan = 7 + count($scopeColumns) + 1;
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($contractorName); ?> Billing</title>
<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" href="img/favicon180.png">
<link rel="icon" href="img/favicon32.png">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($contractorName); ?></h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-billing">Scope Wise Billing</div>

<div class="px-3 mb-2">
    <div class="small fw-semibold text-center"><?php echo htmlspecialchars($pageHeading); ?></div>
</div>

<div class="px-3 mb-3">
    <div class="row g-2">
        <div class="col-6">
            <div class="mob-mgmt-card py-2 px-3 mb-0">
                <div class="small text-muted"><?php echo $isScoped ? 'Sub Project Billing' : 'Total Billing'; ?></div>
                <div class="fw-bold text-primary">&#8377;<?php echo contractorPaymentFormatMoney($grandTotal); ?></div>
            </div>
        </div>
        <div class="col-6">
            <div class="mob-mgmt-card py-2 px-3 mb-0">
                <div class="small text-muted">Customers</div>
                <div class="fw-bold"><?php echo count($rows); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="mob-mgmt-list-wrap">
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-billing mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Beneficiary ID</th>
                    <th>Customer Name</th>
                    <th>Project Name</th>
                    <th>Sub Project</th>
                    <th>Capacity</th>
                    <th>District</th>
                    <?php foreach ($scopeColumns as $scopeName) { ?>
                    <th class="scope-amt"><?php echo htmlspecialchars($scopeName); ?></th>
                    <?php } ?>
                    <th class="scope-amt">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                $lastGroupKey = '';
                if (empty($rows)) {
                    ?>
                <tr>
                    <td colspan="<?php echo $colspan; ?>" class="text-center text-muted py-4">No billing records found for this contractor.</td>
                </tr>
                    <?php
                }
                foreach ($rows as $row) {
                    if (!$isScoped) {
                        $groupKey = (string) ($row['project_name'] ?? '—') . '|' . (string) ($row['sub_project_name'] ?? '—');
                        if ($groupKey !== $lastGroupKey) {
                            $lastGroupKey = $groupKey;
                            ?>
                <tr class="group-row">
                    <td colspan="<?php echo $colspan; ?>">
                        <?php echo htmlspecialchars((string) ($row['project_name'] ?? '—')); ?>
                        &mdash;
                        <?php echo htmlspecialchars((string) ($row['sub_project_name'] ?? '—')); ?>
                    </td>
                </tr>
                            <?php
                        }
                    }
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars((string) $row['beneficiary_id']); ?></td>
                    <td><?php echo htmlspecialchars((string) $row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['project_name'] ?? '—')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['sub_project_name'] ?? '—')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['capacity'] ?? '—')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['district'] ?? '—')); ?></td>
                    <?php foreach ($scopeColumns as $scopeName) {
                        $cellAmt = (float) ($row['scopes'][$scopeName] ?? 0);
                        ?>
                    <td class="scope-amt"><?php echo contractorCommissionScopeCell($cellAmt); ?></td>
                    <?php } ?>
                    <td class="scope-amt"><strong><?php echo contractorPaymentFormatMoney($row['total']); ?></strong></td>
                </tr>
                    <?php
                }
                ?>
            </tbody>
            <?php if (count($rows) > 0) { ?>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-end">Total</th>
                    <?php foreach ($scopeColumns as $scopeName) { ?>
                    <th class="scope-amt"><?php echo contractorCommissionScopeCell($scopeTotals[$scopeName] ?? 0); ?></th>
                    <?php } ?>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($grandTotal); ?></th>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
