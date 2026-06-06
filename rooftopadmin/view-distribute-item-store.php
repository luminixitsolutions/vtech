<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-rooftop-store-dist-dispatch-status.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Order-Store";
$Page = "View-Assign-Order";
$rooftopUserStoreBranchId = (int) ($RooftopBranchId ?? $BranchId ?? 0);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
<style>
.card-datatable-table-wrap { overflow-x: auto; }
#example {
    table-layout: auto;
    width: 100% !important;
    margin-bottom: 0;
}
#example th,
#example td {
    vertical-align: middle;
}
#example thead th {
    padding-top: 0.45rem !important;
    padding-bottom: 0.45rem !important;
    line-height: 1.25 !important;
    height: auto !important;
    white-space: nowrap;
}
#example tbody td:not(.col-assign-dispatch):not(.col-action) {
    overflow: hidden;
    text-overflow: ellipsis;
}
#example tbody .col-product-head {
    white-space: normal !important;
    word-break: break-word;
}
#example tbody td.col-assign-dispatch {
    min-width: 240px;
    width: 240px;
    max-width: 280px;
    white-space: normal !important;
    overflow: hidden;
}
#example thead th.col-assign-dispatch {
    min-width: 240px !important;
    width: 240px !important;
    max-width: 280px;
    white-space: nowrap;
    padding-right: 1.25rem !important;
}
#example .col-assign-dispatch .badge {
    display: inline-block;
    max-width: 100%;
    white-space: normal;
    word-break: break-word;
    line-height: 1.35;
    text-align: left;
}
#example .col-action {
    width: 56px !important;
    min-width: 56px !important;
    max-width: 56px !important;
    padding-left: 10px !important;
    padding-right: 10px !important;
    white-space: nowrap;
    overflow: visible;
}
/* Single header row — avoid scrollX clone misalignment */
#example_wrapper .dataTables_scrollHead {
    display: none !important;
}
#example_wrapper table.dataTable {
    width: 100% !important;
    margin: 0 !important;
}
</style>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
$canAssignDispatch = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
$dispatchOfficers = [];
if ($canAssignDispatch) {
    if ($Roll == 1 || $Roll == 7) {
        $sqlDo = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26 ORDER BY Fname ASC";
    } else {
        $bid = $rooftopUserStoreBranchId;
        $sqlDo = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26 AND (BranchId='$bid' OR RooftopBranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(MulRooftopBranchId,' ','')) OR FIND_IN_SET('$bid', REPLACE(MulBranchId,' ',''))) ORDER BY Fname ASC";
    }
    $dispatchOfficers = getList($sqlDo);
    if (!is_array($dispatchOfficers)) {
        $dispatchOfficers = [];
    }
}

if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_rooftop_distibute_items WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_distibute_item_details WHERE DistId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-distribute-item-store.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Item To Store List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="distribute-item-store-2.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
<?php } ?>
</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

  <div class="form-group col-md-2">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId">
<?php 
 if($Roll == 1 || $Roll == 7){?>
    <option selected="" value="all">All</option>
<?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='$rooftopUserStoreBranchId'";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>
</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<?php
$hasActionCol = in_array('10', $Options) || in_array('11', $Options);
$colOff = $canAssignDispatch ? 1 : 0;
$assignColIdx = $canAssignDispatch ? 0 : -1;
$actionColIdx = $hasActionCol ? ($colOff + 7) : -1;
$numColIdx = $colOff;
?>
<div class="card-datatable table-responsive card-datatable-table-wrap">
<table id="example" class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <?php if ($canAssignDispatch) { ?>
                <th class="col-assign-dispatch" data-orderable="false">Assign to dispatch</th>
                <?php } ?>
               <th>#</th>
               <th>Store Name</th>
               <th class="col-product-head">Product Head</th>
               <th>Vehicle Date</th>
               <th>Vehicle No</th>
               <th>Total Stock Qty</th>
               <th>Date</th>
                <?php if ($hasActionCol) { ?>
                <th class="col-action">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As StoreName,tu.Fname As StoreIncName
                    FROM tbl_rooftop_distibute_items ts 
                    LEFT JOIN tbl_rooftop_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON ts.StoreInchId=tu.id WHERE ts.Status=1 
                    ";
            if($_POST['BranchId']){
                $BranchIdFilter = $_POST['BranchId'];
                if($BranchIdFilter == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BranchId='$BranchIdFilter'";
                }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.CreatedDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            $res = $conn->query($sql);
            $listRows = [];
            $listDistIds = [];
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $listRows[] = $row;
                    $listDistIds[] = (int) $row['id'];
                }
            }
            $dispatchOfficerMap = $canAssignDispatch ? buildRooftopStoreDistDispatchOfficerMap($conn, $listDistIds) : [];
            $totQtyMap = buildRooftopStoreDistTotQtyMap($conn, $listDistIds);
            foreach ($listRows as $row)
            {
                $distId = (int) $row['id'];
                $TotQty = isset($totQtyMap[$distId]) ? $totQtyMap[$distId] : 0;
                if($TotQty > 0){
                $vehDateDisp = '';
                if (!empty($row['VehicalDate']) && $row['VehicalDate'] !== '0000-00-00') {
                    $vehTs = strtotime(str_replace('-', '/', $row['VehicalDate']));
                    if ($vehTs) {
                        $vehDateDisp = date('d/m/Y', $vehTs);
                    }
                }
                $createdDisp = '';
                if (!empty($row['CreatedDate']) && $row['CreatedDate'] !== '0000-00-00') {
                    $createdTs = strtotime(str_replace('-', '/', $row['CreatedDate']));
                    if ($createdTs) {
                        $createdDisp = date('d/m/Y', $createdTs);
                    }
                }
             ?>
            <tr>
                <?php if ($canAssignDispatch) { ?>
                <td class="align-middle col-assign-dispatch">
                    <?php
                    $dispatchOfficerName = isset($dispatchOfficerMap[$distId]) ? $dispatchOfficerMap[$distId] : '';
                    if ($dispatchOfficerName !== '') { ?>
                    <span class="badge badge-success d-block mb-1">Assigned: <?php echo htmlspecialchars($dispatchOfficerName); ?></span>
                    <form method="post" action="save-revert-dispatch-from-distribute-store.php" class="d-inline form-revert-dispatch" onsubmit="return confirm('Revert this assignment back to store? Items will be removed from the dispatch officer.');">
                        <input type="hidden" name="store_dist_id" value="<?php echo (int)$row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-warning">Revert to store</button>
                    </form>
                    <button type="button" class="btn btn-sm btn-link btn-dispatch-history p-0 ml-1" data-dist-id="<?php echo (int)$row['id']; ?>" data-toggle="modal" data-target="#modalDispatchHistory">History</button>
                    <?php } else { ?>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-assign-dispatch-row" data-dist-id="<?php echo (int)$row['id']; ?>" data-toggle="modal" data-target="#modalAssignDispatch">Assign to dispatch</button>
                    <button type="button" class="btn btn-sm btn-link btn-dispatch-history p-0 ml-1" data-dist-id="<?php echo (int)$row['id']; ?>" data-toggle="modal" data-target="#modalDispatchHistory">History</button>
                    <?php } ?>
                </td>
                <?php } ?>
               <td><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars($row['StoreName']); ?></td>
                <td class="col-product-head"><?php echo htmlspecialchars($row['Narration']); ?></td>
                <td><?php echo htmlspecialchars($vehDateDisp); ?></td>
                <td><?php echo htmlspecialchars($row['VehicalNo']); ?></td>
                <td><a href="view-assigning-items.php?id=<?php echo (int)$row['id']; ?>"><?php echo (int)$TotQty; ?></a></td>
                <td><?php echo htmlspecialchars($createdDisp); ?></td>
            <?php if ($hasActionCol) { ?>
            <td class="col-action text-center">
               <?php if (in_array('11', $Options)) { ?>
              <a class="d-inline-block" onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo (int)$row['id']; ?>&action=delete" title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
               <?php } ?>
            </td>
         <?php } ?>
            </tr>
           <?php $i++; } } ?>
        </tbody>
    </table>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>

</div>
</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php if ($canAssignDispatch) { ?>
<div class="modal fade" id="modalAssignDispatch" tabindex="-1" role="dialog" aria-labelledby="modalAssignDispatchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="save-assign-dispatch-from-distribute-store.php" id="formAssignDispatch">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAssignDispatchLabel">Assign to dispatch officer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="dispatchHiddenIds"></div>
                    <?php if (empty($dispatchOfficers)) { ?>
                    <div class="alert alert-warning">No dispatch officers (role 26) found for your access. Add or link dispatch users first.</div>
                    <?php } ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label">Dispatch officer <span class="text-danger">*</span></label>
                            <select name="StoreExeId" id="dispatchStoreExeId" class="form-control" required>
                                <option value="">Select officer</option>
                                <?php foreach ($dispatchOfficers as $off) { ?>
                                <option value="<?php echo (int)$off['id']; ?>"><?php echo htmlspecialchars($off['Fname'] . (isset($off['Phone']) && $off['Phone'] !== '' ? ' (' . $off['Phone'] . ')' : '')); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Assignment date <span class="text-danger">*</span></label>
                            <input type="date" name="CreatedDate" id="dispatchCreatedDate" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-secondary btn-block" id="btnDispatchPreview">Review items</button>
                        </div>
                    </div>
                    <div id="dispatchPreviewWrap" class="mt-2 border rounded p-2 bg-light" style="min-height:48px;">
                        <span class="text-muted small">Lines load when you open this form, or click “Review items” to refresh.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnDispatchSubmit" disabled>Submit assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDispatchHistory" tabindex="-1" role="dialog" aria-labelledby="modalDispatchHistoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDispatchHistoryLabel">Assign / revert history</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="dispatchHistoryWrap"><span class="text-muted small">Select History on a row to load activity.</span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
<?php if ($canAssignDispatch) { ?>
window.dispatchOfficersExist = <?php echo empty($dispatchOfficers) ? 'false' : 'true'; ?>;
<?php } ?>
    $(document).ready(function() {
    var assignColIdx = <?php echo (int)$assignColIdx; ?>;
    var actionColIdx = <?php echo (int)$actionColIdx; ?>;
    var numColIdx = <?php echo (int)$numColIdx; ?>;
    var dtColumnDefs = [];
    if (assignColIdx >= 0) {
        dtColumnDefs.push({ orderable: false, width: '240px', targets: assignColIdx, className: 'col-assign-dispatch' });
    }
    dtColumnDefs.push(
        { width: '40px', targets: numColIdx },
        { width: '12%', targets: numColIdx + 1 },
        { width: '22%', targets: numColIdx + 2, className: 'col-product-head' },
        { width: '9%', targets: numColIdx + 3 },
        { width: '9%', targets: numColIdx + 4 },
        { width: '8%', targets: numColIdx + 5 },
        { width: '9%', targets: numColIdx + 6 }
    );
    if (actionColIdx >= 0) {
        dtColumnDefs.push({ orderable: false, width: '56px', targets: actionColIdx, className: 'col-action text-center' });
    }
    var $tbl = $('#example');
    if ($.fn.dataTable.isDataTable($tbl)) {
        $tbl.DataTable().destroy();
        $tbl.removeClass('dataTable no-footer');
    }
    var dt = $tbl.DataTable({
        autoWidth: false,
        order: [[numColIdx, 'asc']],
        columnDefs: dtColumnDefs,
        initComplete: function () {
            this.api().columns.adjust();
        }
    });
    $(window).on('resize.storeDistList', function () {
        if ($.fn.dataTable.isDataTable($tbl)) {
            $tbl.DataTable().columns.adjust();
        }
    });

<?php if ($canAssignDispatch) { ?>
window.currentDispatchDistId = null;
function loadDispatchPreview() {
    var id = window.currentDispatchDistId;
    if (!id) {
        $('#dispatchPreviewWrap').html('<span class="text-danger small">No assignment selected.</span>');
        $('#btnDispatchSubmit').prop('disabled', true);
        return;
    }
    $('#dispatchHiddenIds').html('<input type="hidden" name="dist_ids[]" value="' + id + '">');
    $('#dispatchPreviewWrap').html('<span class="text-muted">Loading…</span>');
    $.post('ajax_rooftop_distribute_store_dispatch_preview.php', { dist_ids: String(id) }, function (res) {
        if (res && res.ok) {
            $('#dispatchPreviewWrap').html(res.html);
            $('#btnDispatchSubmit').prop('disabled', !window.dispatchOfficersExist || !res.line_count || res.line_count < 1);
        } else {
            $('#dispatchPreviewWrap').html('<div class="text-danger">' + (res && res.error ? res.error : 'Preview failed.') + '</div>');
            $('#btnDispatchSubmit').prop('disabled', true);
        }
    }, 'json').fail(function () {
        $('#dispatchPreviewWrap').html('<div class="text-danger">Request failed.</div>');
        $('#btnDispatchSubmit').prop('disabled', true);
    });
}
$(document).on('click', '.btn-assign-dispatch-row', function () {
    window.currentDispatchDistId = $(this).data('dist-id');
    $('#dispatchPreviewWrap').html('<span class="text-muted small">Opening…</span>');
    $('#btnDispatchSubmit').prop('disabled', true);
});
$('#modalAssignDispatch').on('shown.bs.modal', function () {
    loadDispatchPreview();
});
$('#btnDispatchPreview').on('click', function () {
    loadDispatchPreview();
});
$('#formAssignDispatch').on('submit', function () {
    if (!window.currentDispatchDistId) {
        alert('Open the assign form from a row button.');
        return false;
    }
    $('#dispatchHiddenIds').html('<input type="hidden" name="dist_ids[]" value="' + window.currentDispatchDistId + '">');
    return true;
});
$('#modalAssignDispatch').on('hidden.bs.modal', function () {
    window.currentDispatchDistId = null;
    $('#dispatchPreviewWrap').html('<span class="text-muted small">Lines load when you open this form, or click “Review items” to refresh.</span>');
    $('#btnDispatchSubmit').prop('disabled', true);
    $('#dispatchHiddenIds').empty();
});
window.currentDispatchHistoryId = null;
function loadDispatchHistory() {
    var id = window.currentDispatchHistoryId;
    if (!id) {
        $('#dispatchHistoryWrap').html('<span class="text-danger small">No assignment selected.</span>');
        return;
    }
    $('#dispatchHistoryWrap').html('<span class="text-muted">Loading…</span>');
    $.post('ajax_rooftop_store_dist_dispatch_history.php', { store_dist_id: id }, function (res) {
        if (res && res.ok) {
            $('#dispatchHistoryWrap').html(res.html);
        } else {
            $('#dispatchHistoryWrap').html('<div class="text-danger">' + (res && res.error ? res.error : 'Could not load history.') + '</div>');
        }
    }, 'json').fail(function () {
        $('#dispatchHistoryWrap').html('<div class="text-danger">Request failed.</div>');
    });
}
$(document).on('click', '.btn-dispatch-history', function () {
    window.currentDispatchHistoryId = $(this).data('dist-id');
});
$('#modalDispatchHistory').on('shown.bs.modal', function () {
    loadDispatchHistory();
});
$('#modalDispatchHistory').on('hidden.bs.modal', function () {
    window.currentDispatchHistoryId = null;
    $('#dispatchHistoryWrap').html('<span class="text-muted small">Select History on a row to load activity.</span>');
});
<?php } ?>
});
</script>
</body>
</html>
