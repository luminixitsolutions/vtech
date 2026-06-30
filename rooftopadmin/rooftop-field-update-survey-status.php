<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-rooftop-survey-schema.php';
include_once 'inc-rooftop-survey-doc-handlers.php';
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
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$row7 = getRecord("SELECT * FROM tbl_users WHERE id='$id'");
if (empty($row7['id'])) {
    $custRow = getRecord("SELECT CustId FROM tbl_rooftop_field_survey WHERE id='$id' LIMIT 1");
    if (!empty($custRow['CustId'])) {
        $id = (int) $custRow['CustId'];
        $row7 = getRecord("SELECT * FROM tbl_users WHERE id='$id'");
    }
}

$sql78 = "SELECT * FROM tbl_rooftop_field_survey WHERE CustId='$id'";
$row78 = getRecord($sql78);
$row7u2 = getRecord("SELECT ConsumerNo FROM tbl_user2 WHERE id='$id'");
$customerConsumerNo = trim((string) ($row7u2['ConsumerNo'] ?? ''));
if ($customerConsumerNo === '') {
    $customerConsumerNo = trim((string) ($row78['ConsumerNo'] ?? ''));
}
$applicantCategory = '';
if (!empty($row7['SchemeId'])) {
    $schemeRow = getRecord("SELECT Name FROM tbl_rooftop_scheme WHERE id='" . (int) $row7['SchemeId'] . "' LIMIT 1");
    $applicantCategory = $schemeRow['Name'] ?? '';
}
?>

<?php 
  if(isset($_POST['submit'])){
$SurveyDetails = addslashes(trim($_POST["FieldSurveyDetails"]));
$FieldSurveyDate = addslashes(trim($_POST["FieldSurveyDate"]));
$FieldLattitude = addslashes(trim($_POST["FieldLattitude"]));
$FieldLongitude = addslashes(trim($_POST["FieldLongitude"]));
$FieldContactNo = addslashes(trim($_POST["FieldContactNo"]));
$FieldContactPerson = addslashes(trim($_POST["FieldContactPerson"]));
$FieldSystemType = addslashes(trim($_POST["FieldSystemType"]));
$FieldCapacity = addslashes(trim($_POST["FieldCapacity"]));
$FieldShadowArea = addslashes(trim($_POST["FieldShadowArea"]));
$FieldShadowArea2 = addslashes(trim($_POST["FieldShadowArea2"]));
$FieldOrientation = addslashes(trim($_POST["FieldOrientation"]));
$FieldDistance = addslashes(trim($_POST["FieldDistance"]));
$FieldConnectedLoad = addslashes(trim($_POST["FieldConnectedLoad"]));
$FieldRequiredLoad = addslashes(trim($_POST["FieldRequiredLoad"]));
$FieldComments = addslashes(trim($_POST["FieldComments"]));
$FieldEpsLoad = addslashes(trim($_POST["FieldEpsLoad"]));
$FieldNetMeter = addslashes(trim($_POST["FieldNetMeter"]));
$ConsumerNo = addslashes(trim($_POST["ConsumerNo"]));
$FieldRoofType = addslashes(trim($_POST['FieldRoofType'] ?? ''));
$FieldEarthingDistance = addslashes(trim($_POST['FieldEarthingDistance'] ?? ''));
$FieldPhase1ph = addslashes(trim($_POST['FieldPhase1ph'] ?? ''));
$FieldOngridKW = addslashes(trim($_POST['FieldOngridKW'] ?? ''));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

$docFields = rooftopSurveyCollectDocFields('Field', $_POST, $_FILES);
$FieldSitePhoto = $docFields['FieldSitePhoto'];
$FieldPanCard = $docFields['FieldPanCard'];
$FieldAadharCard = $docFields['FieldAadharCard'];
$FieldAadharCard2 = $docFields['FieldAadharCard2'];
$FieldElectricBill = $docFields['FieldElectricBill'];
$docSql = rooftopSurveyDocSqlSet($docFields);

if(isset($_FILES['FieldBankDetails']['name']) && $_FILES['FieldBankDetails']['name'] != ''){
  $FieldBankDetails = rooftopSurveyUploadPhoto($_FILES['FieldBankDetails']['name'], $_FILES['FieldBankDetails']['tmp_name']);
}
else{
  $FieldBankDetails = $_POST['FieldBankDetailsOld'] ?? '';
}

    $sql = "DELETE FROM tbl_rooftop_field_survey WHERE CustId='$id'";
    $conn->query($sql);
    $sql = "INSERT INTO tbl_rooftop_field_survey SET ConsumerNo='$ConsumerNo',CustId='$id',FieldSurveyDate='$FieldSurveyDate',FieldContactNo='$FieldContactNo',FieldContactPerson='$FieldContactPerson',FieldSystemType='$FieldSystemType',FieldCapacity='$FieldCapacity',FieldShadowArea='$FieldShadowArea',FieldShadowArea2='$FieldShadowArea2',FieldOrientation='$FieldOrientation',FieldDistance='$FieldDistance',FieldConnectedLoad='$FieldConnectedLoad',FieldRequiredLoad='$FieldRequiredLoad',FieldComments='$FieldComments',FieldEpsLoad='$FieldEpsLoad',FieldNetMeter='$FieldNetMeter',$docSql,FieldBankDetails='$FieldBankDetails',FieldRoofType='$FieldRoofType',FieldEarthingDistance='$FieldEarthingDistance',FieldPhase1ph='$FieldPhase1ph',FieldOngridKW='$FieldOngridKW',FieldSurveyDetails='$SurveyDetails',FieldLattitude='$FieldLattitude',FieldLongitude='$FieldLongitude',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
    $conn->query($sql);
    $RooftopFieldSurveyId = mysqli_insert_id($conn);
    $query2 = "UPDATE tbl_users SET RooftopFieldSurveyId='$RooftopFieldSurveyId',FieldSurveyDetails='$SurveyDetails',FieldLattitude='$FieldLattitude',FieldLongitude='$FieldLongitude',FieldSurveyBy='$user_id',FieldSurveyDate='$CreatedDate' WHERE id = '$id'";
    $conn->query($query2);
    $query2 = "UPDATE tbl_user2 SET ConsumerNo='$ConsumerNo' WHERE id = '$id'";
    $conn->query($query2);

    if (is_file(__DIR__ . '/inc-msedcl-smart-site.php')) {
        require_once __DIR__ . '/inc-msedcl-smart-site.php';
        msedclSmartSyncSurveyDoneForUser((int) $id, (int) $user_id);
    }
  
  if($SurveyDetails=='1') {
  $Steps = "Field Survey Done";
  }
  else{
    $Steps = "Field Survey Not Done";  
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
  echo "<script>alert('Field Survey Status Updated Successfully!');window.location.href='field-survey.php';</script>";

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Field Survey Status</h4>


<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Date of Survey  <span class="text-danger">*</span></label>
<input type="date" class="form-control" name="FieldSurveyDate" placeholder="" value="<?php echo $row78['FieldSurveyDate']; ?>" required>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Beneficiary ID / Application Number</label>
<input type="text" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row7['BeneficiaryId'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Beneficiary Name</label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['Fname']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Consumer No.</label>
<input type="text" class="form-control" placeholder="" name="ConsumerNo" value="<?php echo htmlspecialchars($customerConsumerNo, ENT_QUOTES, 'UTF-8'); ?>">
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

<?php
$surveyPrefix = 'Field';
$surveySection = 'customer';
include __DIR__ . '/inc-rooftop-survey-extra-fields.php';
?>

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
<label class="form-label">Latitude</label>
<input type="text" name="FieldLattitude" id="FieldLattitude" class="form-control" placeholder="" value="<?php echo $row78["FieldLattitude"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="FieldLongitude" id="FieldLongitude" class="form-control" placeholder="" value="<?php echo $row78["FieldLongitude"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Number </label>
<input type="text" class="form-control" name="FieldContactNo" placeholder="" value="<?php echo $row78["FieldContactNo"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Person </label>
<input type="text" class="form-control" name="FieldContactPerson" placeholder="" value="<?php echo $row78["FieldContactPerson"]; ?>">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label">Type of System required <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSystemType" name="FieldSystemType" required="">

<option value="" selected >Select</option>
<option value="Hybrid" <?php if($row78["FieldSystemType"]=='Hybrid') {?> selected <?php } ?>>Hybrid</option>
<option value="Ongrid" <?php if($row78["FieldSystemType"]=='Ongrid') {?> selected <?php } ?>>Ongrid</option>
<option value="Offgrid" <?php if($row78["FieldSystemType"]=='Offgrid') {?> selected <?php } ?>>Offgrid</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-3">
<label class="form-label">Capacity Required (in KW) </label>
<input type="text" class="form-control" placeholder="" name="FieldCapacity" value="<?php echo $row78["FieldCapacity"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Area Required (in Sq. feet)</label>
<input type="text" class="form-control" placeholder="" name="FieldShadowArea" value="<?php echo $row78["FieldShadowArea"]; ?>">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label">Roof available area is shadow free or not <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldShadowArea2" name="FieldShadowArea2" required="">
    <option value="" selected >Select</option>
<option value="Yes" <?php if($row78["FieldShadowArea2"]=='Yes') {?> selected <?php } ?>>Yes</option>
<option value="No" <?php if($row78["FieldShadowArea2"]=='No') {?> selected <?php } ?>>No</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-3">
<label class="form-label">Orientation (Toward South) <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldOrientation" name="FieldOrientation" required="">
    <option value="" selected >Select</option>
<option value="Yes" <?php if($row78["FieldOrientation"]=='Yes') {?> selected <?php } ?>>Yes</option>
<option value="No" <?php if($row78["FieldOrientation"]=='No') {?> selected <?php } ?>>No</option>
</select>
<div class="clearfix"></div>
</div>

<?php
$surveySection = 'technical';
include __DIR__ . '/inc-rooftop-survey-extra-fields.php';
?>

<div class="form-group col-md-4">
<label class="form-label">Distance from Panel to meter (in meters) <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="FieldDistance" placeholder="" value="<?php echo $row78["FieldDistance"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Connected Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="FieldConnectedLoad" value="<?php echo $row78["FieldConnectedLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Required Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="FieldRequiredLoad" value="<?php echo $row78["FieldRequiredLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Comments (Required Extension Or Reduction) </label>
<input type="text" class="form-control" placeholder="" name="FieldComments" value="<?php echo $row78["FieldComments"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">EPS Load (if Offgrid or Hybrid) </label>
<input type="text" class="form-control" placeholder="" name="FieldEpsLoad" value="<?php echo $row78["FieldEpsLoad"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Net Meter </label>
<input type="text" class="form-control" placeholder="" name="FieldNetMeter" value="<?php echo $row78["FieldNetMeter"]; ?>">
 <div class="clearfix"></div>
</div>

<?php
$surveyPrefix = 'Field';
include __DIR__ . '/inc-rooftop-survey-doc-uploads.php';
?>

<?php
$surveySection = 'bank';
include __DIR__ . '/inc-rooftop-survey-extra-fields.php';
?>


 <div class="form-group col-md-6">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSurveyDetails" name="FieldSurveyDetails" required="">

<option value="1" <?php if($row78["FieldSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row78["FieldSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
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