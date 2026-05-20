<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Item-Transfer-Workflow";
$Page = "View-Store-To-Store-Transfers";
$row77 = getRecord("SELECT Roll, BranchId, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$BranchId = $row77['BranchId'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 27 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_allowed) {
    echo "<script>alert('Access denied.'); window.location.href='../dashboard.php';</script>";
    exit;
}
$where = "1=1";
if ($Roll == 27) $where = "(t.FromBranchId='$BranchId' OR t.ToBranchId='$BranchId')";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - View Store to Store Transfers</title>
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
                    <h4 class="font-weight-bold py-3 mb-0">Store to Store – Transfer History</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="example">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Date</th>
                                        <th>From Store</th>
                                        <th>To Store</th>
                                        <th>Items / Qty</th>
                                        <th>Narration</th>
                                        <th style="width:100px;">View Items</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT t.*, tb1.Name AS FromStoreName, tb2.Name AS ToStoreName FROM tbl_store_to_store_transfer t LEFT JOIN tbl_branch tb1 ON t.FromBranchId=tb1.id LEFT JOIN tbl_branch tb2 ON t.ToBranchId=tb2.id WHERE $where ORDER BY t.id DESC";
                                    $res = $conn->query($sql);
                                    $i = 1;
                                    while ($row = $res->fetch_assoc()) {
                                        $tid = $row['id'];
                                        $cnt = getRecord("SELECT COUNT(*) AS c FROM tbl_store_to_store_transfer_details WHERE TransferId='$tid'");
                                        $tot = getRecord("SELECT COALESCE(SUM(Qty),0) AS t FROM tbl_store_to_store_transfer_details WHERE TransferId='$tid'");
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row['TransferDate']; ?></td>
                                            <td><?php echo htmlspecialchars($row['FromStoreName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['ToStoreName']); ?></td>
                                            <td><?php echo $cnt['c']; ?> line(s), <?php echo $tot['t']; ?> unit(s)</td>
                                            <td><?php echo htmlspecialchars($row['Narration']); ?></td>
                                            <td>
                                                <?php if ((int)$cnt['c'] > 0) { ?>
                                                <button type="button" class="btn btn-sm btn-info btn-view-transfer-items" data-transfer-id="<?php echo (int)$tid; ?>">View</button>
                                                <?php } else { ?>
                                                <span class="text-muted small">—</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php $i++; }
                                    if ($i == 1) echo '<tr><td colspan="7" class="text-muted">No transfers found.</td></tr>';
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="item_transfer_workflow/store-to-store-transfer.php" class="btn btn-primary">New Store to Store Transfer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once '../footer.php'; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewItemsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0">Transfer #<span id="viewItemsModalTransferId"></span> – Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2" id="viewItemsModalMeta"></p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm w-100" id="modalViewItemsTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Serial No</th>
                            <th>Unit</th>
                            <th>Model No</th>
                        </tr>
                        </thead>
                        <tbody id="modalViewItemsTbody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include_once '../footer_script.php'; ?>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        pageLength: 25,
        order: [[1, 'desc']],
        columnDefs: [
            { targets: 6, orderable: false, searchable: false }
        ]
    });

    var viewItemsDt = null;

    function destroyViewItemsTable() {
        if ($.fn.DataTable.isDataTable('#modalViewItemsTable')) {
            $('#modalViewItemsTable').DataTable().destroy();
        }
        $('#modalViewItemsTbody').empty();
        viewItemsDt = null;
    }

    $('#viewItemsModal').on('hidden.bs.modal', function() {
        destroyViewItemsTable();
    });

    $(document).on('click', '.btn-view-transfer-items', function() {
        var tid = $(this).data('transfer-id');
        if (!tid) return;
        destroyViewItemsTable();
        $.getJSON('item_transfer_workflow/ajax-store-to-store-transfer-lines.php', { transfer_id: tid })
            .done(function(resp) {
                if (!resp || !resp.ok) {
                    alert((resp && resp.message) ? resp.message : 'Could not load transfer items.');
                    return;
                }
                if (!resp.lines || !resp.lines.length) {
                    alert('No items found on this transfer.');
                    return;
                }
                $('#viewItemsModalTransferId').text(resp.transferId);
                var meta = (resp.transferDate || '') + ' · ' + (resp.fromStore || '') + ' → ' + (resp.toStore || '');
                $('#viewItemsModalMeta').text(meta);
                var $tb = $('#modalViewItemsTbody');
                resp.lines.forEach(function(ln, idx) {
                    var tr = $('<tr>');
                    tr.append($('<td>').text(idx + 1));
                    tr.append($('<td>').text(ln.ProductName || ''));
                    tr.append($('<td>').text(ln.Qty != null ? String(ln.Qty) : ''));
                    tr.append($('<td>').text(ln.SerialNo || ''));
                    tr.append($('<td>').text(ln.Unit || ''));
                    tr.append($('<td>').text(ln.ModelNo || ''));
                    $tb.append(tr);
                });
                viewItemsDt = $('#modalViewItemsTable').DataTable({
                    pageLength: 12,
                    lengthMenu: [[12, 25, 50, 100, -1], [12, 25, 50, 100, 'All']],
                    order: [[1, 'asc']],
                    columnDefs: [
                        { targets: 0, orderable: false, searchable: false }
                    ]
                });
                $('#viewItemsModal').modal('show');
            })
            .fail(function() {
                alert('Could not load transfer items.');
            });
    });
});
</script>
</body>
</html>
