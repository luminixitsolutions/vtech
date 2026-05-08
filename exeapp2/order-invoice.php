<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
//include_once 'class.phpmailer.php';
//include_once 'class.smtp.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Orders";
$Page = "View-Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> |  Invoice</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<style type="text/css">
	@page {
    size: auto;
    margin: 0; 

}
@media print {
  body{
    -webkit-print-color-adjust: exact; /*chrome & webkit browsers*/
    color-adjust: exact; /*firefox & IE */
  } 
 #print_btn{
  display :  none;
 }
 #sidebar{
  display :  none;
 }
 #topheader{
  display :  none;
 }
 #footer{
  display :  none;
 }
}
</style>


<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">
<div id="sidebar">
<?php include_once 'ecommerce-sidebar.php'; ?>
</div>


<div id="topheader">

</div>
<?php 
     $OrderId = $_GET['oid'];
      $sql21 = "SELECT ord.*,ca.Fname,ca.Lname,ca.Address,ca.Pincode,ca.Phone,ca.EmailId,c.Name As Country,s.Name As State,ct.Name As City FROM orders ord 
      LEFT JOIN customer_address ca ON ca.id=ord.AddressId
      LEFT JOIN tbl_country c ON c.id=ca.CountryId
      LEFT JOIN tbl_state s ON s.id=ca.StateId
      LEFT JOIN tbl_city ct ON ct.id=ca.CityId
      WHERE ord.id='$OrderId'";
      $res21 = $conn->query($sql21);
      $row21 = $res21->fetch_assoc();
      $OrderNo = $row21['OrderNo'];
      $GrandTot = $row21['OrderTotal']+$row21['ShippingCharge'];
      $TotalPrice = number_format($GrandTot,2);
      $ConfirmTime = $row21['ConfirmTime'];
      $ConfirmDate = $row21['ConfirmDate'];
      $ShippingCharge = $row21['ShippingCharge'];

      $sql22 = "SELECT count(id) as cntid FROM order_details WHERE OrderId='$OrderId'";
      $res22 = $conn->query($sql22);
      $row22 = $res22->fetch_assoc();


function getAttrDetails2($id){
       global $conn;
      $sql3 = "SELECT * FROM attribute_value WHERE id = '$id'";
       $res3 = $conn->query($sql3);
       $row_cnt3 = mysqli_num_rows($res3);
       $row3 = $res3->fetch_assoc();
       if($row_cnt3 > 0){
          $Size = $row3['Name'];
       }
       return $Size;
  }
  ?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0" style="text-align: center;">Invoice</h4>
<div class="card">
<div class="card-body p-5">
<div class="row">
<div class="col-sm-6 pb-4">
<div class="media align-items-center mb-4">
<a href="dashboard.php" class="navbar-brand app-brand demo py-0 mr-4">
<span class="app-brand-logo demo">
<img src="logo.jpg" alt="" class="img-fluid"  style="height:50px;">
</span>
<span class="app-brand-text demo font-weight-bold text-dark ml-2"></span>
</a>
</div>
<div class="mb-1">Survey No. 124 B 1, Shivaji Park Housing Society, Near Chintamani Ganesh Temple, Walhekarwadi, Chinchwad, Pimpri Chinchwad, Haveli, Pune - 411033</div>
<div>+91 844 610 7890</div>
</div>
<div class="col-sm-6 text-right pb-4">
<h6 class="text-big text-large font-weight-bold mb-3">INVOICE <?php echo $row21['OrderNo']; ?></h6>
<div class="mb-1">Order Date:
<strong class="font-weight-semibold"><?php echo date("M d, Y", strtotime(str_replace('-', '/',$row21['OrderDate'])))." - ".$row21['OrderTime'];?></strong>
</div>
<!--<strong class="mr-2">Status:</strong>
<span class="text-big">
<?php if($row21['OrderProcess']=='2') {?>
<span class="badge badge-warning">In Progress</span>
<?php } else if($row21['OrderProcess']=='3') {?>	
<span class="badge badge-danger">Canceled</span>
<span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['CancelDate'])))." - ".$row21['CancelTime'];?></span>
<?php } else if($row21['OrderProcess']=='4'){?>	
  <span class="badge badge-warning">Order Confirmed</span>
  <span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['ConfirmDate'])))." - ".$row21['ConfirmTime'];?></span>
  <?php } else if($row21['OrderProcess']=='5'){?> 
  <span class="badge badge-info">Order Dispatch</span>
  <span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['DispatchDate'])))." - ".$row21['DispatchTime'];?></span>
<?php } else {?>
<span class="badge badge-success">Delivered</span>
<span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['DelieverDate'])))." - ".$row21['DelieverTime'];?></span>
<?php } ?>
</span>-->

<!-- 
<div>Due date:
<strong class="font-weight-semibold">May 12, 2015</strong>
</div> -->
</div>
</div>
<hr class="mb-4">
<div class="row">
<div class="col-sm-6 mb-4 col-7">
<div class="font-weight-bold mb-2">Invoice To:</div>
<div><?php echo $row21['Fname']." ".$row21['Lname']; ?><br>
<?php echo $row21['Phone']; ?><br>
<?php echo $row21['EmailId']; ?><br>
<?php echo $row21['Address']." - ".$row21['Pincode']; ?><br>
<?php echo $row21['City']; ?>, <?php echo $row21['State']; ?>, <?php echo $row21['Country']; ?></div>

</div>
<!-- <div class="col-sm-6 mb-4 col-5">
<div class="font-weight-bold mb-2">QR Code</div>
<div>
  <img src="../googlepay.jpg" style="width: 130px;height: 130px;">
</div>

</div> -->
<!-- <div class="col-sm-6 mb-4">
<div class="font-weight-bold mb-2">Payment Details:</div>
<table>
<tbody>
<tr>
<td class="pr-3">Total Due:</td>
<td>
<strong>$6,095.25</strong>
</td>
</tr>
<tr>
<td class="pr-3">Bank name:</td>
<td>Finance Bank</td>
</tr>
<tr>
<td class="pr-3">Country:</td>
<td>United States</td>
</tr>
<tr>
<td class="pr-3">City:</td>
<td>West New York, NJ 07093</td>
</tr>
<tr>
<td class="pr-3">Address:</td>
<td>23 Sussex Ave.</td>
</tr>
<tr>
<td class="pr-3">IBAN:</td>
<td>ETD85039283901259</td>
</tr>
<tr>
<td class="pr-3">SWIFT code:</td>
<td>AS4F1</td>
</tr>
</tbody>
</table>
</div> -->
</div>
<div class="table-responsive mb-4">
<table class="table m-0">
<thead>
<tr>
<th class="py-3">
Product Details
</th>
<th class="py-3">

</th>
<th class="py-3">
Quantity
</th>
<th class="py-3">
Total
</th>
</tr>
</thead>
<tbody>
	<?php 
        function getAttrDetails($id){
            global $conn;
            $sql3 = "SELECT * FROM attribute_value WHERE id = '$id'";
            $res3 = $conn->query($sql3);
           $row_cnt3 = mysqli_num_rows($res3);
            $row3 = $res3->fetch_assoc();
              if($row_cnt3 > 0){
                    $Size = $row3['Name'];
               }
           return $Size;
           }

            $OrderId = $_GET['oid'];
            $sql2 = "SELECT od.*,p.ProductName,p.Photo,p.CatId,p.id As pid FROM order_details od
                     LEFT JOIN products p ON p.id=od.ProductId 
                     WHERE od.OrderId='$OrderId' ORDER BY od.id ASC";
            $res2 = $conn->query($sql2);
            $row_cnt = mysqli_num_rows($res2);
            if($row_cnt > 0){
                  while($row = $res2->fetch_assoc()){
                      $GrandTotal += $row['Price'];
                      $TotalQty += $row['Quantity'];
                       $SizeId = $row['SizeId'];
                       $RamId = $row['RamId'];
                       $StorageId = $row['StorageId'];
                      $ColorId = $row['ColorId'];
                       $ProdId = $row["pid"];
 ?>	
<tr>
<td class="py-3">
<div class="media align-items-center">
<img src="../uploads/<?php echo $row['Photo'];?>" alt="" style="width: 40px;" class="d-block ui-w-40 ui-bordered mr-4" alt>
<div class="media-body">
<a href="javascript:void(0)" class="font-weight-semibold" style="color: #000;"><?php echo $row['ProductName']; ?></a>
<div class="text-muted">
<?php  if($SizeId=='0' && $RamId=='0' && $StorageId=='0' && $ColorId=='0'){} else{?>
<small>
 <?php if($ColorId!='0'){?>	
<span class="text-muted">Color:</span> <?php echo getAttrDetails($ColorId); ?> &nbsp;
 <?php } ?>
<?php if($SizeId!='0'){?>
<span class="text-muted">Size: </span> <?php echo getAttrDetails($SizeId); ?> &nbsp;
<?php } ?>
<?php if($StorageId!='0'){?>
<span class="text-muted">Storage: </span> <?php echo getAttrDetails($StorageId); ?> &nbsp;
<?php } ?>
<?php if($RamId!='0'){?>
<span class="text-muted">Ram: </span> <?php echo getAttrDetails($RamId); ?> &nbsp;
 <?php } ?>
</small>
<?php } ?>
</div>
</div>
</div>	

</td>
<td class="py-3">

</td> 
<td class="py-3">
<strong>x<?php echo $row['Quantity']; ?></strong>
</td>
 <td class="py-3">
<strong>&#8377;<?php echo number_format($row["Price"],2); ?></strong>
</td>
 
</tr>
<?php }} ?>

<tr>
<td colspan="3" class="text-right py-3">
<strong>Total Quantity:</strong><br>	
<strong>Subtotal:</strong>
<br> <strong>Shipping Charges:</strong>
<br>
<span class="d-block text-big mt-2"><strong>Total:</strong></span>
</td>
<td class="py-3">
x<?php echo $TotalQty; ?>
<br>	
&#8377;<?php echo number_format($GrandTotal,2); ?>
<br>
&#8377;<?php echo number_format($ShippingCharge,2); ?>
<br>
<strong class="d-block text-big mt-2">&#8377;<?php echo number_format($GrandTotal+
      $ShippingCharge,2); ?></strong>
</td>
</tr>
</tbody>
</table>
</div>
<!-- <div class="text-muted">
<strong>Note:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras laoreet, dolor id dapibus dapibus, neque mi tincidunt quam, quis congue ligula risus vitae magna. Curabitur ultrices nisi massa,
nec viverra lorem feugiat sed.
Mauris non porttitor nunc. Integer eu orci in magna auctor vestibulum.
</div> -->
</div>
<div class="card-footer text-right">
<a href="#" class="btn btn-default" id="print_btn" onClick="window.print()"><i class="ion ion-md-print"></i>&nbsp; Print</a>
<!-- <button type="button" class="btn btn-primary ml-2"><i class="ion ion-ios-paper-plane"></i>&nbsp; Send</button> -->
</div>
</div>
</div>

<div id="footer">
<?php include_once 'footer.php'; ?>
</div>
</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/sidenav.js"></script>

<script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>
</body>
</html>
