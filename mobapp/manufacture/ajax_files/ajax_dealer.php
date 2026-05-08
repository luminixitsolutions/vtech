<?php
session_start();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$Password = addslashes($_POST['Password']);
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$GstNo = addslashes(trim($_POST['GstNo']));
$Pincode = trim($_POST['Pincode']);
$Details = addslashes(trim($_POST['Details']));
$Status = $_POST['Status'];
$CatId = $_POST['CatId'];
$Roll = $_POST['Roll'];

$AadharNo = addslashes(trim($_POST['AadharNo']));
$PanNo = addslashes(trim($_POST['PanNo']));

$AccountName = addslashes(trim($_POST['AccountName']));
$BankName = addslashes(trim($_POST['BankName']));
$AccountNo = addslashes(trim($_POST['AccountNo']));
$IfscCode = addslashes(trim($_POST['IfscCode']));
$Branch = addslashes(trim($_POST['Branch']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$GstNo = addslashes(trim($_POST['GstNo']));
$GumastaNo = addslashes(trim($_POST['GumastaNo']));
$MsmeNo = addslashes(trim($_POST['MsmeNo']));

$CreatedDate = date('Y-m-d');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../../uploads/'. $randno . "_".$fnm . $ext;
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
$dest2 = '../../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
  $Photo2 = $_POST['OldPhoto2'];
}


$randno3 = rand(1,100);
$src3 = $_FILES['Photo3']['tmp_name'];
$fnm3 = substr($_FILES["Photo3"]["name"], 0,strrpos($_FILES["Photo3"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["Photo3"]["name"],strpos($_FILES["Photo3"]["name"],"."));
$dest3 = '../../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$Photo3 = $imagepath3 ;
} 
else{
  $Photo3 = $_POST['OldPhoto3'];
}


$randno3 = rand(1,100);
$src3 = $_FILES['AadharCard']['tmp_name'];
$fnm3 = substr($_FILES["AadharCard"]["name"], 0,strrpos($_FILES["AadharCard"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["AadharCard"]["name"],strpos($_FILES["AadharCard"]["name"],"."));
$dest3 = '../../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$AadharCard = $imagepath3 ;
} 
else{
$AadharCard = $_POST['AadharCardOld'];
}

$randno4 = rand(1,100);
$src4 = $_FILES['AadharCard2']['tmp_name'];
$fnm4 = substr($_FILES["AadharCard2"]["name"], 0,strrpos($_FILES["AadharCard2"]["name"],'.')); 
$fnm4 = str_replace(" ","_",$fnm4);
$ext4 = substr($_FILES["AadharCard2"]["name"],strpos($_FILES["AadharCard2"]["name"],"."));
$dest4 = '../../../uploads/'. $randno4 . "_".$fnm4 . $ext4;
$imagepath4 =  $randno4 . "_".$fnm4 . $ext4;
if(move_uploaded_file($src4, $dest4))
{
$AadharCard2 = $imagepath4 ;
} 
else{
$AadharCard2 = $_POST['AadharCardOld2'];
}

$randno6 = rand(1,100);
$src6 = $_FILES['PanCard']['tmp_name'];
$fnm6 = substr($_FILES["PanCard"]["name"], 0,strrpos($_FILES["PanCard"]["name"],'.')); 
$fnm6 = str_replace(" ","_",$fnm6);
$ext6 = substr($_FILES["PanCard"]["name"],strpos($_FILES["PanCard"]["name"],"."));
$dest6 = '../../../uploads/'. $randno6 . "_".$fnm6 . $ext6;
$imagepath6 =  $randno6 . "_".$fnm6 . $ext6;
if(move_uploaded_file($src6, $dest6))
{
$PanCard = $imagepath6 ;
} 
else{
$PanCard = $_POST['PanCardOld'];
}

$randno7 = rand(1,100);
$src7 = $_FILES['PanCard2']['tmp_name'];
$fnm7 = substr($_FILES["PanCard2"]["name"], 0,strrpos($_FILES["PanCard2"]["name"],'.')); 
$fnm7 = str_replace(" ","_",$fnm7);
$ext7 = substr($_FILES["PanCard2"]["name"],strpos($_FILES["PanCard2"]["name"],"."));
$dest7 = '../../../uploads/'. $randno7 . "_".$fnm7 . $ext7;
$imagepath7 =  $randno7 . "_".$fnm7 . $ext7;
if(move_uploaded_file($src7, $dest7))
{
$PanCard2 = $imagepath7 ;
} 
else{
$PanCard2 = $_POST['PanCardOld2'];
}

$randno8 = rand(1,100);
$src8 = $_FILES['GstCertificate']['tmp_name'];
$fnm8 = substr($_FILES["GstCertificate"]["name"], 0,strrpos($_FILES["GstCertificate"]["name"],'.')); 
$fnm8 = str_replace(" ","_",$fnm8);
$ext8 = substr($_FILES["GstCertificate"]["name"],strpos($_FILES["GstCertificate"]["name"],"."));
$dest8 = '../../../uploads/'. $randno8 . "_".$fnm8 . $ext8;
$imagepath8 =  $randno8 . "_".$fnm8 . $ext8;
if(move_uploaded_file($src8, $dest8))
{
$GstCertificate = $imagepath8 ;
} 
else{
$GstCertificate = $_POST['OldGstCertificate'];
}

if($id == ''){
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='9',CreatedDate='$CreatedDate',CreatedBy='$user_id',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "C".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);


echo "<script>window.location.href='../view-dealer.php';</script>";
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Photo='$Photo',Roll='9',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme' WHERE id='$id'";
$conn->query($sql);


echo "<script>window.location.href='../profile.php';</script>";
}

}

if($_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if($_POST['action'] == 'getUserDetails'){
$id = $_POST['id'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.id='$id'";
$row = getRecord($sql);
echo json_encode($row);
}

if($_POST['action'] == 'getUserDetails2'){
$CellNo = $_POST['CellNo'];
$sql = "SELECT tu.*,tu2.Fname AS AgentName FROM tbl_users tu LEFT JOIN tbl_users tu2 ON tu.UnderUser=tu2.id WHERE tu.Phone='$CellNo'";
$row = getRecord($sql);
echo json_encode($row);
}