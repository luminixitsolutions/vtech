<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Structure-Product-Specification";
$Page = "Structure-Product-Specification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | State</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'product-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php  
if(isset($_POST['submit'])){
    $AcDc = $_POST['AcDc']; 
    $Surface = $_POST['Surface'];
    $PumpCapacity = $_POST['PumpCapacity'];
    $ModuleWatt = $_POST['ModuleWatt'];
    $ModuleQty = $_POST['ModuleQty'];
    $Structure = $_POST['Structure'];
    $ModuleMake = $_POST['ModuleMake'];
    $StructureMake = $_POST['StructureMake'];
    $AgencyId = $_POST['AgencyId'];
    $SchemeId = $_POST['SchemeId'];
   $CreatedDate = date('Y-m-d');

      $sql = "DELETE FROM tbl_rooftop_struct_product_specification WHERE 1";
      if($Surface!=''){
        $sql.=" AND Surface='$Surface'";
      }
      if($PumpCapacity!=''){
        $sql.=" AND PumpCapacity='$PumpCapacity'";
      }
      if($ModuleWatt!=''){
        $sql.=" AND ModuleWatt='$ModuleWatt'";
      }
      if($ModuleQty!=''){
        $sql.=" AND ModuleQty='$ModuleQty'";
      }
      if($Structure!=''){
        $sql.=" AND Structure='$Structure'";
      }
      if($ModuleMake!=''){
        $sql.=" AND ModuleMake='$ModuleMake'";
      }
      if($StructureMake!=''){
        $sql.=" AND StructureMake='$StructureMake'";
      }
      if($AgencyId!=''){
        $sql.=" AND AgencyId='$AgencyId'";
      }
      if($SchemeId!=''){
        $sql.=" AND SchemeId='$SchemeId'";
      }
    $conn->query($sql);
    $number = count($_POST["ProdId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProdId"][$i] != ''))  
              {
                 $ProdId = $_POST['ProdId'][$i];
                $ProdName = mysqli_real_escape_string($conn, $_POST['ProdName'][$i]);
                $Unit = $_POST['Unit'][$i];
                $Qty = $_POST['Qty'][$i];
              
                $sql = "INSERT INTO tbl_rooftop_struct_product_specification SET AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',ModuleWatt='$ModuleWatt',ModuleQty='$ModuleQty',Structure='$Structure',ProdId='$ProdId',ProdName='$ProdName',Unit='$Unit',Qty='$Qty',CreatedBy='$user_id',CreatedDate='$CreatedDate',ModuleMake='$ModuleMake',StructureMake='$StructureMake',AgencyId='$AgencyId',SchemeId='$SchemeId'";
                $conn->query($sql);
            }
        }
    }
}

?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Structure Product Specification
</h4><br>

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>

<div class="form-row">


<div class="form-group col-md-4">
      <label class="form-label">Gov Agency <span class="text-danger">*</span></label>
<select class="form-control" id="AgencyId" name="AgencyId" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="">Select Agency</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['AgencyId']==$rw['id']){ ?> selected <?php } ?> 
                value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
  </div>
                      
                       
    
  <div class="form-group col-md-4">
      <label class="form-label">Yojana  <span class="text-danger">*</span></label>
<select class="form-control" id="SchemeId" name="SchemeId" required onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)">
<option selected="" disabled="">Select Yojana</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select * from tbl_scheme WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['SchemeId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>


                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Pump <span class="text-danger">*</span></label>

                                            <select class="form-control" id="Surface" name="Surface" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Type Of Pump</option>
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
                                            <label class="form-label">Capacity <span class="text-danger">*</span></label>

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Pump Capacity</option>
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
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Watt <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleWatt" name="ModuleWatt" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Watt</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=15 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['WaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Qty <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleQty" name="ModuleQty" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Qty</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=16 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['BoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Structure <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Structure" name="Structure" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Structure</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=17 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Make <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleMake" name="ModuleMake" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Make</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=22 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Structure Make <span class="text-danger">*</span></label>
                                            <select class="form-control" id="StructureMake" name="StructureMake" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Structure Make</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=23 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


</div>

</div>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive" id="custresult">

</div>

</div>

</form>
</div>

</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
<script type="text/javascript">
    function getProdList(Surface,PumpCapacity,ModuleWatt,ModuleQty,Structure,ModuleMake,StructureMake,AgencyId,SchemeId){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "../ajax_files/ajax_struct_product_specification.php",
   data:{action:action,Surface:Surface,PumpCapacity:PumpCapacity,ModuleWatt:ModuleWatt,ModuleQty:ModuleQty,Structure:Structure,ModuleMake:ModuleMake,StructureMake:StructureMake,AgencyId:AgencyId,SchemeId:SchemeId},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
</script>

</body>
</html>
