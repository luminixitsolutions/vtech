<?php
session_start();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
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
$Password = "12345";
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$LoanCategory = addslashes(trim($_POST['LoanCategory']));
$SubCategory = addslashes(trim($_POST['SubCategory']));
$Campaign = addslashes(trim($_POST['Campaign']));
$Source = addslashes(trim($_POST['Source']));
$CallDate = addslashes(trim($_POST['CallDate']));
$AgentName = addslashes(trim($_POST['AgentName']));
$AgentComments = addslashes(trim($_POST['AgentComments']));
$PartId = addslashes(trim($_POST['PartId']));
$BranchId = addslashes(trim($_POST['BranchId']));
$EdCampaign = addslashes(trim($_POST['EdCampaign']));
$Course = addslashes(trim($_POST['Course']));
$Medium = addslashes(trim($_POST['Medium']));
$College = addslashes(trim($_POST['College']));
$EdSource = addslashes(trim($_POST['EdSource']));
$EdAgentName = addslashes(trim($_POST['EdAgentName']));
$EdAgentComments = addslashes(trim($_POST['EdAgentComments']));
$ClassName = addslashes(trim($_POST['ClassName']));
$Pincode = trim($_POST['Pincode']);
$LeadId = trim($_POST['LeadId']);
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
$sql = "INSERT INTO tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',EdCampaign='$EdCampaign',EdSource='$EdSource',EdAgentName='$EdAgentName',EdAgentComments='$EdAgentComments',SchoolLeadId='$LeadId',Medium='$Medium',College='$College',Course='$Course',CreatedDate='$CreatedDate',CreatedBy='$user_id',Roll=5,CatId='School',ClassName='$ClassName'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "S".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
     $conn->query($sql3);
echo 1;
}
else{
$sql = "UPDATE tbl_users SET Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',EdCampaign='$EdCampaign',EdSource='$EdSource',EdAgentName='$EdAgentName',EdAgentComments='$EdAgentComments',Medium='$Medium',College='$College',Course='$Course',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',ClassName='$ClassName' WHERE id='$id'";
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