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
$Page = "Store-Stock-Report-2-Debit-Detail";

$reqBranch = isset($_GET['BranchId']) ? (int) $_GET['BranchId'] : 0;
$productId = isset($_GET['ProductId']) ? (int) $_GET['ProductId'] : 0;
$fromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$toDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';

$dateOk = preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate);
$allowed = ($Roll == 1 || $Roll == 7) ? ($reqBranch > 0) : ($reqBranch > 0 && $reqBranch === (int) $BranchId);

if (!$allowed || $productId < 1 || !$dateOk) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invalid</title></head><body><p>Invalid parameters.</p></body></html>';
    exit;
}

$fromEsc = mysqli_real_escape_string($conn, $fromDate);
$toEsc = mysqli_real_escape_string($conn, $toDate);
$b = (int) $reqBranch;
$p = (int) $productId;

$sqlStore = "SELECT Name FROM tbl_branch WHERE id='$b' AND Status='1' LIMIT 1";
$storeRow = getRecord($sqlStore);
$storeName = $storeRow['Name'] ?? '';

$sqlProd = "SELECT ProductName FROM tbl_distibute_item_details2 WHERE ProductId='$p' AND BranchId='$b' LIMIT 1";
$prodRow = getRecord($sqlProd);
$productName = $prodRow['ProductName'] ?? '';

$sqlLines = "SELECT d2.id, d2.DistId, d2.ProductName, d2.Qty, d2.SerialNo, d2.ModelNo, d2.Purity, d2.CreatedDate, d2.VehicalNo, d2.VehicalDate,
    d2.StoreExeId, d2.ProdType, d2.SellId, d2.SellStatus,
    h2.Narration AS BatchNarration, h2.CreatedDate AS BatchCreatedDate,
    u.Fname AS DispatchName
    FROM tbl_distibute_item_details2 d2
    LEFT JOIN tbl_distibute_items2 h2 ON h2.id = d2.DistId
    LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
    WHERE d2.BranchId='$b' AND d2.ProductId='$p'
    AND d2.CreatedDate >= '$fromEsc' AND d2.CreatedDate <= '$toEsc'
    ORDER BY d2.CreatedDate DESC, d2.id DESC";
$res = $conn->query($sqlLines);
$rows = [];
$sumQty = 0;
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
        $sumQty += (float) $r['Qty'];
    }
}

function store_stock_debit_line_type($prodType)
{
    $t = (int) $prodType;
    if ($t === 1) {
        return 'Serial item';
    }
    if ($t === 2) {
        return 'Bag / structure';
    }
    return 'Quantity issue';
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo htmlspecialchars($Proj_Title); ?> — Debit detail</title>
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
<h4 class="font-weight-bold py-3 mb-0">Store stock — debit lines (outward / use)</h4>
<p class="text-muted mb-3">
    <strong><?php echo htmlspecialchars($storeName); ?></strong>
    &nbsp;|&nbsp; Product: <?php echo htmlspecialchars($productName); ?>
    &nbsp;|&nbsp; <?php echo htmlspecialchars($fromDate); ?> to <?php echo htmlspecialchars($toDate); ?>
    &nbsp;|&nbsp; Total qty: <strong><?php echo htmlspecialchars((string) $sumQty); ?></strong>
</p>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="tblStoreStockDebitLines" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Line date</th>
            <th>Qty</th>
            <th>Serial no</th>
            <th>Model</th>
            <th>Unit / note</th>
            <th>Line type</th>
            <th>Assigned to / use</th>
            <th>Batch ref (DistId)</th>
            <th>Batch narration</th>
            <th>Vehicle</th>
            <th>Lines in batch</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($rows as $row) {
            $distId = (int) $row['DistId'];
            $exeId = (int) $row['StoreExeId'];
            $vdt = !empty($row['VehicalDate']) ? date('d/m/Y', strtotime(str_replace('-', '/', $row['VehicalDate']))) : '';
            $ldt = !empty($row['CreatedDate']) ? date('d/m/Y', strtotime(str_replace('-', '/', $row['CreatedDate']))) : '';
            $batchLink = ($distId > 0)
                ? '../view-assigning-store-items.php?id=' . $distId
                : '';

            $assignParts = [];
            if ($exeId > 0) {
                $dn = trim((string) ($row['DispatchName'] ?? ''));
                $assignParts[] = 'Dispatch officer' . ($dn !== '' ? ': ' . $dn : ' (id ' . $exeId . ')');
            }
            if ($distId > 0) {
                $assignParts[] = 'Store issue batch #' . $distId;
            } else {
                $assignParts[] = 'Transfer / adjustment (no batch header)';
            }
            $sellId = (int) $row['SellId'];
            if ($sellId > 0) {
                $assignParts[] = 'Used on delivery challan (sale id ' . $sellId . ')';
            }
            $assignText = implode(' · ', $assignParts);
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($ldt); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Qty']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['SerialNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['ModelNo']); ?></td>
            <td><?php echo htmlspecialchars((string) $row['Purity']); ?></td>
            <td><?php echo htmlspecialchars(store_stock_debit_line_type($row['ProdType'])); ?></td>
            <td><?php echo htmlspecialchars($assignText); ?></td>
            <td><?php echo $distId > 0 ? (int) $distId : '—'; ?></td>
            <td><?php echo htmlspecialchars((string) $row['BatchNarration']); ?></td>
            <td><?php echo htmlspecialchars(trim((string) $row['VehicalNo'] . ($vdt ? ' / ' . $vdt : ''))); ?></td>
            <td><?php if ($batchLink !== '') { ?>
                <a href="<?php echo htmlspecialchars($batchLink); ?>" target="_blank" rel="noopener">Open batch lines</a>
            <?php } else { echo '—'; } ?></td>
        </tr>
        <?php } ?>
        <?php if (count($rows) === 0) { ?>
        <tr><td colspan="12" class="text-center text-muted">No debit lines in this period.</td></tr>
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
    $('#tblStoreStockDebitLines').DataTable({
        scrollX: true,
        pageLength: 1000,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: <?php echo json_encode('Store_stock_debit_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $storeName) . '_' . (int) $p, JSON_HEX_TAG | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE); ?>
            }
        ]
    });
});
</script>
<?php } ?>
</body>
</html>
