<?php
session_start();
include_once 'config.php';
require_once ("database.php");
$user_id = $_SESSION['User']['id'];
$PkgId = $_REQUEST['PkgId'];
$sql = "SELECT * FROM tbl_packages WHERE id='$PkgId'";
    $row = getRecord($sql);
    $Duration = $row['Duration'];
    $PkgName = $row['Name'];
    $PkgAmount = $row['Amount'];
    if($row['Period'] == '1'){
      $Period = "month";
    }
    else{
      $Period = "years";
    }

$PkgDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$valid_period = "+".$Duration." ".$Period;
$PkgExp = date('Y-m-d', strtotime($valid_period));
$Narration = "Amount Used For Subscription";
      
$sql = "UPDATE customers SET PackageId='$PkgId',PkgAmount='$PkgAmount',PkgDate='$PkgDate',Validity='$PkgExp',PackageStatus='1',Member=1,ModifiedDate='$PkgDate' WHERE id='$user_id'";
      $conn->query($sql);
      
$sql = "INSERT INTO wallet SET UserId='$user_id',Amount='$PkgAmount',Status='Dr',CreatedDate='$PkgDate',CreatedTime='$CreatedTime',Narration='$Narration'";
$conn->query($sql);

echo "<script>window.location.href='profile.php';</script>";      
?>