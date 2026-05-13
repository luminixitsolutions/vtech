<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Order-Store";
$Page = "View-Assign-Order";
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
        $bid = (int) $BranchId;
        $sqlDo = "SELECT id, Fname, Phone FROM tbl_users WHERE Status='1' AND Roll=26 AND (BranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(MulBranchId,' ',''))) ORDER BY Fname ASC";
    }
    $dispatchOfficers = getList($sqlDo);
    if (!is_array($dispatchOfficers)) {
        $dispatchOfficers = [];
    }
}

if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_distibute_items WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_distibute_item_details WHERE DistId = '$id'";
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
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
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

<!--<div class="form-group col-md-3">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreInchId" id="StoreInchId">
    <option selected="" value="all">All</option>
    <?php
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND BranchId='".$_REQUEST['BranchId']."'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreInchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> -->


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
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <?php if ($canAssignDispatch) { ?>
                <th class="text-nowrap" style="min-width:150px" data-orderable="false">Assign to dispatch</th>
                <?php } ?>
               <th>#</th>
                
               <th>Store Name</th>
              <!-- <th>Store Incharge Name</th>
               -->
               <th>Product Head</th>
                <th>Vehicle Date</th>
                 <th>Vehicle No</th>
                  <th>Total Stock Qty</th>
                <th>Date</th>
               
               <!--  <th>Delivery Date</th> -->
             
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As StoreName,tu.Fname As StoreIncName,
                    (
                        SELECT u2.Fname
                        FROM tbl_distibute_items2 h2
                        LEFT JOIN tbl_users u2 ON u2.id = h2.StoreExeId
                        WHERE h2.Status='1'
                          AND h2.Narration LIKE '%DistId(s):%'
                          AND h2.Narration REGEXP CONCAT('(^|[^0-9])', ts.id, '([^0-9]|$)')
                        ORDER BY h2.id DESC
                        LIMIT 1
                    ) AS DispatchOfficerName
                    FROM tbl_distibute_items ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON ts.StoreInchId=tu.id WHERE ts.Status=1 
                    ";
             
            if($_POST['BranchId']){
                $BranchId = $_POST['BranchId'];
                if($BranchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BranchId='$BranchId'";
                }
            }
            if($_POST['StoreInchId']){
                $StoreInchId = $_POST['StoreInchId'];
                if($StoreInchId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.StoreInchId='$StoreInchId'";
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
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_distibute_item_details` WHERE DistId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $TotQty = $row2['TotQty'];
                if($TotQty > 0){
             ?>
            <tr>
                <?php if ($canAssignDispatch) { ?>
                <td class="align-middle">
                    <?php if (!empty($row['DispatchOfficerName'])) { ?>
                    <span class="badge badge-success">Assigned: <?php echo htmlspecialchars($row['DispatchOfficerName']); ?></span>
                    <?php } else { ?>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-assign-dispatch-row" data-dist-id="<?php echo (int)$row['id']; ?>" data-toggle="modal" data-target="#modalAssignDispatch">Assign to dispatch</button>
                    <?php } ?>
                </td>
                <?php } ?>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['StoreName']; ?></td>
                
                <!-- <td><?php echo $row['StoreIncName']; ?></td>-->
                <td><?php echo $row['Narration']; ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['VehicalDate']))); ?></td>
                 <td><?php echo $row['VehicalNo']; ?></td>
            <td><a href="view-assigning-items.php?id=<?php echo $row['id']; ?>"><?php echo $TotQty; ?></a></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
              
            
               
          
            <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
                 <?php if(in_array("10", $Options)){?>
              <!-- <a href="edit-sell.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp; -->
               <?php } if(in_array("11", $Options)){?>
              
              <a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a><?php } ?>&nbsp;&nbsp;
              
            </td>
         <?php } ?>
            </tr>
           <?php } $i++;} ?>
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
<?php } ?>

<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
<?php if ($canAssignDispatch) { ?>
window.dispatchOfficersExist = <?php echo empty($dispatchOfficers) ? 'false' : 'true'; ?>;
<?php } ?>
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
        <?php if ($canAssignDispatch) { ?>
        , columnDefs: [{ orderable: false, targets: 0 }]
        <?php } ?>
    });

 $(document).on("change", "#ModelNo", function(event) {
            var val = this.value;
            var action = "getModelNo";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#ProductNo').html(data);
                  
                }
            });

        });
    
    $(document).on("change", "#BranchId", function(event) {
            var val = this.value;
            var action = "getStoreIncharge";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    //alert(data);
                    $('#StoreInchId').html(data);
                }
            });

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
    $.post('ajax_distribute_store_dispatch_preview.php', { dist_ids: String(id) }, function (res) {
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
<?php } ?>
});
</script>
</body>
</html>
