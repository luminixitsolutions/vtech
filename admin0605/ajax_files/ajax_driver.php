<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
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
$FatherPhone = addslashes(trim($_POST['FatherPhone']));
$Designation = addslashes(trim($_POST['Designation']));
$Dob = addslashes(trim($_POST['Dob']));
$AadharNo = addslashes(trim($_POST['AadharNo']));
$BloodGroup = addslashes(trim($_POST['BloodGroup']));
$JoinDate = addslashes(trim($_POST['JoinDate']));
$EmailId2 = addslashes(trim($_POST['EmailId2']));
$UnderUser = addslashes(trim($_POST['UnderUser']));
$Status = $_POST['Status'];
$CatId = $_POST['CatId'];
$Roll = $_POST['Roll'];
$PanNo = addslashes(trim($_POST['PanNo']));
$CompId = addslashes(trim($_POST['CompId']));
$BranchId = addslashes(trim($_POST['BranchId']));
$InTime = addslashes(trim($_POST['InTime']));
$OutTime = addslashes(trim($_POST['OutTime']));
$VehicalNo = addslashes(trim($_POST['VehicalNo']));
$VehicalModel = addslashes(trim($_POST['VehicalModel']));
$EngineNo = addslashes(trim($_POST['EngineNo']));
$VehAverage = addslashes(trim($_POST['VehAverage']));
$CompanyName = addslashes(trim($_POST['CompanyName']));
$PerDayVehicle = addslashes(trim($_POST['PerDayVehicle']));
$RooftopBranchId = addslashes(trim($_POST['RooftopBranchId']));
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
$sql = "INSERT INTO tbl_users SET UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='39',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',VehicalNo='$VehicalNo',VehicalModel='$VehicalModel',EngineNo='$EngineNo',VehAverage='$VehAverage',CompanyName='$CompanyName',PerDayVehicle='$PerDayVehicle'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "VTECH-D".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);


echo "<script>alert('Record Created Successfully!');window.location.href='../user_management/view-drivers.php';</script>";
}
else{
$sql = "UPDATE tbl_users SET UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='39',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',VehicalNo='$VehicalNo',VehicalModel='$VehicalModel',EngineNo='$EngineNo',VehAverage='$VehAverage',CompanyName='$CompanyName',PerDayVehicle='$PerDayVehicle' WHERE id='$id'";
$conn->query($sql);


echo "<script>alert('Record Updated Successfully!');window.location.href='../user_management/view-drivers.php';</script>";
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