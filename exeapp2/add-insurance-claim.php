<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Insurance";
$Page = "Add-Insurance-Claim";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
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
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_service_complaint WHERE id='$id'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $InsuranceClaimDate = addslashes(trim($_POST["InsuranceClaimDate"]));
     $ClainReason = addslashes(trim($_POST["ClainReason"]));
    $DamageDate = addslashes(trim($_POST["DamageDate"]));
$Intimation = addslashes(trim($_POST["Intimation"]));
$MailSent = addslashes(trim($_POST['MailSent']));
$DocumentSent = addslashes(trim($_POST["DocumentSent"]));
$SurveyorVisitDate = addslashes(trim($_POST["SurveyorVisitDate"]));
$DocketNo = addslashes(trim($_POST["DocketNo"]));
$InspectionDetail = addslashes(trim($_POST["InspectionDetail"]));
$InsuranceClainStatus = addslashes(trim($_POST["InsuranceClainStatus"]));
$Amount = addslashes(trim($_POST["Amount"]));
$InstallationMaterial = addslashes(trim($_POST["InstallationMaterial"]));
$SiteInspection = addslashes(trim($_POST["SiteInspection"]));
$ClaimClosed = addslashes(trim($_POST["ClaimClosed"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


    $query2 = "UPDATE tbl_service_complaint SET InsuranceClaimDate='$InsuranceClaimDate',ClainReason='$ClainReason',DamageDate = '$DamageDate',Intimation='$Intimation',MailSent='$MailSent',DocumentSent='$DocumentSent',SurveyorVisitDate = '$SurveyorVisitDate',DocketNo='$DocketNo',InspectionDetail='$InspectionDetail',InsuranceClainStatus='$InsuranceClainStatus',Amount='$Amount',InstallationMaterial='$InstallationMaterial',SiteInspection='$SiteInspection',ClaimClosed='$ClaimClosed',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Insurance Claim Record Updated Successfully!');window.location.href='view-service-module.php';</script>";


    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"> Insurance Claim Process</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     
  <div class="form-group col-md-4">
   <label class="form-label">Date Of Insurance Claim</label>
     <input type="date" name="InsuranceClaimDate" id="InsuranceClaimDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InsuranceClaimDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-lg-4">
<label class="form-label"> Reason for insurance claim<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainReason" id="ClainReason" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=5";
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
   <label class="form-label">Date Of Damage</label>
     <input type="date" name="DamageDate" id="DamageDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DamageDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-12">
   <label class="form-label">Intimation send to insurance company for Damage/Theft</label>
     <input type="text" name="Intimation" id="Intimation" class="form-control"
                                                placeholder="" value="<?php echo $row7["Intimation"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Mail Sent to insurance Company regarding the surveyor appointment</label>
     <input type="text" name="MailSent" id="MailSent" class="form-control"
                                                placeholder="" value="<?php echo $row7["MailSent"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-md-12">
   <label class="form-label">Documents Sent to insurance Company</label>
     <input type="text" name="DocumentSent" id="DocumentSent" class="form-control"
                                                placeholder="" value="<?php echo $row7["DocumentSent"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>


 <div class="form-group col-md-4">
   <label class="form-label">Surveyor Visit Date</label>
     <input type="date" name="SurveyorVisitDate" id="SurveyorVisitDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SurveyorVisitDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Claim Notification No./ Docket No</label>
     <input type="text" name="DocketNo" id="DocketNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["DocketNo"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>


 <div class="form-group col-md-4">
   <label class="form-label">Inspection Detail Received</label>
     <input type="text" name="InspectionDetail" id="InspectionDetail" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionDetail"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>








 

<div class="form-group col-lg-4">
<label class="form-label"> Insurance Claim Status<span class="text-danger">*</span></label>
 <select class="form-control" name="InsuranceClainStatus" id="InsuranceClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['InsuranceClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
   <label class="form-label">Claim Amount Received From Insurance Company</label>
     <input type="text" name="Amount" id="Amount" class="form-control"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-4">
   <label class="form-label">Installation of Material at site</label>
     <input type="text" name="InstallationMaterial" id="InstallationMaterial" class="form-control"
                                                placeholder="" value="<?php echo $row7["InstallationMaterial"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Site Inspection</label>
     <input type="text" name="SiteInspection" id="SiteInspection" class="form-control"
                                                placeholder="" value="<?php echo $row7["SiteInspection"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-4">
   <label class="form-label">Claim Closed</label>
     <input type="text" name="ClaimClosed" id="ClaimClosed" class="form-control"
                                                placeholder="" value="<?php echo $row7["ClaimClosed"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<!-- <div class="form-group col-md-8">
   <label class="form-label">Insurance Claim Payment Received</label>
     <input type="text" name="DocumentSent" id="DocumentSent" class="form-control"
                                                placeholder="" value="<?php echo $row7["DocumentSent"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>


 
<div class="form-group col-md-8">
   <label class="form-label">Material Dispatch to Beneficiers site</label>
     <input type="text" name="MaterialDispatch" id="MaterialDispatch" class="form-control"
                                                placeholder="" value="<?php echo $row7["MaterialDispatch"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> -->

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

             </main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
<script type="text/javascript">
 
    function getUserDetails(){
        var CellNo = $('#CellNo').val();
        var action = "getUserDetails2";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    CellNo: CellNo
                },
                dataType:"json",  
                success: function(data) {
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.Fname+" "+data.Lname);
                    $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                    
                }
            });

    }
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
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                }
            });

        });


    });

     
 </script>
</body>

</html>