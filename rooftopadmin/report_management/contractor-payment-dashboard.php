<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-contractor-payment.php';

$user_id = (int) $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Contractor-Payment-Dashboard';

contractorPaymentEnsureTable($conn);
$summary = contractorPaymentSummaryAll($conn);
$rows = $summary['rows'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Contractor Payment Dashboard</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
<style>
.cp-stat-card { border-left: 4px solid #677788; }
.cp-stat-card.primary { border-left-color: #007bff; }
.cp-stat-card.success { border-left-color: #28a745; }
.cp-stat-card.warning { border-left-color: #ffc107; }
.cp-stat-card.danger { border-left-color: #dc3545; }
.cp-stat-value { font-size: 1.35rem; font-weight: 700; }
</style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>

<div class="layout-container">
<?php include_once '../top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Contractor Payment Dashboard</h4>
<p class="text-muted mb-3">Track commission earned, part payments made, and outstanding balance for each contractor.</p>

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card cp-stat-card primary mb-0">
            <div class="card-body py-3">
                <div class="text-muted small">Total Commission</div>
                <div class="cp-stat-value">&#8377;<?php echo contractorPaymentFormatMoney($summary['totCommission']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card cp-stat-card success mb-0">
            <div class="card-body py-3">
                <div class="text-muted small">Total Paid</div>
                <div class="cp-stat-value">&#8377;<?php echo contractorPaymentFormatMoney($summary['totPaid']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card cp-stat-card mb-0" style="border-left-color:#17a2b8;">
            <div class="card-body py-3">
                <div class="text-muted small">Total Advance</div>
                <div class="cp-stat-value">&#8377;<?php echo contractorPaymentFormatMoney($summary['totAdvance']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card cp-stat-card danger mb-0">
            <div class="card-body py-3">
                <div class="text-muted small">Outstanding Balance</div>
                <div class="cp-stat-value">&#8377;<?php echo contractorPaymentFormatMoney($summary['totBalance']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card cp-stat-card warning mb-0">
            <div class="card-body py-3">
                <div class="text-muted small">Pending Contractors</div>
                <div class="cp-stat-value"><?php echo (int) $summary['pendingCount']; ?> / <?php echo (int) $summary['contractorCount']; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="contractor-payment-add.php" class="btn btn-primary">+ Record Payment</a>
    <a href="contractor-advance-payment-add.php" class="btn btn-info ml-1">+ Record Advance</a>
    <a href="contractor-commision-report.php" class="btn btn-outline-secondary ml-1">Billing Report</a>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="contractorPaymentDashboard" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Contractor Name</th>
            <th>Phone</th>
            <th>Total Commission</th>
            <th>Total Paid</th>
            <th>Advance</th>
            <th>Balance</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($rows as $row) {
            $balClass = $row['balance'] > 0 ? 'text-danger font-weight-bold' : 'text-success';
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td>&#8377;<?php echo contractorPaymentFormatMoney($row['commission']); ?></td>
            <td>&#8377;<?php echo contractorPaymentFormatMoney($row['paid']); ?></td>
            <td class="text-info">&#8377;<?php echo contractorPaymentFormatMoney($row['advance']); ?></td>
            <td class="<?php echo $balClass; ?>">&#8377;<?php echo contractorPaymentFormatMoney($row['balance']); ?></td>
            <td>
                <?php if ($row['balance'] > 0) { ?>
                <a class="btn btn-sm btn-primary" href="contractor-payment-add.php?contractor_id=<?php echo (int) $row['id']; ?>">Pay</a>
                <?php } ?>
                <a class="btn btn-sm btn-info" href="contractor-advance-payment-add.php?contractor_id=<?php echo (int) $row['id']; ?>">Advance</a>
                <a class="btn btn-sm btn-outline-info" href="contractor-payment-history.php?contractor_id=<?php echo (int) $row['id']; ?>">History</a>
                <a class="btn btn-sm btn-outline-secondary" href="view-commision-details.php?id=<?php echo (int) $row['id']; ?>">Commission</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
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
    $('#contractorPaymentDashboard').DataTable({
        order: [[6, 'desc']],
        pageLength: 50,
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});
</script>
</body>
</html>
