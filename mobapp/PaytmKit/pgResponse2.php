<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
require_once '../config.php';
// following files need to be included
require_once("lib/config_paytm.php");
require_once("lib/encdec_paytm.php");

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
/*echo "<pre>";
print_r($_POST);*/
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		//echo "<b>Transaction status is success</b>" . "<br/>";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
		$user_id = $_GET["userid"];
    $addid = $_GET["addid"];
    $pageval = $_GET['pageval'];
    $PkgId = $_GET["PkgId"];
    $OrderDate = date('Y-m-d');
    $OrderTime = date('h:i a');
    $Narration = "Amount Added In Wallet";
    $OrderId  = $paramList['ORDERID'];
		$Mid  = $paramList['MID'];
		$TxnId  = $paramList['TXNID'];
		$TxnAmount  = $paramList['TXNAMOUNT'];
		$PaymentMode  = $paramList['PAYMENTMODE'];
		$Status  = $paramList['STATUS'];
		$GateWayName  = $paramList['GATEWAYNAME'];
		$BankTxnId  = $paramList['BANKTXNID'];
		$BankName  = $paramList['BANKNAME'];
		$TxnDate  = $paramList['TXNDATE'];
		
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

$PkgDate = date('Y-m-d');
      $CreatedTime = date('h:i a');
      $valid_period = "+".$Duration." ".$Period;
      $PkgExp = date('Y-m-d', strtotime($valid_period));
      
		$sql3 = "SELECT * FROM tbl_payment_details WHERE payment_id='$TxnId'";
      $row = getRecord($sql3);
      $oldpayid = $row['payment_id'];
      if($oldpayid == $TxnId){}
        else{
      $sql = "INSERT INTO tbl_payment_details SET UserId='$user_id',payment_id='$TxnId',payment_status='$payment_status',amount='$TxnAmount',buyer_name='$buyer_name',buyer_phone='$buyer_phone',buyer_email='$buyer_email',instrument_type='$PaymentMode',billing_instrument='$billing_instrument',created_at='$created_at',OrderId='$OrderId'";
      $conn->query($sql);
    /* $sql = "UPDATE customers SET PackageId='$PkgId',PkgAmount='$TxnAmount',PkgDate='$PkgDate',Validity='$PkgExp',PackageStatus='1',ModifiedDate='$PkgDate' WHERE id='$user_id'";
      $conn->query($sql);*/
        $sql = "INSERT INTO wallet SET UserId='$user_id',Amount='$TxnAmount',Status='Cr',CreatedDate='$OrderDate',CreatedTime='$OrderTime',Narration='$Narration'";
      $conn->query($sql);
}

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
    //window.location.href = "../customer/dashboard.php";
    window.location.href = "../pricing.php";
  }
}); });</script>
<?php
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/><a href='../add-money.php'>Retry Payment<a>";
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 
		foreach($_POST as $paramName => $paramValue) {
				//echo "<br/>" . $paramName . " = " . $paramValue;
		}
		    

	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>