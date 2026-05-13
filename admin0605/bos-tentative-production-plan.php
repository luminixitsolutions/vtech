<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Production-Plan";
$Page = "BOS-Production-Plan";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product Plan</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

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
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'header.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">BOS Production Plan</h4>

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>

<div class="form-row">

<!-- <div class="form-group col-md-3">
                                            <label class="form-label">AC/DC </label>
                                            <select class="form-control" id="AcDc" name="AcDc">
                                               
                                                <option value="AC" <?php if($row7["AcDc"]=='AC') {?> selected
                                                    <?php } ?>>AC</option>
                                                <option value="DC" <?php if($row7["AcDc"]=='DC') {?> selected
                                                    <?php } ?>>DC</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Pump </label>

                                            <select class="form-control" id="Surface" name="Surface">
<option selected="" value="">Select Type Of Pump</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 ORDER BY Name ASC";
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

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" >
<option selected="" value="">Select Pump Capacity</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpCapacity']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>-->


<div class="form-group col-md-3">
            <label class="form-label">Project <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectId" name="ProjectId" required onchange="getSubHead(this.value)">
<option selected="" value="all">All Project</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['ProjectId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

     <div class="form-group col-md-3">
            <label class="form-label">Project Sub Head <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId" required>
<option selected="" value="all">All Sub Head</option>
  <?php 
        $q = "select * from tbl_project_sub_head WHERE Status='1' AND UnderBy='".$_POST['ProjectId']."'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['ProjectSubHeadId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>
    
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="WaterSource" name="WaterSource">
<option selected="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['WaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="BoreDia" name="BoreDia" >
<option selected="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['BoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Pump Head </label>
                                            <select class="form-control" id="PumpHead" name="PumpHead" >
<option selected="" value="">Select Pump Head</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=1 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">State </label>
                                            <select class="select2-demo form-control" id="StateId" name="StateId" required="">
<option selected=""value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">District </label>
                                            <select class="select2-demo form-control" id="District" name="District" required="">
<option selected="" value="all">All District</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(District) As District from tbl_users WHERE District!='' ORDER BY District ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Taluka </label>
                                            <select class="select2-demo form-control" id="Taluka" name="Taluka" required="">
<option selected="" value="all">All Taluka</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(Taluka) As Taluka from tbl_users WHERE Taluka!='' ORDER BY Taluka ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['Taluka']==$rw['Taluka']){ ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
    <div class="form-group col-md-1">
   <label class="form-label">Limit From </label>
     <input type="number" name="MinLimit" id="MinLimit" class="form-control"
                                                placeholder="" value="<?php echo $_REQUEST["MinLimit"]; ?>"
                                                autocomplete="off" min="0" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-1">
   <label class="form-label">Limit To </label>
     <input type="number" name="MaxLimit" id="MaxLimit" class="form-control"
                                                placeholder="" value="<?php echo $_REQUEST["MaxLimit"]; ?>"
                                                autocomplete="off" min="0" required>
    <div class="clearfix"></div>
 </div>  
                                        
                                        <input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:28px;">
<button type="button" name="submit" class="btn btn-primary btn-finish" onclick="search(document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value,document.getElementById('StateId').value,document.getElementById('District').value,document.getElementById('Taluka').value,document.getElementById('MinLimit').value,document.getElementById('MaxLimit').value,document.getElementById('ProjectId').value,document.getElementById('ProjectSubHeadId').value)">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?MinLimit=0" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>

</div>


</div>
</div>


<div class="card">
<div class="card-datatable table-responsive" id="custresult">
    <div id="target-content" style="display:none;">loading...</div>
</div>

</div>

</form>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
<script>
function getSubHead(id){
             var action = 'getSubHead';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_dropdown.php",
   data:{action:action,id:id},  
  success: function(data){
      $('#ProjectSubHeadId').html(data);
  }
  });
        }
function search(WaterSource,BoreDia,PumpHead,StateId,District,Taluka,MinLimit,MaxLimit,ProjectId,ProjectSubHeadId){
    if(MinLimit==''){
        alert("Please select Minimum Limit");
    }
    else if(MaxLimit==''){
        alert("Please select Maximum Limit");
        $('#MaxLimit').focus();
    }
    else{
    $('#target-content').show();
var action = 'bos-tentative';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_production_plan.php",
   data:{action:action,WaterSource:WaterSource,BoreDia:BoreDia,PumpHead:PumpHead,StateId:StateId,District:District,Taluka:Taluka,MinLimit:MinLimit,MaxLimit:MaxLimit,ProjectId:ProjectId,ProjectSubHeadId:ProjectSubHeadId},  
  success: function(data){
    console.log(data);
      $('#custresult').html(data);
      $('#target-content').hide();
  }
  });
  }
}
 

</script>

</body>
</html>