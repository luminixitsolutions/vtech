<?php
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if(isset($_POST['action']) && $_POST['action'] == 'Save'){
$id = isset($_POST['id']) ? $_POST['id'] : '';
$Fname = addslashes(trim($_POST['Fname'] ?? ''));
$Mname = addslashes(trim($_POST['Mname'] ?? ''));
$Lname = addslashes(trim($_POST['Lname'] ?? ''));
$Phone = trim((string)($_POST['Phone'] ?? ''));
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

$FatherPhone = addslashes(trim($_POST['FatherPhone'] ?? ''));
$Designation = addslashes(trim($_POST['Designation'] ?? ''));
$Dob = addslashes(trim($_POST['Dob'] ?? ''));
$AadharNo = addslashes(trim($_POST['AadharNo'] ?? ''));
$BloodGroup = addslashes(trim($_POST['BloodGroup'] ?? ''));
$JoinDate = addslashes(trim($_POST['JoinDate'] ?? ''));
$EmailId2 = addslashes(trim($_POST['EmailId2'] ?? ''));
$UnderUser = addslashes(trim($_POST['UnderUser'] ?? '0'));

$Status = isset($_POST['Status']) ? $_POST['Status'] : '';
$CatId = isset($_POST['CatId']) ? $_POST['CatId'] : '';
$Roll = isset($_POST['Roll']) ? $_POST['Roll'] : '';
if(!empty($_POST['Options'])){
$Options = implode(",", $_POST['Options']);
}
else{
   $Options = 0; 
}

$PanNo = addslashes(trim($_POST['PanNo'] ?? ''));
$CompId = addslashes(trim($_POST['CompId'] ?? ''));
$BranchId = addslashes(trim($_POST['BranchId'] ?? ''));
$InTime = addslashes(trim($_POST['InTime'] ?? ''));
$OutTime = addslashes(trim($_POST['OutTime'] ?? ''));
$ImmediateBossId = addslashes(trim($_POST['ImmediateBossId'] ?? '0'));
$ImmediateBoss = addslashes(trim($_POST['ImmediateBoss'] ?? '0'));
$OfficeEmployee = (isset($_POST['OfficeEmployee']) && $_POST['OfficeEmployee'] === '1') ? 1 : 0;
$RooftopBranchId = addslashes(trim($_POST['RooftopBranchId'] ?? ''));
$UnderByManager = addslashes(trim($_POST['UnderByManager'] ?? '0'));
$UnderByGrManager = addslashes(trim($_POST['UnderByGrManager'] ?? '0'));
$UnderByBusHead = addslashes(trim($_POST['UnderByBusHead'] ?? '0'));
$CreatedDate = date('Y-m-d');

if(!empty($_POST['MulBranchId'])){
  $MulBranchId = implode(",", $_POST['MulBranchId']);
  }
  else{
     $MulBranchId = 0; 
  }

  if(!empty($_POST['MulRooftopBranchId'])){
    $MulRooftopBranchId = implode(",", $_POST['MulRooftopBranchId']);
    }
    else{
       $MulRooftopBranchId = 0; 
    }

$randno = rand(1,100);
$Photo = isset($_POST['OldPhoto']) ? $_POST['OldPhoto'] : '';
if (!empty($_FILES['Photo']['tmp_name']) && is_uploaded_file($_FILES['Photo']['tmp_name']) && !empty($_FILES['Photo']['name'])) {
    $photoName = $_FILES['Photo']['name'];
    $dot = strrpos($photoName, '.');
    if ($dot !== false) {
        $fnm = str_replace(' ', '_', substr($photoName, 0, $dot));
        $ext = substr($photoName, $dot);
        $dest = '../../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($_FILES['Photo']['tmp_name'], $dest)) {
            $Photo = $imagepath;
        }
    }
}

$randno2 = rand(1,100);
$Photo2 = isset($_POST['OldPhoto2']) ? $_POST['OldPhoto2'] : '';
if (!empty($_FILES['Photo2']['tmp_name']) && is_uploaded_file($_FILES['Photo2']['tmp_name']) && !empty($_FILES['Photo2']['name'])) {
    $photoName2 = $_FILES['Photo2']['name'];
    $dot2 = strrpos($photoName2, '.');
    if ($dot2 !== false) {
        $fnm2 = str_replace(' ', '_', substr($photoName2, 0, $dot2));
        $ext2 = substr($photoName2, $dot2);
        $dest2 = '../../uploads/' . $randno2 . '_' . $fnm2 . $ext2;
        $imagepath2 = $randno2 . '_' . $fnm2 . $ext2;
        if (move_uploaded_file($_FILES['Photo2']['tmp_name'], $dest2)) {
            $Photo2 = $imagepath2;
        }
    }
}

$randno3 = rand(1,100);
$Photo3 = isset($_POST['OldPhoto3']) ? $_POST['OldPhoto3'] : '';
if (!empty($_FILES['Photo3']['tmp_name']) && is_uploaded_file($_FILES['Photo3']['tmp_name']) && !empty($_FILES['Photo3']['name'])) {
    $photoName3 = $_FILES['Photo3']['name'];
    $dot3 = strrpos($photoName3, '.');
    if ($dot3 !== false) {
        $fnm3 = str_replace(' ', '_', substr($photoName3, 0, $dot3));
        $ext3 = substr($photoName3, $dot3);
        $dest3 = '../../uploads/' . $randno3 . '_' . $fnm3 . $ext3;
        $imagepath3 = $randno3 . '_' . $fnm3 . $ext3;
        if (move_uploaded_file($_FILES['Photo3']['tmp_name'], $dest3)) {
            $Photo3 = $imagepath3;
        }
    }
}


if($id == ''){
    $sql = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll='$Roll'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        if($Roll == 27){
         echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-store-incharge.php';</script>";
        }
        else if($Roll == 26){
   echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-dispatch-officer.php';</script>";
}
else{
 echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-employee.php';</script>";
}
        
    }
    else{
$sql = "INSERT INTO tbl_users SET UnderByManager='$UnderByManager',UnderByGrManager='$UnderByGrManager',UnderByBusHead='$UnderByBusHead',MulRooftopBranchId='$MulRooftopBranchId',MulBranchId='$MulBranchId',UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',CreatedDate='$CreatedDate',CreatedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',ImmediateBossId='$ImmediateBossId',ImmediateBoss='$ImmediateBoss'";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "VTECH-E".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
$conn->query($sql3);
$conn->query("INSERT INTO tbl_user2 (id, OfficeEmployee) VALUES ('$EmpId', '$OfficeEmployee') ON DUPLICATE KEY UPDATE OfficeEmployee='$OfficeEmployee'");

if($Roll == 27){
  echo "<script>alert('Record Created Successfully!');window.location.href='../user_management/view-store-incharge.php';</script>";
}
else if($Roll == 26){
  echo "<script>alert('Record Created Successfully!');window.location.href='../user_management/view-dispatch-officer.php';</script>";
}
else{
echo "<script>alert('Record Created Successfully!');window.location.href='../user_management/view-employee.php';</script>";
}
}
}
else{
    $sql = "SELECT * FROM tbl_users WHERE Phone='$Phone' AND Roll='$Roll' AND id!='$id'";
    $rncnt = getRow($sql);
    if($rncnt > 0){
        if($Roll == 27){
         echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-store-incharge.php';</script>";
        }
        else if($Roll == 26){
   echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-dispatch-officer.php';</script>";
}
else{
 echo "<script>alert('Phone No already Exist!');window.location.href='../user_management/add-employee.php';</script>";
}
        
    }
    else{
$sql = "UPDATE tbl_users SET UnderByManager='$UnderByManager',UnderByGrManager='$UnderByGrManager',UnderByBusHead='$UnderByBusHead',MulRooftopBranchId='$MulRooftopBranchId',MulBranchId='$MulBranchId',UnderUser='$UnderUser',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Password='$Password',Phone2='$Phone2',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',Photo='$Photo',Roll='$Roll',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',GstNo='$GstNo',Photo2='$Photo2',Photo3='$Photo3',Details='$Details',CatId='$CatId',PanNo='$PanNo',Options='$Options',CompId='$CompId',RooftopBranchId='$RooftopBranchId',BranchId='$BranchId',FatherPhone='$FatherPhone',Designation='$Designation',Dob='$Dob',AadharNo='$AadharNo',BloodGroup='$BloodGroup',JoinDate='$JoinDate',EmailId2='$EmailId2',InTime='$InTime',OutTime='$OutTime',ImmediateBossId='$ImmediateBossId',ImmediateBoss='$ImmediateBoss' WHERE id='$id'";
$conn->query($sql);
$conn->query("INSERT INTO tbl_user2 (id, OfficeEmployee) VALUES ('$id', '$OfficeEmployee') ON DUPLICATE KEY UPDATE OfficeEmployee='$OfficeEmployee'");

if($Roll == 27){
  echo "<script>alert('Record Updated Successfully!');window.location.href='../user_management/view-store-incharge.php';</script>";
}
else if($Roll == 26){
  echo "<script>alert('Record Updated Successfully!');window.location.href='../user_management/view-dispatch-officer.php';</script>";
}
else{
echo "<script>alert('Record Updated Successfully!');window.location.href='../user_management/view-employee.php';</script>";
}
}
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