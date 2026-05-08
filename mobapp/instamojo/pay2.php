<?php 
$purpose = "Payment";
$amount = $_GET["amount"];
$name = $_GET["name"];
$phone = $_GET["phone"];
$email = $_GET["email"];
$userid = $_GET["userid"];
$pageval = $_GET['pageval'];
$addid = $_GET['addid'];
include 'src/instamojo.php';
$api = new Instamojo\Instamojo('e557cb044f338076704ea0db8bba6c83', 'f701fe7b9837ad8d9bdb89719b13c89e','https://www.instamojo.com/api/1.1/');
//$api = new Instamojo\Instamojo('test_8cf378bc6919eb2a99d656f1f41', 'test_822e55c31c0f248e9ac5e39c149','https://test.instamojo.com/api/1.1/');
try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => $purpose,
        "amount" => $amount,
        "buyer_name" => $name,
        "phone" => $phone,
        "email" => $email,
        "send_email" => true,
        "send_sms" => true,
        'allow_repeated_payments' => false,
       "redirect_url" => "https://dailydoorservices.com/mobapp1/instamojo/thankyou2.php?userid=$userid&pageval=$pageval&addid=$addid",
        "webhook" => "https://dailydoorservices.com/mobapp1/instamojo/webhook.php"
        ));
   $pay_ulr = $response['longurl'];
    header("Location: $pay_ulr");
    exit();
}
catch (Exception $e) {
    print('Error: ' . $e->getMessage());
}     
 ?>