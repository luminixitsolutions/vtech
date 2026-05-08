<?php 
session_start();
include_once 'config.php';
$Phone=$_REQUEST['mobile'];
$latitude=$_REQUEST['lat'];
$longitude=$_REQUEST['lang'];


$sql = "UPDATE tbl_users SET Lattitude='$latitude',Longitude='$longitude' WHERE Phone='$Phone'";
$conn->query($sql);

$sql = "SELECT * FROM tbl_users WHERE Phone='$Phone'";
$row = getRecord($sql);
$UserId = $row['id'];
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$CreatedTime2 = date('H:i:s');
$sql21 = "SELECT MAX(CreatedTime2) AS MaxTime FROM tbl_daily_locations WHERE UserId='2' AND CreatedDate='2023-07-13'";
$row21 = getRecord($sql21);
$MaxTime = $row21['MaxTime'];
//$gettime = $CreatedTime2 - $MaxTime;
$to_time = strtotime($CreatedDate." ".$CreatedTime2);
$from_time = strtotime($CreatedDate." ".$MaxTime);
$gettime = round(abs($to_time - $from_time) / 60,2);
$sql2 = "SELECT * FROM tbl_daily_locations WHERE Lattitude='$latitude' AND Longitude='$longitude' AND UserId='$UserId'";
$rncnt2 = getRow($sql2);
if($rncnt2 > 0){}
else{

$sql = "INSERT INTO tbl_daily_locations SET UserId='$UserId',Lattitude='$latitude',Longitude='$longitude',Phone='$Phone',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CreatedTime2='$CreatedTime2'";
$conn->query($sql);

}

echo "location updated";

?>