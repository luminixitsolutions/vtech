<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Item-Transfer-Workflow";
$Page = "View-Dispatch-To-Store-Transfers";
$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 26 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_allowed) {
    echo "<script>alert('Access denied.'); window.location.href='../dashboard.php';</script>";
    exit;
}
$where = "1=1";
if ($Roll == 26) $where = "t.DispatchOfficerId='$user_id'";
$colspanMain = ($Roll == 1 || $Roll == 7) ? 7 : 6;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - View Dispatch to Store Transfers</title>
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
                    <h4 class="font-weight-bold py-3 mb-0">Dispatch to Store – Transfer History</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="example">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Date</th>
                                        <th>To Store</th>
                                        <?php if ($Roll == 1 || $Roll == 7) { ?><th>Dispatch Officer</th><?php } ?>
                                        <th>Items / Qty</th>
                                        <th>Narration</th>
                                        <th style="width:100px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT t.*, tb.Name AS ToStoreName, tu.Fname AS OfficerName 
FROM tbl_dispatch_to_store_transfer t 
LEFT JOIN tbl_branch tb ON t.ToBranchId=tb.id 
LEFT JOIN tbl_users tu ON t.DispatchOfficerId=tu.id 
WHERE $where ORDER BY t.id DESC";
                                    $res = $conn->query($sql);
                                    $i = 1;
                                    while ($row = $res->fetch_assoc()) {
                                        $tid = $row['id'];
                                        $cnt = getRecord("SELECT COUNT(*) AS c FROM tbl_dispatch_to_store_transfer_details WHERE TransferId='$tid'");
                                        $tot = getRecord("SELECT COALESCE(SUM(Qty),0) AS t FROM tbl_dispatch_to_store_transfer_details WHERE TransferId='$tid'");
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row['TransferDate']; ?></td>
                                            <td><?php echo htmlspecialchars($row['ToStoreName']); ?></td>
                                            <?php if ($Roll == 1 || $Roll == 7) { ?><td><?php echo htmlspecialchars($row['OfficerName']); ?></td><?php } ?>
                                            <td><?php echo $cnt['c']; ?> line(s), <?php echo $tot['t']; ?> unit(s)</td>
                                            <td><?php echo htmlspecialchars($row['Narration']); ?></td>
                                            <td>
                                                <?php if ((int)$cnt['c'] > 0) { ?>
                                                <button type="button" class="btn btn-sm btn-warning btn-revert-transfer" data-transfer-id="<?php echo (int)$tid; ?>">Revert</button>
                                                <?php } else { ?>
                                                <span class="text-muted small">—</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php $i++; }
                                    if ($i == 1) echo '<tr><td colspan="' . (int)$colspanMain . '" class="text-muted">No transfers found.</td></tr>';
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="item_transfer_workflow/dispatch-to-store-transfer.php" class="btn btn-primary">New Transfer to Store</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once '../footer.php'; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Keep revert actions visible: body scrolls, footer stays at bottom of modal */
#revertModal .modal-dialog {
    max-height: calc(100vh - 2rem);
    margin: 1rem auto;
}
#revertModal .modal-content {
    max-height: calc(100vh - 2rem);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
#revertModal #formRevertDispatch {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    min-height: 0;
    overflow: hidden;
}
#revertModal .modal-body--revert-scroll {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}
#revertModal .modal-footer--revert-actions {
    flex-shrink: 0;
    background: #fff;
    border-top: 1px solid #dee2e6;
    position: relative;
    z-index: 2;
}
</style>
<div class="modal fade" id="revertModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header flex-shrink-0">
                <h5 class="modal-title mb-0">Revert transfer #<span id="revertModalTransferId"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form method="post" action="item_transfer_workflow/revert-dispatch-to-store-transfer.php" id="formRevertDispatch">
                <div class="modal-body modal-body--revert-scroll">
                    <p class="small text-muted mb-2" id="revertModalMeta"></p>
                    <p class="small text-muted mb-2">Select line(s) you sent by mistake, then submit. The destination store’s balance for those items will go down (the product line may disappear if balance reaches zero). Returned stock appears again on <strong>Dispatch → Transfer to Store</strong> for the dispatch officer—not in the store’s screen. If every line is reverted, this transfer disappears from history.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm w-100" id="modalRevertLinesTable">
                            <thead>
                            <tr>
                                <th style="width:42px;"><input type="checkbox" id="chkAllRevert" title="Select all on this page"></th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Serial No</th>
                                <th>Unit</th>
                            </tr>
                            </thead>
                            <tbody id="modalRevertLinesTbody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer modal-footer--revert-actions">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning font-weight-bold">Revert selected stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once '../footer_script.php'; ?>
<script>
$(document).ready(function() {
    var actionCol = <?php echo (int)(($Roll == 1 || $Roll == 7) ? 6 : 5); ?>;
    $('#example').DataTable({
        pageLength: 25,
        order: [[1, 'desc']],
        columnDefs: [
            { targets: actionCol, orderable: false, searchable: false }
        ]
    });

    var revertDt = null;

    function destroyRevertTable() {
        if ($.fn.DataTable.isDataTable('#modalRevertLinesTable')) {
            $('#modalRevertLinesTable').DataTable().destroy();
        }
        $('#modalRevertLinesTbody').empty();
        $('#chkAllRevert').prop('checked', false);
        revertDt = null;
    }

    $('#revertModal').on('hidden.bs.modal', function() {
        destroyRevertTable();
    });

    $(document).on('click', '.btn-revert-transfer', function() {
        var tid = $(this).data('transfer-id');
        if (!tid) return;
        destroyRevertTable();
        $.getJSON('item_transfer_workflow/ajax-dispatch-transfer-lines.php', { transfer_id: tid })
            .done(function(resp) {
                if (!resp || !resp.ok) {
                    alert((resp && resp.message) ? resp.message : 'Could not load transfer lines.');
                    return;
                }
                if (!resp.lines || !resp.lines.length) {
                    alert('No lines left on this transfer.');
                    return;
                }
                $('#revertModalTransferId').text(resp.transferId);
                var meta = (resp.transferDate || '') + ' · ' + (resp.toStore || '');
                $('#revertModalMeta').text(meta);
                var $tb = $('#modalRevertLinesTbody');
                resp.lines.forEach(function(ln) {
                    var tr = $('<tr>');
                    tr.append($('<td>').append(
                        $('<input>', { type: 'checkbox', name: 'detail_ids[]', value: ln.id, class: 'revert-line-chk' })
                    ));
                    tr.append($('<td>').text(ln.ProductName || ''));
                    tr.append($('<td>').text(ln.Qty != null ? String(ln.Qty) : ''));
                    tr.append($('<td>').text(ln.SerialNo || ''));
                    tr.append($('<td>').text(ln.Unit || ''));
                    $tb.append(tr);
                });
                revertDt = $('#modalRevertLinesTable').DataTable({
                    pageLength: 12,
                    lengthMenu: [[12, 25, 50, 100, -1], [12, 25, 50, 100, 'All']],
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false }
                    ]
                });
                $('#chkAllRevert').off('change.revert').on('change.revert', function() {
                    var checked = this.checked;
                    revertDt.rows({ page: 'current' }).every(function() {
                        $('td:first .revert-line-chk', this.node()).prop('checked', checked);
                    });
                });
                $('#modalRevertLinesTable').off('draw.dt.revert').on('draw.dt.revert', function() {
                    $('#chkAllRevert').prop('checked', false);
                });
                $('#revertModal').modal('show');
            })
            .fail(function() {
                alert('Could not load transfer lines.');
            });
    });

    $('#chkAllRevert').on('click', function(e) {
        e.stopPropagation();
    });

    var formRevert = document.getElementById('formRevertDispatch');
    if (formRevert) {
        formRevert.addEventListener('submit', function(e) {
            var any = document.querySelectorAll('#modalRevertLinesTbody .revert-line-chk:checked').length > 0;
            if (!any) {
                e.preventDefault();
                alert('Please select at least one line.');
                return;
            }
            if (!confirm('Revert selected line(s)? Stock will return to your dispatch list and be removed from the store for those lines.')) {
                e.preventDefault();
            }
        });
    }
});
</script>
</body>
</html>
