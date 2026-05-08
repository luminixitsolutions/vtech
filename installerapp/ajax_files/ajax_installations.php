<?php
session_start();
include_once '../config.php';
require_once ("../database.php");
if($_POST['action']=='saveinstallation'){
    $id = $_POST['id'];
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$InstallationDate = addslashes(trim($_POST['InstallationDate']));
$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$Money = addslashes(trim($_POST['Money']));
$Specify = addslashes(trim($_POST['Specify']));
$phone = $_POST['phone'];
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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
    $Photo2 = $_POST['OldPhoto2'];
}

// $Photo = getImage(1,$user_id);
// $Photo2 = getImage(2,$user_id);

$sql = "UPDATE tbl_users SET InstPhoto1='$Photo',InstPhoto2='$Photo2',InstallationDate='$InstallationDate',InstMoney='$Money',InstSpecify='$Specify',InstalledBy='$user_id',InstLattitude='$Lattitude',InstLongitude='$Longitude',InstOtpVerify=0 WHERE id='$id'";
$conn->query($sql);

$sql = "SELECT * FROM tbl_installations WHERE CustId='$id'";
$rncnt = getRow($sql);
if($rncnt > 0){
    $row = getRecord($sql);
    $id = $row['id'];
    $sql = "UPDATE tbl_installations SET CustId='$id',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',InstStatus='Installation',Status=1,Type='$Type',Photo13='$Photo2',Photo1='$Photo' WHERE id='$id'";
    $conn->query($sql);
    echo json_encode(array('id'=>$id,'phone'=>$phone,'InstId'=>$InstId));
}
else{
 $sql = "INSERT INTO tbl_installations SET CustId='$id',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',InstStatus='Installation',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Type='$Type',Photo13='$Photo2',Photo1='$Photo'";
    $conn->query($sql);
    $InstId = mysqli_insert_id($conn);
   
  $sql = "DELETE FROM tbl_crop_image WHERE UserId='$user_id'";
       $conn->query($sql);
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;


echo json_encode(array('id'=>$id,'phone'=>$phone,'InstId'=>$InstId));
}
}