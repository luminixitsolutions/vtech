<?php
session_start();
include_once '../config.php';

/**
 * Time-of-day to seconds (supports HH:MM, HH:MM:SS, MySQL TIME) for reliable comparison.
 */
function attendance_time_to_seconds($time) {
    if ($time === null) {
        return null;
    }
    $t = trim((string) $time);
    if ($t === '') {
        return null;
    }
    if (strlen($t) === 5) {
        $t .= ':00';
    }
    if (!preg_match('/^(\d{1,2}):(\d{2}):(\d{2})$/', $t, $m)) {
        return null;
    }
    $h = (int) $m[1];
    $i = (int) $m[2];
    $s = (int) $m[3];
    return $h * 3600 + $i * 60 + $s;
}

$user_id = $_SESSION['User']['id'];
$sql = "SELECT Lattitude,Longitude,PerDaySalary,InTime,OutTime FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql);
$Latitude = $row['Lattitude'];
$Longitude = $row['Longitude'];
$PerDaySalary = $row['PerDaySalary'];
$InTime = $row['InTime'];
$OutTime = $row['OutTime'];
if($_POST['action'] == 'takeAttendance'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    // Work schedule and salary for the employee who is clocking in (defensive if id ever differs from session)
    $sqlEmp = "SELECT Lattitude,Longitude,PerDaySalary,InTime,OutTime FROM tbl_users WHERE id='$userid'";
    $rowEmp = getRecord($sqlEmp);
    if ($rowEmp) {
        $Latitude = $rowEmp['Lattitude'];
        $Longitude = $rowEmp['Longitude'];
        $PerDaySalary = $rowEmp['PerDaySalary'];
        $InTime = $rowEmp['InTime'];
        $OutTime = $rowEmp['OutTime'];
    }
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];

    $randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}

    $CreatedTime = date('H:i:s');
$endTime = strtotime("+15 minutes", strtotime($CreatedTime));
$fifteenmin_time = date('H:i:s', $endTime);

    $inSec = attendance_time_to_seconds($InTime);
    $createdSec = attendance_time_to_seconds($CreatedTime);
    if ($inSec !== null && $createdSec !== null && $createdSec > $inSec) {
        $Latemark = 1;
    } else {
        $Latemark = 0;
    }
        
        if($CreatedTime > '14:00:00'){
            $HalfDay = 1;
        }
        else{
            $HalfDay = 0;
        }
        
    $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
    $rncnt = getRow($sql);
    
    $sql2 = "SELECT * FROM tbl_crop_image WHERE UserId='$userid' AND SrNo=1";
    $row2 = getRecord($sql2);
    $Image = $row2['Image'];
    if($rncnt > 0){
        $sql2 = "UPDATE tbl_attendance SET Salary='$PerDaySalary',Status='$Status',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=1,Latitude='$Latitude',Longitude='$Longitude' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_attendance SET Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=1,Latitude='$Latitude',Longitude='$Longitude'";
        $conn->query($sql2);
    }
    
    $sql = "DELETE FROM tbl_crop_image WHERE UserId='$userid'";
    $conn->query($sql);
    echo 1;
}


if($_POST['action'] == 'takeAttendance2'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = $_POST['SourceLat'];
    $SourceLong = $_POST['SourceLong'];
    $SourceAddress = $_POST['SourceAddress'];
    $ReportStatus = $_POST['ReportStatus'];

    $randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}

    $CreatedTime = date('H:i:s');
    if($CreatedTime > '10:10:00'){
            $Latemark = 1;
        }
        else{
            $Latemark = 0;
        }
        
        if($CreatedTime > '14:00:00'){
            $HalfDay = 1;
        }
        else{
            $HalfDay = 0;
        }
        
    $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
    $rncnt = getRow($sql);
    
    $sql2 = "SELECT * FROM tbl_crop_image WHERE UserId='$userid' AND SrNo=2";
    $row2 = getRecord($sql2);
    $Image = $row2['Image'];
    if($rncnt > 0){
        $sql2 = "UPDATE tbl_attendance SET Salary='$PerDaySalary',Status='$Status',Latitude='$Latitude',Longitude='$Longitude',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=2,ReportStatus='$ReportStatus' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_attendance SET Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Latitude='$Latitude',Longitude='$Longitude',Address='$SourceAddress',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=2,ReportStatus='$ReportStatus'";
        $conn->query($sql2);
    }
    
    $sql = "DELETE FROM tbl_crop_image WHERE UserId='$userid'";
    $conn->query($sql);
    echo 1;
}


if($_POST['action'] == 'checkToday'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
     $sql = "SELECT * FROM tbl_attendance WHERE UserId='$userid' AND CreatedDate='$date'";
     $row = getRecord($sql);
     echo $row['Status'];
    
}
?>