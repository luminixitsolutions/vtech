<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Items-Store-Executive";
$Page = "View-Assign-Store-Executive";
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_distibute_items2 WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_distibute_item_details2 WHERE DistId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-distribute-item-store-executive.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Assign Item To Dispatch Officer List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="distribute-item-store-executive-2.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span>
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
 <select class="form-control" name="BranchId" id="BranchId" required>
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

<?php if($Roll == 1 || $Roll == 7){?>
<div class="form-group col-md-3">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreInchId" id="StoreInchId">
    <option selected="" value="all">All</option>
    <?php
        if($Roll == 1 || $Roll == 7){
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND BranchId='".$_REQUEST['BranchId']."'";
        }
        else{
         $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND BranchId='$user_id'";   
        }
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreInchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> 
<?php } ?>

<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId">
    <option selected="" value="all">All</option>
    <?php
        if($Roll == 1 || $Roll == 7){
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND UnderUser='".$_REQUEST['StoreInchId']."'";
        }
        else{
         $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND UnderUser='$user_id'";   
        }
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
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
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Store Name</th>
               <!--<th>Store Incharge Name</th>-->
               <th>Store Executive Name</th>
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
            if($Roll == 1 || $Roll == 7){
            $sql = "SELECT ts.*,tb.Name As StoreName,tu.Fname As StoreIncName,tu2.Fname As StoreExeName FROM tbl_distibute_items2 ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON ts.StoreInchId=tu.id 
                    LEFT JOIN tbl_users tu2 ON ts.StoreExeId=tu2.id WHERE ts.Status=1 
                    ";
            }
            else{
               $sql = "SELECT ts.*,tb.Name As StoreName,tu.Fname As StoreIncName,tu2.Fname As StoreExeName FROM tbl_distibute_items2 ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id 
                    LEFT JOIN tbl_users tu ON ts.StoreInchId=tu.id 
                    LEFT JOIN tbl_users tu2 ON ts.StoreExeId=tu2.id WHERE ts.Status=1 AND tu2.UnderUser='$user_id'
                    "; 
            }
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
            if($_POST['StoreExeId']){
                $StoreExeId = $_POST['StoreExeId'];
                if($StoreExeId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.StoreExeId='$StoreExeId'";
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
                $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_distibute_item_details2` WHERE DistId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $TotQty = $row2['TotQty'];
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['StoreName']; ?></td>
               <!--  <td><?php echo $row['StoreIncName']; ?></td>-->
                  <td><?php echo $row['StoreExeName']; ?></td>
            
              <td><a href="view-assigning-store-items.php?id=<?php echo $row['id']; ?>"><?php echo $TotQty; ?></a></td>
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
           <?php $i++;} ?>
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


<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
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
        
        $(document).on("change", "#StoreInchId", function(event) {
            var val = this.value;
            //alert(val);
            var action = "getStoreExecutive";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                   // alert(data);
                    $('#StoreExeId').html(data);
                }
            });

        });
});
</script>
</body>
</html>
