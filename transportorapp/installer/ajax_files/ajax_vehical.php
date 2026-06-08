<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];
$sql = "SELECT Lattitude,Longitude,PerDaySalary FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql);
$Latitude = $row['Lattitude'];
$Longitude = $row['Longitude'];
$PerDaySalary = $row['PerDaySalary'];
if($_POST['action'] == 'saveVehicleEntry'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];

  $StartKm = $_POST['StartKm'];
  $EndKm = $_POST['EndKm'];

    $CreatedTime = date('H:i:s');
    
    $sql = "SELECT * FROM tbl_vehicle_details WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
    $rncnt = getRow($sql);
    
    $sql2 = "SELECT * FROM tbl_crop_image WHERE UserId='$userid' AND SrNo=1";
    $row2 = getRecord($sql2);
    $Image = $row2['Image'];
    if($rncnt > 0){
        $sql2 = "UPDATE tbl_vehicle_details SET StartKm='$StartKm',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Photo='$Image',Type=1 WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_vehicle_details SET StartKm='$StartKm',UserId='$userid',CreatedDate='$date',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Photo='$Image',Type=1";
        $conn->query($sql2);
    }
    
    $sql = "DELETE FROM tbl_crop_image WHERE UserId='$userid'";
    $conn->query($sql);
    echo 1;
}


if($_POST['action'] == 'saveEndVehicleEntry'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];

  $StartKm = $_POST['StartKm'];
  $EndKm = $_POST['EndKm'];

    $CreatedTime = date('H:i:s');
    
    $sql = "SELECT * FROM tbl_vehicle_details WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
    $rncnt = getRow($sql);
    
    $sql2 = "SELECT * FROM tbl_crop_image WHERE UserId='$userid' AND SrNo=2";
    $row2 = getRecord($sql2);
    $Image = $row2['Image'];
    if($rncnt > 0){
        $sql2 = "UPDATE tbl_vehicle_details SET EndKm='$EndKm',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Photo='$Image',Type=2 WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_vehicle_details SET EndKm='$EndKm',UserId='$userid',CreatedDate='$date',Latitude='$SourceLat',Longitude='$SourceLong',Address='$SourceAddress',CreatedTime='$CreatedTime',Photo='$Image',Type=2";
        $conn->query($sql2);
    }
    
    $sql = "DELETE FROM tbl_crop_image WHERE UserId='$userid'";
    $conn->query($sql);
    echo 1;
}


?>