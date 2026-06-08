<?php
$api_key = '2617273432AE1B';
$contacts = $Phone;
$from = 'DDSSER';
$sms_text = urlencode('Dear '.$Fname.' For a seamless delivery experience, we will verify your address on tomorrow (Date) between 10:00 AM and 06:00 PM {#var#}(Date). Please allow you to inform your society and allow our  delivery executive inside inside for the same, if applicable. Have a great day! - Daily Door Services (DDSSER)');

$api_url = "http://jskbulksms.in/app/smsapi/index.php?key=".$api_key."&campaign=1&routeid=46&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text."&template_id=1307163593767864474";
//echo $api_url;
//Submit to server

$response = file_get_contents( $api_url);
//echo $response;

?>