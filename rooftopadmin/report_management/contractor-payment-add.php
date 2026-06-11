<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-contractor-payment.php';

$user_id = (int) $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Contractor-Payment-Add';

contractorPaymentEnsureTable($conn);

$contractorId = isset($_REQUEST['contractor_id']) ? (int) $_REQUEST['contractor_id'] : 0;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_payment'])) {
    $contractorId = (int) ($_POST['contractor_id'] ?? 0);
    $result = contractorPaymentSave(
        $conn,
        $contractorId,
        $_POST['amount'] ?? 0,
        $_POST['payment_date'] ?? '',
        $_POST['payment_mode'] ?? '',
        $_POST['reference_no'] ?? '',
        $_POST['narration'] ?? '',
        $user_id
    );
    if ($result['ok']) {
        header('Location: contractor-payment-history.php?contractor_id=' . $contractorId . '&saved=1');
        exit;
    }
    $error = $result['message'];
}

$contractors = getList("SELECT id, Fname, Lname FROM tbl_users WHERE Roll='40' AND Status='1' ORDER BY Fname ASC");
$modes = contractorPaymentModes();

$commission = 0.0;
$paid = 0.0;
$advance = 0.0;
$balance = 0.0;
$contractorName = '';

if ($contractorId > 0) {
    $contractor = contractorPaymentGetContractor($conn, $contractorId);
    if ($contractor) {
        $contractorName = trim((string) ($contractor['Fname'] ?? '') . ' ' . (string) ($contractor['Lname'] ?? ''));
        $commission = contractorPaymentCommissionTotal($conn, $contractorId);
        $paid = contractorPaymentPaidTotal($conn, $contractorId);
        $advance = contractorPaymentAdvanceTotal($conn, $contractorId);
        $balance = contractorPaymentBalance($conn, $contractorId);
    } else {
        $contractorId = 0;
    }
}

$paymentDate = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Record Contractor Payment</title>
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
<h4 class="font-weight-bold py-3 mb-0">Record Contractor Payment</h4>
<p class="text-muted mb-3">Pay commission in parts. Example: Total &#8377;10,000, pay &#8377;2,000 today, balance &#8377;8,000.</p>

<?php if ($error !== '') { ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php } ?>

<div class="mb-3">
    <a href="contractor-payment-dashboard.php" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
</div>

<div class="card mb-4">
<div class="card-body">
<form method="post" id="paymentForm">
<input type="hidden" name="save_payment" value="1">

<div class="form-row">
    <div class="form-group col-md-6">
        <label class="form-label">Contractor <span class="text-danger">*</span></label>
        <select class="select2-demo form-control" name="contractor_id" id="contractor_id" required>
            <option value="">Select Contractor</option>
            <?php foreach ($contractors as $c) {
                $cid = (int) $c['id'];
                $cname = trim((string) ($c['Fname'] ?? '') . ' ' . (string) ($c['Lname'] ?? ''));
                ?>
            <option value="<?php echo $cid; ?>" <?php if ($contractorId === $cid) { ?>selected<?php } ?>><?php echo htmlspecialchars($cname); ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label class="form-label">Total Commission</label>
        <input type="text" class="form-control" id="total_commission" readonly value="<?php echo $contractorId > 0 ? contractorPaymentFormatMoney($commission) : ''; ?>">
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Already Paid</label>
        <input type="text" class="form-control" id="total_paid" readonly value="<?php echo $contractorId > 0 ? contractorPaymentFormatMoney($paid) : ''; ?>">
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Advance Given</label>
        <input type="text" class="form-control text-info font-weight-bold" id="total_advance" readonly value="<?php echo $contractorId > 0 ? contractorPaymentFormatMoney($advance) : ''; ?>">
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Balance</label>
        <input type="text" class="form-control font-weight-bold text-danger" id="balance_amount" readonly value="<?php echo $contractorId > 0 ? contractorPaymentFormatMoney($balance) : ''; ?>">
    </div>
</div>

<hr>

<div class="form-row">
    <div class="form-group col-md-3">
        <label class="form-label">Pay Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0.01" class="form-control" name="amount" id="amount" required <?php echo $contractorId <= 0 || $balance <= 0 ? 'disabled' : ''; ?> max="<?php echo $balance > 0 ? $balance : ''; ?>">
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Payment Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" name="payment_date" required value="<?php echo htmlspecialchars($paymentDate); ?>">
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Payment Mode</label>
        <select class="form-control" name="payment_mode">
            <?php foreach ($modes as $mk => $ml) { ?>
            <option value="<?php echo htmlspecialchars($mk); ?>"><?php echo htmlspecialchars($ml); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label class="form-label">Reference / UTR No.</label>
        <input type="text" class="form-control" name="reference_no" maxlength="100" placeholder="Txn / Cheque no.">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <label class="form-label">Remarks</label>
        <textarea class="form-control" name="narration" rows="2" maxlength="500" placeholder="Optional note"></textarea>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-12">
        <div id="afterPayHint" class="alert alert-light border small mb-3" style="<?php echo ($contractorId > 0 && $balance > 0) ? '' : 'display:none;'; ?>">
            After this payment, remaining balance will be shown on the history page.
        </div>
        <button type="submit" class="btn btn-primary" id="saveBtn" <?php echo $contractorId <= 0 || $balance <= 0 ? 'disabled' : ''; ?>>Save Payment</button>
        <?php if ($contractorId > 0) { ?>
        <a href="contractor-payment-history.php?contractor_id=<?php echo $contractorId; ?>" class="btn btn-outline-info ml-1">View History</a>
        <?php } ?>
    </div>
</div>
</form>
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
    $('#contractor_id').on('change', function() {
        var id = $(this).val();
        if (!id) {
            return;
        }
        window.location = 'contractor-payment-add.php?contractor_id=' + encodeURIComponent(id);
    });

    $('#amount').on('input', function() {
        var bal = parseFloat($('#balance_amount').val().replace(/,/g, '')) || 0;
        var pay = parseFloat($(this).val()) || 0;
        if (pay > bal) {
            $(this).val(bal > 0 ? bal.toFixed(2) : '');
        }
    });
});
</script>
</body>
</html>
