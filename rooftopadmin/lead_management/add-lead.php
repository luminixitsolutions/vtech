<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

$user_id = (int) $_SESSION['Admin']['id'];
$rowUser = getRecord("SELECT * FROM tbl_users WHERE id='$user_id' LIMIT 1");
$defaultBranchId = (int) ($rowUser['RooftopBranchId'] ?? $rowUser['BranchId'] ?? 0);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$row7 = [];
$saveError = '';

if ($id > 0) {
    $row7 = getRecord("SELECT * FROM tbl_rooftop_leads WHERE id='$id' LIMIT 1");
}

if (isset($_POST['submit'])) {
    mysqli_report(MYSQLI_REPORT_OFF);

    $CustId = (int) trim((string) ($_POST['CustId'] ?? ''));
    $CellNo = addslashes(trim((string) ($_POST['CellNo'] ?? '')));
    $CustName = addslashes(trim((string) ($_POST['CustName'] ?? '')));
    $Status = (int) trim((string) ($_POST['Status'] ?? '1'));
    $Address = addslashes(trim((string) ($_POST['Address'] ?? '')));
    $DocumentsStatus = addslashes(trim((string) ($_POST['DocumentsStatus'] ?? '')));
    $ClainReason = addslashes(trim((string) ($_POST['ClainReason'] ?? '')));
    $ClainStatus = addslashes(trim((string) ($_POST['ClainStatus'] ?? '')));
    $BranchId = (int) trim((string) ($_POST['BranchId'] ?? '0'));
    if ($BranchId <= 0) {
        $BranchId = $defaultBranchId;
    }

    $CreatedDate = date('Y-m-d');
    $ModifiedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $postId = (int) trim((string) ($_POST['id'] ?? ''));

    if ($postId <= 0) {
        $qx = "INSERT INTO tbl_rooftop_leads SET CustId='$CustId',CellNo='$CellNo',CustName='$CustName',Status='$Status',Address='$Address',DocumentsStatus='$DocumentsStatus',ClainReason='$ClainReason',ClainStatus='$ClainStatus',CreatedDate='$CreatedDate',CreatedBy='$user_id',BranchId='$BranchId',ModifiedDate='$CreatedDate',ModifiedBy='0',AllocateId='0',OppConverted='0',customer_id='0',DoneBy='0'";

        if ($conn->query($qx)) {
            $PostId = (int) mysqli_insert_id($conn);
            $TicketNo = '#' . rand(1000, 9999);
            $conn->query("UPDATE tbl_rooftop_leads SET TicketNo='$TicketNo' WHERE id='$PostId'");

            $codeSeed = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'), 0, 10);
            $Code = addslashes($codeSeed . $PostId);
            $conn->query("UPDATE tbl_rooftop_leads SET code='$Code' WHERE id='$PostId'");

            $Steps = 'Customer In Leads';
            $conn->query("INSERT INTO tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadSource='$ClainReason',EmpId='$CustId',LeadId='$PostId'");

            echo "<script>alert('Lead Record Saved Successfully!');window.location.href='view-leads.php';</script>";
            exit;
        }

        $saveError = $conn->error ?: 'Unable to save lead. Please try again.';
    } else {
        $query2 = "UPDATE tbl_rooftop_leads SET CustId='$CustId',CellNo='$CellNo',CustName='$CustName',Status='$Status',Address='$Address',DocumentsStatus='$DocumentsStatus',ClainReason='$ClainReason',ClainStatus='$ClainStatus',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',BranchId='$BranchId' WHERE id='$postId'";

        if ($conn->query($query2)) {
            echo "<script>alert('Lead Record Updated Successfully!');window.location.href='view-leads.php';</script>";
            exit;
        }

        $saveError = $conn->error ?: 'Unable to update lead. Please try again.';
        $id = $postId;
        $row7 = getRecord("SELECT * FROM tbl_rooftop_leads WHERE id='$postId' LIMIT 1");
    }
}

$MainPage = 'Lead';
$Page = 'Add-Lead';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Add Lead</h5>
                        
<div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <?php if ($saveError !== '') { ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($saveError, ENT_QUOTES, 'UTF-8'); ?></div>
                                <?php } ?>
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo (int) $id; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                   
      
        <div class="form-group col-md-8">
   <label class="form-label">Customer Name <span class="text-danger">*</span></label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo htmlspecialchars($row7['CustName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 
 

<div class="form-group col-md-4">
                                            <label class="form-label">Contact No <span class="text-danger">*</span></label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo htmlspecialchars($row7['CellNo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                autocomplete="off" oninput="getUserDetails()" required>
                                            <div class="clearfix"></div>
                                        </div>


 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo htmlspecialchars($row7['Address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    <div class="clearfix"></div>
 </div>   


 <div class="form-group col-lg-3">
<label class="form-label"> Lead Source<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainReason" id="ClainReason" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_common_master WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainReason'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Dealer/Employee/Users </label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
     <option selected="" value="">Select </option>
  <optgroup label="Dealer">    
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=9";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>

<optgroup label="Employee">
     <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(2,6,7,12)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</optgroup>



</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label"> Lead Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_common_master WHERE Status='1' AND Roll=11";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


  <div class="form-group col-md-2">
       <label class="form-label">Status <span class="text-danger">*</span></label>
        <select class="form-control" id="Status" name="Status" required="">
            <option selected="" disabled="" value="">Select Status</option>
            <option value="1" <?php if($row7["Status"]=='1') {?> selected
            <?php } ?>>Active</option>
            <option value="0" <?php if($row7["Status"]=='0') {?> selected
            <?php } ?>>Inctive</option>
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



  
                                

 </div>
 </form>





                            </div>
                        </div>
                        



					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
</body>

</html>
