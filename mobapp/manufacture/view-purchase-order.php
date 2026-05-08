<?php session_start();
require_once '../config.php';
require_once '../auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity'])));

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
    //echo $_GET['city_id'];
    if($_GET['city_id']==0 || $_GET['city_id']==''){
    $city_id = $row11['CityId'];  
    
}
else{
  $city_id = $_REQUEST['city_id'];
}
//echo $city_id;
/* $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$UserId'";
    $conn->query($sql);*/
 ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
   <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../img/favicon180.png" sizes="180x180">
    <link rel="icon" href="../img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="../vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet" id="style">
     <?php include_once 'header_script.php'; ?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once '../sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once '../top_header.php'; ?>
    
   
    
        <div class="main-container">
           
            
           
<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_purchase_order WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_purchase_order_products WHERE SellId = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_general_ledger WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);
  /* $sql11 = "DELETE FROM tbl_stocks WHERE SellId = '$id' AND SellType='Purchase'";
  $conn->query($sql11);*/
  ?>
    <script type="text/javascript">
     // alert("Deleted Successfully!");
      window.location.href="view-purchase-order.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Purchase Order
  
<span style="float: right;">
<a href="add-purchase-order.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> New</a></span>

</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  


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
                <th>InvoiceNo</th>
                <th>Order Track</th>
                <th>Branch</th> 
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
             
               
                <th>Action</th>
            
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.*,tb.Name As Branch,tu.Fname As CompanyName FROM tbl_purchase_order ts LEFT JOIN tbl_branch tb ON ts.BranchId=tb.id
                     LEFT JOIN tbl_users tu ON ts.CompId=tu.id WHERE ts.Status=1 AND ts.CustId='$user_id'";
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
                // $sql2 = "SELECT SUM(PaidAmt) AS PaidAmt FROM tbl_general_ledger WHERE SellId='".$row['id']."' AND Type='PR'";
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
                <td><?php echo $row['InvoiceNo']; ?></td>
                <td><a href="take-po-action.php?id=<?php echo $row['id']; ?>" target="_blank" class="badge badge-pill badge-secondary"><?php echo $Status;?></a></td>
 <td><?php echo $row['Branch']; ?></td>
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
            
          
         
            <td>
                 
              <a href="edit-purchase-order.php?id=<?php echo $row['id']; ?>" ><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;
              
              <a onClick="return confirm('Are you sure you want delete this record?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>&nbsp;&nbsp;
             
             
            </td>
       
              
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
    </main>

    <?php include_once '../footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
   <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="../js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="../vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="../js/main.js"></script>
    <script src="../js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="../js/app.js"></script>
     <?php include_once 'footer_script.php'; ?>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
      <script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
    
</body>

</html>
