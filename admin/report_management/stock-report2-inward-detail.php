<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once __DIR__ . '/../../adminapp/inc-mobile-stock-data.php';

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

$allowed = ($Roll == 1 || $Roll == 7) ? ($reqBranch > 0) : ($reqBranch > 0 && $reqBranch === (int) $BranchId);

if (!$allowed || $productId < 1) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invalid</title></head><body><p>Invalid parameters.</p></body></html>';
    exit;
}

$storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$reqBranch' AND Status='1' LIMIT 1");
$storeName = $storeRow['Name'] ?? '';
$prodRow = getRecord("SELECT ProductName FROM tbl_products WHERE id='$productId' LIMIT 1");
$productName = $prodRow['ProductName'] ?? '';
$detail = mobileStockGetCreditDetailRows($reqBranch, $productId, $fromDate, $toDate);
$rows = $detail['rows'];
$sumQty = (float) $detail['sum_qty'];

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
    <?php if ($fromDate !== '' || $toDate !== '') { ?>
    &nbsp;|&nbsp; Period: <?php echo htmlspecialchars($fromDate !== '' ? $fromDate : '…'); ?> — <?php echo htmlspecialchars($toDate !== '' ? $toDate : '…'); ?>
    <?php } else { ?>
    &nbsp;|&nbsp; All dates
    <?php } ?>
    &nbsp;|&nbsp; Total qty: <strong><?php echo htmlspecialchars(mobileStockFormatQty($sumQty)); ?></strong>
</p>
<p class="mb-3">
    <a href="stock-report2.php" class="btn btn-sm btn-secondary">Back to Stock Report</a>
</p>

<div class="card mb-3" style="padding: 10px;">
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
            <th>Batch</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($rows as $row) {
            $refId = (int) ($row['DistId'] ?? 0);
            $vdt = stock_report2_format_date($row['VehicalDate'] ?? '');
            $ldt = stock_report2_format_date($row['CreatedDate'] ?? '');
            $batchLink = trim((string) ($row['BatchLink'] ?? ''));
            $batchLabel = trim((string) ($row['BatchLabel'] ?? 'Open batch'));
            if ($batchLink === '' && $refId > 0) {
                $batchLink = '../view-assigning-items.php?id=' . $refId;
            }
            ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars((string) ($row['LineType'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars($ldt); ?></td>
            <td><?php echo htmlspecialchars(mobileStockFormatQty($row['Qty'])); ?></td>
            <td><?php echo htmlspecialchars((string) ($row['SerialNo'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($row['ModelNo'] ?? '')); ?></td>
            <td><?php echo htmlspecialchars((string) ($row['Purity'] ?? '')); ?></td>
            <td><?php echo $refId > 0 ? (int) $refId : '—'; ?></td>
            <td><?php echo htmlspecialchars(trim((string) ($row['VehicalNo'] ?? '') . ($vdt ? ' / ' . $vdt : ''))); ?></td>
            <td><?php echo htmlspecialchars((string) ($row['HeaderNarration'] ?? '')); ?></td>
            <td><?php if ($batchLink !== '') { ?>
                <a href="<?php echo htmlspecialchars($batchLink); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars($batchLabel); ?></a>
            <?php } else { echo '—'; } ?></td>
        </tr>
        <?php } ?>
        <?php if (empty($rows)) { ?>
        <tr><td colspan="11" class="text-center text-muted">No inward lines found.</td></tr>
        <?php } ?>
    </tbody>
    <?php if (!empty($rows)) { ?>
    <tfoot>
        <tr class="table-active">
            <th colspan="3" class="text-right">Total</th>
            <th><?php echo htmlspecialchars(mobileStockFormatQty($sumQty)); ?></th>
            <th colspan="7"></th>
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
<?php if (!empty($rows)) { ?>
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
