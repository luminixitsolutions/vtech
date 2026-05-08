<?php 
session_start();
include_once '../config.php';
if($_POST['action'] == 'Login'){
$username = trim($_POST['Username']);
$Roll = $_POST['Roll'];
$query = "SELECT * FROM tbl_users WHERE Phone = '$username'";
    $result = $conn->query($query);
     $rncnt = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $Phone = $row['Phone'];
    if($rncnt > 0){
        $otp = rand(1000,9999);
  $_SESSION['otp'] = $otp;
        //$_SESSION['Admin'] = $row;
        $_SESSION['Roll'] = $row['Roll'];
        echo json_encode(array('Status'=>1,'Phone'=>$Phone));
    }
    else{
        unset($_SESSION['Admin']);
        unset($_SESSION['Roll']);
        echo json_encode(array('Status'=>0));
    } 
}


if($_POST['action'] == 'VerifyOtp'){
    $Phone = addslashes(trim($_POST['Phone']));
$YourOtp = addslashes(trim($_POST['YourOtp']));
$GetOtp = addslashes(trim($_POST['GetOtp']));
if($YourOtp == $GetOtp){
$query = "SELECT * FROM tbl_users WHERE Phone = '$Phone' AND Status=1";
$rncnt = getRow($query);
if($rncnt > 0){
$row = getRecord($query);
$_SESSION['Admin'] = $row;
$_SESSION['Roll'] = $row['Roll'];
$uid = $row['id'];
unset($_SESSION['otp']);
echo json_encode(array('Status'=>1,'Username'=>$Phone,'uid'=>$uid));
}
}
else{
echo json_encode(array('Status'=>0));   
}
}
?>