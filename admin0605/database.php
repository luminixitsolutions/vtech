<?php
error_reporting(0);
$con=mysqli_connect('localhost','vtechsolar_newcode','GRImfzV7Ub4K','vtechsolar_newcode');
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAYPJtH-Y:APA91bE3MC3IK8NTw0fUzYZZJwAJcJCQiFoMq0681SFRufXcElQVhJgiLi9SCsmih4DThmtxBnoZC0BP_4Vz_do2RHGpiqFD7RO8ilqt5d-yhxu8w38N6pYkZFXebAohvR9AZ8XHD0hX');
$SiteUrl = "https://dailydoorservices.com/shoppingbazaar/mobapp";
?>
