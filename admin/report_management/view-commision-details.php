<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-contractor-payment.php';

$user_id = (int) $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Contractor-Commision-Details';

$contractorId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$projectId = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;
$subheadId = isset($_GET['subhead_id']) ? (int) $_GET['subhead_id'] : 0;
$contractor = contractorPaymentGetContractor($conn, $contractorId);
if (!$contractor) {
    header('Location: contractor-commision-report.php');
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
$totalPaid = contractorPaymentPaidTotal($conn, $contractorId);
$balance = contractorPaymentBalance($conn, $contractorId);

$pageHeading = $contractorName . ' — Scope Wise Billing';
if ($isScoped) {
    $pageHeading = $contractorName . ' — ' . $projectName . ' / ' . $subheadName;
}

$backUrl = 'contractor-commision-report.php';
if ($isScoped) {
    $backUrl = 'contractor-commision-report.php?project_id=' . $projectId . '&subhead_id=' . $subheadId;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($contractorName); ?> Billing Details</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
<style>
.view-commission-details.layout-content { justify-content: flex-start; }
.view-commission-details .card-datatable .dataTables_wrapper { min-height: 0; }
.view-commission-details .scope-amt { white-space: nowrap; text-align: right; }
.view-commission-details tfoot th { background: #f8f9fa; font-weight: 700; }
.view-commission-details .group-row td { background: #eef2ff; font-weight: 700; }
</style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>

<div class="layout-container">
<?php include_once '../top_header.php'; ?>

<div class="layout-content view-commission-details">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php echo htmlspecialchars($pageHeading); ?></h4>

<div class="mb-3">
    <a href="<?php echo htmlspecialchars($backUrl); ?>" class="btn btn-outline-secondary btn-sm">Back to Contractor Billing Report</a>
    <?php if (!$isScoped && $balance > 0) { ?>
    <a href="contractor-payment-add.php?contractor_id=<?php echo $contractorId; ?>" class="btn btn-primary btn-sm ml-1">Record Payment</a>
    <?php } elseif ($isScoped && $grandTotal > 0) { ?>
    <a href="contractor-payment-add.php?contractor_id=<?php echo $contractorId; ?>" class="btn btn-primary btn-sm ml-1">Record Payment</a>
    <?php } ?>
    <a href="contractor-payment-history.php?contractor_id=<?php echo $contractorId; ?>" class="btn btn-outline-info btn-sm ml-1">Payment History</a>
</div>

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small"><?php echo $isScoped ? 'Sub Project Commission' : 'Total Commission'; ?></div>
            <div class="h5 mb-0">&#8377;<?php echo contractorPaymentFormatMoney($grandTotal); ?></div>
        </div></div>
    </div>
    <?php if (!$isScoped) { ?>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Paid</div>
            <div class="h5 mb-0 text-success">&#8377;<?php echo contractorPaymentFormatMoney($totalPaid); ?></div>
        </div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Balance</div>
            <div class="h5 mb-0 text-danger">&#8377;<?php echo contractorPaymentFormatMoney($balance); ?></div>
        </div></div>
    </div>
    <?php } ?>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Customers</div>
            <div class="h5 mb-0"><?php echo count($rows); ?></div>
        </div></div>
    </div>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="scopeCommissionTable" class="table table-striped table-bordered" style="width:100%">
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
        $colspan = 7 + count($scopeColumns) + 1;
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
            <td><?php echo htmlspecialchars($row['beneficiary_id']); ?></td>
            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
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
        <?php } ?>
        <?php if (count($rows) === 0) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" class="text-center text-muted">No billing records found for this contractor.</td>
        </tr>
        <?php } ?>
    </tbody>
    <?php if (count($rows) > 0) { ?>
    <tfoot>
        <tr>
            <th colspan="7" class="text-right">Total</th>
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
</div>

<?php include_once '../footer.php'; ?>
</div>
</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once '../footer_script.php'; ?>
<script>
$(function() {
    $('#scopeCommissionTable').DataTable({
        paging: false,
        scrollX: true,
        order: [],
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});
</script>
</body>
</html>
