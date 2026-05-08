<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if(isset($_POST['action']) && $_POST['action'] == 'Save'){
$id = isset($_POST['id']) ? $_POST['id'] : '';
$Fname = addslashes(trim($_POST['Fname'] ?? ''));
$Mname = addslashes(trim($_POST['Mname'] ?? ''));
$Lname = addslashes(trim($_POST['Lname'] ?? ''));
$Phone = $_POST['Phone'] ?? '';
$EmailId = $_POST['EmailId'] ?? '';
$Phone2 = $_POST['Phone2'] ?? '';
$Password = addslashes($_POST['Password'] ?? '');
$CountryId = addslashes(trim($_POST['CountryId'] ?? '0'));
$StateId = addslashes(trim($_POST['StateId'] ?? '0'));
$CityId = addslashes(trim($_POST['CityId'] ?? '0'));
$Address = addslashes(trim($_POST['Address'] ?? ''));
$GstNo = addslashes(trim($_POST['GstNo'] ?? ''));
$Pincode = trim($_POST['Pincode'] ?? '');
$Details = addslashes(trim($_POST['Details'] ?? ''));
$Status = $_POST['Status'] ?? '';
$CatId = $_POST['CatId'] ?? '0';
$Roll = $_POST['Roll'] ?? '10';

$AccountName = addslashes(trim($_POST['AccountName'] ?? ''));
$BankName = addslashes(trim($_POST['BankName'] ?? ''));
$AccountNo = addslashes(trim($_POST['AccountNo'] ?? ''));
$IfscCode = addslashes(trim($_POST['IfscCode'] ?? ''));
$Branch = addslashes(trim($_POST['Branch'] ?? ''));
$UpiNo = addslashes(trim($_POST['UpiNo'] ?? ''));


$PanNo = addslashes(trim($_POST['PanNo'] ?? ''));

$Lattitude = addslashes(trim($_POST['Lattitude'] ?? ''));
$Longitude = addslashes(trim($_POST['Longitude'] ?? ''));

$CreatedDate = date('Y-m-d');

$Photo = isset($_POST['OldPhoto']) ? $_POST['OldPhoto'] : '';
if (!empty($_FILES['Photo']['name']) && !empty($_FILES['Photo']['tmp_name']) && $_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$dot = strrpos($_FILES["Photo"]["name"], '.');
$fnm = ($dot !== false) ? substr($_FILES["Photo"]["name"], 0, $dot) : $_FILES["Photo"]["name"];
$fnm = str_replace(" ","_",$fnm);
$ext = ($dot !== false) ? substr($_FILES["Photo"]["name"], $dot) : '';
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
	$Photo = isset($_POST['OldPhoto']) ? $_POST['OldPhoto'] : '';
}
}

$Photo2 = isset($_POST['OldPhoto2']) ? $_POST['OldPhoto2'] : '';
if (!empty($_FILES['Photo2']['name']) && !empty($_FILES['Photo2']['tmp_name']) && $_FILES['Photo2']['error'] === UPLOAD_ERR_OK) {
$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$dot2 = strrpos($_FILES["Photo2"]["name"], '.');
$fnm2 = ($dot2 !== false) ? substr($_FILES["Photo2"]["name"], 0, $dot2) : $_FILES["Photo2"]["name"];
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = ($dot2 !== false) ? substr($_FILES["Photo2"]["name"], $dot2) : '';
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
}
}


$Photo3 = isset($_POST['OldPhoto3']) ? $_POST['OldPhoto3'] : '';
if (!empty($_FILES['Photo3']['name']) && !empty($_FILES['Photo3']['tmp_name']) && $_FILES['Photo3']['error'] === UPLOAD_ERR_OK) {
$randno3 = rand(1,100);
$src3 = $_FILES['Photo3']['tmp_name'];
$dot3 = strrpos($_FILES["Photo3"]["name"], '.');
$fnm3 = ($dot3 !== false) ? substr($_FILES["Photo3"]["name"], 0, $dot3) : $_FILES["Photo3"]["name"];
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = ($dot3 !== false) ? substr($_FILES["Photo3"]["name"], $dot3) : '';
$dest3 = '../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$Photo3 = $imagepath3 ;
}
}


// Another company (Roll 10) must not reuse the same mobile or email; exclude current row on edit.
$phoneEsc = $conn->real_escape_string(trim((string)$Phone));
$emailEsc = $conn->real_escape_string(trim((string)$EmailId));
$dupParts = array();
if ($phoneEsc !== '') {
	$dupParts[] = "Phone='" . $phoneEsc . "'";
}
if ($emailEsc !== '') {
	$dupParts[] = "EmailId='" . $emailEsc . "'";
}
if (count($dupParts) > 0) {
	$dupOr = implode(' OR ', $dupParts);
	if ($id === '' || $id === null) {
		$dupSql = "SELECT id FROM tbl_users WHERE Roll=10 AND (" . $dupOr . ") LIMIT 1";
	} else {
		$idEsc = $conn->real_escape_string((string)$id);
		$dupSql = "SELECT id FROM tbl_users WHERE Roll=10 AND id!='" . $idEsc . "' AND (" . $dupOr . ") LIMIT 1";
	}
	$dupRes = $conn->query($dupSql);
	if ($dupRes && $dupRes->num_rows > 0) {
		echo '0';
		exit;
	}
}

if($id == ''){
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='10',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',Lattitude='$Lattitude',Longitude='$Longitude'";
if (!$conn->query($sql)) {
	echo '-1';
	exit;
}
$EmpId = mysqli_insert_id($conn);
$CustomerId = "VTECH-CP".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);


echo '1';
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='10',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',Lattitude='$Lattitude',Longitude='$Longitude' WHERE id='$id'";
if (!$conn->query($sql)) {
	echo '-1';
	exit;
}
$sql2 = "DELETE FROM tbl_vendor_price WHERE VedId='$id'";
$conn->query($sql2);

echo '1';
}

}

if(isset($_POST['action']) && $_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if(isset($_POST['action']) && $_POST['action'] == 'getUserDetails'){
$id = $_POST['id'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.id='$id'";
$row = getRecord($sql);
echo json_encode($row);
}

if(isset($_POST['action']) && $_POST['action'] == 'getUserDetails2'){
$CellNo = $_POST['CellNo'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Phone='$CellNo'";
$row = getRecord($sql);
echo json_encode($row);
}