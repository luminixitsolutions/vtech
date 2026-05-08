<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "View-Purchase-Order";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Purchase Order List</title>
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
  $sql11 = "DELETE FROM tbl_rooftop_purchase_order WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_purchase_order_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_general_ledger WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);
  /* $sql11 = "DELETE FROM tbl_rooftop_stocks WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);*/
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-purchase-order.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Purchase Order List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-purchase-order.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Create Purchase Order</a></span>
<?php } ?>
</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       
<!-- <div class="form-group col-md-3">
<label class="form-label"> Branch<span class="text-danger">*</span></label>
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
</div> -->

<div class="form-group col-md-3">
<label class="form-label">Company</label>
 <select class="select2-demo form-control" name="CompId" id="CompId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '10' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CompId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label">Manufacture</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '3' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  


<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-3">
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
                <th>Print</th>
                <th>Order Track</th>
                <th>Product Head</th> 
                <th>Company Name</th>
                <th>Manufacture Name</th>
                <th>Contact No</th>
               
                <th>Date</th>
                <th>Gross Amt </th>
                <!--<th>CGST Amt </th>
                <th>SGST Amt </th>
                <th>IGST Amt </th>-->
                <th>Sub Total </th>
              <!--  <th>Less URD</th>-->
                <th>Discount</th>
                <th>Net Payable</th>
                <th>Delivery Date</th>
             
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As CompanyName FROM tbl_rooftop_purchase_order ts LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id
                     LEFT JOIN tbl_users tu ON ts.CompId=tu.id WHERE ts.Status=1";
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CustId='$CustId'";
                }
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
             if($_POST['CompId']){
                $CompId = $_POST['CompId'];
                if($CompId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CompId='$CompId'";
                }
            }

            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.InvoiceDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                // $sql2 = "SELECT SUM(PaidAmt) AS PaidAmt FROM tbl_rooftop_general_ledger WHERE SellId='".$row['id']."' AND Type='PR'";
                // $row2 = getRecord($sql2);
                // $PaidAmt = $row2['PaidAmt'];
                if($row['DeliveredStatus'] == 1){
                    $Status = "Order Delivered";
                }
                else if($row['ReceiveStatus'] == 1){
                    $Status = "Order Received";
                }
                else if($row['SendStatus'] == 1){
                    $Status = "Order Sent";
                }
                else if($row['ApplyStatus'] == 1){
                    $Status = "Order Applied";
                }
                else{
                    $Status = "Apply Order";
                }
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><a href="invoice.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $row['InvoiceNo']; ?></a></td>
                <td><a href="take-po-action.php?id=<?php echo $row['id']; ?>" target="_blank" class="badge badge-pill badge-secondary"><?php echo $Status;?></a></td>
                <td><?php echo $row['ProductHead']; ?></td>
 <!-- <td><?php echo $row['Branch']; ?></td> -->
  <td><?php echo $row['CompanyName']; ?></td>
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
              
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
                <td>&#8377;<?php echo $row['GrossAmt']; ?></td>
              <!--  <td>&#8377;<?php echo $row['CgstAmt']; ?></td>
             <td>&#8377;<?php echo $row['SgstAmt']; ?></td>
             <td>&#8377;<?php echo $row['IgstAmt']; ?></td>-->
             <td>&#8377;<?php echo $row['SubTotal']; ?></td>
            <!-- <td>&#8377;<?php echo $row['UcdAmt']; ?></td>-->
             <td>&#8377;<?php echo $row['Discount']; ?></td>
             <td>&#8377;<?php echo $row['Total']; ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['DeliveryDate']))); ?></td>
            
          
            <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
                 <?php if(in_array("10", $Options)){?>
              <a href="edit-purchase-order.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;
               <?php } if(in_array("11", $Options)){?>
              <a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a><?php } ?>&nbsp;&nbsp;
              <a href="invoice.php?id=<?php echo $row['id']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a>&nbsp;&nbsp; 
              <?php if($row["ProdType"] == 2) {?>
                <a href="invoice2.php?id=<?php echo $row['id']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a> 
              <?php } ?>
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
    
});
</script>
</body>
</html>
