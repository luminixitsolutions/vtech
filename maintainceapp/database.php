<?php
error_reporting(0);
$con=mysqli_connect('localhost','vtechsolar_newcode','GRImfzV7Ub4K','vtechsolar_newcode');
if (! $con) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL; 
    exit;
}
define( 'API_ACCESS_KEY', 'AAAAyDJMXW4:APA91bHj14yYkxDa0A8np5DTYLtBrxihu_kZLKUg_RuzQ7BMtb18wV5uIACvhS1UFcTLeIuNDhn-fLXGkbuguq7HxhqtH-GpN8XeCpgFYqrlGJxEPyPHQHBg4QjgK1UGufBsbNC3OqvV');
$SiteUrl = "https://dailydoorservices.com/shoppingbazaar/mobapp";
?>
