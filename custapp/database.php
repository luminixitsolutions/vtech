<?php
error_reporting(0);
$con=mysqli_connect('localhost','vtechsolar_newcode','GRImfzV7Ub4K','vtechsolar_newcode');
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL; 
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAbM6L9mE:APA91bFtunZnQA2yK9_2AB8DAMYU-p2KkTz7mCC93UoU9Du6w8bWdr06QN7ZQeZueoH9UEoeDh2Es4Hbbhsh6N4BX5AwRCy82wp3GIL1Z6PNcm_egj5isFWxEZFVGyoKLyu_dpVIAYdG');
$SiteUrl = "https://dailydoorservices.com/shoppingbazaar/mobapp";
?>
