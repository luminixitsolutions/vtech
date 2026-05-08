<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['User']['id'];

function uploadImage($filename,$tempfile,$oldfile){
    $randno = rand(1,100);
$src = $tempfile;
$fnm = substr($filename, 0,strrpos($filename,'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($filename,strpos($filename,"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
  $Photo = $oldfile;
}
return $Photo;
}

if($_POST['action'] == 'Save'){

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
$CompName = addslashes(trim($_POST['CompName']));

$AccountName = addslashes(trim($_POST['AccountName']));
$BankName = addslashes(trim($_POST['BankName']));
$AccountNo = addslashes(trim($_POST['AccountNo']));
$IfscCode = addslashes(trim($_POST['IfscCode']));
$Branch = addslashes(trim($_POST['Branch']));
$UpiNo = addslashes(trim($_POST['UpiNo']));

$AgencyId = addslashes(trim($_POST['AgencyId']));
$LeadTeam = addslashes(trim($_POST['LeadTeam']));
$GstRegNo = addslashes(trim($_POST['GstRegNo']));
$PanCardNo = addslashes(trim($_POST['PanCardNo']));
$Gumastha = addslashes(trim($_POST['Gumastha']));
$AreaOperation = addslashes(trim($_POST['AreaOperation']));

$StoreAvailability = addslashes(trim($_POST['StoreAvailability']));
$StoreAddress = addslashes(trim($_POST['StoreAddress']));
$AreaSqft = addslashes(trim($_POST['AreaSqft']));
$Covered = addslashes(trim($_POST['Covered']));
$Security = addslashes(trim($_POST['Security']));
$StoreManager = addslashes(trim($_POST['StoreManager']));

$CapacityPump = addslashes(trim($_POST['CapacityPump']));
$NoOperationalGate = addslashes(trim($_POST['NoOperationalGate']));
$NoOfTeams = addslashes(trim($_POST['NoOfTeams']));
$CountOfMembers = addslashes(trim($_POST['CountOfMembers']));
$MemberName = addslashes(trim($_POST['MemberName']));
$AreaQty = addslashes(trim($_POST['AreaQty']));

$AgencyName = addslashes(trim($_POST['AgencyName']));
$Year = addslashes(trim($_POST['Year']));
$Quantity = addslashes(trim($_POST['Quantity']));
$WorkDone = addslashes(trim($_POST['WorkDone']));
$OwnedVehicle = addslashes(trim($_POST['OwnedVehicle']));
$RentedVehicle = addslashes(trim($_POST['RentedVehicle']));

$NoOfVehicles = addslashes(trim($_POST['NoOfVehicles']));
$PerDaySurvey = addslashes(trim($_POST['PerDaySurvey']));
$PerDayDispatch = addslashes(trim($_POST['PerDayDispatch']));
$PerDayIC = addslashes(trim($_POST['PerDayIC']));
$PerDayInspection = addslashes(trim($_POST['PerDayInspection']));
$WeeklyBilling = addslashes(trim($_POST['WeeklyBilling']));

$F15DayBilling = addslashes(trim($_POST['15DayBilling']));
$MonthlyBilling = addslashes(trim($_POST['MonthlyBilling']));
$PumpBilling = addslashes(trim($_POST['PumpBilling']));
$AadharCardAllMember = addslashes(trim($_POST['AadharCardAllMember']));
$WorkmenCompensation = addslashes(trim($_POST['WorkmenCompensation']));


$Status = $_POST['Status'];
$CatId = $_POST['CatId'];
$Roll = $_POST['Roll'];
if($_POST['Options']!=''){
$Options = implode(",", $_POST['Options']);
}
else{
   $Options = 0; 
}

$PanNo = addslashes(trim($_POST['PanNo']));
$CompId = addslashes(trim($_POST['CompId']));
$BranchId = addslashes(trim($_POST['BranchId']));
$InTime = addslashes(trim($_POST['InTime']));
$OutTime = addslashes(trim($_POST['OutTime']));
$CreatedDate = date('Y-m-d');


$FileName1 = $_FILES["Photo"]["name"];
$TempFile1 = $_FILES["Photo"]["tmp_name"];
$OldPhoto1 = $_POST['OldPhoto'];
$Photo = uploadImage($FileName1,$TempFile1,$OldPhoto1);

$FileName2 = $_FILES["Photo2"]["name"];
$TempFile2 = $_FILES["Photo2"]["tmp_name"];
$OldPhoto2 = $_POST['OldPhoto2'];
$Photo2 = uploadImage($FileName2,$TempFile2,$OldPhoto2);

$FileName3 = $_FILES["Photo3"]["name"];
$TempFile3 = $_FILES["Photo3"]["tmp_name"];
$OldPhoto3 = $_POST['OldPhoto3'];
$Photo3 = uploadImage($FileName3,$TempFile3,$OldPhoto3);

$FileName4 = $_FILES["GstRegPhoto"]["name"];
$TempFile4 = $_FILES["GstRegPhoto"]["tmp_name"];
$OldGstRegPhoto = $_POST['OldGstRegPhoto'];
$GstRegPhoto = uploadImage($FileName4,$TempFile4,$OldGstRegPhoto);

$FileName5 = $_FILES["PanCardPhoto"]["name"];
$TempFile5 = $_FILES["PanCardPhoto"]["tmp_name"];
$OldPanCardPhoto = $_POST['OldPanCardPhoto'];
$PanCardPhoto = uploadImage($FileName5,$TempFile5,$OldPanCardPhoto);

$FileName6 = $_FILES["GumasthaPhoto"]["name"];
$TempFile6 = $_FILES["GumasthaPhoto"]["tmp_name"];
$OldGumasthaPhoto = $_POST['OldGumasthaPhoto'];
$GumasthaPhoto = uploadImage($FileName6,$TempFile6,$OldGumasthaPhoto);


$sql = "INSERT INTO tbl_users SET UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='1',Photo='$Photo',Roll='40',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',CompName='$CompName',AgencyId='$AgencyId',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "VTECH-E".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);

$sql = "INSERT INTO tbl_user2 SET id='$EmpId',LeadTeam='$LeadTeam',GstRegNo='$GstRegNo',GstRegPhoto='$GstRegPhoto',PanCardNo='$PanCardNo',PanCardPhoto='$PanCardPhoto',Gumastha='$Gumastha',GumasthaPhoto='$GumasthaPhoto',AreaOperation='$AreaOperation',StoreAvailability='$StoreAvailability',StoreAddress='$StoreAddress',AreaSqft='$AreaSqft',Covered='$Covered',Security='$Security',StoreManager='$StoreManager',CapacityPump='$CapacityPump',NoOperationalGate='$NoOperationalGate',NoOfTeams='$NoOfTeams',CountOfMembers='$CountOfMembers',MemberName='$MemberName',AreaQty='$AreaQty',AgencyName='$AgencyName',Year='$Year',Quantity='$Quantity',WorkDone='$WorkDone',OwnedVehicle='$OwnedVehicle',RentedVehicle='$RentedVehicle',NoOfVehicles='$NoOfVehicles',PerDaySurvey='$PerDaySurvey',PerDayDispatch='$PerDayDispatch',PerDayIC='$PerDayIC',PerDayInspection='$PerDayInspection',WeeklyBilling='$WeeklyBilling',15DayBilling='$F15DayBilling',MonthlyBilling='$MonthlyBilling',PumpBilling='$PumpBilling',AadharCardAllMember='$AadharCardAllMember',WorkmenCompensation='$WorkmenCompensation'";
$conn->query($sql);
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo json_encode(array('status'=>1,'roll'=>40,'Username'=>$Phone,'uid'=>$EmpId));;
}
?>