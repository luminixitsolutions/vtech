<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-dispatch-officer-stock.php';

$user_id = (int) $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = (int) ($row77['Roll'] ?? 0);
$sessionBranchId = (int) ($row77['BranchId'] ?? 0);

$type = isset($_GET['type']) ? trim((string) $_GET['type']) : '';
$BranchId = isset($_GET['BranchId']) ? (int) $_GET['BranchId'] : 0;
$StoreExeId = isset($_GET['StoreExeId']) ? (int) $_GET['StoreExeId'] : 0;
$ProductId = isset($_GET['ProductId']) ? (int) $_GET['ProductId'] : 0;
$FromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$ToDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';

if (!dispatch_officer_stock_allowed($Roll, $sessionBranchId, $BranchId, $StoreExeId) || $ProductId < 1 || ($type !== 'credit' && $type !== 'debit')) {
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Invalid</title></head><body><p>Invalid parameters.</p></body></html>';
    exit;
}

$b = (int) $BranchId;
$p = (int) $ProductId;
$e = (int) $StoreExeId;
$titleType = $type === 'credit' ? 'Credit (stock to dispatch)' : 'Debit (dispatch to customer)';

$prodRow = getRecord("SELECT ProductName FROM tbl_distibute_item_details2 WHERE ProductId='$p' AND BranchId='$b' LIMIT 1");
$productName = $prodRow['ProductName'] ?? ('Product #' . $p);
$storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$b' LIMIT 1");
$storeName = $storeRow['Name'] ?? '';
$offRow = getRecord("SELECT Fname FROM tbl_users WHERE id='$e' LIMIT 1");
$offName = $offRow['Fname'] ?? '';

$MainPage = "Report";
$Page = "Dispatch-Stock-Report";

$lines = [];
if ($type === 'credit') {
    /* Match main report: all credit lines to this officer (not filtered by report dates). */
    $sql = "SELECT d2.id, d2.Qty, d2.SerialNo, d2.ModelNo, d2.Purity, d2.CreatedDate, d2.VehicalNo, d2.VehicalDate,
        h2.Narration AS BatchNarration
        FROM tbl_distibute_item_details2 d2
        LEFT JOIN tbl_distibute_items2 h2 ON h2.id = d2.DistId
        WHERE d2.BranchId='$b' AND d2.ProductId='$p' AND d2.StoreExeId='$e'
        ORDER BY d2.CreatedDate DESC, d2.id DESC";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $lines[] = $r;
        }
    }
} else {
    $sql = "SELECT id, Qty, SerialNo, ModelNo, CreatedDate, Narration, VehicalNo, VehicalDate, CrDr, SellType, ProductName
        FROM tbl_stocks WHERE BranchId='$b' AND ProductId='$p' AND CreatedBy='$e'";
    if ($FromDate !== '') {
        $fd = mysqli_real_escape_string($conn, $FromDate);
        $sql .= " AND CreatedDate>='$fd'";
    }
    if ($ToDate !== '') {
        $td = mysqli_real_escape_string($conn, $ToDate);
        $sql .= " AND CreatedDate<='$td'";
    }
    $sql .= " ORDER BY CreatedDate DESC, id DESC";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $lines[] = $r;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?php echo htmlspecialchars($Proj_Title); ?> — <?php echo htmlspecialchars($titleType); ?></title>
    <?php include_once '../header_script.php'; ?>
    <style>
    /* Match store-stock-report-2-credit-detail: white body rows, dark header, horizontal rules only */
    .dispatch-qty-detail-card {
        background: #fff;
        border: 1px solid #e2e5e8;
        box-shadow: 0 1px 2px rgba(18, 38, 63, 0.04);
    }
    table.table-dispatch-qty-detail {
        border-collapse: collapse;
        background: #fff;
    }
    table.table-dispatch-qty-detail thead th {
        background: #5c636a !important;
        color: #fff !important;
        font-weight: 600;
        border: none !important;
        border-bottom: 1px solid #45494e !important;
        vertical-align: middle;
        padding: 0.65rem 0.75rem;
    }
    table.table-dispatch-qty-detail thead th.sorting,
    table.table-dispatch-qty-detail thead th.sorting_asc,
    table.table-dispatch-qty-detail thead th.sorting_desc {
        background: #5c636a !important;
        color: #fff !important;
    }
    table.table-dispatch-qty-detail tbody td {
        background: #fff !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: 1px solid #e8eaed !important;
        vertical-align: middle;
        padding: 0.55rem 0.75rem;
    }
    table.table-dispatch-qty-detail tbody tr:hover td {
        background: #fafbfc !important;
    }
    #tblQtyDetail_wrapper table.dataTable tbody tr,
    #tblQtyDetail_wrapper table.dataTable tbody tr.odd,
    #tblQtyDetail_wrapper table.dataTable tbody tr.even {
        background-color: #fff !important;
    }
    #tblQtyDetail_wrapper table.dataTable.no-footer {
        border-bottom: 1px solid #e8eaed;
    }
    /* scrollX duplicates header — keep same look */
    #tblQtyDetail_wrapper .dataTables_scrollHead thead th,
    #tblQtyDetail_wrapper .dataTables_scrollHeadInner table thead th {
        background: #5c636a !important;
        color: #fff !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: 1px solid #45494e !important;
    }
    #tblQtyDetail_wrapper .dataTables_scrollBody table tbody td {
        background: #fff !important;
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-bottom: 1px solid #e8eaed !important;
    }
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

    <h4 class="font-weight-bold py-3 mb-0"><?php echo htmlspecialchars($titleType); ?> <span class="text-muted small">(new tab)</span></h4>
    <p class="mb-3">
        <a href="dispatch-officer-stock-report.php" class="btn btn-sm btn-secondary">Back to Dispatch officer stock report</a>
    </p>
    <div class="card mb-3 p-3">
        <strong>Store:</strong> <?php echo htmlspecialchars($storeName); ?> &nbsp;|&nbsp;
        <strong>Dispatch officer:</strong> <?php echo htmlspecialchars($offName); ?><br>
        <strong>Product:</strong> <?php echo htmlspecialchars($productName); ?>
        <?php if ($type === 'debit' && ($FromDate !== '' || $ToDate !== '')) { ?>
            &nbsp;|&nbsp; <strong>Period (debit):</strong> <?php echo htmlspecialchars($FromDate); ?> — <?php echo htmlspecialchars($ToDate); ?>
        <?php } elseif ($type === 'credit') { ?>
            &nbsp;|&nbsp; <span class="text-muted">All lines to this officer (not filtered by report dates)</span>
        <?php } ?>
    </div>

    <div class="card dispatch-qty-detail-card mb-0" style="padding: 10px;">
    <div class="card-datatable table-responsive">
        <table id="tblQtyDetail" class="table table-dispatch-qty-detail" style="width:100%">
            <thead>
                <?php if ($type === 'credit') { ?>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Serial</th>
                    <th>Model</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Vehicle</th>
                    <th>Narration</th>
                </tr>
                <?php } else { ?>
                <tr>
                    <th>#</th>
                    <th>Qty</th>
                    <th>Serial</th>
                    <th>Model</th>
                    <th>Date</th>
                    <th>Cr/Dr</th>
                    <th>Sell type</th>
                    <th>Vehicle</th>
                    <th>Narration</th>
                </tr>
                <?php } ?>
            </thead>
            <tbody>
                <?php
                $n = 1;
                foreach ($lines as $r) {
                    if ($type === 'credit') {
                        ?>
                <tr>
                    <td><?php echo $n++; ?></td>
                    <td><?php echo htmlspecialchars((string) $r['Qty']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['SerialNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['ModelNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['Purity']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['CreatedDate']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['VehicalNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['BatchNarration']); ?></td>
                </tr>
                        <?php
                    } else {
                        ?>
                <tr>
                    <td><?php echo $n++; ?></td>
                    <td><?php echo htmlspecialchars((string) $r['Qty']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['SerialNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['ModelNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['CreatedDate']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['CrDr']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['SellType']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['VehicalNo']); ?></td>
                    <td><?php echo htmlspecialchars((string) $r['Narration']); ?></td>
                </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>

</div>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
$(document).ready(function() {
    $('#tblQtyDetail').DataTable({
        scrollX: true,
        pageLength: 1000,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        stripeClasses: [],
        buttons: ['excelHtml5', 'pdfHtml5']
    });
});
</script>
</body>
</html>
