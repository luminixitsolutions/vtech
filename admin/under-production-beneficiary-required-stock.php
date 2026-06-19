<?php
session_start();
include_once 'config.php';
require_once 'exe-database.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-under-production-beneficiary-stock-data.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Under-Production-Beneficiary';
$Page = 'Under-Production-Required-Stock';

$rawIds = [];
if (!empty($_GET['uids'])) {
    foreach (explode(',', (string) $_GET['uids']) as $part) {
        $id = (int) trim($part);
        if ($id > 0) {
            $rawIds[] = $id;
        }
    }
} elseif (isset($_GET['uid'])) {
    $uid = (int) $_GET['uid'];
    if ($uid > 0) {
        $rawIds[] = $uid;
    }
}

$custIds = upb_validate_stock_report_customer_ids($conn, $rawIds);
$isCombined = count($custIds) > 1;
$cust = null;
$customers = [];
$hasDeliveryChallan = 0;
$lines = [];
$storeColumns = upb_fetch_customer_store_columns($conn, $custIds);

if (count($custIds) === 1) {
    $uid = (int) $custIds[0];
    $sqlCust = "SELECT * FROM tbl_users WHERE id='" . $uid . "' AND SurveyMatch=1 AND ProjectType=1 AND UnderProdStatus='1' LIMIT 1";
    $cust = getRecord($sqlCust);
    if ($cust && !empty($cust['id'])) {
        $hasDeliveryChallan = getRow("SELECT id FROM tbl_sell WHERE CustId='" . $uid . "' AND SellType='Challan' AND Status=1 LIMIT 1");
        if ($hasDeliveryChallan <= 0) {
            $lines = upb_fetch_required_lines_for_customer($conn, $uid);
        }
    }
} elseif ($isCombined) {
    $customers = upb_fetch_stock_report_customers($conn, $custIds);
    $lines = upb_fetch_combined_required_lines($conn, $custIds);
}

// Serial No Products load via AJAX (20 per batch) — only partition bulk lines on initial render.
$serialCatalogCount = upb_count_serial_product_catalog($conn);
$upbSerialCustIds = count($custIds) > 0 ? $custIds : (count($rawIds) === 1 && (int) $rawIds[0] > 0 ? [(int) $rawIds[0]] : []);
$bulkLines = $lines;
if (count($custIds) >= 1) {
    $parts = upb_partition_required_lines($conn, $lines, []);
    $bulkLines = $parts['bulk'];
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
        #upbSerialStockRoot table#tblRequiredSerialStock {
            min-width: 1100px;
            margin-bottom: 0;
            table-layout: auto;
        }
        table#tblRequiredSerialStock thead th:first-child,
        table#tblRequiredSerialStock tbody td:first-child {
            min-width: 52px;
            width: 56px;
            max-width: 72px;
            text-align: center;
            white-space: nowrap;
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
        #modalAvlByStore .upb-serial-detail-table {
            min-width: 640px;
        }
        .upb-customer-list {
            max-height: 120px;
            overflow-y: auto;
            font-size: 0.9rem;
        }
        .upb-store-short {
            background-color: #fff3cd !important;
            font-weight: 600;
        }
        /* DataTables / theme can force pale text on striped rows — keep readable */
        #tblRequiredStock tbody td,
        #tblRequiredStock tbody th,
        #tblRequiredSerialStock tbody td,
        #tblRequiredSerialStock tbody th,
        table#tblRequiredStock.dataTable > tbody > tr > td,
        table#tblRequiredSerialStock.dataTable > tbody > tr > td,
        table#tblRequiredStock.dataTable > tbody > tr > td a,
        table#tblRequiredSerialStock.dataTable > tbody > tr > td a {
            color: #212529 !important;
        }
        table#tblRequiredStock.table-warning > tbody > tr > td,
        table#tblRequiredSerialStock.table-warning > tbody > tr > td,
        #tblRequiredStock tbody tr.table-warning td,
        #tblRequiredSerialStock tbody tr.table-warning td {
            color: #212529 !important;
        }
        #upbSerialStockRoot {
            margin-bottom: 2rem;
        }
        /* Compact DataTables pagination — same tight spacing as bulk table */
        .upb-stock-card-inner .dataTables_wrapper,
        #upbSerialStockRoot .dataTables_wrapper {
            width: 100% !important;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_info,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_info {
            padding-top: 0.85em;
            white-space: nowrap;
            color: #212529;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate {
            margin-top: 0.85em;
            padding-top: 0;
            white-space: nowrap;
            text-align: right;
            width: auto !important;
            float: right;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate span,
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate a.paginate_button,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate span,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate a.paginate_button {
            display: inline-block !important;
            float: none !important;
            min-width: 0 !important;
            width: auto !important;
            padding: 0.25rem 0.55rem !important;
            margin: 0 0 0 2px !important;
            line-height: 1.25;
            box-sizing: border-box;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate ul.pagination,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin: 2px 0;
            justify-content: flex-end;
            flex-wrap: nowrap;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item {
            margin: 0 1px;
        }
        .upb-stock-card-inner .dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item .page-link,
        #upbSerialStockRoot .dataTables_wrapper div.dataTables_paginate ul.pagination li.page-item .page-link {
            padding: 0.25rem 0.55rem;
            min-width: 0;
            line-height: 1.25;
        }
        .upb-stock-card-inner .dataTables_wrapper > .row:last-child,
        #upbSerialStockRoot .dataTables_wrapper > .row:last-child {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            clear: both;
        }
        .upb-stock-card-inner .dataTables_wrapper > .row:last-child > [class*='col-'],
        #upbSerialStockRoot .dataTables_wrapper > .row:last-child > [class*='col-'] {
            flex: 0 0 auto;
            width: auto !important;
            max-width: 100%;
        }
        #upbSerialStockRoot .upb-serial-dt-wrap {
            border: 1px solid rgba(0,0,0,.06);
            border-radius: .25rem;
            padding: 0 4px;
            overflow: visible;
        }
        #upbSerialStockRoot .dataTables_scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        #upbSerialStockRoot .dataTables_processing {
            display: none !important;
        }
        .upb-serial-dt-wrap {
            position: relative;
        }
        .upb-serial-skeleton {
            position: absolute;
            top: 48px;
            left: 0;
            right: 0;
            z-index: 12;
            background: rgba(255, 255, 255, 0.94);
            padding: 10px 12px 14px;
            min-height: 260px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }
        .upb-skeleton-row {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            align-items: center;
        }
        .upb-skeleton-block {
            height: 14px;
            border-radius: 4px;
            background: linear-gradient(90deg, #e8e8e8 25%, #f4f4f4 50%, #e8e8e8 75%);
            background-size: 200% 100%;
            animation: upbSkeletonShimmer 1.1s ease-in-out infinite;
        }
        .upb-skeleton-block.sm { width: 40px; flex-shrink: 0; }
        .upb-skeleton-block.lg { flex: 1; min-width: 140px; }
        .upb-skeleton-block.xs { width: 52px; flex-shrink: 0; }
        .upb-skeleton-block.md { width: 80px; flex-shrink: 0; }
        @keyframes upbSkeletonShimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .upb-serial-full-loader {
            position: fixed;
            inset: 0;
            z-index: 10050;
            background: rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .upb-serial-full-loader-inner {
            text-align: center;
            padding: 1.25rem 2rem;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 6px 28px rgba(0, 0, 0, 0.12);
            color: #212529;
            min-width: 220px;
        }
        #upbSerialStockRoot.upb-serial-loading .dataTables_wrapper {
            opacity: 0.4;
            pointer-events: none;
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

<?php if (count($custIds) === 0) { ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock</h4>
    <div class="alert alert-warning">No valid customer was selected, or the link is invalid.</div>
    <a href="under-production-beneficiary-stock-report.php" class="btn btn-primary">Back to report</a>
<?php } elseif ($isCombined) { ?>
    <?php
    $totalReq = 0;
    foreach ($lines as $ln) {
        $totalReq += (int) round((float) $ln['ReqQty']);
    }
    $reqQtyLabel = 'Combined required qty';
    ?>
    <h4 class="font-weight-bold py-3 mb-0">Combined required stock — <?php echo count($customers); ?> customer(s)</h4>
    <p class="mb-2"><strong>Total required qty (all items):</strong> <?php echo (int) $totalReq; ?></p>
    <div class="upb-customer-list mb-2 text-muted">
        <?php
        $branchNames = [];
        foreach ($storeColumns as $sc) {
            $branchNames[(int) $sc['branch_id']] = (string) $sc['store_name'];
        }
        foreach ($customers as $c) {
            $bid = (int) ($c['BranchId'] ?? 0);
            $storeLabel = $bid > 0 && isset($branchNames[$bid])
                ? $branchNames[$bid]
                : ($bid > 0 ? ('Store #' . $bid) : 'No store assigned');
            ?>
            <div><?php echo htmlspecialchars((string) $c['Fname']); ?> (<?php echo htmlspecialchars((string) $c['BeneficiaryId']); ?>) — <strong><?php echo htmlspecialchars($storeLabel); ?></strong></div>
        <?php } ?>
    </div>
    <p class="mb-3"><a href="under-production-beneficiary-stock-report.php" class="btn btn-sm btn-secondary">Back to done list</a></p>

    <?php if (count($lines) === 0 && $serialCatalogCount === 0) { ?>
        <div class="alert alert-info">No required materials were found for the selected customer(s).</div>
    <?php } else {
        if (count($bulkLines) > 0) {
            $lines = $bulkLines;
            include __DIR__ . '/inc-under-production-beneficiary-required-stock-table.php';
        }
        include __DIR__ . '/inc-under-production-beneficiary-required-stock-serial-table.php';
    } ?>

<?php } elseif (!$cust || empty($cust['id'])) { ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock</h4>
    <div class="alert alert-warning">This customer was not found, is not marked <strong>Done</strong> under production, or the link is invalid.</div>
    <a href="under-production-beneficiary-stock-report.php" class="btn btn-primary">Back to report</a>
<?php } elseif ($hasDeliveryChallan > 0) { ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock</h4>
    <div class="alert alert-info">A delivery challan is already created for this customer, so they are not shown on the required stock report.</div>
    <a href="under-production-beneficiary-stock-report.php" class="btn btn-primary">Back to report</a>
<?php } else {
    $reqQtyLabel = 'Required qty';
    ?>
    <h4 class="font-weight-bold py-3 mb-0">Required stock — <?php echo htmlspecialchars((string) $cust['Fname']); ?></h4>
    <p class="mb-2">
        <strong>Beneficiary Id:</strong> <?php echo htmlspecialchars((string) $cust['BeneficiaryId']); ?>
        &nbsp;|&nbsp; <strong>Contact:</strong> <?php echo htmlspecialchars((string) $cust['Phone']); ?>
        <?php
        $custBranchId = (int) ($cust['BranchId'] ?? 0);
        if ($custBranchId > 0 && count($storeColumns) > 0) {
            echo ' &nbsp;|&nbsp; <strong>Store:</strong> ' . htmlspecialchars((string) $storeColumns[0]['store_name']);
        } elseif ($custBranchId <= 0) {
            echo ' &nbsp;|&nbsp; <strong>Store:</strong> <span class="text-warning">Not assigned</span>';
        }
        ?>
    </p>
    <p class="mb-3"><a href="under-production-beneficiary-stock-report.php" class="btn btn-sm btn-secondary">Back to done list</a></p>

    <?php if (count($lines) === 0 && $serialCatalogCount === 0) { ?>
        <div class="alert alert-info">No required materials were found for this customer: there are no rows in <code>tbl_cust_product_specification</code> (BOS / structure lines from <strong>Add Pump Customer</strong>) and no lines on a <code>tbl_quotation</code> for this <code>CustId</code>. Save the customer form with product specs, or add a quotation.</div>
    <?php } else {
        if (count($bulkLines) > 0) {
            $lines = $bulkLines;
            include __DIR__ . '/inc-under-production-beneficiary-required-stock-table.php';
        }
        include __DIR__ . '/inc-under-production-beneficiary-required-stock-serial-table.php';
    } ?>

<?php } ?>

</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>

<div id="upbSerialFullLoader" class="upb-serial-full-loader d-none" aria-live="polite" aria-busy="false">
    <div class="upb-serial-full-loader-inner">
        <div class="spinner-border text-primary mb-2" role="status" style="width:2.5rem;height:2.5rem;"></div>
        <div class="font-weight-bold">Loading serial products…</div>
        <div class="small text-muted mt-1">Please wait</div>
    </div>
</div>

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
                <p id="modalAvlSerialSummary" class="small text-muted px-2 pt-2 mb-2 d-none"></p>
                <div class="upb-modal-table-scroll table-responsive">
                    <table class="table table-striped table-bordered table-sm mb-0">
                        <thead class="thead-light">
                            <tr id="modalAvlByStoreHeadRow">
                                <th>Location</th>
                                <th class="text-right" style="width:100px">Qty</th>
                            </tr>
                        </thead>
                        <tbody id="modalAvlByStoreTbody"></tbody>
                    </table>
                </div>
                <div id="modalAvlSerialDetailWrap" class="d-none border-top mt-3 pt-2">
                    <h6 class="px-2 mb-2">Serial numbers <span id="modalAvlSerialCount" class="text-muted font-weight-normal"></span></h6>
                    <div class="upb-modal-table-scroll table-responsive px-2 pb-2">
                        <table class="table table-striped table-bordered table-sm mb-0 upb-serial-detail-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Serial no</th>
                                    <th>Location</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody id="modalAvlSerialTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function upbParseLocations(raw) {
    try {
        var rows = JSON.parse(raw || '[]');
        return Array.isArray(rows) ? rows : [];
    } catch (err) {
        return [];
    }
}

function upbRenderLocationRows($tbody, rows, isSerial) {
    $tbody.empty();
    if (!rows.length) {
        var emptyMsg = isSerial ? 'No available serial numbers by location' : 'No locations';
        $tbody.append($('<tr>').append($('<td colspan="2">').addClass('text-muted text-center py-3').text(emptyMsg)));
        return;
    }
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

function upbLoadSerialDetail(productId) {
    var $serialBody = $('#modalAvlSerialTbody').empty();
    var $wrap = $('#modalAvlSerialDetailWrap').removeClass('d-none');
    $('#modalAvlSerialCount').text('(loading…)');
    $serialBody.append(
        $('<tr>').append($('<td colspan="3">').addClass('text-muted text-center py-3').text('Loading serial numbers…'))
    );

    $.post('ajax_files/ajax_required_stock_item_serials.php', {
        product_id: productId,
        scope: 'all'
    }).done(function (resp) {
        $serialBody.empty();
        var rows = (resp && resp.ok && Array.isArray(resp.rows)) ? resp.rows : [];
        var serialRows = rows.filter(function (r) {
            var sn = r.serial_no != null ? String(r.serial_no).trim() : '';
            return sn !== '' && sn.toLowerCase() !== '(bulk — no serial line)';
        });
        $('#modalAvlSerialCount').text('(' + serialRows.length + ' available)');
        if (!serialRows.length) {
            $serialBody.append(
                $('<tr>').append($('<td colspan="3">').addClass('text-muted text-center py-3').text('No serial numbers found in stock'))
            );
            return;
        }
        serialRows.forEach(function (r) {
            $serialBody.append(
                $('<tr>')
                    .append($('<td>').text(r.serial_no || ''))
                    .append($('<td>').text(r.location || ''))
                    .append($('<td>').text(r.source || ''))
            );
        });
    }).fail(function () {
        $serialBody.empty().append(
            $('<tr>').append($('<td colspan="3">').addClass('text-danger text-center py-3').text('Could not load serial numbers'))
        );
        $('#modalAvlSerialCount').text('');
    });
}

$(document).on('show.bs.modal', '#modalAvlByStore', function (e) {
    var btn = $(e.relatedTarget);
    if (!btn || !btn.length) {
        return;
    }
    var itemName = btn.data('item-name') || '';
    var isSerial = String(btn.data('is-serial') || '0') === '1';
    var productId = parseInt(btn.data('product-id'), 10) || 0;
    var totalAvail = parseInt(btn.data('total-avail'), 10) || 0;
    var required = parseInt(btn.data('required'), 10) || 0;

    $('#modalAvlItemTitle').text(itemName ? ('— ' + itemName) : '');
    $('#modalAvlByStoreHeadRow th').eq(1).text(isSerial ? 'Serials' : 'Qty');

    var $summary = $('#modalAvlSerialSummary');
    if (isSerial) {
        $summary.removeClass('d-none').text(
            'Required: ' + required + ' | Available serial numbers (all locations): ' + totalAvail
        );
    } else {
        $summary.addClass('d-none').text('');
    }

    var rows = upbParseLocations(btn.attr('data-locations') || '[]');
    upbRenderLocationRows($('#modalAvlByStoreTbody'), rows, isSerial);

    if (isSerial && productId > 0) {
        upbLoadSerialDetail(productId);
    } else {
        $('#modalAvlSerialDetailWrap').addClass('d-none');
        $('#modalAvlSerialTbody').empty();
        $('#modalAvlSerialCount').text('');
    }
});

$(document).on('hidden.bs.modal', '#modalAvlByStore', function () {
    $('#modalAvlSerialDetailWrap').addClass('d-none');
    $('#modalAvlSerialTbody').empty();
    $('#modalAvlSerialCount').text('');
    $('#modalAvlSerialSummary').addClass('d-none').text('');
});

(function () {
    function upbInitStockTable(selector, lastColNoSort) {
        var $tbl = $(selector);
        if (!$tbl.length || typeof $.fn.DataTable === 'undefined') {
            return;
        }
        if ($.fn.DataTable.isDataTable($tbl)) {
            $tbl.DataTable().destroy();
        }
        var colDefs = [
            { targets: 0, className: 'text-center text-nowrap', width: '56px' }
        ];
        if (lastColNoSort) {
            colDefs.push({ targets: -1, orderable: false, searchable: false });
        }
        $tbl.DataTable({
            paging: true,
            pagingType: 'simple_numbers',
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
            columnDefs: colDefs,
            language: {
                emptyTable: 'No materials',
                zeroRecords: 'No matching rows'
            }
        });
    }

    function upbBindSerialTableLoader($tbl, $root) {
        var pending = 0;

        function upbShowSerialLoader() {
            pending++;
            $root.addClass('upb-serial-loading');
            $('#upbSerialSkeleton').removeClass('d-none').attr('aria-hidden', 'false');
            $('#upbSerialFullLoader').removeClass('d-none').attr('aria-busy', 'true');
        }

        function upbHideSerialLoader() {
            pending = Math.max(0, pending - 1);
            if (pending === 0) {
                $root.removeClass('upb-serial-loading');
                $('#upbSerialSkeleton').addClass('d-none').attr('aria-hidden', 'true');
                $('#upbSerialFullLoader').addClass('d-none').attr('aria-busy', 'false');
            }
        }

        $tbl.off('.upbSerialLoad');
        $tbl.on('preXhr.dt.upbSerialLoad', function () {
            upbShowSerialLoader();
        });
        $tbl.on('draw.dt.upbSerialLoad', function () {
            upbHideSerialLoader();
        });
        $tbl.on('error.dt.upbSerialLoad', function () {
            pending = 0;
            upbHideSerialLoader();
        });
    }

    function upbInitSerialStockTable() {
        var $root = $('#upbSerialStockRoot');
        var $tbl = $('#tblRequiredSerialStock');
        if (!$root.length || !$tbl.length || typeof $.fn.DataTable === 'undefined') {
            return;
        }
        if ($.fn.DataTable.isDataTable($tbl)) {
            $tbl.DataTable().destroy();
        }

        var custIds = [];
        try {
            custIds = JSON.parse($root.attr('data-cust-ids') || '[]');
        } catch (e) {
            custIds = [];
        }
        var storeCount = parseInt($root.attr('data-store-count'), 10) || 0;

        var columns = [
            { data: 'row_num', className: 'text-center text-nowrap', width: '56px' },
            { data: 'product_name', className: 'text-wrap' },
            { data: 'req_qty', className: 'text-right' }
        ];
        var storeColIndex;
        for (storeColIndex = 0; storeColIndex < storeCount; storeColIndex++) {
            (function (idx) {
                columns.push({
                    data: null,
                    className: 'text-right',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        var stores = row.store_serials || [];
                        var s = stores[idx];
                        var pid = parseInt(row.product_id, 10) || 0;
                        var cnt = s ? (parseInt(s.count, 10) || 0) : 0;
                        if (type !== 'display') {
                            return cnt;
                        }
                        var html = pid > 0 ? String(cnt) : '—';
                        if (s && s.short) {
                            return '<span class="upb-store-short d-inline-block px-1">' + html + '</span>';
                        }
                        return html;
                    }
                });
            })(storeColIndex);
        }
        columns.push({
            data: 'total_serials',
            className: 'text-right font-weight-bold',
            render: function (data, type, row) {
                var pid = parseInt(row.product_id, 10) || 0;
                var total = parseInt(data, 10) || 0;
                if (type !== 'display') {
                    return total;
                }
                var txt = pid > 0 ? String(total) : '—';
                if (row.short) {
                    return '<span class="text-danger font-weight-bold">' + txt + '</span>';
                }
                return txt;
            }
        });
        columns.push({
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                if (type !== 'display') {
                    return '';
                }
                var pid = parseInt(row.product_id, 10) || 0;
                var req = parseInt(row.req_qty, 10) || 0;
                var total = parseInt(row.total_serials, 10) || 0;
                if (pid <= 0) {
                    return '<span class="text-muted">—</span>';
                }
                if (total <= 0) {
                    return '<span class="text-muted">0 in stock</span>';
                }
                var locJson = JSON.stringify(row.locations || []).replace(/'/g, '&#39;');
                var name = String(row.product_name || '').replace(/"/g, '&quot;');
                return '<button type="button" class="btn btn-sm btn-success btn-view-store-avl" ' +
                    'data-toggle="modal" data-target="#modalAvlByStore" ' +
                    'data-product-id="' + pid + '" ' +
                    'data-item-name="' + name + '" ' +
                    'data-required="' + req + '" ' +
                    'data-total-avail="' + total + '" ' +
                    'data-is-serial="1" ' +
                    'data-locations=\'' + locJson + '\'>' +
                    'View serials (' + total + ')</button>';
            }
        });

        var lastCol = columns.length - 1;
        $tbl.DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pagingType: 'simple_numbers',
            pageLength: 20,
            lengthMenu: [[20, 50, 100], [20, 50, 100]],
            searching: true,
            ordering: false,
            order: [],
            autoWidth: true,
            scrollX: true,
            scrollCollapse: true,
            stateSave: false,
            dom: "<'d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3'lf>rtip",
            ajax: {
                url: 'ajax_files/ajax_required_stock_serial_products.php',
                type: 'POST',
                data: function (d) {
                    d.cust_ids = Array.isArray(custIds) ? custIds.join(',') : '';
                },
                dataSrc: function (json) {
                    $('#upbSerialFootReq').text(parseInt(json.page_total_req, 10) || 0);
                    $('#upbSerialFootAvail').text(parseInt(json.page_total_avail, 10) || 0);
                    return json.data || [];
                }
            },
            columns: columns,
            columnDefs: [
                { targets: 0, className: 'text-center text-nowrap', width: '56px' },
                { targets: lastCol, orderable: false, searchable: false }
            ],
            rowCallback: function (row, data) {
                if (data && data.short) {
                    $(row).addClass('table-warning');
                } else {
                    $(row).removeClass('table-warning');
                }
            },
            drawCallback: function () {
                var api = this.api();
                if (api.columns) {
                    api.columns.adjust();
                }
            },
            initComplete: function () {
                var api = this.api();
                if (api.columns) {
                    api.columns.adjust();
                }
            },
            language: {
                processing: 'Loading serial products…',
                emptyTable: 'No serial products',
                zeroRecords: 'No matching serial products',
                info: 'Showing _START_ to _END_ of _TOTAL_ serial products',
                infoEmpty: 'Showing 0 serial products',
                infoFiltered: '(filtered from _MAX_ total)',
                paginate: {
                    first: 'First',
                    last: 'Last',
                    next: 'Next',
                    previous: 'Previous'
                }
            }
        });
        upbBindSerialTableLoader($tbl, $root);
    }

    $(function () {
        upbInitStockTable('#tblRequiredStock', true);
        upbInitSerialStockTable();
    });
})();
</script>
</body>
</html>
