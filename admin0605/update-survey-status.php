<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Pump-Survey";
$Page = "Telephonic-Survey";
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
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/pe-icon-7-stroke.css">
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

            <?php include_once 'header.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Telephonic Survey Status</h5>
                        
 <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);
if(isset($_POST['submit'])){
$SurveyDetails = addslashes(trim($_POST["SurveyDetails"]));
$Details = addslashes(trim($_POST["Details"]));
$TelLattitude = addslashes(trim($_POST["TelLattitude"]));
$TelLongitude = addslashes(trim($_POST["TelLongitude"]));
$TelWaterSource = addslashes(trim($_POST["TelWaterSource"]));
$TelBoreDia = addslashes(trim($_POST["TelBoreDia"]));
$TelTotalDepth = addslashes(trim($_POST["TelTotalDepth"]));
$TelSummerWaterLevel = addslashes(trim($_POST["TelSummerWaterLevel"]));
$TelWinterWaterLevel = addslashes(trim($_POST["TelWinterWaterLevel"]));
$TelPumpHead = addslashes(trim($_POST["TelPumpHead"]));
$TelNoc = addslashes(trim($_POST["TelNoc"]));
$TelNocDate = addslashes(trim($_POST["TelNocDate"]));
$TelSurface = addslashes(trim($_POST["TelSurface"]));
$TelPumpCapacity = addslashes(trim($_POST["TelPumpCapacity"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$SurveyFile = $imagepath2 ;
} 
else{
  $SurveyFile = $_POST['OldPhoto2'];
}


    $query2 = "UPDATE tbl_users SET SurveyDetails='$SurveyDetails',SurveyFile='$SurveyFile',SurveyDescription='$Details',TelLattitude='$TelLattitude',TelLongitude='$TelLongitude',TelWaterSource='$TelWaterSource',TelBoreDia='$TelBoreDia',TelTotalDepth='$TelTotalDepth',TelSummerWaterLevel='$TelSummerWaterLevel',TelWinterWaterLevel='$TelWinterWaterLevel',TelPumpHead='$TelPumpHead',TelSurveyBy='$user_id',TelNoc='$TelNoc',TelNocDate='$TelNocDate',TelSurface='$TelSurface',TelPumpCapacity='$TelPumpCapacity',TelSurveyDate='$CreatedDate' WHERE id = '$id'";
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
  echo "<script>alert('Survey Status Updated Successfully!');window.location.href='co-ordinator-survey.php';</script>";

    //header('Location:courses.php'); 

  }
  ?>

<div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">


<div class="form-group col-md-3">
<label class="form-label">Beneficiary ID </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Customer Name </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['Fname']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Village </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7["Village"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Taluka </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7["Taluka"]; ?>" readonly>
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
<input type="text" name="TelLattitude" id="TelLattitude" class="form-control" placeholder="" value="<?php echo $row7["TelLattitude"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="TelLongitude" id="TelLongitude" class="form-control" placeholder="" value="<?php echo $row7["TelLongitude"]; ?>">
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Pump </label>

                                            <select class="form-control" id="TelSurface" name="TelSurface" readonly>
<!-- <option selected="" disabled=""  value="">Select Type Of Pump</option> -->
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 AND id='".$row7['Surface']."' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['Surface']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>
                                       
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Capacity </label>

                                            <select class="form-control" id="TelPumpCapacity" name="TelPumpCapacity" >
<!-- <option selected="" disabled="" value="">Select Pump Capacity</option> -->
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 AND id='".$row7['PumpCapacity']."' ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpCapacity']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>
                                        
 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="TelWaterSource" name="TelWaterSource" onchange="source(this.value)">
<option selected="" disabled="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['TelWaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                         <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="TelBoreDia" name="TelBoreDia" >
<option selected="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['TelBoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-3">
<label class="form-label">Total Depth of water Source (in Meter) </label>
<input type="text" name="TelTotalDepth" id="TelTotalDepth" class="form-control" placeholder="" value="<?php echo $row7["TelTotalDepth"]; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Summer (in Meter) </label>
<input type="text" name="TelSummerWaterLevel" id="TelSummerWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["TelSummerWaterLevel"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Winter (in Meter) </label>
<input type="text" name="TelWinterWaterLevel" id="TelWinterWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["TelWinterWaterLevel"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Pump Head </label>
<input type="text" name="TelPumpHead" id="TelPumpHead" class="form-control" placeholder="" value="<?php echo $row7["TelPumpHead"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<!--  <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["SurveyDescription"]; ?></textarea>
<div class="clearfix"></div>
</div>
 -->



<div class="form-group col-md-12">
  <label class="form-label">Attach PDF File </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Photo2" style="opacity: 1;">
<input type="hidden" name="OldPhoto2" value="<?php echo $row7['SurveyFile'];?>" id="OldPhoto2">
<span class="custom-file-label"></span>
</label>
<?php if($row7['SurveyFile']=='') {} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo2"></a><a href="../uploads/<?php echo $row7['SurveyFile'];?>" alt="" target="_new"><?php echo $row7['SurveyFile'];?></a></div>
</span>
<?php } ?>
</div>


<!-- <input type="hidden" id="Status" name="Status" value="1"> -->
 <div class="form-group col-md-4">
<label class="form-label">NOC <span class="text-danger">*</span></label>
  <select class="form-control" id="TelNoc" name="TelNoc" required="">


<option value="No" <?php if($row7["TelNoc"]=='No') {?> selected <?php } ?>>No</option>
<option value="Yes" <?php if($row7["TelNoc"]=='Yes') {?> selected <?php } ?>>Yes</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-4">
<label class="form-label">NOC Date </label>
<input type="date" name="TelNocDate" id="TelNocDate" class="form-control" placeholder="" value="<?php echo $row7["TelNocDate"]; ?>" >
<div class="clearfix"></div>
</div>


 <div class="form-group col-md-4">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="SurveyDetails" name="SurveyDetails" required="">

<option value="1" <?php if($row7["SurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row7["SurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
</select>
<div class="clearfix"></div>
</div> 
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.4.1.min.js"></script>
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

     <script>
  function source(val){
    if(val == 2){
      $('#TelSummerWaterLevel').attr("readonly",false).focus();
      $('#TelTotalDepth').attr("readonly",false);//true
   
    //   $('#TelPumpHead').val('');
    //   $('#TelTotalDepth').val('');

    }
    else{
      $('#TelSummerWaterLevel').attr("readonly",false);//true
      $('#TelTotalDepth').attr("readonly",false).focus();
    
    //   $('#TelPumpHead').val('');
    //   $('#TelSummerWaterLevel').val('');
    }
  }
function calSummerPumpHead(){
      var action = "calSummerPumpHead";
      var TelSummerWaterLevel = $('#TelSummerWaterLevel').val();
            $.ajax({
                url: "ajax_files/ajax_product_specification.php",
                method: "POST",
                data: {
                    action: action,
                    TelSummerWaterLevel:TelSummerWaterLevel
                },
                success: function(data) {
                  console.log(data);
                    $('#TelPumpHead').val(data);
                  
                }
            });
}

function calDepthPumpHead(){
var action = "calDepthPumpHead";
      var TelTotalDepth = $('#TelTotalDepth').val();
            $.ajax({
                url: "ajax_files/ajax_product_specification.php",
                method: "POST",
                data: {
                    action: action,
                    TelTotalDepth:TelTotalDepth
                },
                success: function(data) {
                  console.log(data);
                    $('#TelPumpHead').val(data);
                  
                }
            });
}

function calSurfaceHead(val){
    if(val == 13){
        $('#TelPumpHead').val('20 Meter'); 
    }
    else{ 
        var TelWaterSource = $('#TelWaterSource').val();
if(TelWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
    }
}
  $(document).ready(function() {
    var TelWaterSource = $('#TelWaterSource').val();
    source(TelWaterSource);
    var Surface = $('#TelSurface').val();
    calSurfaceHead(Surface);
           $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });

        });
        
         $(document).on("input", "#TelTotalDepth", function(event) {
var TelWaterSource = $('#TelWaterSource').val();
var Surface = $('#Surface').val();
if(Surface == 13){
    $('#TelPumpHead').val('20 Meter'); 
}
else{
if(TelWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
}
});

$(document).on("input", "#TelSummerWaterLevel", function(event) {
var TelWaterSource = $('#TelWaterSource').val();
var Surface = $('#Surface').val();
if(Surface == 13){
    $('#TelPumpHead').val('20 Meter'); 
}
else{
if(TelWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
}
});
});

        //CKEDITOR.replace( 'editor1');


</script>
</body>

</html>
