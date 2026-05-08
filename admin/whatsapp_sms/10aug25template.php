<?php 
include_once '../../sms-function.php';
// customer 
$CustPhone = "9595454907";
$phone = "91".$CustPhone;
$template = "10aug25template";
$params = [];  // Name, Date, Location

if (function_exists('sendWhatsAppTemplate')) {
$languageCode = 'hi';
$response = sendWhatsAppTemplate($phone, $template, $params,$languageCode);
print_r($response);
}