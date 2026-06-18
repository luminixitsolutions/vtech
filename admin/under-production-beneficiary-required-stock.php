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

// Always load full serial catalog for stock report customers (not only BOM serial lines).
$serialLines = [];
$bulkLines = $lines;
if (count($custIds) === 1) {
    $singleCustId = (int) $custIds[0];
    $serialLines = upb_fetch_serial_required_lines_for_customer($conn, $singleCustId, $lines);
    $parts = upb_partition_required_lines($conn, $lines, $serialLines);
    $bulkLines = $parts['bulk'];
} elseif ($isCombined && count($custIds) > 0) {
    $serialLines = upb_fetch_combined_serial_required_lines($conn, $custIds);
    $parts = upb_partition_required_lines($conn, $lines, $serialLines);
    $bulkLines = $parts['bulk'];
} elseif (count($custIds) === 0 && count($rawIds) === 1 && (int) $rawIds[0] > 0) {
    // Preview serial catalog when customer fails done-beneficiary validation but uid was given.
    $serialLines = upb_fetch_serial_required_lines_for_customer($conn, (int) $rawIds[0], []);
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
        .upb-stock-main-scroll table#tblRequiredStock,
        .upb-stock-card-inner table#tblRequiredSerialStock {
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

    <?php if (count($lines) === 0 && count($serialLines) === 0) { ?>
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

    <?php if (count($lines) === 0 && count($serialLines) === 0) { ?>
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
    $(function () {
        upbInitStockTable('#tblRequiredSerialStock', true);
        upbInitStockTable('#tblRequiredStock', true);
    });
})();
</script>
</body>
</html>
