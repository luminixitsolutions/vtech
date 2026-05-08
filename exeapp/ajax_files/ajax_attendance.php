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

/**
 * Distance between two WGS84 points in meters (Haversine).
 */
function attendance_haversine_meters($lat1, $lon1, $lat2, $lon2) {
    $lat1 = (float) $lat1;
    $lon1 = (float) $lon1;
    $lat2 = (float) $lat2;
    $lon2 = (float) $lon2;
    $R = 6371000.0;
    $toRad = M_PI / 180.0;
    $dLat = ($lat2 - $lat1) * $toRad;
    $dLon = ($lon2 - $lon1) * $toRad;
    $a = sin($dLat / 2) * sin($dLat / 2)
        + cos($lat1 * $toRad) * cos($lat2 * $toRad) * sin($dLon / 2) * sin($dLon / 2);
    return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

function attendance_office_geofence_radius_m() {
    return 100.0;
}

/**
 * tbl_users row plus OfficeEmployee from tbl_user2 (canonical storage for office flag).
 *
 * @return array|null
 */
function attendance_employee_row_for_geofence($conn, $userid) {
    $esc = mysqli_real_escape_string($conn, (string) $userid);
    $rowEmp = getRecord("SELECT Lattitude,Longitude,PerDaySalary,InTime,OutTime,CompId FROM tbl_users WHERE id='$esc' LIMIT 1");
    if (!$rowEmp) {
        return null;
    }
    $u2 = getRecord("SELECT OfficeEmployee FROM tbl_user2 WHERE id='$esc' LIMIT 1");
    if ($u2 && isset($u2['OfficeEmployee']) && (int) $u2['OfficeEmployee'] === 1) {
        $rowEmp['OfficeEmployee'] = 1;
    } else {
        $rowEmp['OfficeEmployee'] = 0;
    }
    return $rowEmp;
}

/**
 * Office staff: eligible only if saved profile coordinates are within radius of company office.
 * Uses employee Lattitude/Longitude in tbl_users vs company (Roll 10), not live GPS.
 *
 * @param mysqli $conn
 * @param array|null $rowEmp from attendance_employee_row_for_geofence()
 */
function attendance_office_within_geofence($conn, $rowEmp) {
    if (!$rowEmp) {
        return true;
    }
    $officeEmp = isset($rowEmp['OfficeEmployee']) ? (int) $rowEmp['OfficeEmployee'] : 0;
    $compId = isset($rowEmp['CompId']) ? (int) $rowEmp['CompId'] : 0;
    if ($officeEmp !== 1 || $compId <= 0) {
        return true;
    }
    $esc = mysqli_real_escape_string($conn, (string) $compId);
    $sqlCo = "SELECT Lattitude, Longitude FROM tbl_users WHERE id='$esc' AND Roll=10 LIMIT 1";
    $rowCo = getRecord($sqlCo);
    if (!$rowCo || !isset($rowCo['Lattitude'], $rowCo['Longitude'])
        || $rowCo['Lattitude'] === '' || $rowCo['Longitude'] === ''
        || !is_numeric($rowCo['Lattitude']) || !is_numeric($rowCo['Longitude'])) {
        return true;
    }
    $empLat = isset($rowEmp['Lattitude']) ? trim((string) $rowEmp['Lattitude']) : '';
    $empLng = isset($rowEmp['Longitude']) ? trim((string) $rowEmp['Longitude']) : '';
    if ($empLat === '' || $empLng === '' || !is_numeric($empLat) || !is_numeric($empLng)) {
        return false;
    }
    $d = attendance_haversine_meters(
        (float) $empLat,
        (float) $empLng,
        (float) $rowCo['Lattitude'],
        (float) $rowCo['Longitude']
    );
    return $d <= attendance_office_geofence_radius_m();
}

$user_id = $_SESSION['User']['id'];
$sql = "SELECT Lattitude,Longitude,PerDaySalary,InTime,OutTime,CompId FROM tbl_users WHERE id='$user_id'";
$row = getRecord($sql);
$Latitude = $row['Lattitude'];
$Longitude = $row['Longitude'];
$PerDaySalary = $row['PerDaySalary'];
$InTime = $row['InTime'];
$OutTime = $row['OutTime'];
if($_POST['action'] == 'takeAttendance'){
    $date = $_POST['date'];
    $userid = $_POST['userid'];
    $rowEmp = attendance_employee_row_for_geofence($conn, $userid);
    if ($rowEmp) {
        $Latitude = $rowEmp['Lattitude'];
        $Longitude = $rowEmp['Longitude'];
        $PerDaySalary = $rowEmp['PerDaySalary'];
        $InTime = $rowEmp['InTime'];
        $OutTime = $rowEmp['OutTime'];
    }
    //$Status = $_POST['status'];
    $Status = 1;
    $SourceLat = isset($_POST['SourceLat']) ? trim($_POST['SourceLat']) : '';
    $SourceLong = isset($_POST['SourceLong']) ? trim($_POST['SourceLong']) : '';
    $SourceAddress = isset($_POST['SourceAddress']) ? $_POST['SourceAddress'] : '';

    $srcLatF = is_numeric($SourceLat) ? (float) $SourceLat : null;
    $srcLngF = is_numeric($SourceLong) ? (float) $SourceLong : null;
    if (!attendance_office_within_geofence($conn, $rowEmp)) {
        echo '2';
        exit;
    }

    $saveLat = ($srcLatF !== null) ? mysqli_real_escape_string($conn, (string) $SourceLat) : mysqli_real_escape_string($conn, (string) $Latitude);
    $saveLng = ($srcLngF !== null) ? mysqli_real_escape_string($conn, (string) $SourceLong) : mysqli_real_escape_string($conn, (string) $Longitude);
    $SourceAddressEsc = mysqli_real_escape_string($conn, (string) $SourceAddress);

    $randno = rand(1, 100);
    $src = $_FILES['Photo']['tmp_name'];
    $fnm = substr($_FILES["Photo"]["name"], 0, strrpos($_FILES["Photo"]["name"], '.'));
    $fnm = str_replace(" ", "_", $fnm);
    $ext = substr($_FILES["Photo"]["name"], strpos($_FILES["Photo"]["name"], "."));
    $dest = '../../uploads/' . $randno . "_" . $fnm . $ext;
    $imagepath = $randno . "_" . $fnm . $ext;
    if (move_uploaded_file($src, $dest)) {
        $Photo = $imagepath;
    } else {
        $Photo = isset($_POST['OldPhoto']) ? $_POST['OldPhoto'] : '';
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
        $sql2 = "UPDATE tbl_attendance SET Salary='$PerDaySalary',Status='$Status',Address='$SourceAddressEsc',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=1,Latitude='$saveLat',Longitude='$saveLng' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=1";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_attendance SET Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Address='$SourceAddressEsc',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=1,Latitude='$saveLat',Longitude='$saveLng'";
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
    $SourceLat = isset($_POST['SourceLat']) ? trim($_POST['SourceLat']) : '';
    $SourceLong = isset($_POST['SourceLong']) ? trim($_POST['SourceLong']) : '';
    $SourceAddress = isset($_POST['SourceAddress']) ? $_POST['SourceAddress'] : '';
    $ReportStatus = $_POST['ReportStatus'];

    $rowEmp2 = attendance_employee_row_for_geofence($conn, $userid);
    if ($rowEmp2) {
        $Latitude = $rowEmp2['Lattitude'];
        $Longitude = $rowEmp2['Longitude'];
        $PerDaySalary = $rowEmp2['PerDaySalary'];
    }
    $srcLatF2 = is_numeric($SourceLat) ? (float) $SourceLat : null;
    $srcLngF2 = is_numeric($SourceLong) ? (float) $SourceLong : null;
    if (!attendance_office_within_geofence($conn, $rowEmp2)) {
        echo '2';
        exit;
    }
    $saveLat2 = ($srcLatF2 !== null) ? mysqli_real_escape_string($conn, (string) $SourceLat) : mysqli_real_escape_string($conn, (string) $Latitude);
    $saveLng2 = ($srcLngF2 !== null) ? mysqli_real_escape_string($conn, (string) $SourceLong) : mysqli_real_escape_string($conn, (string) $Longitude);
    $SourceAddressEsc2 = mysqli_real_escape_string($conn, (string) $SourceAddress);
    $ReportStatusEsc = mysqli_real_escape_string($conn, (string) $ReportStatus);

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
        $sql2 = "UPDATE tbl_attendance SET Salary='$PerDaySalary',Status='$Status',Latitude='$saveLat2',Longitude='$saveLng2',Address='$SourceAddressEsc2',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=2,ReportStatus='$ReportStatusEsc' WHERE UserId='$userid' AND CreatedDate='$date' AND Type=2";
        $conn->query($sql2);
    }
    else{
        
        
       $sql2 = "INSERT INTO tbl_attendance SET Salary='$PerDaySalary',Status='$Status',UserId='$userid',CreatedDate='$date',Latitude='$saveLat2',Longitude='$saveLng2',Address='$SourceAddressEsc2',CreatedTime='$CreatedTime',Latemark='$Latemark',HalfDay='$HalfDay',Photo='$Image',Type=2,ReportStatus='$ReportStatusEsc'";
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