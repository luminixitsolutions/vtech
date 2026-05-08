<?php 
session_start();
require_once '../config.php';
include_once 'class.phpmailer.php';
include_once 'class.smtp.php';
$UserEmail = $_SESSION['User']['EmailId'];
//echo "<pre>";
//print_r($_SESSION["cart_item"]);?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
  <title>Thank You, Mojo</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  </head>
  <body>
    <div class="container">
  <div class="page-header">
        <h1><a href="index.php">Instamojo Payment</a></h1>
        <p class="lead">A test payment integration for instamojo paypemnt gateway. Written in PHP</p>
    </div>
  <h3 style="color:#6da552">Thank You, Payment succus!!</h3>
<?php
include 'src/instamojo.php';
$api = new Instamojo\Instamojo('e557cb044f338076704ea0db8bba6c83', 'f701fe7b9837ad8d9bdb89719b13c89e','https://www.instamojo.com/api/1.1/');
//$api = new Instamojo\Instamojo('test_8cf378bc6919eb2a99d656f1f41', 'test_822e55c31c0f248e9ac5e39c149','https://test.instamojo.com/api/1.1/');
$payid = $_GET["payment_request_id"];
try {
    $response = $api->paymentRequestStatus($payid);
  /*echo "<h4>Payment ID: " . $response['payments'][0]['payment_id'] . "</h4>" ;
    echo "<h4>Post ID: " . $response['payments'][0]['post_id'] . "</h4>" ;
    echo "<h4>Payment Name: " . $response['payments'][0]['buyer_name'] . "</h4>" ;
    echo "<h4>Payment Email: " . $response['payments'][0]['email'] . "</h4>" ;*/
    $email = $response['email'];
    $user_id = $_GET["userid"];
    $addid = $_GET["addid"];
    $OrderDate = date('Y-m-d');
    $OrderTime = date('h:i a');
    $ShippingCharge = $_GET['ship_charge'];
    $oid = $_GET['oid'];
    $OrderNo = $_GET['OrderNo'];
     

foreach ($_SESSION["cart_item"] as $product){
      $Prod_code = $product["code"];
      $SizeId = $product["sizeid"];
      $RamId = $product["ramid"];
      $StorageId = $product["storageid"];
      $ColorId = $product["colorid"];
      $Price = $product['totalprice'];
      $Quantity = $product['quantity'];
      $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $ProductId = $row['id'];
      $total_price3 += ($product["price"]*$product["quantity"]);

      $sql = "INSERT INTO order_details SET OrderId='$oid',OrderNo='$OrderNo',UserId='$user_id',ProductId='$ProductId',ProductCode='$Prod_code',SizeId='$SizeId',RamId='$RamId',StorageId='$StorageId',ColorId='$ColorId',Quantity='$Quantity',Price='$Price',OrderDate='$OrderDate'";
      $conn->query($sql);

}
$sql2 = "UPDATE orders SET OrderTotal='$total_price3',Roll='7',Status='1' WHERE id='$oid'";
$conn->query($sql2);
if(isset($_SESSION['CouponCode'])){
$sql_11 = "INSERT INTO tbl_applied_code SET Oid='$oid',UserId='$user_id',Code='$CouponCode',Amount='$Discount',CreatedDate='$OrderDate'";
$conn->query($sql_11);
}
$sqlc_11 = "SELECT * FROM orders WHERE id='$oid'";
$row11 = getRecord($sqlc_11);
$Discount = $row11['Discount'];
$Promoprice = $row11['Promoprice'];
$TotAmt = $total_price3 - $Discount - $Promoprice;
include '../../incomissioncal.php';

$to = $UserEmail;
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
//include("incmailcontent.php");
//include("sendmailsmtp.php");    
unset($_SESSION['cart_item']);
unset($_SESSION['pincode']);
unset($_SESSION['Promocode']);
unset($_SESSION['Promoprice']);
unset($_SESSION['RightSph']);
unset($_SESSION['RightCyl']);
unset($_SESSION['RightAxis']);
unset($_SESSION['LeftSph']);
unset($_SESSION['LeftCyl']);
unset($_SESSION['LeftAxis']);
unset($_SESSION['File']);
unset($_SESSION['CouponCode']);
unset($_SESSION['CouponAmt']);
?>
       <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "for Placing Order.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
          window.location.href="../my-orders.php";
  }
}); });</script>
<?php
       
 }
catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}
?>

</div> <!-- /container -->
</body>
</html>