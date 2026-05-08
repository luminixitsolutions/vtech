<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once "database.php";
//require_once "../exe-database.php";
//require_once "../admin-database.php";
//$SiteUrl = "https://dailydoorservices.com/mobapp/";
//$AdminSiteUrl = "https://dailydoorservices.com/adminapp";
//$ExeSiteUrl = "https://dailydoorservices.com/exeapp";
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Orders";
$Page = "View-Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> |  Order Details</title>
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

<?php include_once 'ecommerce-sidebar.php'; ?>






 <?php 
     $OrderId = $_GET['oid'];
      $sql21 = "SELECT ord.*,ca.Fname,ca.Lname,ca.Address,ca.Pincode,ca.Phone,ca.EmailId,c.Name As Country,s.Name As State,ct.Name As City,a.Name As Area,v.Fname As VedFname,v.Lname As VedLname,v.Address As VedAddress,v.Pincode As VedPincode,v.Phone As VedPhone,v.EmailId As VedEmailId FROM orders ord 
      LEFT JOIN customer_address ca ON ca.id=ord.AddressId 
      LEFT JOIN tbl_users v ON v.id=ord.VedId 
      LEFT JOIN tbl_country c ON c.id=ca.CountryId 
      LEFT JOIN tbl_state s ON s.id=ca.StateId 
      LEFT JOIN tbl_city ct ON ct.id=ca.CityId 
      LEFT JOIN tbl_area a ON a.id=ca.AreaId 
      WHERE ord.id='$OrderId'";

      $res21 = $conn->query($sql21);
      $row21 = $res21->fetch_assoc();
      $CustId = $row21['UserId'];
      $sql22 = "SELECT count(id) as cntid FROM order_details WHERE OrderId='$OrderId' AND Type='Cart'";
      $res22 = $conn->query($sql22);
      $row22 = $res22->fetch_assoc();


if(isset($_POST['confirm_submit'])){
  $oid = $_POST['oid'];
  $ordno = $_POST['ordno'];
  $ConfirmDate = $_POST['ConfirmDate'];
  $ConfirmTime = $_POST['ConfirmTime'];
  $EmpId = $_POST['EmpId'];
  $VedId = $_POST['VedId'];
  
  $sql2 = "SELECT Phone,EmailId,Fname,Lname FROM tbl_users WHERE id='$EmpId'";
  $res2 = $conn->query($sql2);
  $row2 = $res2->fetch_assoc();
  $EmpPhone = $row2['Phone'];
  $EmpEmail = $row2['EmailId'];
  $EmpName = $row21['Fname']." ".$row21['Lname'];
  
  $sql21 = "SELECT * FROM confirm_orders WHERE Ordid='$oid'";
      $res21 = $conn->query($sql21);
      $rncnt21 = mysqli_num_rows($res21);
      if($rncnt21 > 0){
         $sql2 = "UPDATE confirm_orders SET EmpId='$EmpId',VedId='$VedId',ConfirmDate='$ConfirmDate',ConfirmTime='$ConfirmTime' WHERE Ordid='$oid'";
        $conn->query($sql2);
      }
      else{
        $sql = "INSERT INTO confirm_orders SET Ordid='$oid',OrdNo='$ordno',EmpId='$EmpId',VedId='$VedId',Status='1',ConfirmDate='$ConfirmDate',ConfirmTime='$ConfirmTime'";
         $conn->query($sql);
      }
      $sql2 = "UPDATE orders SET OrderProcess='4',ConfirmDate='$ConfirmDate',ConfirmTime='$ConfirmTime' WHERE id='$oid'";
  $conn->query($sql2);

  $sqlc11 = "SELECT Tokens FROM tbl_users WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = "Your Order Confirm.";
  $body =  "Confirmed: Your Order has been confirmed.";
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }

  //executive notification
  $sqlc11_1 = "SELECT ExeTokens FROM tbl_users WHERE id='$EmpId'";
  $data_1=mysqli_query($con2,$sqlc11_1);
  while($rowc11_1=mysqli_fetch_array($data_1))
  {
  $title = $ordno." Order Confirm.";
  $body =  "Confirmed: ".$ordno." Order has been confirmed. Please Picked & Delivered Successfully!";
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$ExeSiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
  ?>
    <script type="text/javascript">
        alert("Order Confirmed Successfully!");
        window.location.href="order-details.php?oid=<?php echo $oid; ?>";
    </script>
 <?php }  

 if(isset($_POST['submit'])){
  $oid = $_POST['oid'];
  $DelieverDate = $_POST['DelieverDate'];
  $DelieverTime = $_POST['DelieverTime'];
  $sql = "UPDATE orders SET OrderProcess='1',DelieverDate='$DelieverDate',DelieverTime='$DelieverTime' WHERE id='$oid'";
  $conn->query($sql);
  $sqlc11 = "SELECT Tokens FROM tbl_users WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = "Your Order Delivered.";
  $body =  "Delivered: Your  package has been delivered.";
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
  ?>
  <script type="text/javascript">
    alert("Order Delivered Successfully!");
    window.location.href="order-details.php?oid=<?php echo $oid; ?>";
  </script>
 <?php }   

  if(isset($_POST['cancel_submit'])){
  $oid = $_POST['oid'];
  $Message = addslashes(trim($_POST['Message']));
  $CancelDate = $_POST['CancelDate'];
  $CancelTime = $_POST['CancelTime'];
  $sql = "UPDATE orders SET OrderProcess='3',CancelDate='$CancelDate',CancelTime='$CancelTime',CancelMessage='$Message' WHERE id='$oid'";
  $conn->query($sql);
  $sqlc11 = "SELECT Tokens FROM tbl_users WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = "Your Order Cancelled.";
  $body =  "Cancelled: Your Daily Doorstep order ".$OrderNo." has been cancelled.";
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
  ?>
  <script type="text/javascript">
    alert("Order Canceled Successfully!");
    window.location.href="order-details.php?oid=<?php echo $oid; ?>";
  </script>
 <?php
 }   

   if($_GET['action']=='dispatch'){
     $oid = $_GET['oid'];
    $OrderNo = $row21['OrderNo'];
      $DispatchDate = date('Y-m-d');
  $DispatchTime = date('h:i a');
/*  $sql = "UPDATE order_details SET EmpId='$EmpId',DispatchStatus='1',DispatchDate='$DispatchDate',DispatchTime='$DispatchTime',OrderProcess='5' WHERE OrderId='$oid' AND VedId='$user_id'";
  $conn->query($sql);*/
  $sql2 = "UPDATE orders SET OrderProcess='5',DispatchDate='$DispatchDate',DispatchTime='$DispatchTime' WHERE id='$oid'";
  $conn->query($sql2);
  $sqlc11 = "SELECT Tokens FROM tbl_users WHERE id='$CustId'";
  $data=mysqli_query($con,$sqlc11);
  while($rowc11=mysqli_fetch_array($data))
  {
  $title = "Order Dispatch.";
  $body =  "Your order ".$OrderNo." of ".$row22['cntid']." item has been dispatched";
  $reg_id = $rowc11[0];
  $registrationIds = array($reg_id);
  $url = "$SiteUrl/my-orders-details.php?oid=$oid";
  include '../incnotification.php';
  }
  ?>
  <script type="text/javascript">
    alert("Order Dispatch Successfully!");
    window.location.href="order-details.php?oid=<?php echo $oid; ?>";
  </script>
 <?php
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Orders details</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">E-commerce</li>
<li class="breadcrumb-item active">Orders details</li>
</ol>
</div>
<div class="card">

<div class="card-header with-elements">
<h6 class="card-header-title mb-0">Orders (<?php echo $row21['OrderNo']; ?>)</h6>
<div style="text-align: center;padding-left: 100px;"><label class="switcher switcher-success">
                                        <input type="checkbox" id="PaStatus" class="switcher-input" onchange="changeOrderStatus('<?php echo $_GET['oid'];?>')"  <?php if($row21['PaStatus'] == 1){?> checked <?php } ?> 
                                        >
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Approved/Pending</span>
                                    </label></div>
<div class="card-header-elements ml-auto">
<strong class="mr-2">Status:</strong>
<span class="text-big">
<?php if($row21['OrderProcess']=='2') {?>
<span class="badge badge-warning">In Progress</span>
<?php } else if($row21['OrderProcess']=='3') {?>  
<span class="badge badge-danger">Canceled</span>
<span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['CancelDate'])))." - ".$row21['CancelTime'];?></span>
<?php } else if($row21['OrderProcess']=='5'){?> 
  <span class="badge badge-success">Order Dispatch</span>
  <span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['DispatchDate'])))." - ".$row21['DispatchTime'];?></span>
<?php } else if($row21['OrderProcess']=='4'){?> 
  <span class="badge badge-warning">Order Confirmed</span>
  <span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['ConfirmDate'])))." - ".$row21['ConfirmTime'];?></span>
  <?php } else{?> 
<span class="badge badge-success">Delivered</span>
<span class="text-muted small ml-1"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['DelieverDate'])))." - ".$row21['DelieverTime'];?></span>
<?php } ?>
</span>
</div>
</div>

<?php 
$sql11 = "SELECT * FROM cancel_orders WHERE Ordid='$OrderId'";
$res11 = $conn->query($sql11);
$rncnt11 = mysqli_num_rows($res11);
if($rncnt11 > 0) {
?>
  <div class="card-body">
<h6 class="small font-weight-semibold">Order Cancel Details</h6>
<div class="table-responsive">
<table class="table table-bordered m-0" style="min-width:550px;">
  <thead>
    <tr  class="text-center">
       <td>Canceled By</td>
      <td>Canceled Reason</td>
      <td>Canceled Date</td>
      <td>Canceled Time</td>
    </tr>
  </thead>
<tbody>
  <?php while($row11 = $res11->fetch_assoc()){
    $CancelById = $row11['CancelById'];
    $CancelBy = $row11['CancelBy'];
    if($CancelBy == 'Customer'){
       $sql22 = "SELECT Fname,Lname FROM tbl_users WHERE id='$CancelById'";
        $row23 = getRecord($sql22);
    }
    else{
      $CancelBy = "Admin";
    }
  ?>
<tr  class="text-center">
<td class="align-middle p-4"><?php echo $row23['Fname']." ".$row23['Lname']." (".$row11['CancelBy'].")"; ?></td>
<td class="align-middle p-4" style="text-align: justify;"><?php echo $row11['Message']; ?></td>
<td class="align-middle p-4"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row11['CancelDate']))); ?></td>
<td class="align-middle p-4"><?php echo $row11['CancelTime']; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
<?php } ?>
<hr class="m-0">
<div class="card-body pb-1">
<div class="row">
<div class="col-md-3 mb-3">
<div class="text-muted small">Order Date</div>
<?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row21['OrderDate'])))." - ".$row21['OrderTime'];?>
</div>
<div class="col-md-1 mb-3">
<div class="text-muted small">Items</div>
<?php echo $row22['cntid']; ?>
</div>
<div class="col-md-2 mb-3">
<div class="text-muted small">Amount</div>
&#8377;<?php echo number_format($row21["OrderTotal"]-$row21["Discount"],2); ?>
</div>
<?php if($row21['OrderProcess']=='1' || $row21['OrderProcess']=='3' || $row21['OrderProcess']=='4' || $row21['OrderProcess']=='5') {} else{?>
<div class="col-md-2 mb-3">
<a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default" class="btn btn-info btn-sm">Send Order</a>
<?php include_once 'confirm_exe_modal.php'; ?>
</div>
<?php } if($row21['OrderProcess']=='1' || $row21['OrderProcess']=='3' || $row21['OrderProcess']=='5') {} else{?>
<!-- <div class="col-md-2 mb-3">
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?oid=<?php echo $OrderId; ?>&action=dispatch" onClick="return confirm('Are you sure you want to Dispatch this Order?');"  class="btn btn-success btn-sm"><i class="fa fa-truck" aria-hidden="true"></i> Order Dispatch</a>
</div> -->  
<?php } ?>

<?php if($row21['OrderProcess']=='1' || $row21['OrderProcess']=='3') {} 
else if($row21['OrderProcess']=='5'){?>
<!-- <div class="col-md-2 mb-3"> -->
<!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default" class="btn btn-info btn-sm"><i class="fa fa-gift" aria-hidden="true"></i> Order Delieverd</a>
 --><?php //include_once 'delieverd_modal.php'; ?>
<!-- </div> -->
<?php } if($row21['OrderProcess']=='3' || $row21['OrderProcess']=='1' || $row21['OrderProcess']=='5') {} else{?>
<div class="col-md-2 mb-3">
<a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default2" class="btn btn-danger btn-sm"> Order Cancel</a>
<?php include_once 'canceled_modal.php'; ?>
</div>

<?php } if($row21['OrderProcess']=='4') {?>
<div class="col-md-2 mb-3">
<a href="javascript:void(0)" data-toggle="modal" data-target="#modals-default3" class="btn btn-warning btn-sm">View Confirm Details</a>
<?php include_once 'confirmed_modal.php'; ?>
</div>
<?php } ?>
<div class="col-md-2 mb-3">
<a href="view-order-tracking2.php?oid=<?php echo $OrderId; ?>" target="_new"  class="btn btn-success btn-sm"><i class="fa fa-truck" aria-hidden="true"></i> Update Tracking</a>
</div>
</div>
</div>
<hr class="m-0">


<div class="card-body">
<h6 class="small font-weight-semibold">Billing/Shipping Info</h6>
<div class="row">
<div class="col-md-6 mb-3">
<div class="text-muted small">Name</div>
<?php echo $row21['Fname']." ".$row21['Lname']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Phone</div>
<?php echo $row21['Phone']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Email</div>
<?php echo $row21['EmailId']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Country</div>
<?php echo $row21['Country']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">State / Region</div>
<?php echo $row21['State']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">City</div>
<?php echo $row21['City']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Area</div>
<?php echo $row21['Area']; ?>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">ZIP Code</div>
<?php echo $row21['Pincode']; ?>
</div>
<div class="col-md-9 mb-3">
<div class="text-muted small">Address</div>
<?php echo $row21['Address']." - ".$row21['Pincode']; ?>
</div>
</div>
</div>

<hr class="m-0">


<div class="card-body">
<h6 class="small font-weight-semibold">Discount</h6>
<div class="row">
<div class="col-md-3 mb-3">
<div class="text-muted small">Sub Total</div>
<input type="text" id="SubTotal" value="<?php echo $row21['OrderTotal'];?>" class="form-control" readonly>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Discount</div>
<input type="text" id="Discount" value="<?php echo $row21['Discount'];?>" class="form-control" oninput="calDiscount()">
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">Total Amount</div>
<input type="text" id="TotalAmt" value="<?php echo $row21['OrderTotal']-$row21['Discount'];?>" class="form-control" readonly>
</div>
<div class="col-md-3 mb-3">
<div class="text-muted small">&nbsp;</div>
<button type="submit" class="btn btn-danger" id="submit" name="submit" onclick="updateDiscount('<?php echo $_GET['oid'];?>')">Update</button>
</div>

</div>
</div>

<hr class="m-0">



<?php 
$sql2 = "SELECT * FROM tbl_cancel_items WHERE oid='".$_GET['oid']."'";
$rncnt2 = getRow($sql2);
if($rncnt2 > 0){
  $row2 = getRecord($sql2);
?>
<div class="card-body">
<h6 class="small font-weight-semibold">Vendor Message</h6>
<div class="row">
<div class="col-md-10 mb-3">
<!-- <div class="text-muted small">Message</div> -->
<?php echo $row2['Message']; ?>
</div>
<div class="col-md-2 mb-3">
<a href="refund-amount.php?oid=<?php echo $_GET['oid']; ?>" target="_new"  class="btn btn-success btn-sm"><i class="fas fa-money-bill-wave mr-2"></i> Refund Amount</a>
</div>
</div>
</div>
<hr class="m-0">
<?php } ?>
<div class="card-body">
<h6 class="small font-weight-semibold">Items</h6>
<div class="table-responsive">
<table class="table table-bordered m-0" style="min-width:550px;">
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
                       if($row['ColorPhoto'] == '' && file_exists('../uploads/'.$row["Photo"])){
                          $ProdPhoto = $row['Photo'];
                       }
                       else if($row["ColorPhoto"] !='' && file_exists('../uploads/'.$row["ColorPhoto"])){
                          $ProdPhoto = $row['ColorPhoto'];
                      }
                       else{
                           $ProdPhoto = "no_image.jpg";
                     }

                    if($row['Repeats'] == 'daily'){
                        $Days = $row['Daily'];
                    }   
                    else if($row['Repeats'] == 'weekdays'){
                        $Days = $row['WeekDays'];
                    }   
                    else {
                        $Days = $row['Weekends'];
                    }
                    if($row['Type'] == 'Subscription'){
                        $ProdType = "Subscription";
                    }
                    else{
                        $ProdType = "";
                    }
 ?> 
<tr>
<td class="p-4">
<div class="media align-items-center">
<img src="../uploads/<?php echo $ProdPhoto;?>" alt="" style="width: 40px;" class="d-block ui-w-40 ui-bordered mr-4" alt>
<div class="media-body">
<a href="javascript:void(0)" class="d-block text-dark"><?php echo $row['ProductName']; ?> - <span style="color:red;"><?php echo $ProdType; ?></span></a>
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
<?php if($row['Type'] == 'Subscription'){?>
<br>
<span class="text-secondary small"><?php echo $row["Quantity"]; ?> Pkt - <?php echo $row['Repeats']; ?> (<?php echo $row['Recharge']; ?> Days)<br>(<?php echo $Days; ?>)</span>  
<?php } ?> 
</div>
</div>
</td>

<td class="align-middle p-4" style="width: 66px;">
x<?php echo $row['Quantity']; ?>
</td>

<td class="text-right font-weight-semibold align-middle p-4" style="width: 100px;">
&#8377;<?php echo number_format($row["Price"],2); ?>
</td>
</tr>
    <?php }} ?>
 <tr>
     <td colspan="1"><strong>Total</strong></td>
     <td class="align-middle p-4" style="width: 66px;"><strong>x<?php echo $TotalQty; ?></strong></td>
     <td class="text-right font-weight-semibold align-middle p-4" style="width: 100px;"><strong>&#8377;<?php echo number_format($GrandTotal,2); ?></strong></td>
</tr> 
<?php if($row21["Discount"]!='0.00'){?>
<tr style="color:red;">
     <td colspan="1"><strong>Discount (Coupon Applied)</strong></td>
     <td class="align-middle p-4" style="width: 66px;"><strong><?php echo $row21["CouponCode"];?></strong></td>
     <td class="text-right font-weight-semibold align-middle p-4" style="width: 100px;"><strong>-&#8377;<?php echo number_format($row21["Discount"],2); ?></strong></td>
</tr> 
<?php } ?>
<tr>
     <td colspan="1"><strong>Total Amount</strong></td>
     <td class="align-middle p-4" style="width: 66px;"><strong></strong></td>
     <td class="text-right font-weight-semibold align-middle p-4" style="width: 100px;"><strong>&#8377;<?php echo number_format($GrandTotal-$row21["Discount"],2); ?></strong></td>
</tr> 
</tbody>
</table>
</div>
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
  function changeOrderStatus(oid){
    if($('#PaStatus').prop('checked') == true) {
            $('#PaStatus').val(1);
            var val = 1;
        }
        else{
           $('#PaStatus').val(0);
           var val = 0;
            }
    var action = "changeOrderStatus";

            $.ajax({
                url: "ajax_files/ajax_shop_products.php",
                method: "POST",
                data: {
                    action: action,
                    oid: oid,
                    val:val
                },
                success: function(data) {
                    alert(data);
                  
                }
            });
  }
function calDiscount(){
  var SubTotal = $('#SubTotal').val();
  var Discount = $('#Discount').val();
  var TotalAmt = Number(SubTotal) - Number(Discount);
  $('#TotalAmt').val(parseFloat(TotalAmt).toFixed(2));
}
  function updateDiscount(oid){
    var action = "updateDiscount";
   //var SubTotal = $('#SubTotal').val();
  var Discount = $('#Discount').val();
 // var TotalAmt = $('#TotalAmt').val();
            $.ajax({
                url: "ajax_files/ajax_shop_products.php",
                method: "POST",
                data: {
                    action: action,
                    oid: oid,
                    
                    Discount:Discount
                },
                success: function(data) {
                    alert(data);
                    window.location.href="order-details.php?oid="+oid;
                }
            });
  }
     $(document).ready(function() {
  $('.bs-markdown').markdown({
    iconlibrary: 'fa',
    footer: '<div id="md-character-footer"></div><small id="md-character-counter" class="text-muted">350 character left</small>',

    onChange: function(e) {
      var contentLength = e.getContent().length;

      if (contentLength > 350) {
        $('#md-character-counter')
          .removeClass('text-muted')
          .addClass('text-danger')
          .html((contentLength - 350) + ' character surplus.');
      } else {
        $('#md-character-counter')
          .removeClass('text-danger')
          .addClass('text-muted')
          .html((350 - contentLength) + ' character left.');
      }
    },
  });
   });
</script>
</body>
</html>
