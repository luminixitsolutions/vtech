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
$Page = "Stock-Report2-Inward-Detail";

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

$dateSqlDist = '';
$dateSqlStock = '';
if ($dateFilter) {
    $fromEsc = mysqli_real_escape_string($conn, $fromDate);
    $toEsc = mysqli_real_escape_string($conn, $toDate);
    $dateSqlDist = " AND d.CreatedDate >= '$fromEsc' AND d.CreatedDate <= '$toEsc'";
    $dateSqlStock = " AND s.CreatedDate >= '$fromEsc' AND s.CreatedDate <= '$toEsc'";
}

$sqlDist = "SELECT d.id, d.DistId, d.ProductName, d.Qty, d.SerialNo, d.ModelNo, d.Purity, d.CreatedDate, d.VehicalNo, d.VehicalDate,
    h.Narration AS HeaderNarration, 'Store allotment' AS LineType
    FROM tbl_distibute_item_details d
    LEFT JOIN tbl_distibute_items h ON h.id = d.DistId
    WHERE d.BranchId='$b' AND d.ProductId='$p' $dateSqlDist
    ORDER BY d.CreatedDate DESC, d.id DESC";

$sqlStock = "SELECT s.id, s.SellId AS DistId, s.ProductName, s.Qty, s.SerialNo, s.ModelNo, '' AS Purity, s.CreatedDate, s.VehicalNo, s.VehicalDate,
    s.Narration AS HeaderNarration, CONCAT('Purchase / ', COALESCE(s.SellType, '')) AS LineType
    FROM tbl_stocks s
    WHERE s.Status=1 AND s.BranchId='$b' AND s.ProductId='$p' AND s.CrDr='cr' $dateSqlStock
    ORDER BY s.CreatedDate DESC, s.id DESC";

$distRows = [];
$stockRows = [];
$sumQty = 0;

$res = $conn->query($sqlDist);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $distRows[] = $r;
        $sumQty += (float) $r['Qty'];
    }
}
$res = $conn->query($sqlStock);
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $stockRows[] = $r;
        $sumQty += (float) $r['Qty'];
    }
}

function stock_report2_format_date($d)
{
    return !empty($d) ? date('d/m/Y', strtotime(str_replace('-', '/', $d))) : '';
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo htmlspecialchars($Proj_Title); ?> — Inward detail</title>
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
<h4 class="font-weight-bold py-3 mb-0">Stock report — inward lines</h4>
<p class="text-muted mb-3">
    <strong><?php echo htmlspecialchars($storeName); ?></strong>
    &nbsp;|&nbsp; Product: <?php echo htmlspecialchars($productName); ?>
    <?php if ($dateFilter) { ?>
    &nbsp;|&nbsp; Period: <?php echo htmlspecialchars($fromDate); ?> — <?php echo htmlspecialchars($toDate); ?>
    <?php } else { ?>
    &nbsp;|&nbsp; All dates
    <?php } ?>
    &nbsp;|&nbsp; Total qty: <strong><?php echo htmlspecialchars((string) $sumQty); ?></strong>
</p>
<p class="mb-3">
    <a href="stock-report2.php" class="btn btn-sm btn-secondary">Back to Stock Report</a>
</p>

<?php
$sections = array(
    array('title' => 'Store allotment (tbl_distibute_item_details)', 'rows' => $distRows, 'batch' => true),
    array('title' => 'Purchase / stock added (tbl_stocks, Cr)', 'rows' => $stockRows, 'batch' => false),
);
foreach ($sections as $sec) {
    ?>
<div class="card mb-3" style="padding: 10px;">
<h5 class="mb-2"><?php echo htmlspecialchars($sec['title']); ?></h5>
<div class="card-datatable table-responsive">
<table class="table table-striped table-bordered tblStockReport2Inward" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Line date</th>
            <th>Qty</th>
            <th>Serial no</th>
            <th>Model</th>
            <th>Unit / note</th>
            <th>Ref</th>
            <th>Vehicle</th>
            <th>Narration</th>
            <?php if ($sec['batch']) { ?><th>Batch</th><?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($sec['rows'] as $row) {
            $refId = (int) $row['DistId'];
            $vdt = stock_report2_format_date($row['VehicalDate']);
            $ldt = stock_report2_format_date($row['CreatedDate']);
            $batchLink = ($sec['batch'] && $refId > 0) ? '../view-assigning-items.php?id=' . $refId : '';
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars((string) $row['LineType']); ?></td>
            <td><?php echo htmlspecialchars($ldt); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Qty']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['SerialNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['ModelNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Purity']); ?></td>
            <td><?php echo $refId > 0 ? (int) $refId : '—'; ?></td>
            <td><?php echo htmlspecialchars(trim((string) $row['VehicalNo'] . ($vdt ? ' / ' . $vdt : ''))); ?></td>
            <td><?php echo htmlspecialchars((string) $row['HeaderNarration']); ?></td>
            <?php if ($sec['batch']) { ?>
            <td><?php if ($batchLink !== '') { ?>
                <a href="<?php echo htmlspecialchars($batchLink); ?>" target="_blank" rel="noopener">Open batch</a>
            <?php } else { echo '—'; } ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
        <?php if (count($sec['rows']) === 0) { ?>
        <tr><td colspan="<?php echo $sec['batch'] ? 11 : 10; ?>" class="text-center text-muted">No lines in this section.</td></tr>
        <?php } ?>
    </tbody>
</table>
</div>
</div>
<?php } ?>

</div>
<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once '../footer_script.php'; ?>
<?php if (count($distRows) + count($stockRows) > 0) { ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.tblStockReport2Inward').DataTable({
        scrollX: true,
        pageLength: 500,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});
</script>
<?php } ?>
</body>
</html>
