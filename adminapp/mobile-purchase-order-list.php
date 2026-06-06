<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$filters = array();
if (isset($_POST['Search']) && $_POST['Search'] === 'Search') {
    if (!empty($_POST['CompId']) && (int) $_POST['CompId'] > 0) {
        $filters['comp_id'] = (int) $_POST['CompId'];
    }
    if (!empty($_POST['CustId']) && (int) $_POST['CustId'] > 0) {
        $filters['cust_id'] = (int) $_POST['CustId'];
    }
    if (!empty($_POST['FromDate'])) {
        $filters['from_date'] = trim((string) $_POST['FromDate']);
    }
    if (!empty($_POST['ToDate'])) {
        $filters['to_date'] = trim((string) $_POST['ToDate']);
    }
}

$searched = isset($_POST['Search']) && $_POST['Search'] === 'Search';
$rows = mobileMgmtGetPurchaseOrders($filters);
$poGroups = mobileMgmtSplitPurchaseOrdersByToday($rows);
$todayRows = $poGroups['today'];
$otherRows = $poGroups['other'];
$companies = mobileMgmtQueryRows("SELECT id, Fname FROM tbl_users WHERE Roll='10' AND Status='1' ORDER BY Fname ASC");
$manufacturers = mobileMgmtQueryRows("SELECT id, Fname FROM tbl_users WHERE Roll='3' AND Status='1' ORDER BY Fname ASC");

$selectedCompId = isset($_POST['CompId']) ? (int) $_POST['CompId'] : 0;
$selectedCustId = isset($_POST['CustId']) ? (int) $_POST['CustId'] : 0;
$fromDate = isset($_POST['FromDate']) ? trim((string) $_POST['FromDate']) : '';
$toDate = isset($_POST['ToDate']) ? trim((string) $_POST['ToDate']) : '';
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Purchase Orders</title>
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
    <h1>Purchase Orders</h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="post" action="">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Company</label>
                <select class="form-control form-control-sm" name="CompId">
                    <option value="0">All</option>
                    <?php foreach ($companies as $company) { ?>
                    <option value="<?php echo (int) $company['id']; ?>" <?php echo ($selectedCompId === (int) $company['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($company['Fname']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Manufacture</label>
                <select class="form-control form-control-sm" name="CustId">
                    <option value="0">All</option>
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                    <option value="<?php echo (int) $manufacturer['id']; ?>" <?php echo ($selectedCustId === (int) $manufacturer['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($manufacturer['Fname']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">From Date</label>
                <input type="date" name="FromDate" class="form-control form-control-sm" value="<?php echo htmlspecialchars($fromDate, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">To Date</label>
                <input type="date" name="ToDate" class="form-control form-control-sm" value="<?php echo htmlspecialchars($toDate, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <input type="hidden" name="Search" value="Search">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <?php if ($searched) { ?>
                <a href="mobile-purchase-order-list.php" class="btn btn-outline-secondary btn-sm">Clear</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php if (empty($rows)) { ?>
    <div class="mob-mgmt-empty">No purchase orders found.</div>
    <?php } else {
        $renderPoTable = function ($tableRows, $startIndex = 1) {
            ?>
        <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
            <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-po mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>PO No</th>
                        <th>Status</th>
                        <th>Company</th>
                        <th>Manufacture</th>
                        <th>Contact</th>
                        <th>Date</th>
                        <th>Net Payable</th>
                        <th>Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = $startIndex;
                    foreach ($tableRows as $row) {
                        ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['InvoiceNo'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars(mobileMgmtPurchaseOrderStatusLabel($row)); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['CompanyName'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['CustName'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['CellNo'] ?? '')); ?></td>
                        <td><?php echo mobileMgmtFormatDate($row['InvoiceDate'] ?? ''); ?></td>
                        <td><?php echo mobileMgmtFormatAmount($row['Total'] ?? ''); ?></td>
                        <td><?php echo mobileMgmtFormatDate($row['DeliveryDate'] ?? ''); ?></td>
                    </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
            <?php
            return $i;
        };

        if (!empty($todayRows)) {
            ?>
    <div class="mob-mgmt-section-title">Today's Purchase Orders (<?php echo count($todayRows); ?>)</div>
            <?php
            $nextIndex = $renderPoTable($todayRows);
        } else {
            $nextIndex = 1;
        }

        if (!empty($otherRows)) {
            ?>
    <div class="mob-mgmt-section-title">All Purchase Orders (<?php echo count($otherRows); ?>)</div>
            <?php
            $renderPoTable($otherRows, $nextIndex);
        }
    } ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
