<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Item-Transfer-Workflow";
$Page = "Dispatch-To-Store-Transfer";
$row77 = getRecord("SELECT Roll, BranchId, MulBranchId, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$BranchId = $row77['BranchId'] ?? 0;
$MulBranchId = $row77['MulBranchId'] ?? '0';
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_dispatch = ($Roll == 26 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_dispatch) {
    echo "<script>alert('Access denied. Only Dispatch Officer, Admin, or users with Transfer menu (Option 72) can access this page.'); window.location.href='../dashboard.php';</script>";
    exit;
}
$hasDetail2IdTd = false;
$chkTd = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
if ($chkTd && $chkTd->num_rows > 0) {
    $hasDetail2IdTd = true;
}
// Lines still linked in tbl_dispatch_to_store_transfer_details must be hidden from dispatch qty.
// LEFT JOIN + IS NULL is reliable across MySQL versions; DISTINCT avoids duplicate join rows.
$d2JoinOpenTransfer = $hasDetail2IdTd
    ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
    : "";
$d2WhereNotOpenTransfer = $hasDetail2IdTd
    ? "AND td_open.Detail2Id IS NULL"
    : "";
$Created_Date = isset($_REQUEST['TransferDate']) ? $_REQUEST['TransferDate'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Dispatch: Transfer to Store</title>
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
                    <h4 class="font-weight-bold py-3 mb-0">Dispatch Officer - Transfer Items to Store</h4>
                    <p class="text-muted">Transfer items from your stock (assigned by admin) to a store.</p>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="get" action="">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label class="form-label">To Store <span class="text-danger">*</span></label>
                                        <select class="form-control" name="ToBranchId" id="ToBranchId" required onchange="this.form.submit()">
                                            <option value="">Select Store</option>
                                            <?php
                                            $sqlb = "SELECT * FROM tbl_branch WHERE Status='1' ORDER BY Name";
                                            if ($Roll != 1 && $Roll != 7) {
                                                if (!empty($BranchId)) {
                                                    $sqlb = "SELECT * FROM tbl_branch WHERE Status='1' AND id!='" . (int)$BranchId . "' ORDER BY Name";
                                                }
                                            }
                                            $rb = $conn->query($sqlb);
                                            while ($b = $rb->fetch_assoc()) {
                                                $sel = (isset($_REQUEST['ToBranchId']) && $_REQUEST['ToBranchId'] == $b['id']) ? ' selected' : '';
                                                echo '<option value="' . $b['id'] . '"' . $sel . '>' . htmlspecialchars($b['Name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label class="form-label">Transfer Date <span class="text-danger">*</span></label>
                                        <input type="date" name="TransferDate" class="form-control" value="<?php echo $Created_Date; ?>">
                                    </div>
                                </div>
                            </form>

                            <?php
                            $ToBranchId = isset($_REQUEST['ToBranchId']) ? (int)$_REQUEST['ToBranchId'] : 0;
                            if ($ToBranchId <= 0) {
                                echo '<p class="text-info mb-0"><strong>Select a destination store</strong> above. Your available dispatch stock (including quantities returned after a revert) is listed only after you choose a store.</p>';
                            }
                            if ($ToBranchId > 0) {
                                ?>
                                <form method="post" action="save-dispatch-to-store-transfer.php">
                                    <input type="hidden" name="ToBranchId" value="<?php echo $ToBranchId; ?>">
                                    <input type="hidden" name="TransferDate" value="<?php echo $Created_Date; ?>">

                                    <div class="form-row mt-3">
                                        <label class="form-label font-weight-bold" style="font-size: 16px; color: #0dc30d;">Qty-based Products</label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="text" id="searchQty" class="form-control" placeholder="Search by product name... (filter only; your entered qty is kept)">
                                        <small class="text-muted">Search, select qty, then search again for other products. All entered qtys are submitted together.</small>
                                    </div>
                                    <table class="table table-bordered table-sm" id="tblQty">
                                        <thead><tr><th>Product</th><th>Available Qty</th><th>Qty to Transfer</th><th>Unit</th></tr></thead>
                                        <tbody>
                                        <?php
                                        $sq = "SELECT d2.ProductId, d2.ProductName, d2.Purity, SUM(d2.Qty) AS AvailQty FROM tbl_distibute_item_details2 d2 $d2JoinOpenTransfer WHERE d2.StoreExeId='$user_id' AND d2.ProdType=0 $d2WhereNotOpenTransfer GROUP BY d2.ProductId, d2.ProductName, d2.Purity HAVING AvailQty > 0";
                                        $rq = $conn->query($sq);
                                        $has_qty = false;
                                        while ($qr = $rq->fetch_assoc()) {
                                            $has_qty = true;
                                            $avail = (int)$qr['AvailQty'];
                                            $pid = $qr['ProductId'];
                                            ?>
                                            <tr class="qty-data-row">
                                                <td><?php echo htmlspecialchars($qr['ProductName']); ?></td>
                                                <td><input type="number" class="form-control" value="<?php echo $avail; ?>" readonly size="5"></td>
                                                <td><input type="number" name="QtyProduct[<?php echo $pid; ?>]" class="form-control" min="0" max="<?php echo $avail; ?>" value="0"></td>
                                                <td><?php echo htmlspecialchars($qr['Purity']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        if (!$has_qty) echo '<tr class="qty-msg-row" data-msg="1"><td colspan="4" class="text-muted">No qty-based products available.</td></tr>';
                                        ?>
                                        </tbody>
                                    </table>
                                    <div id="paginationQty" class="mt-2 mb-3"></div>

                                    <div class="form-row mt-3">
                                        <label class="form-label font-weight-bold" style="font-size: 16px; color: #0dc30d;">Serial No Products</label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <input type="text" id="searchSerial" class="form-control" placeholder="Search by product name or serial no... (filter only; checked items stay checked)">
                                        <small class="text-muted">Search, check items, search another product and check more. Submit transfers all checked items.</small>
                                    </div>
                                    <table class="table table-bordered table-sm" id="tblSerial">
                                        <thead><tr><th><input type="checkbox" id="chkAllSerial"></th><th>Product</th><th>Serial No</th></tr></thead>
                                        <tbody>
                                        <?php
                                        $ss = "SELECT d2.id, d2.ProductName, d2.SerialNo FROM tbl_distibute_item_details2 d2 $d2JoinOpenTransfer WHERE d2.StoreExeId='$user_id' AND d2.ProdType IN (1,2) AND d2.SerialNo!='' $d2WhereNotOpenTransfer ORDER BY d2.ProductName, d2.SerialNo";
                                        $rs = $conn->query($ss);
                                        $has_serial = false;
                                        if ($rs) while ($sr = $rs->fetch_assoc()) {
                                            $has_serial = true;
                                            ?>
                                            <tr class="serial-data-row">
                                                <td><input type="checkbox" name="SerialIds[]" value="<?php echo $sr['id']; ?>" class="serial-chk"></td>
                                                <td><?php echo htmlspecialchars($sr['ProductName']); ?></td>
                                                <td><?php echo htmlspecialchars($sr['SerialNo']); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        if (!$has_serial) echo '<tr class="serial-msg-row" data-msg="1"><td colspan="3" class="text-muted">No serial products available.</td></tr>';
                                        ?>
                                        </tbody>
                                    </table>
                                    <div id="paginationSerial" class="mt-2 mb-3"></div>

                                    <div class="form-group mt-3">
                                        <label class="form-label">Narration</label>
                                        <input type="text" name="Narration" class="form-control" placeholder="Optional notes">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="submit" class="btn btn-primary">Save Transfer</button>
                                        <a href="view-dispatch-to-store-transfers.php" class="btn btn-secondary">View My Transfers</a>
                                    </div>
                                </form>
                                <?php
                            } else {
                                echo '<p class="text-muted">Select a store above to see your available stock and transfer items.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php include_once '../footer.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
(function() {
    var PER_PAGE = 10;
    var currentPageQty = 1, currentPageSerial = 1;

    var chkAll = document.getElementById('chkAllSerial');
    if (chkAll) chkAll.addEventListener('change', function() {
        document.querySelectorAll('.serial-chk').forEach(function(c) { c.checked = chkAll.checked; });
    });

    function getRowTextQty(row) {
        var cells = row.getElementsByTagName('td');
        return cells.length ? (cells[0].textContent || '').toLowerCase() : '';
    }
    function getRowTextSerial(row) {
        var cells = row.getElementsByTagName('td');
        if (cells.length < 3) return '';
        return ((cells[1].textContent || '') + ' ' + (cells[2].textContent || '')).toLowerCase();
    }

    function applyFilter(tableId, getRowText, searchVal) {
        var table = document.getElementById(tableId);
        if (!table) return;
        var tbody = table.querySelector('tbody');
        if (!tbody) return;
        var rows = tbody.querySelectorAll('tr');
        rows.forEach(function(row) {
            if (row.querySelector('td[colspan]')) { row.style.display = searchVal ? 'none' : ''; return; }
            var rowText = getRowText(row);
            row.style.display = (!searchVal || rowText.indexOf(searchVal) !== -1) ? '' : 'none';
        });
    }

    function getFilteredRows(tableId, rowClass, getRowText, searchVal) {
        var table = document.getElementById(tableId);
        if (!table) return [];
        var rows = table.querySelectorAll('tbody tr.' + rowClass);
        return Array.prototype.filter.call(rows, function(r) {
            var rowText = getRowText(r);
            return !searchVal || rowText.indexOf(searchVal) !== -1;
        });
    }

    function renderPagination(containerId, tableId, rowClass, getRowText, searchVal, currentPage, setCurrentPage) {
        var container = document.getElementById(containerId);
        var filtered = getFilteredRows(tableId, rowClass, getRowText, searchVal);
        var total = filtered.length;
        var totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
        currentPage = Math.min(Math.max(1, currentPage), totalPages);
        setCurrentPage(currentPage);

        var start = (currentPage - 1) * PER_PAGE;
        var end = start + PER_PAGE;
        var table = document.getElementById(tableId);
        var allDataRows = table ? table.querySelectorAll('tbody tr.' + rowClass) : [];
        allDataRows.forEach(function(row) {
            var idx = filtered.indexOf(row);
            if (idx === -1) row.style.display = 'none';
            else row.style.display = (idx >= start && idx < end) ? '' : 'none';
        });
        var msgRow = table ? table.querySelector('tbody tr[data-msg]') : null;
        if (msgRow) msgRow.style.display = total ? 'none' : '';

        if (!container) return;
        if (total === 0) { container.innerHTML = ''; return; }
        var from = start + 1, to = Math.min(end, total);
        var html = '<div class="d-flex align-items-center flex-wrap gap-2"><span class="text-muted">Showing ' + from + '-' + to + ' of ' + total + '</span>';
        html += '<ul class="pagination mb-0 ml-2">';
        html += '<li class="page-item ' + (currentPage <= 1 ? 'disabled' : '') + '"><a class="page-link" href="javascript:void(0)" data-page="' + (currentPage - 1) + '">Prev</a></li>';
        for (var p = 1; p <= totalPages; p++) {
            if (totalPages > 7 && p !== 1 && p !== totalPages && Math.abs(p - currentPage) > 2) {
                if (p === 2 || p === totalPages - 1) html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                continue;
            }
            html += '<li class="page-item ' + (p === currentPage ? 'active' : '') + '"><a class="page-link" href="javascript:void(0)" data-page="' + p + '">' + p + '</a></li>';
        }
        html += '<li class="page-item ' + (currentPage >= totalPages ? 'disabled' : '') + '"><a class="page-link" href="javascript:void(0)" data-page="' + (currentPage + 1) + '">Next</a></li></ul></div>';
        container.innerHTML = html;
        container.querySelectorAll('.page-link[data-page]').forEach(function(link) {
            link.addEventListener('click', function() {
                var p = parseInt(this.getAttribute('data-page'), 10);
                if (p < 1 || p > totalPages) return;
                var termQty = (document.getElementById('searchQty') && document.getElementById('searchQty').value || '').toLowerCase().trim();
                var termSerial = (document.getElementById('searchSerial') && document.getElementById('searchSerial').value || '').toLowerCase().trim();
                if (containerId === 'paginationQty') renderPagination(containerId, tableId, rowClass, getRowTextQty, termQty, p, setCurrentPage);
                else renderPagination(containerId, tableId, rowClass, getRowTextSerial, termSerial, p, setCurrentPage);
            });
        });
    }

    function updateQty() {
        var term = (document.getElementById('searchQty') && document.getElementById('searchQty').value || '').toLowerCase().trim();
        applyFilter('tblQty', getRowTextQty, term);
        renderPagination('paginationQty', 'tblQty', 'qty-data-row', getRowTextQty, term, currentPageQty, function(p) { currentPageQty = p; });
    }
    function updateSerial() {
        var term = (document.getElementById('searchSerial') && document.getElementById('searchSerial').value || '').toLowerCase().trim();
        applyFilter('tblSerial', getRowTextSerial, term);
        renderPagination('paginationSerial', 'tblSerial', 'serial-data-row', getRowTextSerial, term, currentPageSerial, function(p) { currentPageSerial = p; });
    }

    var searchQty = document.getElementById('searchQty');
    if (searchQty) searchQty.addEventListener('input', function() { currentPageQty = 1; updateQty(); });
    var searchSerial = document.getElementById('searchSerial');
    if (searchSerial) searchSerial.addEventListener('input', function() { currentPageSerial = 1; updateSerial(); });

    updateQty();
    updateSerial();
})();
</script>
</body>
</html>
