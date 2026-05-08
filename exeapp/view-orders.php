<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "E-Commerce";
$Page = "View-Orders";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Orders List</title>
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

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
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
if($_GET["action"]=="delete")
{
  $id = $_GET["oid"];
  $sql11 = "DELETE FROM orders WHERE id = '$id'";
  $conn->query($sql11);
    $sql12 = "DELETE FROM order_details WHERE OrderId = '$id'";
  $conn->query($sql12);
 
  
  ?>
    <script type="text/javascript">
      alert("Order Deleted Successfully!");
      window.location.href="view-orders.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Orders List</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
       <thead>
<tr>
<th>Order ID</th>
<th>Customer Name</th>
<th>Order Date</th>
<th>Payment Mode</th>
<!--<th>Delivery Method</th>-->
<th>Sub Price</th>
<th>Discount</th>
<th>Total Price</th>
<th>Payment Status</th>
<th>Paid Amt</th>
<th>Balance Amt</th>
<th>Payment Details</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php 
 $sql2 = "SELECT ord.*,c.Fname,c.Lname,os.Name As OrderStatus,pm.Name As Payment_Method FROM orders ord 
         LEFT JOIN payment_method pm ON pm.id=ord.PaymentMethod 
         LEFT JOIN order_status os ON os.id=ord.OrderProcess
         LEFT JOIN tbl_users c ON c.id=ord.UserId WHERE ord.Type='Cart'";
       
  $sql2.= " ORDER BY ord.srno DESC";
  //echo $sql2;
    $res2 = $conn->query($sql2);
    $row_cnt = mysqli_num_rows($res2);
    if($row_cnt > 0){
    while($row = $res2->fetch_assoc()){
      if($row['DeliveryMethod'] == 1){
          $DeliveryMethod = "Home Delivery";
        }
        else if($row['DeliveryMethod'] == 2){
          $DeliveryMethod = "Pickup By Store";
        }
        else{
          $DeliveryMethod = "-";
        }

        if($row['PaStatus'] == 1){
          $bgcolor = "background-color: #daf8da;";
        }
        else{
          $bgcolor = "background-color: #fff2f2;";
        }
     ?>
<tr style="<?php echo $bgcolor;?>">
  <td><?php echo $row['OrderNo']; ?></td>
<td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['OrderDate'])))."<br>".$row['OrderTime'];?></td>
<td><?php echo $row['Payment_Method']; ?></td>
<!--<td><?php echo $DeliveryMethod; ?></td>-->
<td>&#8377;<?php echo number_format($row["OrderTotal"],2); ?></td>
<td>&#8377;<?php echo number_format($row["Discount"],2); ?></td>
<td>&#8377;<?php echo number_format($row["OrderTotal"]-$row["Discount"],2); ?></td>
<td><?php if($row['PaymentStatus']=='1'){echo "<span style='color:green;'>Paid</span>";} else { echo "<span style='color:red;'>Not Paid</span>";} ?></td>
<td>&#8377;<?php echo number_format($row["PaidAmt"],2); ?></td>
<td>&#8377;<?php echo number_format($row["OrderTotal"]-$row["Discount"]-$row["PaidAmt"],2); ?></td>
<td><?php if($row['PaymentStatus']=='1'){?>
<strong>Payment Id : </strong><?php echo $row['PaymentId'];?><br>
<strong>Payment Receipt : </strong><a href="../uploads/<?php echo $row['PaymentReceipt'];?>" target="_blank">View Receipt</a>
<?php } ?></td>
<td><?php if($row['OrderProcess']=='2') {?>
<span class="badge badge-warning">In Progress</span>
<?php } else if($row['OrderProcess']=='3') {?>  
<span class="badge badge-danger">Canceled</span>
<?php } else if($row['OrderProcess']=='4') {?>  
<span class="badge badge-warning">Confirmed</span>
<?php } else if($row['OrderProcess']=='5') {?>  
<span class="badge badge-info">Dispatch</span>
<?php } else{?> 
<span class="badge badge-success">Delivered</span>
<?php } ?>
</td>
<td><div class="card-header-elements ml-auto">
<div class="btn-group">
<button type="button" class="btn btn-sm btn-default icon-btn borderless btn-round md-btn-flat dropdown-toggle hide-arrow" data-toggle="dropdown" aria-expanded="false"><i class="ion ion-ios-more"></i></button>
<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 25px; left: 25px;">
<a href="order-details.php?oid=<?php echo $row['id']; ?>" class="dropdown-item"><i class="lnr lnr-eye mr-2 text-muted"></i> &nbsp; Information</a>
<!-- <a href="javascript:" class="dropdown-item"><i class="feather icon-refresh-cw text-muted"></i> &nbsp; Order Status</a> -->
<a href="order-invoice.php?oid=<?php echo $row['id']; ?>" class="dropdown-item" target="_blank"><i class="ion ion-md-print mr-2 text-muted"></i> &nbsp; Print Invoice</a>
<!--<a href="refund-amount.php?oid=<?php echo $row['id']; ?>" class="dropdown-item" target="_blank"><i class="fas fa-money-bill-wave mr-2 text-muted"></i> &nbsp; Refund Amount</a>-->
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?oid=<?php echo $row['id']; ?>&action=delete" onClick="return confirm('Are you sure you want delete this Order?');" class="dropdown-item"><i class="feather icon-trash text-muted"></i> &nbsp; Remove</a>
</div>
</div>
</div></td>
</tr>
<?php }} ?>

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

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 
  $(document).ready(function() {
    $('#example').DataTable({
      "order": [[ 2, "desc" ]],
    });
});
</script>
</body>
</html>
