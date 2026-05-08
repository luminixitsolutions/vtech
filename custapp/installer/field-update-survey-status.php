<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Customer Account List</title>
 <!-- manifest meta -->
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
if($row7["FieldLattitude"] == ''){
    $Fieldlat = $Latitude;
}
else{
    $Fieldlat = $row7["FieldLattitude"];
}

if($row7["FieldLongitude"] == ''){
    $FieldLong = $Longitude;
}
else{
    $FieldLong = $row7["FieldLongitude"];
}
?>

<?php 
  if(isset($_POST['submit'])){
$SurveyDetails = addslashes(trim($_POST["FieldSurveyDetails"]));
$Details = addslashes(trim($_POST["Details"]));
$FieldLattitude = addslashes(trim($_POST["FieldLattitude"]));
$FieldLongitude = addslashes(trim($_POST["FieldLongitude"]));
$FieldWaterSource = addslashes(trim($_POST["FieldWaterSource"]));
$FieldBoreDia = addslashes(trim($_POST["FieldBoreDia"]));
$FieldTotalDepth = addslashes(trim($_POST["FieldTotalDepth"]));
$FieldSummerWaterLevel = addslashes(trim($_POST["FieldSummerWaterLevel"]));
$FieldWinterWaterLevel = addslashes(trim($_POST["FieldWinterWaterLevel"]));
$FieldPumpHead = addslashes(trim($_POST["FieldPumpHead"]));
$FieldNoc = addslashes(trim($_POST["FieldNoc"]));
$FieldNocDate = addslashes(trim($_POST["FieldNocDate"]));
$FieldSurface = addslashes(trim($_POST["FieldSurface"]));
$FieldPumpCapacity = addslashes(trim($_POST["FieldPumpCapacity"]));
$FieldSurveyRejectReason = addslashes(trim($_POST["FieldSurveyRejectReason"]));
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


    $query2 = "UPDATE tbl_users SET FieldSurveyDetails='$SurveyDetails',FieldSurveyFile='$SurveyFile',FieldLattitude='$FieldLattitude',FieldLongitude='$FieldLongitude',FieldWaterSource='$FieldWaterSource',FieldBoreDia='$FieldBoreDia',FieldTotalDepth='$FieldTotalDepth',FieldSummerWaterLevel='$FieldSummerWaterLevel',FieldWinterWaterLevel='$FieldWinterWaterLevel',FieldPumpHead='$FieldPumpHead',FieldSurveyBy='$user_id',FieldSurveyDate='$CreatedDate',FieldNoc='$FieldNoc',FieldNocDate='$FieldNocDate',FieldSurface='$FieldSurface',FieldPumpCapacity='$FieldPumpCapacity',FieldSurveyRejectReason='$FieldSurveyRejectReason' WHERE id = '$id'";
  $conn->query($query2);
  
  if($SurveyDetails=='1') {
  $Steps = "Field Survey Done";
  }
  else if($SurveyDetails=='2') {
  $Steps = "Field Survey Rejected";
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
  echo "<script>alert('Field Survey Status Updated Successfully!');window.location.href='survey.php?val=$SurveyDetails';</script>";

    //header('Location:courses.php'); 

  }
 ?>

        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Field Survey Status

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Beneficiary ID </label>
<input type="text" class="form-control" placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
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
<input type="text" name="FieldLattitude" id="FieldLattitude" class="form-control" placeholder="" value="<?php echo $Fieldlat; ?>">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="FieldLongitude" id="FieldLongitude" class="form-control" placeholder="" value="<?php echo $FieldLong; ?>">
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Pump </label>

                                            <select class="form-control" id="FieldSurface" name="FieldSurface" readonly>
<!--<option selected="" disabled=""  value="">Select Type Of Pump</option>-->
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

                                            <select class="form-control" id="FieldPumpCapacity" name="FieldPumpCapacity" readonly>
<!--<option selected="" disabled="" value="">Select Pump Capacity</option>-->
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
                                            <select class="form-control" id="FieldWaterSource" name="FieldWaterSource" onchange="source(this.value)">
<option selected="" disabled="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['FieldWaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                         <div class="form-group col-md-3 Pump" id="hidediameter">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="FieldBoreDia" name="FieldBoreDia" >
<option selected="" disabled="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['FieldBoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-3">
<label class="form-label">Total Depth of water Source (in Meter) </label>
<input type="text" name="FieldTotalDepth" id="FieldTotalDepth" class="form-control" placeholder="" value="<?php echo $row7["FieldTotalDepth"]; ?>" >
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Summer (in Meter) </label>
<input type="text" name="FieldSummerWaterLevel" id="FieldSummerWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["FieldSummerWaterLevel"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Winter (in Meter) </label>
<input type="text" name="FieldWinterWaterLevel" id="FieldWinterWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["FieldWinterWaterLevel"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Pump Head </label>
<input type="text" name="FieldPumpHead" id="FieldPumpHead" class="form-control" placeholder="" value="<?php echo $row7["FieldPumpHead"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<!--  <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["SurveyDescription"]; ?></textarea>
<div class="clearfix"></div>
</div>
 -->



<div class="form-group col-md-6">
  <label class="form-label">Attach Photo </label>
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

<div class="form-group col-md-4">
<label class="form-label">NOC <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldNoc" name="FieldNoc" required="">


<option value="No" <?php if($row7["FieldNoc"]=='No') {?> selected <?php } ?>>No</option>
<option value="Yes" <?php if($row7["FieldNoc"]=='Yes') {?> selected <?php } ?>>Yes</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-4">
<label class="form-label">NOC Date </label>
<input type="date" name="FieldNocDate" id="FieldNocDate" class="form-control" placeholder="" value="<?php echo $row7["FieldNocDate"]; ?>" >
<div class="clearfix"></div>
</div>
<!-- <input type="hidden" id="Status" name="Status" value="1"> -->
 <div class="form-group col-md-6">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSurveyDetails" name="FieldSurveyDetails" required="">

<option value="1" <?php if($row7["FieldSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row7["FieldSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
<option value="2" <?php if($row7["FieldSurveyDetails"]=='2') {?> selected <?php } ?>>Survey Rejected</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-lg-12">
<label class="form-label">If Survey Rejected, Specify Reason </label>
<textarea name="FieldSurveyRejectReason" class="form-control" placeholder=""><?php echo $row7["FieldSurveyRejectReason"]; ?></textarea>
<div class="clearfix"></div>
</div>
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
</div>
</div>
<br><br>

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

<script>
  function source(val){
    if(val == 2){
       $('#hidediameter').show(); 
       $('#FieldBoreDia').val('').attr("selected",true);
      $('#FieldSummerWaterLevel').attr("readonly",false).focus();
      $('#FieldTotalDepth').attr("readonly",false);//true
      $('#FieldPumpHead').val('');
      $('#FieldTotalDepth').val('');

    }
    else{
        $('#hidediameter').hide();
        $('#FieldBoreDia').val('').attr("selected",true);
      $('#FieldSummerWaterLevel').attr("readonly",false);//true
      $('#FieldTotalDepth').attr("readonly",false).focus();
      $('#FieldPumpHead').val('');
      $('#FieldSummerWaterLevel').val('');
    }
  }
function calSummerPumpHead(){
      var action = "calSummerPumpHead";
      var FieldSummerWaterLevel = $('#FieldSummerWaterLevel').val();
            $.ajax({
                url: "ajax_files/ajax_product_specification.php",
                method: "POST",
                data: {
                    action: action,
                    TelSummerWaterLevel:FieldSummerWaterLevel
                },
                success: function(data) {
                  console.log(data);
                    $('#FieldPumpHead').val(data);
                  
                }
            });
}

function calDepthPumpHead(){
var action = "calDepthPumpHead";
      var FieldTotalDepth = $('#FieldTotalDepth').val();
            $.ajax({
                url: "ajax_files/ajax_product_specification.php",
                method: "POST",
                data: {
                    action: action,
                    TelTotalDepth:FieldTotalDepth
                },
                success: function(data) {
                  console.log(data);
                    $('#FieldPumpHead').val(data);
                  
                }
            });
}

 function calSurfaceHead(val){
    if(val == 13){
        $('#FieldPumpHead').val('20 Meter'); 
    }
    else{ 
        var FieldWaterSource = $('#FieldWaterSource').val();
if(FieldWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
    }
}
  $(document).ready(function() {
     var FieldWaterSource = $('#FieldWaterSource').val();
    source(FieldWaterSource);
    var Surface = $('#FieldSurface').val();
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

            
             $(document).on("input", "#FieldTotalDepth", function(event) {
var FieldWaterSource = $('#FieldWaterSource').val();
var Surface = $('#FieldSurface').val();
if(Surface == 13){
    $('#FieldPumpHead').val('20 Meter'); 
}
else{
if(FieldWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
}
});

$(document).on("input", "#FieldSummerWaterLevel", function(event) {
var FieldWaterSource = $('#FieldWaterSource').val();
var Surface = $('#FieldSurface').val();
if(Surface == 13){
    $('#FieldPumpHead').val('20 Meter'); 
}
else{
if(FieldWaterSource == 2){
    calSummerPumpHead();
}
else{
    calDepthPumpHead();
}
}
});
});


</script>
</body>
</html>
