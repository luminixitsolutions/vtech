<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "Add-Lead";
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
                        <h5 class="font-weight-bold py-3 mb-0">Add Lead Irrigation</h5>
                        
 <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_irrigation_leads WHERE id='$id'";
$row7 = getRecord($sql7);
$CurrentIrrigationMethod = $row7['CurrentIrrigationMethod'] ?? '';
$InterestedInIrrigation  = $row7['InterestedInIrrigation'] ?? '';
$CropsGrown              = $row7['CropsGrown'] ?? '';
$InterestedSoilTesting   = $row7['InterestedSoilTesting'] ?? '';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if(isset($_POST['submit'])){

    // Common values
    $CustId   = addslashes(trim($_POST["CustId"]));
    $CustName = addslashes(trim($_POST["CustName"]));
    $CellNo   = addslashes(trim($_POST["CellNo"]));
    $Address  = addslashes(trim($_POST["Address"]));

    $CurrentIrrigationMethod = addslashes(trim($_POST["CurrentIrrigationMethod"]));
    $InterestedInIrrigation  = addslashes(trim($_POST["InterestedInIrrigation"]));
    $CropsGrown              = addslashes(trim($_POST["CropsGrown"]));
    $InterestedSoilTesting   = addslashes(trim($_POST["InterestedSoilTesting"]));

    $ClainStatus   = addslashes(trim($_POST["ClainStatus"]));   // OR name it LeadStatus
    $BranchId = addslashes(trim($_POST["BranchId"] ?? '0'));
    $Status   = addslashes(trim($_POST["Status"])); 

    $CreatedDate = date('Y-m-d');
    $ModifiedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');


    /* *********************************************************
       NEW RECORD — INSERT
    ********************************************************** */
    if($id == ''){

        // 1️⃣ Insert record first
        $sql = "INSERT INTO tbl_irrigation_leads SET 
            CustId='$CustId',
            CustName='$CustName',
            CellNo='$CellNo',
            Address='$Address',

            CurrentIrrigationMethod='$CurrentIrrigationMethod',
            InterestedInIrrigation='$InterestedInIrrigation',
            CropsGrown='$CropsGrown',
            InterestedSoilTesting='$InterestedSoilTesting',
            ClainStatus='$ClainStatus',
            Status='$Status',
            BranchId='$BranchId',
            CreatedBy='$user_id',
            CreatedDate='$CreatedDate',
            CreatedTime='$CreatedTime'";

        $conn->query($sql);

        $LeadId = mysqli_insert_id($conn);

        // 2️⃣ Generate Ticket No like #1005 etc.
        $TicketNo = "#".rand(100,999).$LeadId;
        $conn->query("UPDATE tbl_irrigation_leads SET TicketNo='$TicketNo' WHERE id='$LeadId'");

        echo "<script>alert('Irrigation Lead Saved Successfully!');window.location.href='view-irrigation-leads.php';</script>";
    }



    /* *********************************************************
       EXISTING RECORD — UPDATE
    ********************************************************** */
    else {

        $sql = "UPDATE tbl_irrigation_leads SET 
            CustId='$CustId',
            CustName='$CustName',
            CellNo='$CellNo',
            Address='$Address',
            ClainStatus='$ClainStatus',
            CurrentIrrigationMethod='$CurrentIrrigationMethod',
            InterestedInIrrigation='$InterestedInIrrigation',
            CropsGrown='$CropsGrown',
            InterestedSoilTesting='$InterestedSoilTesting',

            Status='$Status',
            BranchId='$BranchId',
            ModifiedBy='$user_id',
            ModifiedDate='$ModifiedDate'
            WHERE id='$id'";

        $conn->query($sql);

        echo "<script>alert('Irrigation Lead Updated Successfully!');window.location.href='view-irrigation-leads.php';</script>";
    }
}
?>

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
<label class="form-label"> Customer</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT tu.id,tu.Fname,tu.Phone FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['CustId'] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>                  
      
        <div class="form-group col-md-8">
   <label class="form-label">Customer Name <span class="text-danger">*</span></label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 
 

<div class="form-group col-md-4">
                                            <label class="form-label">Contact No <span class="text-danger">*</span></label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" required>
                                            <div class="clearfix"></div>
                                        </div>


 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo $row7['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


<div class="form-group col-md-7">
    <label class="form-label">Current Method of Irrigation?</label>
    <input type="text" 
           name="CurrentIrrigationMethod" 
           id="CurrentIrrigationMethod" 
           class="form-control"
           placeholder="e.g. Flood / Canal / Borewell / Others"
           value="<?php echo isset($CurrentIrrigationMethod) ? $CurrentIrrigationMethod : ''; ?>"
           autocomplete="off">
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-5">
    <label class="form-label">Interested in Drip or Sprinkler Irrigation?</label>
    <select name="InterestedInIrrigation" 
            id="InterestedInIrrigation" 
            class="form-control">
        <option value="">Select</option>
        <option value="Yes" <?php if(isset($InterestedInIrrigation) && $InterestedInIrrigation=="Yes"){echo "selected";} ?>>Yes</option>
        <option value="No"  <?php if(isset($InterestedInIrrigation) && $InterestedInIrrigation=="No"){echo "selected";} ?>>No</option>
    </select>
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-7">
    <label class="form-label">Which crops are grown in the field?</label>
    <input type="text" 
           name="CropsGrown" 
           id="CropsGrown" 
           class="form-control"
           placeholder="e.g. Cotton, Wheat, Sugarcane"
           value="<?php echo isset($CropsGrown) ? $CropsGrown : ''; ?>"
           autocomplete="off">
    <div class="clearfix"></div>
</div>

<div class="form-group col-md-5">
    <label class="form-label">Interested for Soil Testing?</label>
    <select name="InterestedSoilTesting" 
            id="InterestedSoilTesting" 
            class="form-control">
        <option value="">Select</option>
        <option value="Yes" <?php if(isset($InterestedSoilTesting) && $InterestedSoilTesting=="Yes"){echo "selected";} ?>>Yes</option>
        <option value="No"  <?php if(isset($InterestedSoilTesting) && $InterestedSoilTesting=="No"){echo "selected";} ?>>No</option>
    </select>
    <div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label"> Lead Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=11";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


  <div class="form-group col-md-3">
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
   
   <!-- jQuery (FULL) -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

<script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>

<script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
<script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>

     

     <script>
         $(document).ready(function() {
         $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            var action = "getUserDetails";
            $.ajax({
                url: "../ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    console.log(data);
                   
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                   
                   
                    
                }
            });

        });
    });
     </script>
</body>

</html>
