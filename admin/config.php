<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vtechsolar_newcode";

/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    // echo "Successfully Connected";
}
$Proj_Title = "VTECH";
$SiteUrl = "http://localhost/vtechnewcode/admin/";
$SiteUrl = rtrim($SiteUrl, '/');
/** Set true after SMS (incsmsapi.php) is purchased and configured; otherwise OTP is only logged for manual entry. */
$AdminLoginOtpSendSms = false;
/** If true, OTP verify page pre-fills digits (local testing only — set false on production). */
$AdminLoginOtpDevPrefill = true;
date_default_timezone_set("Asia/Kolkata");
/*$sms_username = "giradkatotp";
$sms_password = "ind123";
$sms_sender = "SAIAPP";
$site_url = "http://localhost/education";*/

function getList($sql){
  global $conn;
  $row3 = [];
  $res2 = $conn->query($sql);
  if ($res2) {
    while ($row2 = $res2->fetch_assoc()) {
      $row3[] = $row2;
    }
  }
  return $row3;
}

function getRecord($sql){
  global $conn;  
    $res2 = $conn->query($sql);
	$row2 = $res2->fetch_assoc();
    return $row2;
}

function getRow($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    if ($res2 === false) {
        return 0;
    }
    return mysqli_num_rows($res2);
}

function getFinYear(){
  if (date('m') <= 3) {//Upto June 2014-2015
    $financial_year = (date('Y')-1) . '-' . date('Y');
} else {//After June 2015-2016
    $financial_year = date('Y') . '-' . (date('Y') + 1);
}
return $financial_year;
}
?>