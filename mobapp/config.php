<?php
error_reporting(0);
$servername = "localhost";
$username = "vtechsolar_newcode";
$password = "GRImfzV7Ub4K";
$dbname = "vtechsolar_newcode";

/// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if($conn->connect_error) {
    die("connection failed : " . $conn->connect_error);
} else {
     //echo "Successfully Connected";
}
$Proj_Title = "VTECH";
$SiteUrl = "https://vtechsolar.in/vtechnewcode/mobapp/";
$Uploadurl = "https://vtechsolar.in/";
date_default_timezone_set("Asia/Kolkata");

function getList($sql){
  global $conn;  
    $res2 = $conn->query($sql);
    while($row2 = $res2->fetch_assoc()){
        $row3[] = $row2;
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
	$row2 = mysqli_num_rows($res2);
    return $row2;
}
?>