<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$BranchId = $row77['BranchId'];

$MainPage = "Report";
$Page = "Stock-Report-Sell-Detail";

$reqBranch = isset($_GET['BranchId']) ? (int) $_GET['BranchId'] : 0;
$productId = isset($_GET['ProductId']) ? (int) $_GET['ProductId'] : 0;
$fromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$toDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';
$dateFilter = ($fromDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate))
    && ($toDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate));

$allowed = ($Roll == 1 || $Roll == 7) ? ($reqBranch > 0) : ($reqBranch > 0 && $reqBranch === (int) $BranchId);

if (!$allowed || $productId < 1) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invalid</title></head><body><p>Invalid parameters.</p></body></html>';
    exit;
}

$b = (int) $reqBranch;
$p = (int) $productId;

$sqlStore = "SELECT Name FROM tbl_branch WHERE id='$b' AND Status='1' LIMIT 1";
$storeRow = getRecord($sqlStore);
$storeName = $storeRow['Name'] ?? '';

$sqlProd = "SELECT ProductName FROM tbl_products WHERE id='$p' LIMIT 1";
$prodRow = getRecord($sqlProd);
$productName = $prodRow['ProductName'] ?? '';

$sqlLines = "SELECT id, Qty, SerialNo, ModelNo, CreatedDate, Narration, VehicalNo, VehicalDate, CrDr, SellType, ProductName, SellId
    FROM tbl_stocks
    WHERE Status=1 AND BranchId='$b' AND ProductId='$p' AND CrDr='dr'";
if ($dateFilter) {
    $fromEsc = mysqli_real_escape_string($conn, $fromDate);
    $toEsc = mysqli_real_escape_string($conn, $toDate);
    $sqlLines .= " AND CreatedDate >= '$fromEsc' AND CreatedDate <= '$toEsc'";
}
$sqlLines .= " ORDER BY CreatedDate DESC, id DESC";
$res = $conn->query($sqlLines);
$rows = [];
$sumQty = 0;
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
        $sumQty += (float) $r['Qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo htmlspecialchars($Proj_Title); ?> — Sell detail</title>
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
<h4 class="font-weight-bold py-3 mb-0">Outstanding stock — sell lines</h4>
<p class="text-muted mb-3">
    <strong><?php echo htmlspecialchars($storeName); ?></strong>
    &nbsp;|&nbsp; Product: <?php echo htmlspecialchars($productName); ?>
    &nbsp;|&nbsp; Customer / challan sales (tbl_stocks, Dr)
    <?php if ($dateFilter) { ?>
    &nbsp;|&nbsp; Period: <?php echo htmlspecialchars($fromDate); ?> — <?php echo htmlspecialchars($toDate); ?>
    <?php } ?>
    &nbsp;|&nbsp; Total qty: <strong><?php echo htmlspecialchars((string) $sumQty); ?></strong>
</p>
<p class="mb-3">
    <a href="stock-report.php" class="btn btn-sm btn-secondary">Back to Outstanding Stock Report</a>
    <a href="stock-report2.php" class="btn btn-sm btn-outline-secondary ml-1">Back to Stock Report</a>
</p>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="tblStockReportSellLines" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Line date</th>
            <th>Qty</th>
            <th>Serial no</th>
            <th>Model</th>
            <th>Sell type</th>
            <th>Vehicle</th>
            <th>Narration</th>
            <th>Sell ref</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($rows as $row) {
            $sellId = (int) $row['SellId'];
            $vdt = !empty($row['VehicalDate']) ? date('d/m/Y', strtotime(str_replace('-', '/', $row['VehicalDate']))) : '';
            $ldt = !empty($row['CreatedDate']) ? date('d/m/Y', strtotime(str_replace('-', '/', $row['CreatedDate']))) : '';
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($ldt); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Qty']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['SerialNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['ModelNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['SellType']); ?></td>
            <td><?php echo htmlspecialchars(trim((string) $row['VehicalNo'] . ($vdt ? ' / ' . $vdt : ''))); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Narration']); ?></td>
            <td><?php echo $sellId > 0 ? (int) $sellId : '—'; ?></td>
        </tr>
        <?php } ?>
        <?php if (count($rows) === 0) { ?>
        <tr><td colspan="9" class="text-center text-muted">No sell lines for this product at this branch.</td></tr>
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
<?php if (count($rows) > 0) { ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#tblStockReportSellLines').DataTable({
        scrollX: true,
        pageLength: 1000,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: <?php echo json_encode('Stock_report_sell_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $storeName) . '_' . (int) $p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE); ?>
            }
        ]
    });
});
</script>
<?php } ?>
</body>
</html>
