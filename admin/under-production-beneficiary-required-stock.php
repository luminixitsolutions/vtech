<?php
session_start();
include_once 'config.php';
require_once 'exe-database.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Under-Production-Beneficiary';
$Page = 'Under-Production-Required-Stock';

$uid = isset($_GET['uid']) ? (int) $_GET['uid'] : 0;
$cust = null;
$hasDeliveryChallan = 0;
if ($uid > 0) {
    $sqlCust = "SELECT * FROM tbl_users WHERE id='" . $uid . "' AND SurveyMatch=1 AND ProjectType=1 AND UnderProdStatus='1' LIMIT 1";
    $cust = getRecord($sqlCust);
    if ($cust && !empty($cust['id'])) {
        $hasDeliveryChallan = getRow("SELECT id FROM tbl_sell WHERE CustId='" . $uid . "' AND SellType='Challan' AND Status=1 LIMIT 1");
    }
}

/**
 * Net qty from stock ledger for bulk items (ProdType 0) at optional branch.
 */
function upb_stock_net($conn, $productId, $branchId = null)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return 0;
    }
    $where = "Status=1 AND ProductId='" . $productId . "' AND ProdType=0";
    if ($branchId !== null && $branchId !== '' && $branchId !== 'all') {
        $bid = (int) $branchId;
        $where .= " AND BranchId='" . $bid . "'";
    }
    $row = getRecord(
        "SELECT SUM(CASE WHEN CrDr='cr' THEN Qty ELSE 0 END) AS crq,
                SUM(CASE WHEN CrDr='dr' THEN Qty ELSE 0 END) AS drq
         FROM tbl_stocks WHERE " . $where
    );
    $cr = isset($row['crq']) ? (float) $row['crq'] : 0;
    $dr = isset($row['drq']) ? (float) $row['drq'] : 0;
    return (int) max(0, round($cr - $dr));
}

/**
 * Per-store net qty for a product (bulk / ProdType 0) from stock ledger only.
 */
function upb_stock_by_branch($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }
    $sql = "SELECT ts.BranchId,
                   COALESCE(
                       NULLIF(TRIM(MAX(tb.Name)), ''),
                       IF(ts.BranchId = 0, 'Main / central stock (ledger, not assigned to a store)',
                          CONCAT('Branch #', ts.BranchId, ' (no name in master)'))
                   ) AS StoreName,
                   SUM(CASE WHEN ts.CrDr='cr' THEN ts.Qty ELSE 0 END) -
                   SUM(CASE WHEN ts.CrDr='dr' THEN ts.Qty ELSE 0 END) AS AvailQty
            FROM tbl_stocks ts
            LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
            WHERE ts.Status=1 AND ts.ProductId='" . $productId . "' AND ts.ProdType=0
            GROUP BY ts.BranchId
            HAVING AvailQty > 0
            ORDER BY AvailQty DESC";
    $list = getList($sql);
    return is_array($list) ? $list : [];
}

/**
 * Where bulk qty sits: same idea as item_transfer_workflow/stock-location-report.php —
 * store net (tbl_distibute_item_details minus tbl_distibute_item_details2 at branch),
 * then dispatch officer lines on details2, then ledger (tbl_stocks) if nothing in distribute.
 *
 * @return list of [ 'StoreName' => string, 'AvailQty' => float|int ]
 */
function upb_available_locations($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }

    $hasDispatchTransferTbl = false;
    $hasDetail2IdCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasDispatchTransferTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasDetail2IdCol = true;
        }
    }
    $d2JoinOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol)
        ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
        : '';
    $d2WhereOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol) ? 'AND td_open.Detail2Id IS NULL' : '';

    $out = [];

    $sqlStore = "SELECT d.BranchId, MAX(b.Name) AS BranchName,
        (COALESCE(SUM(d.Qty),0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        INNER JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProdType = 0 AND d.ProductId='" . $productId . "'
        GROUP BY d.BranchId
        HAVING AvailQty > 0.0001
        ORDER BY MAX(b.Name)";
    foreach (getList($sqlStore) as $r) {
        $bn = isset($r['BranchName']) ? trim((string) $r['BranchName']) : '';
        if ($bn === '') {
            continue;
        }
        $out[] = [
            'StoreName' => 'Store (balance): ' . $bn,
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'store',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => 0,
        ];
    }

    $sqlDisp = "SELECT d2.StoreExeId, d2.BranchId,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        COALESCE(NULLIF(TRIM(b.Name), ''), 'branch not set') AS assign_branch_name,
        SUM(d2.Qty) AS AvailQty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $d2JoinOpen . "
        WHERE d2.ProdType = 0 AND d2.StoreExeId > 0 AND d2.ProductId='" . $productId . "' " . $d2WhereOpen . "
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING SUM(d2.Qty) > 0.0001
        ORDER BY officer_name, assign_branch_name";
    foreach (getList($sqlDisp) as $r) {
        $on = isset($r['officer_name']) ? trim((string) $r['officer_name']) : '';
        $br = isset($r['assign_branch_name']) ? trim((string) $r['assign_branch_name']) : '';
        if ($br === '') {
            $br = 'branch not set';
        }
        $out[] = [
            'StoreName' => 'Dispatch officer: ' . $on . ' (store: ' . $br . ')',
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'dispatch',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => (int) ($r['StoreExeId'] ?? 0),
        ];
    }

    if (count($out) > 0) {
        return $out;
    }

    $ledger = upb_stock_by_branch($conn, $productId);
    if (!is_array($ledger)) {
        return [];
    }
    $wrapped = [];
    foreach ($ledger as $row) {
        $wrapped[] = array_merge($row, [
            'row_kind' => 'ledger',
            'branch_id' => isset($row['BranchId']) ? (int) $row['BranchId'] : 0,
            'store_exe_id' => 0,
        ]);
    }
    return $wrapped;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo htmlspecialchars($Proj_Title); ?> | Required stock</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <style>
        /* Card contains DT — avoid Bootstrap .row negative margin clipping first columns */
        .upb-stock-card-inner {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid rgba(0,0,0,.06);
            border-radius: .25rem;
            padding: 0 4px;
        }
        .upb-stock-card-inner .dataTables_wrapper > .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .upb-stock-card-inner .dataTables_wrapper > .row > [class*='col-'] {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .upb-stock-main-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: 1px solid rgba(0,0,0,.06);
            border-radius: .25rem;
        }
        .upb-stock-card-inner table#tblRequiredStock,
        .upb-stock-main-scroll table#tblRequiredStock {
            min-width: 1100px;
            margin-bottom: 0;
            table-layout: auto;
        }
        table#tblRequiredStock thead th:first-child,
        table#tblRequiredStock tbody td:first-child {
            min-width: 52px;
            width: 56px;
            max-width: 72px;
            text-align: center;
            white-space: nowrap;
        }
        /* DataTables must only draw sort carets in thead — hide any stray pseudo on body cells */
        table#tblRequiredStock.dataTable > tbody > tr > td.sorting_1,
        table#tblRequiredStock.dataTable > tbody > tr > td.sorting_2,
        table#tblRequiredStock.dataTable > tbody > tr > td.sorting_3 {
            background-image: none !important;
        }
        .upb-stock-main-scroll th,
        .upb-stock-main-scroll td {
            vertical-align: middle;
        }
        .upb-modal-table-scroll {
            overflow-x: auto;
            max-width: 100%;
        }
        .upb-modal-table-scroll table {
            min-width: 480px;
            margin-bottom: 0;
        }
        #modalAvlByStore .modal-body .table td:first-child,
        #modalAvlByStore .modal-body .table th:first-child {
            padding-left: 1rem;
        }
    </style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'sidebar.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<?php if (!$cust || empty($cust['id'])) { ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock</h4>
    <div class="alert alert-warning">This customer was not found, is not marked <strong>Done</strong> under production, or the link is invalid.</div>
    <a href="under-production-beneficiary-stock-report.php" class="btn btn-primary">Back to report</a>
<?php } elseif ($hasDeliveryChallan > 0) { ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock</h4>
    <div class="alert alert-info">A delivery challan is already created for this customer, so they are not shown on the required stock report.</div>
    <a href="under-production-beneficiary-stock-report.php" class="btn btn-primary">Back to report</a>
<?php } else {

    $cid = (int) $cust['id'];
    // Pump / beneficiary BOM is stored on the customer record (Add Pump Customer), not in tbl_quotation.
    $lines = getList(
        "SELECT cps.ProdId AS ProductId,
                MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) AS ProductName,
                SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) AS ReqQty
         FROM tbl_cust_product_specification cps
         LEFT JOIN tbl_products tp ON tp.id = cps.ProdId
         WHERE cps.CustId = '" . $cid . "'
         GROUP BY cps.ProdId
         HAVING SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) > 0
         ORDER BY MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) ASC"
    );
    if (!is_array($lines)) {
        $lines = [];
    }
    if (count($lines) === 0) {
        $lines = getList(
            "SELECT qop.ProductId, qop.ProductName, SUM(COALESCE(qop.Qty,0)) AS ReqQty
             FROM tbl_quotation_order_products qop
             INNER JOIN tbl_quotation q ON q.id = qop.SellId AND q.CustId = '" . $cid . "'
             GROUP BY qop.ProductId, qop.ProductName
             ORDER BY qop.ProductName ASC"
        );
        if (!is_array($lines)) {
            $lines = [];
        }
    }
    ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock — <?php echo htmlspecialchars((string) $cust['Fname']); ?></h4>
    <p class="mb-2">
        <strong>Beneficiary Id:</strong> <?php echo htmlspecialchars((string) $cust['BeneficiaryId']); ?>
        &nbsp;|&nbsp; <strong>Contact:</strong> <?php echo htmlspecialchars((string) $cust['Phone']); ?>
    </p>
    <p class="mb-3"><a href="under-production-beneficiary-stock-report.php" class="btn btn-sm btn-secondary">Back to done list</a></p>

    <?php if (count($lines) === 0) { ?>
        <div class="alert alert-info">No required materials were found for this customer: there are no rows in <code>tbl_cust_product_specification</code> (BOS / structure lines from <strong>Add Pump Customer</strong>) and no lines on a <code>tbl_quotation</code> for this <code>CustId</code>. Save the customer form with product specs, or add a quotation.</div>
    <?php } else { ?>
        <div class="card" style="padding: 10px;">
            <div class="upb-stock-card-inner">
                <table id="tblRequiredStock" class="table table-striped table-bordered table-sm nowrap" style="width:100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th style="min-width:220px">Item</th>
                            <th class="text-right" style="min-width:110px">Required qty</th>
                            <th class="text-right" style="min-width:140px">Total available (all stores)</th>
                            <th style="min-width:120px">Available by store</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 1;
                        foreach ($lines as $ln) {
                            $pid = (int) $ln['ProductId'];
                            $req = (int) round((float) $ln['ReqQty']);
                            $name = (string) $ln['ProductName'];
                            $totalAvail = upb_stock_net($conn, $pid, null);
                            $byBranch = upb_available_locations($conn, $pid);
                            $short = ($pid > 0 && $req > $totalAvail);
                            ?>
                            <tr class="<?php echo $short ? 'table-warning' : ''; ?>">
                                <td><?php echo $n++; ?></td>
                                <td><?php echo htmlspecialchars($name); ?><?php if ($pid <= 0) {
                                    echo ' <span class="text-muted">(no product id)</span>';
                                } ?></td>
                                <td class="text-right"><?php echo $req; ?></td>
                                <td class="text-right"><?php echo $pid > 0 ? $totalAvail : '—'; ?></td>
                                <td>
                                    <?php
                                    if ($pid <= 0) {
                                        echo '<span class="text-muted">Map a product id on the customer BOM / quotation to show store stock.</span>';
                                    } elseif (count($byBranch) === 0) {
                                        echo '<span class="text-muted">No positive balance in any store (ledger).</span>';
                                    } else {
                                        $locPayload = [];
                                        foreach ($byBranch as $b) {
                                            $bid = isset($b['branch_id']) ? (int) $b['branch_id'] : (isset($b['BranchId']) ? (int) $b['BranchId'] : 0);
                                            $locPayload[] = [
                                                'StoreName' => (string) ($b['StoreName'] ?? ''),
                                                'AvailQty' => isset($b['AvailQty']) ? (float) $b['AvailQty'] : 0,
                                                'BranchId' => $bid,
                                                'row_kind' => (string) ($b['row_kind'] ?? 'ledger'),
                                                'branch_id' => $bid,
                                                'store_exe_id' => (int) ($b['store_exe_id'] ?? 0),
                                            ];
                                        }
                                        $locJson = htmlspecialchars(json_encode($locPayload, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                                        $itemTitle = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                                        ?>
                                        <button type="button" class="btn btn-sm btn-primary btn-view-store-avl"
                                            data-toggle="modal" data-target="#modalAvlByStore"
                                            data-product-id="<?php echo (int) $pid; ?>"
                                            data-item-name="<?php echo $itemTitle; ?>"
                                            data-required="<?php echo (int) $req; ?>"
                                            data-total-avail="<?php echo (int) $totalAvail; ?>"
                                            data-locations="<?php echo $locJson; ?>">View</button>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>

<?php } ?>

</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>

<div class="modal fade" id="modalAvlByStore" tabindex="-1" role="dialog" aria-labelledby="modalAvlByStoreLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAvlByStoreLabel">Available by store <span id="modalAvlItemTitle" class="text-muted font-weight-normal"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 px-3 pt-2">
                <div class="upb-modal-table-scroll table-responsive">
                    <table class="table table-striped table-bordered table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Location</th>
                                <th class="text-right" style="width:100px">Qty</th>
                            </tr>
                        </thead>
                        <tbody id="modalAvlByStoreTbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).on('show.bs.modal', '#modalAvlByStore', function (e) {
    var btn = $(e.relatedTarget);
    if (!btn || !btn.length) {
        return;
    }
    var itemName = btn.data('item-name') || '';
    $('#modalAvlItemTitle').text(itemName ? ('— ' + itemName) : '');
    var raw = btn.attr('data-locations') || '[]';
    var rows = [];
    try {
        rows = JSON.parse(raw);
    } catch (err) {
        rows = [];
    }
    var $tbody = $('#modalAvlByStoreTbody').empty();
    if (!rows.length) {
        $tbody.append($('<tr>').append($('<td colspan="2">').addClass('text-muted text-center py-3').text('No locations')));
    } else {
        rows.forEach(function (r) {
            var loc = (r.StoreName != null && String(r.StoreName).trim() !== '')
                ? String(r.StoreName)
                : ('Store #' + (r.branch_id != null ? r.branch_id : (r.BranchId != null ? r.BranchId : '')));
            var q = Math.round(parseFloat(r.AvailQty != null ? r.AvailQty : 0));
            $tbody.append(
                $('<tr>')
                    .append($('<td>').text(loc))
                    .append($('<td>').addClass('text-right').text(q))
            );
        });
    }
});

(function () {
    $(function () {
        var $tbl = $('#tblRequiredStock');
        if (!$tbl.length || typeof $.fn.DataTable === 'undefined') {
            return;
        }
        if ($.fn.DataTable.isDataTable($tbl)) {
            $tbl.DataTable().destroy();
        }
        $tbl.DataTable({
            paging: true,
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            ordering: true,
            order: [[0, 'asc']],
            orderClasses: false,
            stateSave: false,
            info: true,
            searching: true,
            autoWidth: true,
            scrollX: false,
            scrollCollapse: false,
            dom: "<'d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3'lf>rtip",
            columnDefs: [
                { targets: 0, className: 'text-center text-nowrap', width: '56px' },
                { targets: 4, orderable: false, searchable: false }
            ],
            language: {
                emptyTable: 'No materials',
                zeroRecords: 'No matching rows'
            }
        });
    });
})();
</script>
</body>
</html>
