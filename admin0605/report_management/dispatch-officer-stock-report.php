<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Dispatch-Stock-Report";
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
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
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
<h4 class="font-weight-bold py-3 mb-0">Dispatch Officier Stock Report
    
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
    <option selected="" value="" disabled>Select</option>
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

<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required>
<!--    <option selected="" value="all">All</option>-->
    <?php
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> 

<div class="form-group col-md-2">
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
   <?php if($_POST['Search'] == 'Search'){?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                
               
             <th>Product Name</th>
                <th>Credit Qty</th>
                 
                   <th>Debit Qty</th>
               
               
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $BranchId = $_POST['BranchId'];
            $FromDate = $_POST['FromDate'];
            $ToDate = $_POST['ToDate'];
            $sql2 = "SELECT * FROM tbl_branch WHERE id='$BranchId'";
            $row2 = getRecord($sql2);
            $StoreName = $row2['Name'];
            $sql = "SELECT ProductId,ProductName FROM `tbl_distibute_item_details2` WHERE BranchId='$BranchId'";
           if($_POST['StoreExeId']){
                $StoreExeId = $_POST['StoreExeId'];
                if($StoreExeId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND StoreExeId='$StoreExeId'";
                }
            }
            $sql.= " GROUP BY ProductId";
             
           //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT SUM(Qty) As Qty FROM `tbl_distibute_item_details2` WHERE BranchId='$BranchId' AND ProductId='".$row['ProductId']."'";
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND CreatedDate<='$ToDate'";
                }
                
                if($_POST['StoreExeId']){
                $StoreExeId = $_POST['StoreExeId'];
                if($StoreExeId == 'all'){
                    $sql2.= " ";
                }
                else{
                $sql2.= " AND StoreExeId='$StoreExeId'";
                }
                }
               
                $row2 = getRecord($sql2);
                if($row2['Qty'] > 0){
                     $TotCreditQty+=$row2['Qty'];
                $sql3 = "SELECT SUM(Qty) As Qty FROM `tbl_stocks` WHERE BranchId='$BranchId' AND ProductId='".$row['ProductId']."'";
                if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql3.= " AND CreatedDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql3.= " AND CreatedDate<='$ToDate'";
                }
                
                if($_POST['StoreExeId']){
                $StoreExeId = $_POST['StoreExeId'];
                if($StoreExeId == 'all'){
                    $sql3.= " ";
                }
                else{
                $sql3.= " AND CreatedBy='$StoreExeId'";
                }
                }
               
                $row3 = getRecord($sql3);
                $TotDebitQty+=$row3['Qty'];
                if($row3['Qty']==''){
                    $debitqty = 0;
                }
                else{
                    $debitqty = $row3['Qty'];
                }
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                
            <td><?php echo $row2['Qty']; ?></td>
            <td><?php echo $debitqty; ?></td>
             </tr>
           <?php $i++;}}  ?>
           
            <tr>
               <td><?php echo $i; ?></td>
           
                 <th>Total</th>
            <th><?php echo $TotCreditQty; ?></th>
            <th><?php echo $TotDebitQty; ?></th>
           </tr>
           
           <tr>
               <td><?php echo $i+1; ?></td>
               
                 <th>Balance</th>
            <th><?php echo $TotCreditQty-$TotDebitQty; ?></th>
            <th></th>
           </tr>
          
        </tbody>
    </table>
</div>
<?php } ?>
</div>
</div>


<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once '../footer_script.php'; ?>

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        "pageLength":1000,
         "order": [[ 0, "asc" ]],
       dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
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
});
</script>
</body>
</html>
