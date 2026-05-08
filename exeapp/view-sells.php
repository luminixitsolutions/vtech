<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "View-Sell";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Delivery Challan List</title>
 <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_sell WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_sell_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND SellType='Challan'";
  $conn->query($sql11);
   $sql11 = "DELETE FROM tbl_stocks WHERE SellId = '$id' AND SellType='Challan'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-sells.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Delivery Challan List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
 <?php if($Roll == 1 || $Roll == 7){?>
    <a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a>
    <?php } else {?>
<a href="add-sell.php?action=search&BranchId=<?php echo $BranchId;?>" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a>
<?php } ?>
<?php } ?>
</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       
<div class="form-group col-md-3">
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


<div class="form-group col-md-3">
<label class="form-label">Customers</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
  if($Roll==1 || $Roll==7){
  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1 AND tu.Roll=5";
 }
 else{
  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1 AND tu.Roll=5 AND tp.DispatchOfficerId='$user_id'";   
 }
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
                <th>DM No</th>
               <th>Store</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                 
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
            if($Roll==1 || $Roll==7){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 ";
             }
             else if($Roll==27){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.BranchId='$BranchId'"; 
             }
             else{
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_sell ts 
                    LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.CreatedBy='$user_id'"; 
             }
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
            
            if($_POST['ProductId']){
                $ProductId = $_POST['ProductId'];
                if($ProductId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.ProductId='$ProductId'";
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
                $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_sell_products` WHERE SellId='".$row['id']."'";
                $row2 = getRecord($sql2);
                $TotQty = $row2['TotQty'];
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['InvoiceNo']; ?></td>
               <td><?php echo $row['Branch']; ?></td>
              <td><?php echo $row['CustName']; ?></td>
              <td><?php echo $row['CellNo']; ?></td>
             <td><?php echo $TotQty; ?></td>
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
              
                <!--  <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['DeliveryDate']))); ?></td> -->
             
               
          
            <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
                 <?php if(in_array("10", $Options)){?>
              <!-- <a href="edit-sell.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp; -->
               <?php } if(in_array("11", $Options)){?>
                <a href="print-delivery-challan.php?id=<?php echo $row['id']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a>&nbsp;&nbsp; 
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

</main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
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
