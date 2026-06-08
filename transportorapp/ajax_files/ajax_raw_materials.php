<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$Name = addslashes(trim($_POST['Name']));
$Details = addslashes(trim($_POST['Details']));
$Price = addslashes(trim($_POST['Price']));
$Qty = addslashes(trim($_POST['Qty']));
$Status = $_POST['Status'];
$Roll = $_POST['Roll'];
$CreatedDate = date('Y-m-d');

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

if($id == ''){
$sql = "INSERT INTO tbl_raw_materials SET Name='$Name',Details='$Details',Price='$Price',Qty='$Qty',Status='$Status',Photo='$Photo',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
$conn->query($sql);
$RawId = mysqli_insert_id($conn);
$sql2 = "INSERT INTO tbl_raw_stock SET RawId='$RawId',Price='$Price',Qty='$Qty',CrDr='Cr',Status='$Status',CreatedDate='$CreatedDate',CreatedBy='$user_id',Type=1";
$conn->query($sql2);
echo 1;
}
else{
$sql = "UPDATE tbl_raw_materials SET Name='$Name',Details='$Details',Price='$Price',Qty='$Qty',Status='$Status',Photo='$Photo',ModifiedDate='$CreatedDate',ModifiedBy='$user_id' WHERE id='$id'";
$conn->query($sql);
$sql2 = "UPDATE tbl_raw_stock SET Price='$Price',Qty='$Qty',Status='$Status',ModifiedDate='$CreatedDate',ModifiedBy='$user_id' WHERE RawId='$id'";
$conn->query($sql2);
echo 1;
}

}

if($_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_raw_materials SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}
