<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-stock-data.php';

$userRow = mobileStockGetSessionUser();
$Roll = is_array($userRow) ? (int) ($userRow['Roll'] ?? 0) : 0;
$userBranchId = is_array($userRow) ? (int) ($userRow['BranchId'] ?? 0) : 0;
$branches = mobileStockGetBranchList($Roll, $userBranchId);

$searched = isset($_POST['Search']) && $_POST['Search'] === 'Search';
$selectedBranchId = isset($_POST['BranchId']) ? (int) $_POST['BranchId'] : 0;
$selectedOfficerId = isset($_POST['StoreExeId']) ? (int) $_POST['StoreExeId'] : 0;

if (!$searched && $selectedBranchId < 1 && count($branches) === 1) {
    $selectedBranchId = (int) $branches[0]['id'];
}

$branchForOfficers = $selectedBranchId;
if ($branchForOfficers < 1 && (int) $Roll !== 1 && (int) $Roll !== 7) {
    $branchForOfficers = $userBranchId;
}
$officers = mobileStockGetDispatchOfficersForBranch($Roll, $userBranchId, $branchForOfficers);

$report = null;
$reportError = '';

if ($searched) {
    if (!mobileStockDispatchOfficerAllowed($Roll, $userBranchId, $selectedBranchId, $selectedOfficerId)) {
        $reportError = 'Invalid store or dispatch officer selected.';
    } elseif ($selectedBranchId < 1) {
        $reportError = 'Please select a store.';
    } elseif ($selectedOfficerId < 1) {
        $reportError = 'Please select a dispatch officer.';
    } else {
        set_time_limit(120);
        $report = mobileStockGetDispatchOfficerReportData($selectedBranchId, $selectedOfficerId);
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Dispatch Officer Stock Report</title>
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
    <a href="stock-management.php"><span class="material-icons">arrow_back</span></a>
    <h1>Dispatch Officer Stock</h1>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Store <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm" name="BranchId" id="BranchId" required>
                    <?php if ((int) $Roll === 1 || (int) $Roll === 7) { ?>
                    <option value="" disabled <?php echo $selectedBranchId < 1 ? 'selected' : ''; ?>>Select</option>
                    <?php }
                    foreach ($branches as $branch) {
                        $bid = (int) $branch['id'];
                        ?>
                    <option value="<?php echo $bid; ?>" <?php echo ($selectedBranchId === $bid) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($branch['Name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Dispatch Officer <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm" name="StoreExeId" id="StoreExeId" required>
                    <?php if (empty($officers)) { ?>
                    <option value="" selected disabled><?php echo ($selectedBranchId < 1 && ((int) $Roll === 1 || (int) $Roll === 7)) ? 'Select store first' : 'No dispatch officer for this store'; ?></option>
                    <?php } else {
                        if ($selectedOfficerId < 1) { ?>
                    <option value="" disabled selected>Select</option>
                        <?php }
                        foreach ($officers as $officer) {
                            $oid = (int) $officer['id'];
                            ?>
                    <option value="<?php echo $oid; ?>" <?php echo ($selectedOfficerId === $oid) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($officer['Fname']); ?>
                    </option>
                    <?php }
                    } ?>
                </select>
            </div>
            <input type="hidden" name="Search" value="Search">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <?php if ($searched) { ?>
                <a href="mobile-dispatch-officer-stock.php" class="btn btn-outline-secondary btn-sm">Clear</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php if ($reportError !== '') { ?>
    <div class="mob-mgmt-empty"><?php echo htmlspecialchars($reportError); ?></div>
    <?php } elseif ($report !== null) { ?>
    <div class="mob-mgmt-section-title dispatch">Dispatch Officer Stock — <?php echo htmlspecialchars($report['officer_name']); ?></div>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-stock mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Credit Qty</th>
                    <th>Debit Qty</th>
                    <th>Balance Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($report['rows'] as $row) {
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['ProductName'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars(mobileStockFormatQty($row['credit_qty'])); ?></td>
                    <td><?php echo htmlspecialchars(mobileStockFormatQty($row['debit_qty'])); ?></td>
                    <td><?php echo htmlspecialchars(mobileStockFormatQty($row['balance_qty'])); ?></td>
                </tr>
                    <?php
                }
                if (empty($report['rows'])) {
                    ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">No stock records found for selected filters.</td>
                </tr>
                    <?php
                }
                ?>
                <tr class="mob-mgmt-table-total">
                    <td><?php echo $i; ?></td>
                    <th>Total</th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_credit'])); ?></th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_debit'])); ?></th>
                    <th><?php echo htmlspecialchars(mobileStockFormatQty($report['tot_balance'])); ?></th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
<script>
(function () {
    var selectedOfficerId = <?php echo (int) $selectedOfficerId; ?>;

    function refreshDispatchOfficers(branchId) {
        var $officer = $('#StoreExeId');
        if (!branchId) {
            $officer.html('<option value="" selected disabled>Select store first</option>');
            return;
        }
        $officer.html('<option value="" selected disabled>Loading...</option>');
        $.ajax({
            url: 'ajax-mobile-dispatch-officers.php',
            method: 'POST',
            data: { BranchId: branchId },
            success: function (html) {
                $officer.html(html);
                if (selectedOfficerId > 0) {
                    $officer.val(String(selectedOfficerId));
                }
            },
            error: function () {
                $officer.html('<option value="" selected disabled>Unable to load officers</option>');
            }
        });
    }

    $('#BranchId').on('change', function () {
        selectedOfficerId = 0;
        refreshDispatchOfficers(this.value);
    });

    var initialBranchId = $('#BranchId').val();
    if (initialBranchId && $('#StoreExeId option').length <= 1) {
        refreshDispatchOfficers(initialBranchId);
    }
})();
</script>
</body>
</html>
