<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-insurance-site.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "Add-Service-Complaint";

$id = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$complaintId = (int) $id;
$row7 = $complaintId > 0 ? getRecord("SELECT * FROM tbl_service_complaint WHERE id='$complaintId'") : null;

if (isset($_POST['submit'])) {
    $CustId = $conn->real_escape_string(trim($_POST['CustId'] ?? ''));
    $CellNo = $conn->real_escape_string(trim($_POST['CellNo'] ?? ''));
    $CustName = $conn->real_escape_string(trim($_POST['CustName'] ?? ''));
    $BeneficiaryId = $conn->real_escape_string(trim($_POST['BeneficiaryId'] ?? ''));
    $Address = $conn->real_escape_string(trim($_POST['Address'] ?? ''));
    $RelatedIssue = $conn->real_escape_string(trim($_POST['RelatedIssue'] ?? ''));
    $ClainStatus = $conn->real_escape_string(trim($_POST['ClainStatus'] ?? ''));
    $ServiceType = $conn->real_escape_string(trim($_POST['ServiceType'] ?? 'Insurance'));
    $Taluka = $conn->real_escape_string(trim($_POST['Taluka'] ?? ''));
    $Village = $conn->real_escape_string(trim($_POST['Village'] ?? ''));
    $District = $conn->real_escape_string(trim($_POST['District'] ?? ''));
    $InsuranceComplaint = $conn->real_escape_string(trim($_POST['InsuranceComplaint'] ?? ''));
    $ComplaintDate = $conn->real_escape_string(trim($_POST['ComplaintDate'] ?? ''));
    $Remark = $conn->real_escape_string(trim($_POST['Remark'] ?? ''));
    $DocReq = $conn->real_escape_string(trim($_POST['DocReq'] ?? 'No'));
    $OrgDocReq = $conn->real_escape_string(trim($_POST['OrgDocReq'] ?? 'No'));
    $SurveyUpdate = $conn->real_escape_string(trim($_POST['SurveyUpdate'] ?? 'No'));
    $ClaimAmt = $conn->real_escape_string(trim($_POST['ClaimAmt'] ?? ''));
    $InsuranceApproved = $conn->real_escape_string(trim($_POST['InsuranceApproved'] ?? 'No'));
    $PaymentReceived = $conn->real_escape_string(trim($_POST['PaymentReceived'] ?? 'No'));
    $AmountReceived = $conn->real_escape_string(trim($_POST['AmountReceived'] ?? ''));
    $MaterialReplacement = $conn->real_escape_string(trim($_POST['MaterialReplacement'] ?? 'No'));
    $CreatedDate = date('Y-m-d');
    $ModifiedDate = date('Y-m-d');
    $Status = 1;

    if ($complaintId <= 0) {
        $qx = "INSERT INTO tbl_service_complaint SET
            CustId='$CustId',
            CellNo='$CellNo',
            CustName='$CustName',
            Status='$Status',
            Address='$Address',
            RelatedIssue='$RelatedIssue',
            CreatedDate='$CreatedDate',
            CreatedBy='$user_id',
            ClainStatus='$ClainStatus',
            ServiceType='$ServiceType',
            Taluka='$Taluka',
            Village='$Village',
            District='$District',
            BeneficiaryId='$BeneficiaryId',
            InsuranceComplaint='$InsuranceComplaint',
            ComplaintDate='$ComplaintDate',
            Remark='$Remark',
            DocReq='$DocReq',
            OrgDocReq='$OrgDocReq',
            SurveyUpdate='$SurveyUpdate',
            ClaimAmt='$ClaimAmt',
            InsuranceApproved='$InsuranceApproved',
            PaymentReceived='$PaymentReceived',
            AmountReceived='$AmountReceived',
            MaterialReplacement='$MaterialReplacement',
            ComplaintClose='No'";
        $conn->query($qx);
        $PostId = (int) mysqli_insert_id($conn);
        $TicketNo = '#' . rand(1000, 9999);
        $conn->query("UPDATE tbl_service_complaint SET TicketNo='$TicketNo' WHERE id='$PostId'");
        echo "<script>alert('Service Complaint Created Successfully!');window.location.href='view-service-module.php';</script>";
        exit;
    }

    $query2 = "UPDATE tbl_service_complaint SET
        CustId='$CustId',
        CellNo='$CellNo',
        CustName='$CustName',
        Status='$Status',
        Address='$Address',
        RelatedIssue='$RelatedIssue',
        ModifiedDate='$ModifiedDate',
        ModifiedBy='$user_id',
        ClainStatus='$ClainStatus',
        ServiceType='$ServiceType',
        Taluka='$Taluka',
        Village='$Village',
        District='$District',
        BeneficiaryId='$BeneficiaryId',
        InsuranceComplaint='$InsuranceComplaint',
        ComplaintDate='$ComplaintDate',
        Remark='$Remark',
        DocReq='$DocReq',
        OrgDocReq='$OrgDocReq',
        SurveyUpdate='$SurveyUpdate',
        ClaimAmt='$ClaimAmt',
        InsuranceApproved='$InsuranceApproved',
        PaymentReceived='$PaymentReceived',
        AmountReceived='$AmountReceived',
        MaterialReplacement='$MaterialReplacement'
        WHERE id='$complaintId'";
    $conn->query($query2);
    echo "<script>alert('Service Complaint Updated Successfully!');window.location.href='view-service-module.php';</script>";
    exit;
}

if ($complaintId <= 0) {
    $CustId2 = isset($_GET['CustId']) ? $_GET['CustId'] : '';
    $row3 = $CustId2 !== '' ? getRecord("SELECT id,Phone,CustomerId,Fname,Address,Taluka,Village,District,BeneficiaryId FROM tbl_users WHERE id='$CustId2'") : null;
    $CellNo = $row3['Phone'] ?? '';
    $CustName = $row3['Fname'] ?? '';
    $Address = $row3['Address'] ?? '';
    $Taluka = $row3['Taluka'] ?? '';
    $Village = $row3['Village'] ?? '';
    $District = $row3['District'] ?? '';
    $BeneficiaryId = $row3['BeneficiaryId'] ?? '';
    $row7 = $row7 ?: [];
} else {
    $CustId2 = $row7['CustId'] ?? '';
    $CellNo = $row7['CellNo'] ?? '';
    $CustName = $row7['CustName'] ?? '';
    $Address = $row7['Address'] ?? '';
    $Taluka = $row7['Taluka'] ?? '';
    $Village = $row7['Village'] ?? '';
    $District = $row7['District'] ?? '';
    $BeneficiaryId = $row7['BeneficiaryId'] ?? '';
    $row7 = $row7 ?: [];
}
$customerInsurance = ($CustId2 !== '' && (int) $CustId2 > 0)
    ? insuranceGetLatestCustomerInsurance((int) $CustId2)
    : null;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($complaintId > 0) {?>Edit <?php } else{?> Add <?php } ?> Insurance Service Complaint
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
    .insurance-info-block {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .insurance-info-block .form-control[readonly] {
        background: #fff;
    }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($complaintId > 0) {?>Edit <?php } else{?> Add
                            <?php } ?> Insurance Service Complaint</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <input type="hidden" name="ServiceType" id="ServiceType" value="Insurance">
                                    <div class="form-row">
                                    
                                     
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
 if($CustId2 == ''){
$sql12 = "SELECT ti.* FROM tbl_installations ti INNER JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1 ORDER BY ti.CustName ASC";
 }
 else{
  $sql12 = "SELECT ti.* FROM tbl_installations ti INNER JOIN tbl_users tu ON ti.CustId=tu.id WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1 AND ti.CustId='".$conn->real_escape_string($CustId2)."'";
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

<div class="form-group col-md-12">
    <div class="insurance-info-block">
        <h6 class="font-weight-bold mb-2">Latest Insurance Details</h6>
        <p class="text-muted small mb-3" id="insuranceSourceNote"><?php
            if (!empty($customerInsurance)) {
                echo 'Source: ' . htmlspecialchars($customerInsurance['source_label'] ?? 'Insurance record');
            } else {
                echo 'Select a customer to load insurance from Completed / Renewed Insurance.';
            }
        ?></p>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label class="form-label">Insurance No</label>
                <input type="text" id="insInsuranceNo" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['insurance_no'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-3">
                <label class="form-label">Insurance Company Name</label>
                <input type="text" id="insCompanyName" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['company_name'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-label">Date Of Issue</label>
                <input type="text" id="insDateOfIssue" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['date_of_issue_display'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-label">Date Of Expiry</label>
                <input type="text" id="insDateOfExpiry" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['date_of_expiry_display'] ?? ''); ?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-label">No of Year</label>
                <input type="text" id="insNoOfYears" class="form-control" readonly value="<?php echo htmlspecialchars($customerInsurance['no_of_years'] ?? ''); ?>">
            </div>
        </div>
    </div>
</div>


<div class="form-group col-md-3">
   <label class="form-label">Complaint Date <span class="text-danger">*</span></label>
     <input type="date" name="ComplaintDate" id="ComplaintDate" class="form-control"
                                                placeholder="" value="<?php echo htmlspecialchars($row7['ComplaintDate'] ?? ''); ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 




 <div class="form-group col-lg-3">
<label class="form-label"> Type Of Insurance Complaint <span class="text-danger">*</span></label>
 <select class="form-control" name="InsuranceComplaint" id="InsuranceComplaint" required>
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
</div> 

  <div class="form-group col-md-3">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" required>

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
</div>  -->

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
   <label class="form-label">Remark/Requirement </label>
     <input type="text" name="Remark" id="Remark" class="form-control"
                                                placeholder="" value="<?php echo htmlspecialchars($row7['Remark'] ?? ''); ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-3">
  <label class="form-label"> Document Required<span class="text-danger">*</span></label>
   <select class="form-control" name="DocReq" id="DocReq" required>
    <option value="No" <?php if($row7['DocReq'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['DocReq'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>

<div class="form-group col-md-5">
  <label class="form-label"> Original Doc Submit to insurance Company and also to Surveyor <span class="text-danger">*</span></label>
   <select class="form-control" name="OrgDocReq" id="OrgDocReq" required>
    <option value="No" <?php if($row7['OrgDocReq'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['OrgDocReq'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
  <label class="form-label"> Survey update report by surveyor <span class="text-danger">*</span></label>
   <select class="form-control" name="SurveyUpdate" id="SurveyUpdate" required>
    <option value="No" <?php if($row7['SurveyUpdate'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['SurveyUpdate'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>



 <div class="form-group col-md-3">
   <label class="form-label">Claim Amount </label>
     <input type="text" name="ClaimAmt" id="ClaimAmt" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row7['ClaimAmt'] ?? ''); ?>" autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-3">
  <label class="form-label"> Insurance Approved or not  <span class="text-danger">*</span></label>
   <select class="form-control" name="InsuranceApproved" id="InsuranceApproved" required>
    <option value="No" <?php if($row7['InsuranceApproved'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['InsuranceApproved'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
  <label class="form-label"> Payment received  <span class="text-danger">*</span></label>
   <select class="form-control" name="PaymentReceived" id="PaymentReceived" required>
    <option value="No" <?php if($row7['PaymentReceived'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['PaymentReceived'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
   <label class="form-label">Amount Received </label>
     <input type="text" name="AmountReceived" id="AmountReceived" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row7['AmountReceived'] ?? ''); ?>" autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-3">
  <label class="form-label"> Material Replacement Done  <span class="text-danger">*</span></label>
   <select class="form-control" name="MaterialReplacement" id="MaterialReplacement" required>
    <option value="No" <?php if($row7['MaterialReplacement'] == 'No'){?> selected <?php } ?>>No</option>
    <option value="Yes" <?php if($row7['MaterialReplacement'] == 'Yes'){?> selected <?php } ?>>Yes</option>
  </select>
  <div class="clearfix"></div>
</div>

 

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
  function clearInsuranceDetails(message) {
    $('#insInsuranceNo, #insCompanyName, #insDateOfIssue, #insDateOfExpiry, #insNoOfYears').val('');
    $('#insuranceSourceNote').text(message || 'Select a customer to load insurance from Completed / Renewed Insurance.');
  }

  function fillInsuranceDetails(data) {
    if (!data || !data.found) {
      clearInsuranceDetails(data && data.message ? data.message : 'No insurance record found for this customer.');
      return;
    }
    $('#insInsuranceNo').val(data.insurance_no || '');
    $('#insCompanyName').val(data.company_name || '');
    $('#insDateOfIssue').val(data.date_of_issue || '');
    $('#insDateOfExpiry').val(data.date_of_expiry || '');
    $('#insNoOfYears').val(data.no_of_years || '');
    $('#insuranceSourceNote').text(data.source_label ? ('Source: ' + data.source_label) : 'Latest insurance record loaded.');
  }

  function loadInsuranceForCustomer(custId) {
    if (!custId) {
      clearInsuranceDetails();
      return;
    }
    $.ajax({
      url: 'ajax-get-customer-insurance.php',
      method: 'POST',
      data: { CustId: custId },
      dataType: 'json',
      success: function(data) {
        fillInsuranceDetails(data);
      },
      error: function() {
        clearInsuranceDetails('Could not load insurance details.');
      }
    });
  }

  $(document).ready(function() {
    $(document).on('change', '#CustId', function() {
      var val = this.value;
      $.ajax({
        url: 'ajax_files/ajax_vendor.php',
        method: 'POST',
        data: { action: 'getUserDetails', id: val },
        dataType: 'json',
        success: function(data) {
          $('#Address').val(data.Taluka + ', ' + data.Village + ', ' + data.District);
          $('#CustName').val(data.Fname);
          $('#CellNo').val(data.Phone);
          $('#Gname').val(data.Gname);
          $('#BeneficiaryId').val(data.BeneficiaryId);
          $('#Taluka').val(data.Taluka);
          $('#Village').val(data.Village);
          $('#District').val(data.District);
        }
      });
      loadInsuranceForCustomer(val);
    });

    if ($('#CustId').val()) {
      loadInsuranceForCustomer($('#CustId').val());
    }
  });
</script>
</body>

</html>