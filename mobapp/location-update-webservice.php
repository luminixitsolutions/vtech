<?php 
session_start();
include_once 'config.php';
$id=$_REQUEST['id'];
$latitude=$_REQUEST['latitude'];
$longitude=$_REQUEST['longitude'];

if($id != ''){
$sql = "UPDATE customers SET latitude='$latitude',longitude='$longitude' WHERE id='$id'";
$conn->query($sql);
echo "location updated";
}
else{
 //echo "0 Row affected";   
}
?>