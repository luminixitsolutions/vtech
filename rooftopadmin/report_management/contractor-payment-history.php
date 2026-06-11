<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-contractor-payment.php';

$user_id = (int) $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Contractor-Payment-History';

contractorPaymentEnsureTable($conn);

$contractorId = isset($_GET['contractor_id']) ? (int) $_GET['contractor_id'] : 0;
$contractor = contractorPaymentGetContractor($conn, $contractorId);

if (!$contractor) {
    header('Location: contractor-payment-dashboard.php');
    exit;
}

$contractorName = trim((string) ($contractor['Fname'] ?? '') . ' ' . (string) ($contractor['Lname'] ?? ''));
$commission = contractorPaymentCommissionTotal($conn, $contractorId);
$paid = contractorPaymentPaidTotal($conn, $contractorId);
$advance = contractorPaymentAdvanceTotal($conn, $contractorId);
$balance = contractorPaymentBalance($conn, $contractorId);
$payments = contractorPaymentHistoryRows($conn, $contractorId);
$saved = isset($_GET['saved']) && $_GET['saved'] === '1';

$runningPaid = 0.0;
$historyRows = [];
foreach (array_reverse($payments) as $p) {
    $runningPaid += contractorPaymentAmountValue($p['Amount'] ?? 0);
    $historyRows[] = [
        'payment' => $p,
        'running_paid' => $runningPaid,
        'running_balance' => max(0, $commission - $advance - $runningPaid),
    ];
}
$historyRows = array_reverse($historyRows);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Contractor Payment History</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>

<div class="layout-container">
<?php include_once '../top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Payment History — <?php echo htmlspecialchars($contractorName); ?></h4>

<?php if ($saved) { ?>
<div class="alert alert-success">Payment recorded successfully.</div>
<?php } ?>

<div class="mb-3">
    <a href="contractor-payment-dashboard.php" class="btn btn-outline-secondary btn-sm">Dashboard</a>
    <?php if ($balance > 0) { ?>
    <a href="contractor-payment-add.php?contractor_id=<?php echo $contractorId; ?>" class="btn btn-primary btn-sm ml-1">+ Pay Again</a>
    <?php } ?>
    <a href="view-commision-details.php?id=<?php echo $contractorId; ?>" class="btn btn-outline-info btn-sm ml-1">Commission Details</a>
</div>

<div class="row mb-3">
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Total Commission</div>
            <div class="h5 mb-0">&#8377;<?php echo contractorPaymentFormatMoney($commission); ?></div>
        </div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Total Paid</div>
            <div class="h5 mb-0 text-success">&#8377;<?php echo contractorPaymentFormatMoney($paid); ?></div>
        </div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Advance Given</div>
            <div class="h5 mb-0 text-info">&#8377;<?php echo contractorPaymentFormatMoney($advance); ?></div>
        </div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Balance</div>
            <div class="h5 mb-0 text-danger">&#8377;<?php echo contractorPaymentFormatMoney($balance); ?></div>
        </div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-2">
        <div class="card mb-0"><div class="card-body py-3">
            <div class="text-muted small">Payments Made</div>
            <div class="h5 mb-0"><?php echo count($payments); ?></div>
        </div></div>
    </div>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="paymentHistoryTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Payment Date</th>
            <th>Amount Paid</th>
            <th>Balance After</th>
            <th>Mode</th>
            <th>Reference</th>
            <th>Remarks</th>
            <th>Recorded By</th>
            <th>Recorded On</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($historyRows as $hr) {
            $p = $hr['payment'];
            $payDate = !empty($p['PaymentDate']) ? date('d/m/Y', strtotime($p['PaymentDate'])) : '';
            $createdOn = !empty($p['CreatedDate']) ? date('d/m/Y H:i', strtotime($p['CreatedDate'])) : '';
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($payDate); ?></td>
            <td>&#8377;<?php echo contractorPaymentFormatMoney($p['Amount'] ?? 0); ?></td>
            <td>&#8377;<?php echo contractorPaymentFormatMoney($hr['running_balance']); ?></td>
            <td><?php echo htmlspecialchars((string) ($p['PaymentMode'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($p['ReferenceNo'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($p['Narration'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($p['CreatedByName'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars($createdOn); ?></td>
        </tr>
        <?php } ?>
        <?php if (count($historyRows) === 0) { ?>
        <tr><td colspan="9" class="text-center text-muted">No payments recorded yet.</td></tr>
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
    $('#paymentHistoryTable').DataTable({
        order: [[1, 'desc']],
        paging: false,
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});
</script>
</body>
</html>
