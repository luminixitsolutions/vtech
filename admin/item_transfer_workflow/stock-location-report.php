<?php
/**
 * Stock location snapshot: where qty / serial stock sits (store balance, dispatch officer, reserved for transfer).
 * Reflects current DB state after assign, transfer, revert, etc.
 */
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Item-Transfer-Workflow";
$Page = "Stock-Location-Report";

$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 1 || $Roll == 7 || $Roll == 26 || $Roll == 27
    || in_array('72', $Options) || in_array('165', $Options) || in_array('166', $Options));
if (!$is_allowed) {
    echo "<script>alert('Access denied.'); window.location.href='../dashboard.php';</script>";
    exit;
}

$hasDispatchTransferTbl = false;
$t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
if ($t1 && $t1->num_rows > 0) {
    $hasDispatchTransferTbl = true;
}
$hasDetail2IdCol = false;
if ($hasDispatchTransferTbl) {
    $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
    if ($c && $c->num_rows > 0) {
        $hasDetail2IdCol = true;
    }
}

$filterBranch = isset($_REQUEST['BranchId']) ? (int)$_REQUEST['BranchId'] : 0;
$filterQ = isset($_REQUEST['q']) ? trim((string)$_REQUEST['q']) : '';
$view = isset($_REQUEST['view']) ? trim((string)$_REQUEST['view']) : 'all';
if (!in_array($view, array('all', 'qty_store', 'qty_dispatch', 'serial', 'reserved'), true)) {
    $view = 'all';
}

$escQ = $conn->real_escape_string($filterQ);
$branchSql = $filterBranch > 0 ? " AND d.BranchId='" . (int)$filterBranch . "' " : '';
$branchSqlD2 = $filterBranch > 0 ? " AND d2.BranchId='" . (int)$filterBranch . "' " : '';
$productLike = $filterQ !== '' ? " AND (d.ProductName LIKE '%$escQ%' OR CAST(d.ProductId AS CHAR) LIKE '%$escQ%') " : '';
$productLikeD2 = $filterQ !== '' ? " AND (d2.ProductName LIKE '%$escQ%' OR CAST(d2.ProductId AS CHAR) LIKE '%$escQ%') " : '';

$d2JoinOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol)
    ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
    : "";
$d2WhereOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol) ? "AND td_open.Detail2Id IS NULL" : "";

/* ---- Store: net qty (Cr in tbl_distibute_item_details minus Dr in tbl_distibute_item_details2) ---- */
$rowsStoreQty = array();
if ($view === 'all' || $view === 'qty_store') {
    $sql = "SELECT b.id AS branch_id, b.Name AS branch_name, 'Store (net balance)' AS account_type,
        d.ProductId, MAX(d.ProductName) AS product_name,
        (COALESCE(SUM(d.Qty),0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS avail_qty,
        MAX(COALESCE(tp.Unit, '')) AS unit_label
    FROM tbl_distibute_item_details d
    INNER JOIN tbl_branch b ON b.id = d.BranchId
    LEFT JOIN tbl_products tp ON tp.id = d.ProductId
    WHERE d.ProdType = 0 $branchSql " . ($filterQ !== '' ? $productLike : '') . "
    GROUP BY b.id, b.Name, d.BranchId, d.ProductId
    HAVING avail_qty > 0.0001
    ORDER BY b.Name, MAX(d.ProductName)";
    $rs = $conn->query($sql);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $rowsStoreQty[] = $r;
        }
    }
}

/* ---- Dispatch officer: available qty (not reserved in open dispatch-to-store transfer) ---- */
$rowsDispatchQty = array();
if ($view === 'all' || $view === 'qty_dispatch') {
    $sql = "SELECT d2.StoreExeId, COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        b.Name AS assign_branch_name, 'Dispatch officer (available)' AS account_type,
        d2.ProductId, d2.ProductName, d2.Purity AS unit_label,
        SUM(d2.Qty) AS avail_qty
    FROM tbl_distibute_item_details2 d2
    LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
    LEFT JOIN tbl_branch b ON b.id = d2.BranchId
    $d2JoinOpen
    WHERE d2.ProdType = 0 AND d2.StoreExeId > 0 $d2WhereOpen $branchSqlD2 $productLikeD2
    GROUP BY d2.StoreExeId, d2.ProductId, d2.ProductName, d2.Purity, u.Fname, b.Name
    HAVING SUM(d2.Qty) > 0.0001
    ORDER BY officer_name, d2.ProductName";
    $rs = $conn->query($sql);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $rowsDispatchQty[] = $r;
        }
    }
}

/* ---- Reserved: detail2 lines locked in an open dispatch-to-store transfer ---- */
$rowsReserved = array();
if ($hasDispatchTransferTbl && $hasDetail2IdCol && ($view === 'all' || $view === 'reserved')) {
    $sql = "SELECT t.id AS transfer_id, tb_to.Name AS to_store_name, t.TransferDate,
        COALESCE(u.Fname, '') AS dispatch_officer, d2.ProductName, d2.Purity AS unit_label,
        td.Qty AS line_qty, d2.SerialNo,
        'Reserved (pending transfer to store)' AS account_type
    FROM tbl_dispatch_to_store_transfer_details td
    INNER JOIN tbl_dispatch_to_store_transfer t ON t.id = td.TransferId
    INNER JOIN tbl_distibute_item_details2 d2 ON d2.id = td.Detail2Id
    LEFT JOIN tbl_branch tb_to ON tb_to.id = t.ToBranchId
    LEFT JOIN tbl_users u ON u.id = t.DispatchOfficerId
    WHERE 1=1 " . ($filterBranch > 0 ? " AND t.ToBranchId='" . (int)$filterBranch . "' " : "")
        . ($filterQ !== '' ? " AND (td.ProductName LIKE '%$escQ%' OR d2.ProductName LIKE '%$escQ%') " : "") . "
    ORDER BY t.id DESC, td.id ASC";
    $rs = $conn->query($sql);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $rowsReserved[] = $r;
        }
    }
}

/* ---- Serial-level rows: dispatch (available) ---- */
$rowsSerialDispatch = array();
if ($view === 'all' || $view === 'serial') {
    $sql = "SELECT 'Dispatch (available serial)' AS account_type,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        b.Name AS assign_branch_name, d2.ProductName, d2.SerialNo, d2.ProdType, d2.Qty, d2.Purity AS unit_label
    FROM tbl_distibute_item_details2 d2
    LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
    LEFT JOIN tbl_branch b ON b.id = d2.BranchId
    $d2JoinOpen
    WHERE d2.StoreExeId > 0 AND d2.ProdType IN (1,2) AND d2.SerialNo IS NOT NULL AND TRIM(d2.SerialNo) <> ''
    $d2WhereOpen $branchSqlD2 " . ($filterQ !== '' ? " AND (d2.ProductName LIKE '%$escQ%' OR d2.SerialNo LIKE '%$escQ%') " : "") . "
    ORDER BY officer_name, d2.ProductName, d2.SerialNo";
    $rs = $conn->query($sql);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $rowsSerialDispatch[] = $r;
        }
    }
}

/* ---- Serial-level at store: in tbl_distibute_item_details, not allocated in details2 ---- */
$rowsSerialStore = array();
if ($view === 'all' || $view === 'serial') {
    $sql = "SELECT 'Store (available serial)' AS account_type, br.Name AS branch_name,
        d.ProductName, d.SerialNo, d.ProdType, d.Qty, COALESCE(tp.Unit, '') AS unit_label
    FROM tbl_distibute_item_details d
    INNER JOIN tbl_branch br ON br.id = d.BranchId
    LEFT JOIN tbl_products tp ON tp.id = d.ProductId
    WHERE d.ProdType IN (1,2) AND d.SerialNo IS NOT NULL AND TRIM(d.SerialNo) <> ''
    $branchSql " . ($filterQ !== '' ? " AND (d.ProductName LIKE '%$escQ%' OR d.SerialNo LIKE '%$escQ%') " : "") . "
    AND NOT EXISTS (
        SELECT 1 FROM tbl_distibute_item_details2 x
        WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.SerialNo = d.SerialNo AND x.ProdType = d.ProdType
    )
    ORDER BY br.Name, d.ProductName, d.SerialNo";
    $rs = $conn->query($sql);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $rowsSerialStore[] = $r;
        }
    }
}

$branches = array();
$br = $conn->query("SELECT id, Name FROM tbl_branch WHERE Status='1' ORDER BY Name");
if ($br) {
    while ($b = $br->fetch_assoc()) {
        $branches[] = $b;
    }
}

/* CSV export */
if (!empty($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="stock-location-report-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, array('Section', 'Account / location', 'Branch', 'Product', 'Qty', 'Unit', 'Serial', 'Note'));
    foreach ($rowsStoreQty as $r) {
        fputcsv($out, array('Store qty', $r['account_type'], $r['branch_name'], $r['product_name'], $r['avail_qty'], $r['unit_label'], '', ''));
    }
    foreach ($rowsDispatchQty as $r) {
        fputcsv($out, array('Dispatch qty', $r['account_type'], $r['assign_branch_name'], $r['ProductName'], $r['avail_qty'], $r['unit_label'], '', $r['officer_name']));
    }
    foreach ($rowsReserved as $r) {
        fputcsv($out, array('Reserved', $r['account_type'], $r['to_store_name'], $r['ProductName'], $r['line_qty'], $r['unit_label'], $r['SerialNo'], 'Transfer #' . $r['transfer_id'] . ' ' . $r['dispatch_officer']));
    }
    foreach ($rowsSerialDispatch as $r) {
        fputcsv($out, array('Serial dispatch', $r['account_type'], $r['assign_branch_name'], $r['ProductName'], $r['Qty'], $r['unit_label'], $r['SerialNo'], $r['officer_name']));
    }
    foreach ($rowsSerialStore as $r) {
        fputcsv($out, array('Serial store', $r['account_type'], $r['branch_name'], $r['ProductName'], $r['Qty'], $r['unit_label'], $r['SerialNo'], ''));
    }
    fclose($out);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Stock Location Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once '../header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once '../sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once '../top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0">Stock location report</h4>
                    <p class="text-muted small mb-3">
                        Snapshot of where quantity and serial stock currently sits: <strong>store net balance</strong> (after assign/debit),
                        <strong>dispatch officer available</strong> (after assign, minus lines already in an open transfer to store),
                        <strong>reserved</strong> (locked until transfer completes or revert),
                        and <strong>serial</strong> lines at store vs dispatch.
                    </p>

                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="get" action="item_transfer_workflow/stock-location-report.php" class="form-row align-items-end">
                                <div class="form-group col-md-3 mb-2 mb-md-0">
                                    <label class="form-label">Branch filter</label>
                                    <select name="BranchId" class="form-control">
                                        <option value="0">All branches</option>
                                        <?php foreach ($branches as $b) {
                                            $sel = ($filterBranch === (int)$b['id']) ? ' selected' : '';
                                            echo '<option value="' . (int)$b['id'] . '"' . $sel . '>' . htmlspecialchars($b['Name']) . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-2 mb-md-0">
                                    <label class="form-label">Product search</label>
                                    <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($filterQ); ?>" placeholder="Name or product id">
                                </div>
                                <div class="form-group col-md-3 mb-2 mb-md-0">
                                    <label class="form-label">View</label>
                                    <select name="view" class="form-control">
                                        <option value="all"<?php echo $view === 'all' ? ' selected' : ''; ?>>All sections</option>
                                        <option value="qty_store"<?php echo $view === 'qty_store' ? ' selected' : ''; ?>>Store qty only</option>
                                        <option value="qty_dispatch"<?php echo $view === 'qty_dispatch' ? ' selected' : ''; ?>>Dispatch qty only</option>
                                        <option value="serial"<?php echo $view === 'serial' ? ' selected' : ''; ?>>Serials only</option>
                                        <option value="reserved"<?php echo $view === 'reserved' ? ' selected' : ''; ?>>Reserved (pending transfer) only</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-0">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                    <a href="item_transfer_workflow/stock-location-report.php?<?php echo http_build_query(array('BranchId' => $filterBranch, 'q' => $filterQ, 'view' => $view, 'export' => 'csv')); ?>" class="btn btn-outline-secondary">Export CSV</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if ($view === 'all' || $view === 'qty_store') { ?>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Store — net quantity (credit minus assign/debit at branch)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 w-100" id="tblStoreQty">
                                    <thead><tr><th>Store / branch</th><th>Product</th><th>Available qty</th><th>Unit</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($rowsStoreQty as $r) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['branch_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['product_name']); ?> <span class="text-muted small">(#<?php echo (int)$r['ProductId']; ?>)</span></td>
                                            <td><?php echo htmlspecialchars((string)$r['avail_qty']); ?></td>
                                            <td><?php echo htmlspecialchars($r['unit_label']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($rowsStoreQty)) { ?>
                                        <tr><td colspan="4" class="text-muted">No rows (or zero balance) for this filter.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($view === 'all' || $view === 'qty_dispatch') { ?>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Dispatch officer — available quantity (assign minus lines in open transfer to store)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 w-100" id="tblDispatchQty">
                                    <thead><tr><th>Officer</th><th>Assigned from branch</th><th>Product</th><th>Available qty</th><th>Unit</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($rowsDispatchQty as $r) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['officer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['assign_branch_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['ProductName']); ?> <span class="text-muted small">(#<?php echo (int)$r['ProductId']; ?>)</span></td>
                                            <td><?php echo htmlspecialchars((string)$r['avail_qty']); ?></td>
                                            <td><?php echo htmlspecialchars($r['unit_label']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($rowsDispatchQty)) { ?>
                                        <tr><td colspan="5" class="text-muted">No rows for this filter.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($hasDispatchTransferTbl && $hasDetail2IdCol && ($view === 'all' || $view === 'reserved')) { ?>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Reserved — in open dispatch → store transfer (not yet delivered / not reverted)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 w-100" id="tblReserved">
                                    <thead><tr><th>Transfer #</th><th>To store</th><th>Date</th><th>Dispatch officer</th><th>Product</th><th>Qty</th><th>Serial</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($rowsReserved as $r) { ?>
                                        <tr>
                                            <td><?php echo (int)$r['transfer_id']; ?></td>
                                            <td><?php echo htmlspecialchars($r['to_store_name']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$r['TransferDate']); ?></td>
                                            <td><?php echo htmlspecialchars($r['dispatch_officer']); ?></td>
                                            <td><?php echo htmlspecialchars($r['ProductName']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$r['line_qty']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$r['SerialNo']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($rowsReserved)) { ?>
                                        <tr><td colspan="7" class="text-muted">No reserved lines for this filter.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } elseif ($view === 'reserved') { ?>
                    <div class="alert alert-warning">Reserved section needs table <code>tbl_dispatch_to_store_transfer_details</code> with column <code>Detail2Id</code>.</div>
                    <?php } ?>

                    <?php if ($view === 'all' || $view === 'serial') { ?>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Serial numbers — dispatch (available)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 w-100" id="tblSerDisp">
                                    <thead><tr><th>Officer</th><th>Branch</th><th>Product</th><th>Serial</th><th>Qty</th><th>Unit</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($rowsSerialDispatch as $r) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['officer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['assign_branch_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['ProductName']); ?></td>
                                            <td><?php echo htmlspecialchars($r['SerialNo']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$r['Qty']); ?></td>
                                            <td><?php echo htmlspecialchars($r['unit_label']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($rowsSerialDispatch)) { ?>
                                        <tr><td colspan="6" class="text-muted">No serial rows for this filter.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">Serial numbers — store (physical row, not yet assigned to dispatch)</div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0 w-100" id="tblSerStore">
                                    <thead><tr><th>Store / branch</th><th>Product</th><th>Serial</th><th>Qty</th><th>Unit</th></tr></thead>
                                    <tbody>
                                    <?php foreach ($rowsSerialStore as $r) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($r['branch_name']); ?></td>
                                            <td><?php echo htmlspecialchars($r['ProductName']); ?></td>
                                            <td><?php echo htmlspecialchars($r['SerialNo']); ?></td>
                                            <td><?php echo htmlspecialchars((string)$r['Qty']); ?></td>
                                            <td><?php echo htmlspecialchars($r['unit_label']); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php if (empty($rowsSerialStore)) { ?>
                                        <tr><td colspan="5" class="text-muted">No serial rows for this filter.</td></tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <?php include_once '../footer.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
$(function() {
    var dtOpts = { pageLength: 25, order: [] };
    if ($('#tblStoreQty').length) $('#tblStoreQty').DataTable(dtOpts);
    if ($('#tblDispatchQty').length) $('#tblDispatchQty').DataTable(dtOpts);
    if ($('#tblReserved').length) $('#tblReserved').DataTable(dtOpts);
    if ($('#tblSerDisp').length) $('#tblSerDisp').DataTable(dtOpts);
    if ($('#tblSerStore').length) $('#tblSerStore').DataTable(dtOpts);
});
</script>
</body>
</html>
