<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Stock";
$Page = "View-Available-Stock";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Stock List</title>
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
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-available-stocks.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Stock List
</h4>

<div class="card" style="padding: 10px;">
      <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-3">
<label class="form-label"> Category</label>
 <select class="form-control" name="CatId" id="CatId">
<option selected="" value="all">All Category</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_category WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label"> Brand</label>
 <select class="form-control" name="BrandId" id="BrandId">
<option selected="" value="all">All Brand</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_sub_category WHERE CatId='".$_REQUEST["CatId"]."' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BrandId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label"> Model</label>
 <select class="select2-demo form-control" name="ModelNo" id="ModelNo">
<option selected="" value="all">All Model</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_products";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["ModelNo"] == $result['ModelNo']) {?> selected <?php } ?> value="<?php echo $result['ModelNo'];?>">
    <?php echo $result['ModelNo']; ?></option>
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
                <th>Quantity</th>
                <th>Company</th>
                <th>Category</th>
                <th>Model</th>
                 <th>Series</th>
                <th>Date</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tc.Name AS Category,tsc.Name AS Brand FROM tbl_stocks ts 
                    LEFT JOIN tbl_category tc ON tc.id=ts.CatId 
                    LEFT JOIN tbl_sub_category tsc ON tsc.id=ts.BrandId WHERE ts.Status=1";
            if($_POST['CatId']){
                $CatId = $_POST['CatId'];
                if($CatId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.CatId='$CatId'";
                }
            }
            if($_POST['BrandId']){
                $BrandId = $_POST['BrandId'];
                if($BrandId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.BrandId='$BrandId'";
                }
            }
            if($_POST['ModelNo']){
                $ModelNo = $_POST['ModelNo'];
                if($ModelNo == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.ModelNo='$ModelNo'";
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
            $sql.=" GROUP BY ts.ModelNo ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               $sql2 = "SELECT SUM(Qty) As Qty FROM tbl_stocks WHERE ModelNo='".$row['ModelNo']."' AND BuyStatus=0";
               $row2 = getRecord($sql2);
               if($row2['Qty'] == ''){
                    $Qty = 0;
               }
               else{
                    $Qty = $row2['Qty'];
               }

               $sql3 = "SELECT GROUP_CONCAT(ProductNo) AS ProductNo FROM `tbl_stocks` WHERE ModelNo='".$row['ModelNo']."'";
               $row3 = getRecord($sql3);
               if($row3['ProductNo'] != ''){
                    $ProductNo = $row3['ProductNo'];
               }
               else{
                    $ProductNo = "";
               }
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                 <td><?php echo $Qty;?></td>
                <td><?php echo $row['Category']; ?></td>
               
              <td><?php echo $row['Brand']; ?></td>
              <td><?php echo $row['ModelNo']; ?></td>
              <td><?php echo $ProductNo; ?></td>
         <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
                <!--   <td><a href="javascript:void(0)" onclick="availableSeries(<?php echo $row['ModelNo']; ?>)" class="badge badge-pill badge-primary">Show</a></td> -->
           
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
</div>
</div>
</div>


<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Available 
<span class="font-weight-light">Series</span>
</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
</div>
<div class="modal-body" id="showdata">


 

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
 function availableSeries(modelno){
    var action = "availableSeries";
            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    modelno: modelno
                },
                success: function(data) {

                    $('#modals-default').modal('show');
                    $('#showdata').html(data);
                  
                }
            });
 }
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });

    $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });

        });

});
</script>
</body>
</html>
