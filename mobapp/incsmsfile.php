<?php
$api_key = '2617273432AE1B';
$contacts = $Phone;
$from = 'DDSSER';
$sms_text = urlencode('Dear '.$Name.' & '.$otp.' is the OTP for your login at dailydoorservices. In case you have not requested this, please contact us at our app {DDSSER}');

$api_url = "http://jskbulksms.in/app/smsapi/index.php?key=".$api_key."&campaign=1&routeid=46&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text."&template_id=1307163593832568542";
//echo $api_url;
//Submit to server

$response = file_get_contents( $api_url);
//echo $response;

?>