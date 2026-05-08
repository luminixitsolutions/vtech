<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Customers";
$Page = "Add-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Customer Account
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

    fieldset legend {
        background: inherit;
        font-family: "Lato", sans-serif;
        color: #650812;
        font-size: 15px;
        left: 10px;
        padding: 0 10px;
        position: absolute;
        top: -12px;
        font-weight: 400;
        width: auto !important;
        border: none !important;
    }

    fieldset {
        background: #ffffff;
        border: 1px solid #4FAFB8;
        border-radius: 5px;
        margin: 20px 0 1px 0;
        padding: 20px;
        position: relative;
    }
    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            

  <?php include_once 'back-header.php'; ?> 
            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);


if($_REQUEST["action"]=="deletelink")
{
  $id = $_REQUEST["id"];
  $pid = $_REQUEST["leadid"];
  $sql11 = "DELETE FROM tbl_stud_docs WHERE id = '$id'";
  $conn->query($sql11);
?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
       window.location.href="add-customer.php?id=<?php echo $pid;?>";
    </script>
<?php }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Customer Account</h4>

                        <div class="card mb-4">
                            <div class="card-body" style="padding: 1px;">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                      <fieldset>
 <legend>Customer Detail</legend>
                                    <div class="form-row">
                                        
                                        <div class="form-group col-md-12" >
<label class="form-label"> Company<span class="text-danger">*</span></label>
 <select class="form-control" name="CompId" id="CompId" required>
<option selected="" value="">Select Company</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CompId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ProjectType" name="ProjectType" required="" onchange="showHide(this.value)">
                                               <option value="" selected>Select Project Type</option>
                                                <option value="1" <?php if($row7["ProjectType"]=='1') {?> selected
                                                    <?php } ?>>Pump Projects</option>
                                                <option value="2" <?php if($row7["ProjectType"]=='2') {?> selected
                                                    <?php } ?>>Rooftop Projects</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

 <div class="form-group col-md-6">
                                            <label class="form-label">Beneficiary ID  </label>
                                            <input type="text" name="BeneficiaryId" id="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $row7["BeneficiaryId"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Name Of Beneficiary/Grampanchayat </label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>
               
                 <div class="form-group col-md-3">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Store</option>
<?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}

  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>                         
                                        	
	<div class="form-group col-md-3">
			<label class="form-label">Gov Agency </label>
<select class="form-control" id="AgencyId" name="AgencyId">
<option selected="" disabled="">Select Agency</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['SchemeId']==$rw['id']){ ?> selected <?php } ?> 
                value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
	</div>
                                        	
	<div class="form-group col-md-3">
			<label class="form-label">Yojana </label>
<select class="form-control" id="SchemeId" name="SchemeId">
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
	
	
                                        
                                        <div class="form-group col-md-3">
		<label class="form-label">Country <span class="text-danger">*</span></label>
		<select class="form-control" name="CountryId" id="CountryId" required="">
<option selected="" disabled="">Select Country</option>
    <?php 
      $q = "select * from tbl_country";
      $r = $conn->query($q);
      while($rw = $r->fetch_assoc())
      {
    ?>
      <option <?php if(1==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
    <?php } ?>
</select>
	</div>
	<div class="form-group col-md-3">
		<label class="form-label">State <span class="text-danger">*</span></label>
<select class="form-control" id="StateId" name="StateId" required="">
<option selected="" disabled="">Select State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>
	<div class="form-group col-md-3">
			<label class="form-label">City </label>
<select class="form-control" id="CityId" name="CityId">
<option selected="" disabled="">Select City</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select * from tbl_city WHERE StateId='$StateId' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['CityId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>


<div class="form-group col-md-3">
                                            <label class="form-label">Taluka</label>
                                            <input type="text" name="Taluka" id="Taluka" class="form-control"
                                                placeholder="" value="<?php echo $row7["Taluka"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Village</label>
                                            <input type="text" name="Village" id="Village" class="form-control"
                                                placeholder="" value="<?php echo $row7["Village"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">District</label>
                                            <input type="text" name="District" id="District" class="form-control"
                                                placeholder="" value="<?php echo $row7["District"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Block</label>
                                            <input type="text" name="Block" id="Block" class="form-control"
                                                placeholder="" value="<?php echo $row7["Block"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         <div class="form-group col-md-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off" required><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

  <div class="form-group col-md-4">
                                            <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

<input type="hidden" value="12345" name="Password" id="Password">
                                        <!-- <div class="form-group col-md-4">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="Password" id="Password" class="form-control"
                                                placeholder="" value="<?php echo $row7["Password"]; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div>
 -->
 <div class="form-group col-md-4">
 <label class="form-label">Work Order No</label>
                                            <input type="text" name="WoNo" id="WoNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["WoNo"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">AC/DC </label>
                                            <select class="form-control" id="AcDc" name="AcDc" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
                                               
                                                <option value="AC" <?php if($row7["AcDc"]=='AC') {?> selected
                                                    <?php } ?>>AC</option>
                                                <option value="DC" <?php if($row7["AcDc"]=='DC') {?> selected
                                                    <?php } ?>>DC</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Pump </label>

                                            <select class="form-control" id="Surface" name="Surface" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled=""  value="">Select Type Of Pump</option>
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
                                       
                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Capacity </label>

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
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

                                        <div class="form-group col-md-4 Rooftop" style="display:none;">
                                            <label class="form-label">Rooftop Plant Capacity </label>
                                            <input type="text" name="RooftopPlantCapacity" id="RooftopPlantCapacity" class="form-control"
                                                placeholder="" value="<?php echo $row7["RooftopPlantCapacity"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">Lattitude </label>
                                            <input type="text" name="Lattitude" id="Lattitude" class="form-control"
                                                placeholder="" value="<?php echo $row7["Lattitude"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Longitude </label>
                                            <input type="text" name="Longitude" id="Longitude" class="form-control"
                                                placeholder="" value="<?php echo $row7["Longitude"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Rooftop" style="display:none;">
                                            <label class="form-label">Off Grid/ On Grid System </label>
                                            <input type="text" name="OffOnGrid" id="OffOnGrid" class="form-control"
                                                placeholder="" value="<?php echo $row7["OffOnGrid"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Rooftop" style="display:none;">
                                            <label class="form-label">Consumer No. </label>
                                            <input type="text" name="ConsumerNo" id="ConsumerNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["ConsumerNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Rooftop" style="display:none;">
                                            <label class="form-label">Sanction Load </label>
                                            <input type="text" name="SanctionLoad" id="SanctionLoad" class="form-control"
                                                placeholder="" value="<?php echo $row7["SanctionLoad"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Rooftop" style="display:none;">
                                            <label class="form-label">Load Extension Required (kW) </label>
                                            <input type="text" name="LoadExtension" id="LoadExtension" class="form-control"
                                                placeholder="" value="<?php echo $row7["LoadExtension"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="WaterSource" name="WaterSource" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['WaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                         <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="BoreDia" name="BoreDia" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['BoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Depth Of Source In Summer </label>
                                            <input type="text" name="SummerDepth" id="SummerDepth" class="form-control"
                                                placeholder="" value="<?php echo $row7["SummerDepth"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Depth Of Source In Winter </label>
                                            <input type="text" name="WinterDepth" id="WinterDepth" class="form-control"
                                                placeholder="" value="<?php echo $row7["WinterDepth"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        


<div class="form-group col-md-4 Pump">
                                            <label class="form-label">Pump Head </label>
                                            <select class="form-control" id="PumpHead" name="PumpHead" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Pump Head</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=1 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">BG Number </label>
                                            <input type="text" name="BgNumber" id="BgNumber" class="form-control"
                                                placeholder="" value="<?php echo $row7["BgNumber"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">BG Validity </label>
                                            <input type="text" name="BgValidity" id="BgValidity" class="form-control"
                                                placeholder="" value="<?php echo $row7["BgValidity"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">BG Claim Period  </label>
                                            <input type="text" name="BgClaimPeriod" id="BgClaimPeriod" class="form-control"
                                                placeholder="" value="<?php echo $row7["BgClaimPeriod"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Insurance Number  </label>
                                            <input type="text" name="InsuranceNumber" id="InsuranceNumber" class="form-control"
                                                placeholder="" value="<?php echo $row7["InsuranceNumber"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Insurance Agency  </label>

                                            <select class="form-control" id="InsuranceAgency" name="InsuranceAgency">
<option selected="" disabled="" value="">Select Insurance Agency</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=9 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option <?php if($row7['InsuranceAgency']==$rw['Name']){ ?> selected <?php } ?> value="<?php echo $rw['Name']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                          
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">Insurance Validity  </label>
                                            <input type="text" name="InsuranceValidity" id="InsuranceValidity" class="form-control"
                                                placeholder="" value="<?php echo $row7["InsuranceValidity"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Installation Vendor Name  </label>
                                            <input type="text" name="InstallationVendor" id="InstallationVendor" class="form-control"
                                                placeholder="" value="<?php echo $row7["InstallationVendor"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Date Of Inspection  </label>
                                            <input type="date" name="InspectionDate" id="InspectionDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InspectionDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Date Of Commissioning </label>
                                            <input type="date" name="CommissioningDate" id="CommissioningDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["CommissioningDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Customer Type </label>
                                            <select class="form-control" id="CustType" name="CustType" onchange="showDoc(this.value)">
<option selected="" disabled="" value="">Select Customer Type</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=8 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option <?php if($row7['CustType']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-12 compdata" style="display:none;">
                                            <label class="form-label">Company Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="CompName" id="CompName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CompName"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-12 compdata" style="display:none;">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <textarea name="CompAddress" class="form-control" placeholder="Address"
                                                autocomplete="off"><?php echo $row7["CompAddress"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-6 compdata" style="display:none;">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="CompPhone" id="CompPhone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["CompPhone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-6 compdata" style="display:none;">
                                            <label class="form-label">Authorised Person Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="AuthorName" id="AuthorName" class="form-control"
                                                placeholder="" value="<?php echo $row7["AuthorName"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         </div>

                    </fieldset>

<fieldset>
 <legend>Product Specification</legend>
<div class="form-row" id="custresult"> 

</div>
</fieldset>

 <fieldset>
 <legend>ID Proof Documents</legend>
<div class="form-row"> 
<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Front Aadhar Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="AadharCard" style="opacity: 1;">
<input type="hidden" name="AadharCardOld" value="<?php echo $row7['AadharCard'];?>" id="AadharCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row7['AadharCard']=='') {} else{?>
  <span id="show_photo3">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo3"></a><a href="../uploads/<?php echo $row7['AadharCard'];?>" target="_blank"><?php echo $row7['AadharCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Back Aadhar Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="AadharCard2" style="opacity: 1;">
<input type="hidden" name="AadharCardOld2" value="<?php echo $row7['AadharCard2'];?>" id="AadharCardOld2">
<span class="custom-file-label"></span>
</label>
<?php if($row7['AadharCard2']=='') {} else{?>
  <span id="show_photo4">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo4"></a><a href="../uploads/<?php echo $row7['AadharCard2'];?>" target="_blank"><?php echo $row7['AadharCard2'];?></a></div>
</span>
<?php } ?>
</div>

 <div class="form-group col-md-6 maindoc">
                                            <label class="form-label">Aadhar Card No  </label>
                                            <input type="text" name="AadharNo" id="AadharNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-6 maindoc">
                                            <label class="form-label">PAN Card No  </label>
                                            <input type="text" name="PanNo" id="PanNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Front Pan Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="PanCard" style="opacity: 1;">
<input type="hidden" name="PanCardOld" value="<?php echo $row7['PanCard'];?>" id="PanCardOld">
<span class="custom-file-label"></span>
</label>
<?php if($row7['PanCard']=='') {} else{?>
  <span id="show_photo5">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo5"></a><a href="../uploads/<?php echo $row7['PanCard'];?>" target="_blank"><?php echo $row7['PanCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 maindoc">
  <label class="form-label">Upload Back Pan Card Of Owner </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="PanCard2" style="opacity: 1;">
<input type="hidden" name="PanCardOld2" value="<?php echo $row7['PanCard2'];?>" id="PanCardOld2">
<span class="custom-file-label"></span>
</label>
<?php if($row7['PanCard2']=='') {} else{?>
  <span id="show_photo6">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo6"></a><a href="../uploads/<?php echo $row7['PanCard2'];?>" target="_blank"><?php echo $row7['PanCard2'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
<label class="form-label">GSTIN No </label>
<input type="text" name="GstNo" id="GstNo" class="form-control" placeholder="" value="<?php echo $row7["GstNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload GST Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="GstCertificate" style="opacity: 1;">
<input type="hidden" name="OldGstCertificate" value="<?php echo $row7['GstCertificate'];?>" id="OldGstCertificate">
<span class="custom-file-label"></span>
</label>
<?php if($row7['GstCertificate']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['GstCertificate'];?>" target="_blank"><?php echo $row7['GstCertificate'];?></a></div>
</span>
<?php } ?>
</div>


<div class="form-group col-md-6 otherdoc">
<label class="form-label">Gumasta No </label>
<input type="text" name="GumastaNo" id="GumastaNo" class="form-control" placeholder="" value="<?php echo $row7["GumastaNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload Gumasta Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Gumasta" style="opacity: 1;">
<input type="hidden" name="OldGumasta" value="<?php echo $row7['Gumasta'];?>" id="OldGumasta">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Gumasta']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['Gumasta'];?>" target="_blank"><?php echo $row7['Gumasta'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6 otherdoc">
<label class="form-label">MSME No </label>
<input type="text" name="MsmeNo" id="MsmeNo" class="form-control" placeholder="" value="<?php echo $row7["MsmeNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 otherdoc">
  <label class="form-label">Upload MSME Certificate </label>
<label class="custom-file">
<input type="file" class="custom-file-input" name="Msme" style="opacity: 1;">
<input type="hidden" name="OldMsme" value="<?php echo $row7['Msme'];?>" id="OldMsme">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Msme']=='') {} else{?>
  <span id="show_photo_lic">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo_lic"></a><a href="../uploads/<?php echo $row7['Msme'];?>" target="_blank"><?php echo $row7['Msme'];?></a></div>
</span>
<?php } ?>
</div>

</div>

                    </fieldset>
 <fieldset>
 <legend>Bank Account Detail</legend>
<div class="form-row">               
                                    
                                       <div class="form-group col-md-6">
<label class="form-label">Bank Holder Name </label>
<input type="text" name="AccountName" id="AccountName" class="form-control" placeholder="" value="<?php echo $row7["AccountName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Bank Name </label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Account No </label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Branch </label>
<input type="text" name="Branch" id="Branch" class="form-control" placeholder="" value="<?php echo $row7["Branch"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">IFSC Code </label>
<input type="text" name="IfscCode" id="IfscCode" class="form-control" placeholder="" value="<?php echo $row7["IfscCode"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">UPI ID </label>
<input type="text" name="UpiNo" id="UpiNo" class="form-control" placeholder="" value="<?php echo $row7["UpiNo"]; ?>">
<div class="clearfix"></div>
</div>



 

                                      <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                               
                                                <option value="1" <?php if($row7["Status"]=='1') {?> selected
                                                    <?php } ?>>Active</option>
                                                <option value="0" <?php if($row7["Status"]=='0') {?> selected
                                                    <?php } ?>>Inctive</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                    </div> 
                                     </fieldset>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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
function showDoc(val){
    if(val == 31){
$('.maindoc').show();
$('.otherdoc').hide();
$('.compdata').hide();
    }
    else if(val == 34){
         $('.compdata').show();
         $('.maindoc').show();
        $('.otherdoc').show();
    }
    else{
        $('.compdata').hide();
        $('.maindoc').show();
        $('.otherdoc').show();
    }
}
        function getProdList(acdc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead){
  var action = 'view2';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_product_specification.php",
   data:{action:action,acdc:acdc,Surface:Surface,PumpCapacity:PumpCapacity,WaterSource:WaterSource,BoreDia:BoreDia,PumpHead:PumpHead},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }

    function myFunction2() {

        var x = document.getElementById("Password");
        if (x.type === "password") {
            x.type = "text";
            $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
        } else {
            x.type = "password";
            $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
        }
    }

    function error_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.error({
            title: 'Error',
            message: 'Phone No Already Exists',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function success_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.success({
            title: 'Success',
            message: 'Saved Successfully...',
            location: isRtl ? 'tl' : 'tr'
        });
    }
    $(document).ready(function() {
        var userid = $('#userid').val();
       
        var ProjectType = $('#ProjectType').val();
        var AcDc = $('#AcDc').val();
        var Surface = $('#Surface').val();
        var PumpCapacity = $('#PumpCapacity').val();
        var WaterSource = $('#WaterSource').val();
        var BoreDia = $('#BoreDia').val();
        var PumpHead = $('#PumpHead').val();
        var CustType = $('#CustType').val();
        if(userid!=''){
        showHide(ProjectType);
        if(ProjectType == 1){
        getProdList(AcDc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead);
        }
        showDoc(CustType);
    }
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "ajax_files/ajax_customer_account.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {
console.log(data);$('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-customers.php';
                            }, 2000);
                        }
                        
                    }
                })



            } else {
                //$('#Fname').focus();
                return false;
            }
        });

        $(document).on("click", "#delete_photo", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete Profile Photo?")) {
                var action = "deletePhoto";
                var id = $('#userid').val();
                var Photo = $('#OldPhoto').val();
                $.ajax({
                    url: "ajax_files/ajax_customer_account.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: id,
                        Photo: Photo
                    },
                    success: function(data) {

                        $('#show_photo').hide();
                        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr(
                            'dir') === 'rtl';
                        $.growl.success({
                            title: 'Success',
                            message: data,
                            location: isRtl ? 'tl' : 'tr'
                        });

                    }
                });
            }

        });
      
        
        $(document).on("change", "#CountryId", function(event){
  var val = this.value;
   var action = "getState";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#StateId').html(data);
    }
    });

 });

         $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getCity";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#CityId').html(data);
    }
    });

 });
    });


    function showHide(val){
        var AcDc = $('#AcDc').val();
        var Surface = $('#Surface').val();
        var PumpCapacity = $('#PumpCapacity').val();
        var WaterSource = $('#WaterSource').val();
        var BoreDia = $('#BoreDia').val();
        var PumpHead = $('#PumpHead').val();
        var CustType = $('#CustType').val();
        if(val == 1){
            $('.Rooftop').hide();
            $('.Pump').show();
            $('#custresult').show();
            getProdList(AcDc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead);
        }
        else{
$('.Rooftop').show();
$('.Pump').hide();
$('#custresult').hide();
        }
    }
    </script>
       <script>
    function handleImageRemoval(data) {
        var type = 1;
        $.ajax({  
                url :"example/async-remove.php",  
                method:"GET",  
                data:{type:type},
                success:function(data){ 
                    
                }
            });
    }
  
    </script>

<script src="example/js/slim.kickstart.min.js"></script>
</body>

</html>