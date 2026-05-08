<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-dispatch-officer-stock.php';

$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = (int) ($row77['Roll'] ?? 0);
$sessionBranchId = (int) ($row77['BranchId'] ?? 0);

$MainPage = "Report";
$Page = "Dispatch-Stock-Report-Tab";

$BranchId = isset($_GET['BranchId']) ? (int) $_GET['BranchId'] : 0;
$StoreExeId = isset($_GET['StoreExeId']) ? (int) $_GET['StoreExeId'] : 0;
$FromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$ToDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';
$doSearch = isset($_GET['Search']) && $_GET['Search'] === '1';

$reportOk = $doSearch && dispatch_officer_stock_allowed($Roll, $sessionBranchId, $BranchId, $StoreExeId);
$data = ['rows' => [], 'totCredit' => 0, 'totDebit' => 0];
if ($reportOk) {
    $data = dispatch_officer_stock_compute_rows($conn, $BranchId, $StoreExeId, $FromDate, $ToDate);
}

$storeName = '';
if ($BranchId > 0) {
    $br = getRecord("SELECT Name FROM tbl_branch WHERE id='" . (int) $BranchId . "' LIMIT 1");
    $storeName = $br['Name'] ?? '';
}
$offName = '';
if ($StoreExeId > 0) {
    $or = getRecord("SELECT Fname FROM tbl_users WHERE id='" . (int) $StoreExeId . "' LIMIT 1");
    $offName = $or['Fname'] ?? '';
}

$backUrl = 'dispatch-officer-stock-report.php';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo htmlspecialchars($Proj_Title); ?> — Dispatch officer stock (tab)</title>
    <meta charset="utf-8">
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
    <h4 class="font-weight-bold py-3 mb-0">Dispatch Officier Stock Report <span class="text-muted small">(opened in new tab)</span></h4>
    <p class="mb-3">
        <a href="<?php echo htmlspecialchars($backUrl); ?>" class="btn btn-sm btn-secondary">Back to filter page</a>
    </p>
    <?php if (!$reportOk) { ?>
        <div class="alert alert-warning">Open this page from the main report using <strong>Open in new tab</strong> with store and dispatch officer selected, or add parameters: <code>BranchId</code>, <code>StoreExeId</code>, <code>Search=1</code>, optional <code>FromDate</code>, <code>ToDate</code>.</div>
    <?php } else { ?>
        <div class="card mb-3 p-3">
            <strong>Store:</strong> <?php echo htmlspecialchars($storeName); ?> &nbsp;|&nbsp;
            <strong>Dispatch officer:</strong> <?php echo htmlspecialchars($offName); ?>
            <?php if ($FromDate !== '' || $ToDate !== '') { ?>
                &nbsp;|&nbsp; <strong>From:</strong> <?php echo htmlspecialchars($FromDate); ?>
                &nbsp; <strong>To:</strong> <?php echo htmlspecialchars($ToDate); ?>
            <?php } ?>
        </div>
        <div class="card-datatable table-responsive">
            <table id="exampleTab" class="table table-striped table-bordered" style="width:100%">
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
                    foreach ($data['rows'] as $r) {
                        $pid = (int) $r['ProductId'];
                        $creditUrl = 'dispatch-officer-stock-qty-detail.php?' . http_build_query([
                            'type' => 'credit',
                            'BranchId' => $BranchId,
                            'StoreExeId' => $StoreExeId,
                            'ProductId' => $pid,
                        ]);
                        $debitUrl = 'dispatch-officer-stock-qty-detail.php?' . http_build_query([
                            'type' => 'debit',
                            'BranchId' => $BranchId,
                            'StoreExeId' => $StoreExeId,
                            'ProductId' => $pid,
                            'FromDate' => $FromDate,
                            'ToDate' => $ToDate,
                        ]);
                        $cq = $r['CreditQty'];
                        $dq = $r['DebitQty'];
                        ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars((string) $r['ProductName']); ?></td>
                        <td><?php if ($cq > 0) { ?><a href="<?php echo htmlspecialchars($creditUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string) $cq); ?></a><?php } else { echo '0'; } ?></td>
                        <td><?php if ($dq > 0) { ?><a href="<?php echo htmlspecialchars($debitUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars((string) $dq); ?></a><?php } else { echo htmlspecialchars((string) $dq); } ?></td>
                        <td><?php echo htmlspecialchars((string) $r['BalanceQty']); ?></td>
                    </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th><?php echo htmlspecialchars((string) $data['totCredit']); ?></th>
                        <th><?php echo htmlspecialchars((string) $data['totDebit']); ?></th>
                        <th><?php echo htmlspecialchars((string) ($data['totCredit'] - $data['totDebit'])); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</div>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once '../footer_script.php'; ?>
<?php if ($reportOk) { ?>
<script>
$(document).ready(function() {
    $('#exampleTab').DataTable({
        scrollX: true,
        pageLength: 1000,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'pdfHtml5']
    });
});
</script>
<?php } ?>
</body>
</html>
