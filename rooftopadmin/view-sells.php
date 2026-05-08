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
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
    
  
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
     <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'header.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">View Delivery Challan List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
    <?php if($Roll == 1 || $Roll == 7){?>
    <a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a>
    <?php } else {?>
<a href="add-sell.php?action=search&BranchId=<?php echo $BranchId;?>" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a>
<?php } ?>
</span>
<?php } ?></h5>
                        
 <style>
    .flex-wrap {
            margin-bottom: -35px;
    }
        
    div.dataTables_wrapper div.dataTables_paginate {
            margin-top: 1px;
    }
    

</style>

<br>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_rooftop_sell WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_sell_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_general_ledger WHERE SellId = '$id' AND SellType='Challan'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_rooftop_stocks WHERE SellId = '$id' AND SellType='Challan'";
  $conn->query($sql11);
  $sql11 = "UPDATE tbl_rooftop_distibute_item_details2 SET SellStatus=0 WHERE SellId = '$id'";
  $conn->query($sql11);

  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-sells.php";
    </script>
<?php } ?>
                    <div class="card mb-4" style="padding: 10px;">
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
  $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
}
else{
   $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id IN ($MulRooftopBranchId)";
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
  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_rooftop_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tu.UnderProdStatus=1 AND tu.SurveyDetails=1 AND tu.FieldSurveyDetails=1 AND tu.ProjectType=2 AND tu.Roll=5";
 }
 else{
  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_rooftop_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tu.UnderProdStatus=1 AND tu.SurveyDetails=1 AND tu.FieldSurveyDetails=1 AND tu.ProjectType=2 AND tu.Roll=5 AND tp.DispatchOfficerId='$user_id'";   
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
              <th>Print</th>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
                <th>Action</th>
             <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
             if($Roll==1 || $Roll==7){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_rooftop_sell ts 
                    LEFT JOIN tbl_rooftop_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 ";
             }
             else if($Roll==27){
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_rooftop_sell ts 
                    LEFT JOIN tbl_rooftop_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.BranchId='$BranchId'"; 
             }
             else{
                $sql = "SELECT ts.*,tb.Name As Branch FROM tbl_rooftop_sell ts 
                    LEFT JOIN tbl_rooftop_branch tb ON ts.BranchId=tb.id WHERE ts.Status=1 AND ts.CreatedBy='$user_id'"; 
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
                $sql2 = "SELECT SUM(Qty) AS TotQty FROM `tbl_rooftop_sell_products` WHERE SellId='".$row['id']."' AND ProductName!=''";
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
             
               <td>
                    <a href="print-delivery-challan.php?id=<?php echo $row['id']; ?>" target="_blank"><i class="lnr lnr-printer text-danger"></i></a>
               </td>
          
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
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script  src="<?php echo $SiteUrl;?>/assets/js/pdfmake.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/vfs_fonts.js"></script>
   <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
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
