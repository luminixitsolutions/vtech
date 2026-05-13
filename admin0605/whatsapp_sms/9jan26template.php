<?php 
include_once '../../sms-function.php';
// customer 
$CustPhone = "9595454907";
$phone = "91".$CustPhone;
$template = "9jan26template";
$params = [];  // Name, Date, Location

if (function_exists('sendWhatsAppTemplate')) {
$languageCode = 'mr';
$response = sendWhatsAppTemplate($phone, $template, $params,$languageCode);
print_r($response);
}