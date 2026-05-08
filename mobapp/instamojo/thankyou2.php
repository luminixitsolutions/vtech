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
        <p class="lead">Daily Doorstep</p>
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
    $pageval = $_GET['pageval'];
    $OrderDate = date('Y-m-d');
    $OrderTime = date('h:i a');
    $Narration = "Amount Added In Wallet";

        $payment_id = $response['payments'][0]['payment_id'];
      $payment_status = $response['payments'][0]['status'];
      $amount = $response['payments'][0]['amount'];
      $buyer_name = $response['payments'][0]['buyer_name'];
      $buyer_phone = $response['payments'][0]['buyer_phone'];
      $buyer_email = $response['payments'][0]['buyer_email'];
      $instrument_type = $response['payments'][0]['instrument_type'];
      $billing_instrument = $response['payments'][0]['billing_instrument'];
      $created_at = $response['payments'][0]['created_at'];

     $sql3 = "SELECT * FROM tbl_payment_details WHERE payment_id='$payment_id'";
      $row = getRecord($sql3);
      $oldpayid = $row['payment_id'];
      if($oldpayid == $payment_id){}
        else{
      $sql = "INSERT INTO tbl_payment_details SET UserId='$user_id',payment_id='$payment_id',payment_status='$payment_status',amount='$amount',buyer_name='$buyer_name',buyer_phone='$buyer_phone',buyer_email='$buyer_email',instrument_type='$instrument_type',billing_instrument='$billing_instrument',created_at='$created_at'";
      $conn->query($sql);
     $sql = "INSERT INTO wallet SET UserId='$user_id',Amount='$amount',Status='Cr',CreatedDate='$OrderDate',CreatedTime='$OrderTime',Narration='$Narration'";
      $conn->query($sql);
}
/*include("incmailcontent.php");
include("sendmailsmtp.php"); */   

?>
       <script type="text/javascript">
        setTimeout(function () { 
swal({
  title: "Thank you",
  text: "for Add Money In Your Wallet.",
  type: "success",
  confirmButtonText: "OK"
},
function(isConfirm){
  if (isConfirm) {
    <?php if($pageval == 'cart') {?>
          window.location.href="../place-order.php?addid=<?php echo $addid;?>&userid=<?php echo $user_id;?>";
    <?php } else{?>
    window.location.href="../add-money.php";
    <?php } ?>      
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