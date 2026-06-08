<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$ColgId = addslashes(trim($_POST["ColgId"]));
$CourseId = addslashes(trim($_POST["CourseId"]));
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$Password = addslashes(trim($_POST["Password"]));
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$Pincode = trim($_POST['Pincode']);
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
$sql = "INSERT INTO tbl_users SET ColgId='$ColgId',CourseId='$CourseId',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',CreatedDate='$CreatedDate',CreatedBy='$user_id',Roll=2";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "S".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_users SET ColgId='$ColgId',CourseId='$CourseId',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',ModifiedDate='$CreatedDate',ModifiedBy='$user_id' WHERE id='$id'";
$conn->query($sql);
echo 1;
}

}

if($_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if($_POST['action'] == 'getCustDetails'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_users WHERE id=$id";
    $row = getRecord($sql);
    echo json_encode($row);
}