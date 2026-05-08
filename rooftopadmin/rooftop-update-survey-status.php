<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "Rooftop-Customers";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);

$sql78 = "SELECT * FROM tbl_rooftop_tel_survey WHERE CustId='$id'";
$row78 = getRecord($sql78);
?>

<?php 
function uploadPhoto($orgfile,$tempfile){
  $randno = rand(1,100);
$src = $tempfile;
$fnm = substr($orgfile, 0,strrpos($orgfile,'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($orgfile,strpos($orgfile,"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
return $Photo;

}
  if(isset($_POST['submit'])){
$SurveyDetails = addslashes(trim($_POST["TelSurveyDetails"]));
$TelSurveyDate = addslashes(trim($_POST["TelSurveyDate"]));
$TelLattitude = addslashes(trim($_POST["TelLattitude"]));
$TelLongitude = addslashes(trim($_POST["TelLongitude"]));
$TelContactNo = addslashes(trim($_POST["TelContactNo"]));
$TelContactPerson = addslashes(trim($_POST["TelContactPerson"]));
$TelSystemType = addslashes(trim($_POST["TelSystemType"]));
$TelCapacity = addslashes(trim($_POST["TelCapacity"]));
$TelShadowArea = addslashes(trim($_POST["TelShadowArea"]));
$TelShadowArea2 = addslashes(trim($_POST["TelShadowArea2"]));
$TelOrientation = addslashes(trim($_POST["TelOrientation"]));
$TelDistance = addslashes(trim($_POST["TelDistance"]));
$TelConnectedLoad = addslashes(trim($_POST["TelConnectedLoad"]));
$TelRequiredLoad = addslashes(trim($_POST["TelRequiredLoad"]));
$TelComments = addslashes(trim($_POST["TelComments"]));
$TelEpsLoad = addslashes(trim($_POST["TelEpsLoad"]));
$TelNetMeter = addslashes(trim($_POST["TelNetMeter"]));
$ConsumerNo = addslashes(trim($_POST["ConsumerNo"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

if(isset($_FILES['TelSitePhoto']['name'])){
  $temp1 = $_FILES['TelSitePhoto']['tmp_name'];
  $orgfile1 = $_FILES['TelSitePhoto']['name'];
  $TelSitePhoto = uploadPhoto($orgfile1,$temp1);
}
else{
  $TelSitePhoto = $_POST['TelSitePhotoOld'];
}

if(isset($_FILES['TelPanCard']['name'])){
  $temp2 = $_FILES['TelPanCard']['tmp_name'];
  $orgfile2 = $_FILES['TelPanCard']['name'];
  $TelPanCard = uploadPhoto($orgfile2,$temp2);
}
else{
  $TelPanCard = $_POST['PanCardOld'];
}

if(isset($_FILES['TelPanCard']['name'])){
  $temp3 = $_FILES['TelAadharCard']['tmp_name'];
  $orgfile3 = $_FILES['TelAadharCard']['name'];
  $TelAadharCard = uploadPhoto($orgfile3,$temp3);
}
else{
  $TelAadharCard = $_POST['AadharCardOld'];
}

if(isset($_FILES['TelPanCard']['name'])){
  $temp4 = $_FILES['TelAadharCard2']['tmp_name'];
  $orgfile4 = $_FILES['TelAadharCard2']['name'];
  $TelAadharCard2 = uploadPhoto($orgfile4,$temp4);
}
else{
  $TelAadharCard2 = $_POST['AadharCardOld2'];
}

if(isset($_FILES['TelPanCard']['name'])){
  $temp5 = $_FILES['TelElectricBill']['tmp_name'];
  $orgfile5 = $_FILES['TelElectricBill']['name'];
  $TelElectricBill = uploadPhoto($orgfile5,$temp5);
}
else{
  $TelElectricBill = $_POST['ElectricBillOld'];
}

    $sql = "DELETE FROM tbl_rooftop_tel_survey WHERE CustId='$id'";
    $conn->query($sql);
    $sql = "INSERT INTO tbl_rooftop_tel_survey SET ConsumerNo='$ConsumerNo',CustId='$id',TelSurveyDate='$TelSurveyDate',TelContactNo='$TelContactNo',TelContactPerson='$TelContactPerson',TelSystemType='$TelSystemType',TelCapacity='$TelCapacity',TelShadowArea='$TelShadowArea',TelShadowArea2='$TelShadowArea2',TelOrientation='$TelOrientation',TelDistance='$TelDistance',TelConnectedLoad='$TelConnectedLoad',TelRequiredLoad='$TelRequiredLoad',TelComments='$TelComments',TelEpsLoad='$TelEpsLoad',TelNetMeter='$TelNetMeter',TelSitePhoto='$TelSitePhoto',TelPanCard='$TelPanCard',TelAadharCard='$TelAadharCard',TelAadharCard2='$TelAadharCard2',TelElectricBill='$TelElectricBill',TelSurveyDetails='$SurveyDetails',TelLattitude='$TelLattitude',TelLongitude='$TelLongitude',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $RooftopTelSurveyId = mysqli_insert_id($conn);
    $query2 = "UPDATE tbl_users SET RooftopTelSurveyId='$RooftopTelSurveyId',SurveyDetails='$SurveyDetails',TelLattitude='$TelLattitude',TelLongitude='$TelLongitude',TelSurveyBy='$user_id',TelSurveyDate='$CreatedDate' WHERE id = '$id'";
    $conn->query($query2);
    $query2 = "UPDATE tbl_user2 SET ConsumerNo='$ConsumerNo' WHERE id = '$id'";
    $conn->query($query2);
  
  if($SurveyDetails=='1') {
  $Steps = "Telephonic Survey Done";
  }
  else{
    $Steps = "Telephonic Survey Not Done";  
  }
  $sql = "SELECT * FROM tbl_steps WHERE CustId='$id' AND SrNo='1'";
  $rncnt = getRow($sql);
  if($rncnt > 0){
      $sql = "UPDATE tbl_steps SET Steps='$Steps' WHERE CustId='$id' AND SrNo='1'";
      $conn->query($sql);
  }
  else{
  $sql = "INSERT INTO tbl_steps SET SrNo=1,CustId='$id',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='0',LeadActId='0'";
  $conn->query($sql);
  }
  echo "<script>alert('Telephonic Survey Status Updated Successfully!');window.location.href='co-ordinator-survey.php';</script>";

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Telephonic Survey Status</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Date of Survey  <span class="text-danger">*</span></label>
<input type="date" class="form-control" name="TelSurveyDate" placeholder="" value="<?php echo $row78['TelSurveyDate']; ?>" required>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Beneficiary ID </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Name of Consumer </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['Fname']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Electricity consumer no. </label>
<input type="text" class="form-control" placeholder="" name="ConsumerNo" value="<?php echo $row78['ConsumerNo']; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Village/City </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7["Village"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Taluka </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7["Taluka"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">District </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7["District"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">State </label>
<select class="form-control" readonly>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE id='".$row7['StateId']."'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="TelLattitude" id="TelLattitude" class="form-control" placeholder="" value="<?php echo $row78["TelLattitude"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="TelLongitude" id="TelLongitude" class="form-control" placeholder="" value="<?php echo $row78["TelLongitude"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Number </label>
<input type="text" class="form-control" name="TelContactNo" placeholder="" value="<?php echo $row78["TelContactNo"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Person </label>
<input type="text" class="form-control" name="TelContactPerson" placeholder="" value="<?php echo $row78["TelContactPerson"]; ?>">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label">Type of System required <span class="text-danger">*</span></label>
  <select class="form-control" id="TelSystemType" name="TelSystemType" required="">

<option value="" selected >Select</option>
<option value="Hybrid" <?php if($row78["TelSystemType"]=='Hybrid') {?> selected <?php } ?>>Hybrid</option>
<option value="Ongrid" <?php if($row78["TelSystemType"]=='Ongrid') {?> selected <?php } ?>>Ongrid</option>
<option value="Offgrid" <?php if($row78["TelSystemType"]=='Offgrid') {?> selected <?php } ?>>Offgrid</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-3">
<label class="form-label">Capacity Required (in KW) </label>
<input type="text" class="form-control" placeholder="" name="TelCapacity" value="<?php echo $row78["TelCapacity"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Shadow free Area Required (in Sq. feet) </label>
<input type="text" class="form-control" placeholder="" name="TelShadowArea" value="<?php echo $row78["TelShadowArea"]; ?>">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label">Shadow free area available <span class="text-danger">*</span></label>
  <select class="form-control" id="TelShadowArea2" name="TelShadowArea2" required="">
    <option value="" selected >Select</option>
<option value="Yes" <?php if($row78["TelShadowArea2"]=='Yes') {?> selected <?php } ?>>Yes</option>
<option value="No" <?php if($row78["TelShadowArea2"]=='No') {?> selected <?php } ?>>No</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-3">
<label class="form-label">Orientation (Toward South) <span class="text-danger">*</span></label>
  <select class="form-control" id="TelOrientation" name="TelOrientation" required="">
    <option value="" selected >Select</option>
<option value="Yes" <?php if($row78["TelOrientation"]=='Yes') {?> selected <?php } ?>>Yes</option>
<option value="No" <?php if($row78["TelOrientation"]=='No') {?> selected <?php } ?>>No</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Distance From LT Panel (Ongrid & Hybrid) in meters <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="TelDistance" placeholder="" value="<?php echo $row78["TelDistance"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Connected Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="TelConnectedLoad" value="<?php echo $row78["TelConnectedLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Required Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="TelRequiredLoad" value="<?php echo $row78["TelRequiredLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Comments (Required Extension Or Reduction) </label>
<input type="text" class="form-control" placeholder="" name="TelComments" value="<?php echo $row78["TelComments"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">EPS Load (if Offgrid or Hybrid) </label>
<input type="text" class="form-control" placeholder="" name="TelEpsLoad" value="<?php echo $row78["TelEpsLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Net Meter </label>
<input type="text" class="form-control" placeholder="" name="TelNetMeter" value="<?php echo $row78["TelNetMeter"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Site Photo </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TelSitePhoto" style="opacity: 1;">
<input type="hidden" name="TelSitePhotoOld" value="<?php echo $row78['TelSitePhoto'];?>" id="TelSitePhotoOld">
<span class="custom-file-label"></span>
</label>
<?php if($row78['TelSitePhoto']=='') {} else{?>
  <span id="show_photo3">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo3"></a><a href="../uploads/<?php echo $row78['TelSitePhoto'];?>" target="_blank"><?php echo $row78['TelSitePhoto'];?></a></div>
</span>
<?php } ?>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Pan Card </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TelPanCard" style="opacity: 1;">
<input type="hidden" name="PanCardOld" value="<?php echo $row78['TelPanCard'];?>" id="PanCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row78['TelPanCard']=='') {} else{?>
  <span id="show_photo5">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo5"></a><a href="../uploads/<?php echo $row78['TelPanCard'];?>" target="_blank"><?php echo $row78['TelPanCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Front Aadhar Card </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TelAadharCard" style="opacity: 1;">
<input type="hidden" name="AadharCardOld" value="<?php echo $row78['TelAadharCard'];?>" id="AadharCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row78['TelAadharCard']=='') {} else{?>
  <span id="show_photo3">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo3"></a><a href="../uploads/<?php echo $row78['TelAadharCard'];?>" target="_blank"><?php echo $row78['TelAadharCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Back Aadhar Card </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TelAadharCard2" style="opacity: 1;">
<input type="hidden" name="AadharCardOld2" value="<?php echo $row78['TelAadharCard2'];?>" id="AadharCardOld2">
<span class="custom-file-label"></span>
</label>
<?php if($row78['TelAadharCard2']=='') {} else{?>
  <span id="show_photo4">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo4"></a><a href="../uploads/<?php echo $row78['TelAadharCard2'];?>" target="_blank"><?php echo $row78['TelAadharCard2'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Electricity Bill(12 Month)</label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="TelElectricBill" style="opacity: 1;">
<input type="hidden" name="ElectricBillOld" value="<?php echo $row78['TelElectricBill'];?>" id="ElectricBillOld">
<span class="custom-file-label"></span>
</label>
<?php if($row78['TelElectricBill']=='') {} else{?>
  <span id="show_photo6">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo6"></a><a href="../uploads/<?php echo $row78['TelElectricBill'];?>" target="_blank"><?php echo $row78['TelElectricBill'];?></a></div>
</span>
<?php } ?>
</div>


 <div class="form-group col-md-6">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="TelSurveyDetails" name="TelSurveyDetails" required="">
<option value="0" <?php if($row78["TelSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
<option value="1" <?php if($row78["TelSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>

</select>
<div class="clearfix"></div>
</div> 
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
 
</body>
</html>