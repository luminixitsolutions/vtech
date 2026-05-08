<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "Add-Sell";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
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
$sql7 = "SELECT * FROM tbl_service_complaint WHERE id='$id'";
$row7 = getRecord($sql7);
if($id == ''){
  $CustId2 = $_GET["CustId"];
  $sql3 = "SELECT id,Phone,CustomerId,Fname,Address,Taluka,Village,District,BeneficiaryId,ProjectId,ProjectSubHeadId FROM tbl_users WHERE id='$CustId2'";
  $row3 = getRecord($sql3);
  $CellNo = $row3['Phone'];
  $CustName = $row3['Fname'];
  $Address = $row3['Address'];
  $Taluka = $row3['Taluka'];
  $Village = $row3['Village'];
  $District = $row3['District'];
  $BeneficiaryId = $row3['BeneficiaryId'];
  $ProjectId = $row3['ProjectId'];
  $ProjectSubHeadId = $row3['ProjectSubHeadId'];
  $row7['Problem'] = "";
}
else{
  $CustId2 = $row7["CustId"];
  $CellNo = $row7['CellNo'];
  $CustName = $row7['CustName'];
  $Address = $row7['Address'];
  $Taluka = $row7['Taluka'];
  $Village = $row7['Village'];
  $District = $row7['District'];
  $BeneficiaryId = $row7['BeneficiaryId'];
  $ProjectId = $row7['ProjectId'];
  $ProjectSubHeadId = $row7['ProjectSubHeadId'];
  $row7['Problem'] = explode(",",$row7['Problem']);
}


if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $CellNo = addslashes(trim($_POST["CellNo"]));
    $CustName = addslashes(trim($_POST["CustName"]));
    $BeneficiaryId = addslashes(trim($_POST["BeneficiaryId"]));
$Status = 1;
$Address = addslashes(trim($_POST["Address"]));
$RelatedIssue = addslashes(trim($_POST['RelatedIssue']));
$Issue = addslashes(trim($_POST["Issue"]));
$Message = addslashes(trim($_POST["Message"]));
$ClainStatus = addslashes(trim($_POST["ClainStatus"]));
$BranchId = addslashes(trim($_POST["BranchId"]));
$ServiceType = addslashes(trim($_POST["ServiceType"]));
$Taluka = addslashes(trim($_POST["Taluka"]));
$Village = addslashes(trim($_POST["Village"]));
$District = addslashes(trim($_POST["District"]));
$InstallationDate = addslashes(trim($_POST["InstallationDate"]));
$RegistrationId = addslashes(trim($_POST["RegistrationId"]));
$ComissioningDate = addslashes(trim($_POST["ComissioningDate"]));
$WaterSource = addslashes(trim($_POST["WaterSource"]));
$Category = addslashes(trim($_POST["Category"]));
$ServiceSystem = addslashes(trim($_POST["ServiceSystem"]));
$AcDc = addslashes(trim($_POST["AcDc"]));
$Surface = addslashes(trim($_POST["Surface"]));
$RecentVfdNo = addslashes(trim($_POST["RecentVfdNo"]));
$RecentMotorNo = addslashes(trim($_POST["RecentMotorNo"]));
$RecentPumpNo = addslashes(trim($_POST["RecentPumpNo"]));
$Problem = addslashes(trim(implode(",",$_POST["Problem"])));
$Remark = addslashes(trim($_POST["Remark"]));
$Photos = addslashes(trim($_POST["Photos"]));
$InspectionDate = addslashes(trim($_POST["InspectionDate"]));
$InspectionBy = addslashes(trim($_POST["InspectionBy"]));
$LastDate = addslashes(trim($_POST["LastDate"]));
$SystemStatus = addslashes(trim($_POST["SystemStatus"]));
$Extra = addslashes(trim($_POST["Extra"]));
$ExtraPump = addslashes(trim($_POST["ExtraPump"]));
$ExtraVfd = addslashes(trim($_POST["ExtraVfd"]));
$PolicyNo = addslashes(trim($_POST["PolicyNo"]));
$ClainDone = addslashes(trim($_POST["ClainDone"]));
$Rms = addslashes(trim($_POST["Rms"]));

$VfdProblem = addslashes(trim($_POST["VfdProblem"]));
$PlateDamageInsurance = addslashes(trim($_POST["PlateDamageInsurance"]));
$PumpProblem = addslashes(trim($_POST["PumpProblem"]));
$MotorProblem = addslashes(trim($_POST["MotorProblem"]));
$PhotoReceived = addslashes(trim($_POST["PhotoReceived"]));
$VideoReceived = addslashes(trim($_POST["VideoReceived"]));
$LetterReceived = addslashes(trim($_POST["LetterReceived"]));
$LastCallUpdate = addslashes(trim($_POST["LastCallUpdate"]));
$RecentProblem = addslashes(trim($_POST["RecentProblem"]));
$SystemWorking = addslashes(trim($_POST["SystemWorking"]));
$PolicyPeriod = addslashes(trim($_POST["PolicyPeriod"]));
$Depth = addslashes(trim($_POST["Depth"]));
$PumpMake = addslashes(trim($_POST["PumpMake"]));
$MotorMake = addslashes(trim($_POST["MotorMake"]));
$InsuranceCompany = addslashes(trim($_POST["InsuranceCompany"]));
$InsuranceComplaint = addslashes(trim($_POST["InsuranceComplaint"]));
$ComplaintDate = addslashes(trim($_POST["ComplaintDate"]));
$ProjectId = addslashes(trim($_POST["ProjectId"]));
$ProjectSubHeadId = addslashes(trim($_POST["ProjectSubHeadId"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


if($_GET['id']==''){
     $qx = "INSERT INTO tbl_service_complaint SET ProjectId='$ProjectId',ProjectSubHeadId='$ProjectSubHeadId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',RelatedIssue='$RelatedIssue',Issue = '$Issue',Message='$Message',CreatedDate='$CreatedDate',CreatedBy='$user_id',ClainStatus='$ClainStatus',BranchId='$BranchId',ServiceType='$ServiceType',Taluka='$Taluka',Village='$Village',District='$District',InstallationDate='$InstallationDate',RegistrationId='$RegistrationId',ComissioningDate='$ComissioningDate',WaterSource='$WaterSource',Category='$Category',ServiceSystem='$ServiceSystem',AcDc='$AcDc',Surface='$Surface',RecentVfdNo='$RecentVfdNo',RecentMotorNo='$RecentMotorNo',RecentPumpNo='$RecentPumpNo',Problem='$Problem',Remark='$Remark',Photos='$Photos',InspectionDate='$InspectionDate',InspectionBy='$InspectionBy',LastDate='$LastDate',SystemStatus='$SystemStatus',Extra='$Extra',ExtraPump='$ExtraPump',ExtraVfd='$ExtraVfd',PolicyNo='$PolicyNo',ClainDone='$ClainDone',Rms='$Rms',VfdProblem='$VfdProblem',PlateDamageInsurance='$PlateDamageInsurance',PumpProblem='$PumpProblem',MotorProblem='$MotorProblem',PhotoReceived='$PhotoReceived',VideoReceived='$VideoReceived',LetterReceived='$LetterReceived',LastCallUpdate='$LastCallUpdate',RecentProblem='$RecentProblem',SystemWorking='$SystemWorking',PolicyPeriod='$PolicyPeriod',Depth='$Depth',PumpMake='$PumpMake',MotorMake='$MotorMake',InsuranceCompany='$InsuranceCompany',BeneficiaryId='$BeneficiaryId',InsuranceComplaint='$InsuranceComplaint',ComplaintDate='$ComplaintDate'";
  $conn->query($qx);
  $PostId = mysqli_insert_id($conn);
  $TicketNo= "#".rand(1000,9999);
  $sql = "UPDATE tbl_service_complaint SET TicketNo='$TicketNo' WHERE id='$PostId'";
  $conn->query($sql);
  echo "<script>alert('Service Complaint Created Successfully!');window.location.href='view-customer-complaints.php?custid=$CustId';</script>";
}
else{
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_service_complaint SET ProjectId='$ProjectId',ProjectSubHeadId='$ProjectSubHeadId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',RelatedIssue='$RelatedIssue',Issue = '$Issue',Message='$Message',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',ClainStatus='$ClainStatus',BranchId='$BranchId',ServiceType='$ServiceType',Taluka='$Taluka',Village='$Village',District='$District',InstallationDate='$InstallationDate',RegistrationId='$RegistrationId',ComissioningDate='$ComissioningDate',WaterSource='$WaterSource',Category='$Category',ServiceSystem='$ServiceSystem',AcDc='$AcDc',Surface='$Surface',RecentVfdNo='$RecentVfdNo',RecentMotorNo='$RecentMotorNo',RecentPumpNo='$RecentPumpNo',Problem='$Problem',Remark='$Remark',Photos='$Photos',InspectionDate='$InspectionDate',InspectionBy='$InspectionBy',LastDate='$LastDate',SystemStatus='$SystemStatus',Extra='$Extra',ExtraPump='$ExtraPump',ExtraVfd='$ExtraVfd',PolicyNo='$PolicyNo',ClainDone='$ClainDone',Rms='$Rms',VfdProblem='$VfdProblem',PlateDamageInsurance='$PlateDamageInsurance',PumpProblem='$PumpProblem',MotorProblem='$MotorProblem',PhotoReceived='$PhotoReceived',VideoReceived='$VideoReceived',LetterReceived='$LetterReceived',LastCallUpdate='$LastCallUpdate',RecentProblem='$RecentProblem',SystemWorking='$SystemWorking',PolicyPeriod='$PolicyPeriod',Depth='$Depth',PumpMake='$PumpMake',MotorMake='$MotorMake',InsuranceCompany='$InsuranceCompany',BeneficiaryId='$BeneficiaryId',InsuranceComplaint='$InsuranceComplaint',ComplaintDate='$ComplaintDate' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Service Complaint Updated Successfully!');window.location.href='view-customer-complaints.php?custid=$CustId';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Service Complaint</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
 if($_GET['CustId'] == ''){
$sql12 = "SELECT ti.* FROM tbl_installations ti INNER JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1 ORDER BY ti.CustName ASC";
 }
 else{
  $sql12 = "SELECT ti.* FROM tbl_installations ti INNER JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1 AND ti.CustId='".$_GET['CustId']."'";
}
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($CustId2 == $result['CustId']) {?> selected <?php } ?> value="<?php echo $result['CustId'];?>">
    <?php echo $result['CustName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-3">
                                            <label class="form-label">Beneficiary ID </label>
                                            <input type="text" name="BeneficiaryId" id="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $BeneficiaryId; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $CellNo; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-6">
   <label class="form-label">Customer/Farmer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $CustName; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 


  <div class="form-group col-md-6">
            <label class="form-label">Project <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectId" name="ProjectId" required onchange="getSubHead(this.value)">
<option selected="" disabled="">Select Project</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($ProjectId==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

     <div class="form-group col-md-6">
            <label class="form-label">Project Sub Head <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId" required>
<option selected="" disabled="">Select Sub Head</option>
  <?php 
        $q = "select * from tbl_project_sub_head WHERE Status='1' AND UnderBy='$ProjectId'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($ProjectSubHeadId==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo $Address; ?></textarea>
    <div class="clearfix"></div>
 </div>  

 <div class="form-group col-md-4">
   <label class="form-label">Taluka </label>
     <input type="text" name="Taluka" id="Taluka" class="form-control"
                                                placeholder="" value="<?php echo $Taluka; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Village </label>
     <input type="text" name="Village" id="Village" class="form-control"
                                                placeholder="" value="<?php echo $Village; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">District </label>
     <input type="text" name="District" id="District" class="form-control"
                                                placeholder="" value="<?php echo $District; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 


<div class="form-group col-md-3">
   <label class="form-label">Complaint Date <span class="text-danger">*</span></label>
     <input type="date" name="ComplaintDate" id="ComplaintDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ComplaintDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <!-- <div class="form-group col-md-3">
   <label class="form-label">Registration ID </label>
     <input type="text" name="RegistrationId" id="RegistrationId" class="form-control"
                                                placeholder="" value="<?php echo $row7["RegistrationId"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3">
   <label class="form-label">Date Of Comissioning </label>
     <input type="date" name="ComissioningDate" id="ComissioningDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ComissioningDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>  -->

 <!-- <div class="form-group col-md-4">
   <label class="form-label">Source </label>
     <input type="text" name="WaterSource" id="Source" class="form-control"
                                                placeholder="" value="<?php echo $row7["WaterSource"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Depth </label>
     <input type="text" name="Depth" id="Depth" class="form-control"
                                                placeholder="" value="<?php echo $row7["Depth"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">System </label>
     <input type="text" name="ServiceSystem" id="ServiceSystem" class="form-control"
                                                placeholder="" value="<?php echo $row7["ServiceSystem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">AC/DC </label>
     <input type="text" name="AcDc" id="AcDc" class="form-control"
                                                placeholder="" value="<?php echo $row7["AcDc"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Surface/Submersible </label>
     <input type="text" name="Surface" id="Surface" class="form-control"
                                                placeholder="" value="<?php echo $row7["Surface"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>  -->

 <!-- <div class="form-group col-md-4">
   <label class="form-label">Previous VFD No </label>
     <input type="text" name="PrevVfdNo" id="PrevVfdNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PrevVfdNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Previous Motor No </label>
     <input type="text" name="PrevMotorNo" id="PrevMotorNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PrevMotorNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Previous Pump No </label>
     <input type="text" name="PrevPumpNo" id="PrevPumpNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PrevPumpNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> -->

<!--  <div class="form-group col-md-4">
   <label class="form-label">Recent VFD No </label>
     <input type="text" name="RecentVfdNo" id="RecentVfdNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["RecentVfdNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Recent Motor No </label>
     <input type="text" name="RecentMotorNo" id="RecentMotorNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["RecentMotorNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Recent Pump No </label>
     <input type="text" name="RecentPumpNo" id="RecentPumpNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["RecentPumpNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Pump Make </label>
     <input type="text" name="PumpMake" id="PumpMake" class="form-control"
                                                placeholder="" value="<?php echo $row7["PumpMake"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Motor Make </label>
     <input type="text" name="MotorMake" id="MotorMake" class="form-control"
                                                placeholder="" value="<?php echo $row7["MotorMake"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> -->

<div class="form-group col-md-3">
<label class="form-label"> Service Type<span class="text-danger">*</span></label>
 <select class="form-control" name="ServiceType" id="ServiceType" required>

<!-- 

  <option value="Insurance" <?php if($row7['ServiceType'] == 'Insurance'){?> selected <?php } ?>>Insurance</option> -->
    <option value="Maintaince" <?php if($row7['ServiceType'] == 'Maintaince'){?> selected <?php } ?>>Maintaince</option>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-lg-3">
<label class="form-label"> Type Of Insurance Complaint</label>
 <select class="form-control" name="InsuranceComplaint" id="InsuranceComplaint">
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Roll='5'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['InsuranceComplaint'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

  <div class="form-group col-md-3">
<label class="form-label"> Service Related Issue</label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue">

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($row7['RelatedIssue'] == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($row7['RelatedIssue'] == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-lg-3">
<label class="form-label"> Issue</label>
 <select class="form-control" name="Issue" id="Issue">
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-3">
<label class="form-label"> Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>



<div class="form-group col-md-12">
   <label class="form-label">Problems </label>
   <select class="select2-demo form-control" multiple style="width: 100%" name="Problem[]" id="Problem">
<?php  
 $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
    if($_GET['id'] == ''){
    ?>
<option value="<?php echo $result['Name'];?>" ><?php echo $result['Name'];?></option>
  <?php } else {?>
    <option value="<?php echo $result['Name'];?>" <?php if(in_array($result["Name"],$row7['Problem'])) { ?> selected <?php } ?>><?php echo $result['Name'];?></option>
  <?php  } }  ?>

</select>
    
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Remark/Requirement </label>
     <input type="text" name="Remark" id="Remark" class="form-control"
                                                placeholder="" value="<?php echo $row7["Remark"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <!-- <div class="form-group col-md-4">
   <label class="form-label">Photos/Video </label>
     <input type="text" name="Photos" id="Photos" class="form-control"
                                                placeholder="" value="<?php echo $row7["Photos"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Inspection Date </label>
     <input type="date" name="InspectionDate" id="InspectionDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Inspection By </label>
     <input type="text" name="InspectionBy" id="InspectionBy" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionBy"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Last </label>
     <input type="date" name="LastDate" id="LastDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["LastDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">System ok or Not </label>
     <input type="text" name="SystemStatus" id="SystemStatus" class="form-control"
                                                placeholder="" value="<?php echo $row7["SystemStatus"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Extra Motor</label>
     <input type="text" name="Extra" id="Extra" class="form-control"
                                                placeholder="" value="<?php echo $row7["Extra"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-3">
   <label class="form-label">Extra Pump</label>
     <input type="text" name="ExtraPump" id="ExtraPump" class="form-control"
                                                placeholder="" value="<?php echo $row7["ExtraPump"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>


 <div class="form-group col-md-3">
   <label class="form-label">Extra VFD</label>
     <input type="text" name="ExtraVfd" id="ExtraVfd" class="form-control"
                                                placeholder="" value="<?php echo $row7["ExtraVfd"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-6">
   <label class="form-label">Name Of Insurance Company</label>
     <input type="text" name="InsuranceCompany" id="InsuranceCompany" class="form-control"
                                                placeholder="" value="<?php echo $row7["InsuranceCompany"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Insurance Policy Number</label>
     <input type="text" name="PolicyNo" id="PolicyNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PolicyNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Insurance Policy Period</label>
     <input type="text" name="PolicyPeriod" id="PolicyPeriod" class="form-control"
                                                placeholder="" value="<?php echo $row7["PolicyPeriod"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">If any claim done</label>
     <input type="text" name="ClainDone" id="ClainDone" class="form-control"
                                                placeholder="" value="<?php echo $row7["ClainDone"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">RMS Updated Upto</label>
     <input type="text" name="Rms" id="Rms" class="form-control"
                                                placeholder="" value="<?php echo $row7["Rms"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">VFD problem</label>
     <input type="text" name="VfdProblem" id="VfdProblem" class="form-control"
                                                placeholder="" value="<?php echo $row7["VfdProblem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">if plate damage insurance claim or not</label>
     <input type="text" name="PlateDamageInsurance" id="PlateDamageInsurance" class="form-control"
                                                placeholder="" value="<?php echo $row7["PlateDamageInsurance"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-4">
   <label class="form-label">Pump problem</label>
     <input type="text" name="PumpProblem" id="PumpProblem" class="form-control"
                                                placeholder="" value="<?php echo $row7["PumpProblem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Motor problem</label>
     <input type="text" name="MotorProblem" id="MotorProblem" class="form-control"
                                                placeholder="" value="<?php echo $row7["MotorProblem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-4">
   <label class="form-label">Photo received</label>
     <input type="text" name="PhotoReceived" id="PhotoReceived" class="form-control"
                                                placeholder="" value="<?php echo $row7["PhotoReceived"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Video received</label>
     <input type="text" name="VideoReceived" id="VideoReceived" class="form-control"
                                                placeholder="" value="<?php echo $row7["VideoReceived"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Letter received</label>
     <input type="text" name="LetterReceived" id="LetterReceived" class="form-control"
                                                placeholder="" value="<?php echo $row7["LetterReceived"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Last calling update</label>
     <input type="date" name="LastCallUpdate" id="LastCallUpdate" class="form-control"
                                                placeholder="" value="<?php echo $row7["LastCallUpdate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Recent Problem</label>
     <input type="text" name="RecentProblem" id="RecentProblem" class="form-control"
                                                placeholder="" value="<?php echo $row7["RecentProblem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">System working properly</label>
     <input type="text" name="SystemWorking" id="SystemWorking" class="form-control"
                                                placeholder="" value="<?php echo $row7["SystemWorking"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> -->

<!-- <div class="form-group col-md-12">
   <label class="form-label">Message</label>
      <textarea  type="text" name="Message" id="Message" class="form-control"><?php echo $row7['Message']; ?></textarea>
    <div class="clearfix"></div>
 </div>   --> 


 

</div>
<br>

                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
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

<script>
  $(document).ready(function() {
  $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    
                    $('#Address').val(data.Taluka+", "+data.Village+", "+data.District);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                    $('#Gname').val(data.Gname);
                    $('#BeneficiaryId').val(data.BeneficiaryId);
                    
                }
            });

        });
  });
</script>
</body>

</html>