<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$RawId = addslashes(trim($_POST['RawId']));
$Price = addslashes(trim($_POST['Price']));
$Qty = addslashes(trim($_POST['Qty']));
$CrDr = $_POST['CrDr'];
$CreatedDate = date('Y-m-d');

if($id == ''){
$sql2 = "INSERT INTO tbl_raw_stock SET RawId='$RawId',Price='$Price',Qty='$Qty',CrDr='$CrDr',Status='1',CreatedDate='$CreatedDate',CreatedBy='$user_id'";
$conn->query($sql2);
echo 1;
}
else{
$sql2 = "UPDATE tbl_raw_stock SET RawId='$RawId',Price='$Price',Qty='$Qty',CrDr='$CrDr',Status='1',ModifiedDate='$CreatedDate',ModifiedBy='$user_id' WHERE id='$id'";
$conn->query($sql2);
echo 1;
}

}

if($_POST['action'] == 'getRawDetails'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_raw_materials WHERE id='$id'";
    $row = getRecord($sql);
    echo json_encode($row);
}