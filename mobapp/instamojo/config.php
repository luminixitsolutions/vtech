<?php
error_reporting(0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nearbystore2";

/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
// check connection 
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
    // echo "Successfully Connected";
}
$Proj_Title = "Education";
$SiteUrl = "";
date_default_timezone_set("Asia/Kolkata");
?>