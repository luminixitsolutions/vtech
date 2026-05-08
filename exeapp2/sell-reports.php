<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Sell-Reports";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Sell Reports</title>
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









<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Sell Reports
</h4>
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

        <div class="form-group col-md-4">
<label class="form-label">User Name <span class="text-danger">*</span></label>
     <select class="select2-demo form-control" name="UserId" id="UserId" required>
      <option value="all" selected>All</option>
      <optgroup label="Vendor">
    <?php 

        $q = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=3";
        $r = $conn->query($q);
         while($rw = $r->fetch_assoc())
        {
        ?>
   <option <?php if($rw["id"] == $_POST['UserId']) { ?>selected="selected" <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['Phone'].")"; ?></option>
   <?php } ?>
   </optgroup>

   <optgroup label="Customer">
    <?php 

        $q = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=2";
        $r = $conn->query($q);
         while($rw = $r->fetch_assoc())
        {
        ?>
   <option <?php if($rw["id"] == $_POST['UserId']) { ?>selected="selected" <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']." ".$rw['Lname']." (".$rw['Phone'].")"; ?></option>
   <?php } ?>
   </optgroup>
</select>
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

<style>
table, td, th {  
  border: 1px solid #ddd;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 15px;
}
</style>
<?php  


if(isset($_POST['Search'])) {
?>
<div class="card">
<div class="card-datatable table-responsive" style="padding: 10px;">
<table id="example" class="table-striped table-bordered">
       <thead>
            <tr>
               <th>#</th>
               <th>Invoice No</th>
               <th>Invoice Date</th>
                <th>Vendor Name</th>
                <th>Contact No</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT * FROM tbl_vendor_orders WHERE Status=1 ";
            if($_POST['UserId']){
                $UserId = $_POST['UserId'];
                if($UserId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND VedId='$UserId'";
            }
            }
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND InvoiceDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND InvoiceDate<='$ToDate'";
            }
            $sql.=" ORDER BY InvoiceDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                /*if($row['CrDr']=='Cr'){
                    $CrQty+=$row['Qty'];
                }
                else{
                    $DrQty+=$row['Qty'];
                }*/
                $TotAmt+=$row['Amount'];
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <!-- <td><a href="javascript:void(0);" onclick="receiptPrint(<?php echo $row['id']; ?>)"><?php echo $row['InvoiceNo']; ?></a></td>-->
                 <td><?php echo $row['InvoiceNo']; ?></td>
              <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InvoiceDate']))); ?></td>
              <td><?php echo $row['VedName']; ?></td>
                <td><?php echo $row['CellNo']; ?></td>
                <td>&#8377;<?php echo $row['Amount']; ?></td>
                
           
         
              
            </tr>
           <?php $i++;} ?>
           <tr>
                <th><?php echo $i; ?></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total Sell Amount</th>
                <th>&#8377;<?php echo $TotAmt;?></th>
                
           </tr>
           
        </tbody>
    </table>
</div>
</div>
<?php } ?>
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
  function receiptPrint(id){
     setTimeout(function() {
        window.open(
            'receipt.php?id=' + id + '&roll=vendor', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800'
        );
    }, 1);
 }
	$(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
    });
});
</script>
</body>
</html>
