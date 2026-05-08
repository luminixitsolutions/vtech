<?php 
session_start();
require_once '../config.php';
require_once '../database.php';
include_once 'class.phpmailer.php';
include_once 'class.smtp.php';
$UserEmail = $_SESSION['User']['EmailId'];
//echo "<pre>";
//print_r($_SESSION["hall_item"]);?>
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
        <h1><a href="index.php">Daily Door Step</a></h1>
       
    </div>
  <h3 style="color:#6da552">Thank You, Payment succus!!</h3>
<?php
include 'src/instamojo.php';
$api = new Instamojo\Instamojo('e557cb044f338076704ea0db8bba6c83', 'f701fe7b9837ad8d9bdb89719b13c89e','https://www.instamojo.com/api/1.1/');
//$api = new Instamojo\Instamojo('test_8cf378bc6919eb2a99d656f1f41', 'test_822e55c31c0f248e9ac5e39c149','https://test.instamojo.com/api/1.1/');
$payid = $_GET["payment_request_id"];
try {
    $response = $api->paymentRequestStatus($payid);
    $email = $response['email'];
    $user_id = $_GET["userid"];
    $UserId = $_GET["userid"];
    $PkgId = $_GET["PkgId"];
    $OrderDate = date('Y-m-d');
    $OrderTime = date('h:i a');
    
     $sql = "SELECT * FROM tbl_packages WHERE id='$PkgId'";
    $row = getRecord($sql);
    $Duration = $row['Duration'];
    $PkgName = $row['Name'];
    if($row['Period'] == '1'){
      $Period = "month";
    }
    else{
      $Period = "years";
    }
    
    $payment_id = $response['payments'][0]['payment_id'];
      $payment_status = $response['payments'][0]['status'];
      $amount = $response['payments'][0]['amount'];
      $buyer_name = $response['payments'][0]['buyer_name'];
      $buyer_phone = $response['payments'][0]['buyer_phone'];
      $buyer_email = $response['payments'][0]['buyer_email'];
      $instrument_type = $response['payments'][0]['instrument_type'];
      $billing_instrument = $response['payments'][0]['billing_instrument'];
      $created_at = $response['payments'][0]['created_at'];
      $PkgDate = date('Y-m-d');
      $CreatedTime = date('h:i a');
      $valid_period = "+".$Duration." ".$Period;
      $PkgExp = date('Y-m-d', strtotime($valid_period));
      
      $sql3 = "SELECT * FROM tbl_payment_details WHERE payment_id='$payment_id'";
      $row = getRecord($sql3);
      $oldpayid = $row['payment_id'];
      if($oldpayid == $payment_id){}
        else{
      $sql = "INSERT INTO tbl_payment_details SET UserId='$UserId',PkgId='$PkgId',payment_id='$payment_id',payment_status='$payment_status',amount='$amount',buyer_name='$buyer_name',buyer_phone='$buyer_phone',buyer_email='$buyer_email',instrument_type='$instrument_type',billing_instrument='$billing_instrument',created_at='$created_at'";
      $conn->query($sql);
      $sql2 = "UPDATE customers SET PackageId='$PkgId',PkgAmount='$amount',PkgDate='$PkgDate',Validity='$PkgExp',PackageStatus='1',ModifiedDate='$PkgDate' WHERE id='$UserId'";
       $conn->query($sql2);
       
        }
        ?>
<script type="text/javascript">
		setTimeout(function () { 
swal({
  title: "Thank you for Subscribing.",
  text: "",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
    //window.location.href = "../customer/dashboard.php";
    window.location.href = "../profile.php";
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