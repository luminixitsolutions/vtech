<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
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

    <?php include_once '../header_script.php'; ?>
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
            <!-- [ Layout sidenav ] Start -->
             <?php include_once 'account-sidebar.php'; ?>
            <!-- [ Layout sidenav ] End -->
            <!-- [ Layout container ] Start -->
            <div class="layout-container">
                <!-- [ Layout navbar ( Header ) ] Start -->
                 <?php include_once '../top_header.php'; ?>
                <!-- [ Layout navbar ( Header ) ] End -->

<?php  
$id = $_GET['id'];
$sql7 = "SELECT tu.*,tb.Name As BranchName,ts.Name As Scheme,tc.Name As Country,ts2.Name As State,tc2.Name As City FROM tbl_users tu 
         LEFT JOIN tbl_branch tb ON tu.BranchId=tb.id 
         LEFT JOIN tbl_scheme ts ON tu.SchemeId=ts.id 
         LEFT JOIN tbl_country tc ON tu.CountryId=tc.id 
         LEFT JOIN tbl_state ts2 ON tu.StateId=ts2.id 
         LEFT JOIN tbl_city tc2 ON tu.CityId=tc2.id WHERE tu.id='$id'";
$row7 = getRecord($sql7);
$SellId = $row7['SellId'];
$AccountName = $row7['AccountName'];
$BankName = $row7['BankName'];
$AccountNo = $row7['AccountNo'];
$Branch = $row7['Branch'];
$IfscCode = $row7['IfscCode'];
$UpiNo = $row7['UpiNo'];
function commonMaster($id){
    global $conn;
    $sql = "SELECT * FROM tbl_common_master WHERE id='$id'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    return $row['Name'];
}
?>
                <!-- [ Layout content ] Start -->
               <div class="layout-content">

                    <!-- [ content ] Start -->
                     <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Users view</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item">Users</li>
                                <li class="breadcrumb-item active">Users view</li>
                            </ol>
                        </div>

                        <div class="media align-items-center py-3 mb-3">
                            <img src="../cust-user-icon.jpg" alt="" class="d-block ui-w-100 rounded-circle">
                            <div class="media-body ml-4">
                                <h4 class="font-weight-bold mb-0"><?php echo $row7["Fname"]; ?></h4>
                                <div class="text-muted mb-2">ID: <?php echo $row7["BeneficiaryId"]; ?></div>
                                <a href="add-rooftop-customer.php?id=<?php echo $row7['id']; ?>" class="btn btn-primary btn-sm">Edit</a>&nbsp;
                               
                            </div>
                        </div>

                       <div class="nav-tabs-top">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#user-edit-account">Customer Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab6">Survey Status</a>
                                </li>
                               <!-- <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2">Product Specification</a>
                                </li>-->
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab7">Calling Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab3">ID Proof Documents</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab4">Bank Account Detail</a>
                                </li>
                                <?php if($row7["Delivered"] == 1){?>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab5">Delivered Products</a>
                                </li>
                            <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="user-edit-account">
                                    <div class="row">
                                        <div class="col-lg-6">
                                    <div class="table-responsive">
                                    <table class="table user-view-table m-0">
                                        <tbody>
                                            <tr>
                                                <th>Project Type:</th>
                                                <td><?php if($row7["ProjectType"]=='1') {?> Pump Projects <?php } else { ?> Rooftop Projects <?php } ?></td>
                                            </tr>
                                            <tr>
                                                <th>Beneficiary ID:</th>
                                                <td><?php echo $row7["BeneficiaryId"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Name Of Beneficiary/Grampanchayat:</th>
                                                <td><?php echo $row7["Fname"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Branch:</th>
                                                <td><?php echo $row7["BranchName"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Gov Agency:</th>
                                                <td><?php echo $row7["Scheme"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Location:</th>
                                                <td><?php echo $row7["City"].", ".$row7["State"].", ".$row7["Country"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Taluka / Village / District:</th>
                                                <td><?php echo $row7["Taluka"]." / ".$row7["Village"]." / ".$row7["District"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Mobile No:</th>
                                                <td><?php echo $row7["Phone"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Email Id:</th>
                                                <td><?php echo $row7["EmailId"]; ?></td>
                                            </tr>
                                           
                                            <tr>
                                                <th>Lattitude:</th>
                                                <td><?php echo $row7["Lattitude"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Longitude:</th>
                                                <td><?php echo $row7["Longitude"]; ?></td>
                                            </tr>
                                          
                                          
                                            
                                        </tbody>
                                    </table>
                                </div>
                                 </div>

                                 <div class="col-lg-6">
                                     <div class="table-responsive">
                                    <table class="table user-view-table m-0">
                                        <tbody>
  <tr>
                                                <th>Rooftop Plant Capacity:</th>
                                                <td><?php echo $row7["RooftopPlantCapacity"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Off Grid/ On Grid System:</th>
                                                <td><?php echo $row7["OffOnGrid"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Consumer No:</th>
                                                <td><?php echo $row7["ConsumerNo"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Sanction Load:</th>
                                                <td><?php echo $row7["SanctionLoad"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Load Extension Required (kW):</th>
                                                <td><?php echo $row7["LoadExtension"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>BG Claim Period:</th>
                                                <td><?php echo $row7["BgClaimPeriod"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Number:</th>
                                                <td><?php echo $row7["InsuranceNumber"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Agency:</th>
                                                <td><?php echo $row7["InsuranceAgency"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Insurance Validity:</th>
                                                <td><?php echo $row7["InsuranceValidity"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Installation Vendor Name:</th>
                                                <td><?php echo $row7["InstallationVendor"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date Of Inspection:</th>
                                                <td><?php echo $row7["InspectionDate"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Date Of Commissioning:</th>
                                                <td><?php echo $row7["CommissioningDate"]; ?></td>
                                            </tr>
                                          
                                           
                                            </tbody>
                                    </table>
                                 </div>
                                  </div>

                                </div>
                                </div>
                    <?php 
                    $sql78 = "SELECT * FROM tbl_rooftop_tel_survey WHERE CustId='$id'";
$row78 = getRecord($sql78);
?>
                                  <div class="tab-pane fade" id="tab6">
                                      <div class="card-body">
                                      <h4 class="font-weight-bold py-3 mb-0">Telephonic Survey Status</h4>
                                      
                                      <div class="form-row">


<div class="form-group col-md-3">
<label class="form-label">Date of Survey  <span class="text-danger">*</span></label>
<input type="date" class="form-control" name="TelSurveyDate" placeholder="" value="<?php echo $row78['TelSurveyDate']; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Electricity consumer no. </label>
<input type="text" class="form-control" placeholder="" name="ConsumerNo" value="<?php echo $row7['ConsumerNo']; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="TelLattitude" id="TelLattitude" class="form-control" placeholder="" value="<?php echo $row78["TelLattitude"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="TelLongitude" id="TelLongitude" class="form-control" placeholder="" value="<?php echo $row78["TelLongitude"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Number </label>
<input type="text" class="form-control" name="TelContactNo" placeholder="" value="<?php echo $row78["TelContactNo"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Person </label>
<input type="text" class="form-control" name="TelContactPerson" placeholder="" value="<?php echo $row78["TelContactPerson"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Type of System required </label>
<input type="text" class="form-control" name="TelContactPerson" placeholder="" value="<?php echo $row78["TelSystemType"]; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Capacity Required (in KW) </label>
<input type="text" class="form-control" placeholder="" name="TelCapacity" value="<?php echo $row78["TelCapacity"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Shadow free Area Required (in Sq. feet) </label>
<input type="text" class="form-control" placeholder="" name="TelShadowArea" value="<?php echo $row78["TelShadowArea"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Shadow free area available </label>
<input type="text" class="form-control" placeholder="" name="TelShadowArea" value="<?php echo $row78["TelShadowArea2"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label"> Orientation (Toward South) </label>
<input type="text" class="form-control" placeholder="" name="TelShadowArea" value="<?php echo $row78["TelOrientation"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Connected Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="TelConnectedLoad" value="<?php echo $row78["TelConnectedLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Distance From LT Panel (Ongrid & Hybrid) in meters <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="TelDistance" placeholder="" value="<?php echo $row78["TelDistance"]; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-4">
<label class="form-label">Required Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="TelRequiredLoad" value="<?php echo $row78["TelRequiredLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Comments (Required Extension Or Reduction) </label>
<input type="text" class="form-control" placeholder="" name="TelComments" value="<?php echo $row78["TelComments"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">EPS Load (if Offgrid or Hybrid) </label>
<input type="text" class="form-control" placeholder="" name="TelEpsLoad" value="<?php echo $row78["TelEpsLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Net Meter </label>
<input type="text" class="form-control" placeholder="" name="TelNetMeter" value="<?php echo $row78["TelNetMeter"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-4">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSurveyDetails" name="FieldSurveyDetails" disabled>

<option value="1" <?php if($row78["TelSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row78["TelSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-6">
  <label class="form-label">Site Photo </label><br>
<?php if($row78['TelSitePhoto']=='') {
    echo "<span style='color:red;'>No Site Photo Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['TelSitePhoto'];?>" alt="" target="_new"><?php echo $row78['TelSitePhoto'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Pan Card </label><br>
<?php if($row78['TelPanCard']=='') {
    echo "<span style='color:red;'>No Pan Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['TelPanCard'];?>" alt="" target="_new"><?php echo $row78['TelPanCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Front Aadhar Card </label><br>
<?php if($row78['TelAadharCard']=='') {
    echo "<span style='color:red;'>No Front Aadhar Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['TelAadharCard'];?>" alt="" target="_new"><?php echo $row78['TelAadharCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Back Aadhar Card </label><br>
<?php if($row78['TelAadharCard2']=='') {
    echo "<span style='color:red;'>No Back Aadhar Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['TelAadharCard2'];?>" alt="" target="_new"><?php echo $row78['TelAadharCard2'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Electricity Bill(12 Month) </label><br>
<?php if($row78['TelElectricBill']=='') {
    echo "<span style='color:red;'>No Electricity Bill Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['TelElectricBill'];?>" alt="" target="_new"><?php echo $row78['TelElectricBill'];?></a></div>
</span>
<?php } ?>
</div>

<!-- <input type="hidden" id="Status" name="Status" value="1"> -->

</div>
</div>

<?php 
$sql78 = "SELECT * FROM tbl_rooftop_field_survey WHERE CustId='$id'";
$row78 = getRecord($sql78);
?>
<div class="card-body">
                                      <h4 class="font-weight-bold py-3 mb-0">Field Survey Status</h4>
                                      
                                      <div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Date of Survey  <span class="text-danger">*</span></label>
<input type="date" class="form-control" name="TelSurveyDate" placeholder="" value="<?php echo $row78['FieldSurveyDate']; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Electricity consumer no. </label>
<input type="text" class="form-control" placeholder="" name="ConsumerNo" value="<?php echo $row7['ConsumerNo']; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="TelLattitude" id="TelLattitude" class="form-control" placeholder="" value="<?php echo $row78["FieldLattitude"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="FieldLongitude" id="FieldLongitude" class="form-control" placeholder="" value="<?php echo $row78["FieldLongitude"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Number </label>
<input type="text" class="form-control" name="FieldContactNo" placeholder="" value="<?php echo $row78["FieldContactNo"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Contact Person </label>
<input type="text" class="form-control" name="FieldContactPerson" placeholder="" value="<?php echo $row78["FieldContactPerson"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Type of System required </label>
<input type="text" class="form-control" name="FieldContactPerson" placeholder="" value="<?php echo $row78["FieldSystemType"]; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-3">
<label class="form-label">Capacity Required (in KW) </label>
<input type="text" class="form-control" placeholder="" name="FieldCapacity" value="<?php echo $row78["FieldCapacity"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Shadow free Area Required (in Sq. feet) </label>
<input type="text" class="form-control" placeholder="" name="FieldShadowArea" value="<?php echo $row78["FieldShadowArea"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Shadow free area available </label>
<input type="text" class="form-control" placeholder="" name="FieldShadowArea" value="<?php echo $row78["FieldShadowArea2"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-3">
<label class="form-label"> Orientation (Toward South) </label>
<input type="text" class="form-control" placeholder="" name="FieldShadowArea" value="<?php echo $row78["FieldOrientation"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Connected Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="FieldConnectedLoad" value="<?php echo $row78["FieldConnectedLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Distance From LT Panel (Ongrid & Hybrid) in meters <span class="text-danger">*</span></label>
<input type="text" class="form-control" name="FieldDistance" placeholder="" value="<?php echo $row78["FieldDistance"]; ?>" readonly>
 <div class="clearfix"></div>
</div>


<div class="form-group col-md-4">
<label class="form-label">Required Load (in kW) </label>
<input type="text" class="form-control" placeholder="" name="FieldRequiredLoad" value="<?php echo $row78["FieldRequiredLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label"> Comments (Required Extension Or Reduction) </label>
<input type="text" class="form-control" placeholder="" name="FieldComments" value="<?php echo $row78["FieldComments"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">EPS Load (if Offgrid or Hybrid) </label>
<input type="text" class="form-control" placeholder="" name="FieldEpsLoad" value="<?php echo $row78["FieldEpsLoad"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Net Meter </label>
<input type="text" class="form-control" placeholder="" name="FieldNetMeter" value="<?php echo $row78["FieldNetMeter"]; ?>" readonly>
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-4">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSurveyDetails" name="FieldSurveyDetails" disabled>

<option value="1" <?php if($row78["FieldSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row78["FieldSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
</select>
<div class="clearfix"></div>
</div> 

<div class="form-group col-md-6">
  <label class="form-label">Site Photo </label><br>
<?php if($row78['FieldSitePhoto']=='') {
    echo "<span style='color:red;'>No Site Photo Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['FieldSitePhoto'];?>" alt="" target="_new"><?php echo $row78['FieldSitePhoto'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Pan Card </label><br>
<?php if($row78['FieldPanCard']=='') {
    echo "<span style='color:red;'>No Pan Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['FieldPanCard'];?>" alt="" target="_new"><?php echo $row78['FieldPanCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Front Aadhar Card </label><br>
<?php if($row78['FieldAadharCard']=='') {
    echo "<span style='color:red;'>No Front Aadhar Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['FieldAadharCard'];?>" alt="" target="_new"><?php echo $row78['FieldAadharCard'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Back Aadhar Card </label><br>
<?php if($row78['FieldAadharCard2']=='') {
    echo "<span style='color:red;'>No Back Aadhar Card Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['FieldAadharCard2'];?>" alt="" target="_new"><?php echo $row78['FieldAadharCard2'];?></a></div>
</span>
<?php } ?>
</div>

<div class="form-group col-md-6">
  <label class="form-label">Electricity Bill(12 Month) </label><br>
<?php if($row78['FieldElectricBill']=='') {
    echo "<span style='color:red;'>No Electricity Bill Found!</span>";
} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row78['FieldElectricBill'];?>" alt="" target="_new"><?php echo $row78['FieldElectricBill'];?></a></div>
</span>
<?php } ?>
</div>
</div>

                                      </div>
                                      </div>
                                      
                                      <div class="tab-pane fade" id="tab7">
                                      <div class="card-body">
                                          
                                      <h4 class="font-weight-bold py-3 mb-0">Dispatched Calling Confirmation</h4>
                                      <?php 
                                      $CustId = $id;
                                          $sql = "SELECT * FROM tbl_dispatch_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                            <div class="form-row">
                                             <div class="form-group col-md-12">
   <label class="form-label">Subject </label>
     <input type="text" name="Subjects" id="Subjects" class="form-control"
                                                placeholder="" value="<?php echo $row7['Subjects']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>

<?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=18";
$rncnt = getRow($sql);?>
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt;?>">
<?php 
$row = getList($sql);
foreach($row as $result){
  $sql3 = "SELECT * FROM tbl_dispatch_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
  $rncnt3 = getRow($sql3);
  $row3 = getRecord($sql3);

  ?>
   <input type="hidden" name="id[<?php echo $result["id"]; ?>]" value="<?php echo $result["id"]; ?>">
  <input type="hidden" name="Question[<?php echo $result["id"]; ?>]" value="<?php echo $result['Name'];?>">
<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['Name'];?> </label><br>
<label class="switcher switcher-success">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="Yes" <?php if($row3['Answer'] == 'Yes'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Yes</span>
                                    </label>

                                    <label class="switcher switcher-danger">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="No" <?php if($row3['Answer'] == 'No'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">No</span>
                                    </label>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['ExpTitle'];?> </label>
<textarea name="Specify[<?php echo $result['id'];?>]" class="form-control" placeholder="" disabled><?php echo $row3['Specify'];?></textarea>
 <div class="clearfix"></div>
</div>
<?php }  ?>
</div>
<?php } else {?>
 <h5 class="font-weight-bold" style="color:red;">No Calling Records</h5>
<?php } ?>


                                        <h4 class="font-weight-bold py-3 mb-0">Before Installation Calling</h4>
                                        <?php 
                                          $sql = "SELECT * FROM tbl_before_installation_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                         <div class="form-row">
                                             <div class="form-group col-md-12">
   <label class="form-label">Subject </label>
     <input type="text" name="Subjects" id="Subjects" class="form-control"
                                                placeholder="" value="<?php echo $row7['Subjects']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>

<?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=19";
$rncnt = getRow($sql);?>
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt;?>">
<?php 
$row = getList($sql);
foreach($row as $result){
  $sql3 = "SELECT * FROM tbl_before_installation_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
  $rncnt3 = getRow($sql3);
  $row3 = getRecord($sql3);

  ?>
   <input type="hidden" name="id[<?php echo $result["id"]; ?>]" value="<?php echo $result["id"]; ?>">
  <input type="hidden" name="Question[<?php echo $result["id"]; ?>]" value="<?php echo $result['Name'];?>">
<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['Name'];?> </label><br>
<label class="switcher switcher-success">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="Yes" <?php if($row3['Answer'] == 'Yes'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Yes</span>
                                    </label>

                                    <label class="switcher switcher-danger">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="No" <?php if($row3['Answer'] == 'No'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">No</span>
                                    </label>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['ExpTitle'];?> </label>
<textarea name="Specify[<?php echo $result['id'];?>]" class="form-control" placeholder="" disabled><?php echo $row3['Specify'];?></textarea>
 <div class="clearfix"></div>
</div>
<?php }  ?>
</div>
<?php } else {?>
 <h5 class="font-weight-bold" style="color:red;">No Calling Records</h5>
<?php } ?>


                                        <h4 class="font-weight-bold py-3 mb-0">After Installation Calling</h4>
                                        <?php 
                                          $sql = "SELECT * FROM tbl_after_installation_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                        <div class="form-row">
                                             <div class="form-group col-md-12">
   <label class="form-label">Subject </label>
     <input type="text" name="Subjects" id="Subjects" class="form-control"
                                                placeholder="" value="<?php echo $row7['Subjects']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>

<?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=20";
$rncnt = getRow($sql);?>
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt;?>">
<?php 
$row = getList($sql);
foreach($row as $result){
  $sql3 = "SELECT * FROM tbl_after_installation_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
  $rncnt3 = getRow($sql3);
  $row3 = getRecord($sql3);

  ?>
   <input type="hidden" name="id[<?php echo $result["id"]; ?>]" value="<?php echo $result["id"]; ?>">
  <input type="hidden" name="Question[<?php echo $result["id"]; ?>]" value="<?php echo $result['Name'];?>">
<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['Name'];?> </label><br>
<label class="switcher switcher-success">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="Yes" <?php if($row3['Answer'] == 'Yes'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Yes</span>
                                    </label>

                                    <label class="switcher switcher-danger">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="No" <?php if($row3['Answer'] == 'No'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">No</span>
                                    </label>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['ExpTitle'];?> </label>
<textarea name="Specify[<?php echo $result['id'];?>]" class="form-control" placeholder="" disabled><?php echo $row3['Specify'];?></textarea>
 <div class="clearfix"></div>
</div>
<?php }  ?>
</div>
<?php } else {?>
 <h5 class="font-weight-bold" style="color:red;">No Calling Records</h5>
<?php } ?>


                                        <h4 class="font-weight-bold py-3 mb-0">Before Inspection Calling</h4>
                                        <?php 
                                          $sql = "SELECT * FROM tbl_before_inspection_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                         <div class="form-row">
                                             <div class="form-group col-md-12">
   <label class="form-label">Subject </label>
     <input type="text" name="Subjects" id="Subjects" class="form-control"
                                                placeholder="" value="<?php echo $row7['Subjects']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>

<?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=21";
$rncnt = getRow($sql);?>
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt;?>">
<?php 
$row = getList($sql);
foreach($row as $result){
  $sql3 = "SELECT * FROM tbl_before_inspection_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
  $rncnt3 = getRow($sql3);
  $row3 = getRecord($sql3);

  ?>
   <input type="hidden" name="id[<?php echo $result["id"]; ?>]" value="<?php echo $result["id"]; ?>">
  <input type="hidden" name="Question[<?php echo $result["id"]; ?>]" value="<?php echo $result['Name'];?>">
<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['Name'];?> </label><br>
<label class="switcher switcher-success">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="Yes" <?php if($row3['Answer'] == 'Yes'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">Yes</span>
                                    </label>

                                    <label class="switcher switcher-danger">
                                        <input type="radio" class="switcher-input" name="Answer[<?php echo $result['id'];?>]" value="No" <?php if($row3['Answer'] == 'No'){?> checked <?php } ?> disabled>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">No</span>
                                    </label>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label"><?php echo $result['ExpTitle'];?> </label>
<textarea name="Specify[<?php echo $result['id'];?>]" class="form-control" placeholder="" disabled><?php echo $row3['Specify'];?></textarea>
 <div class="clearfix"></div>
</div>
<?php }  ?>
</div>
<?php } else {?>
 <h5 class="font-weight-bold" style="color:red;">No Calling Records</h5>
<?php } ?>

                                      </div>
                                      </div>
                                <div class="tab-pane fade" id="tab2">

                                   <div class="form-row" id="custresult" style="padding: 15px;"> 
                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              
               <th>Unit</th>
               <th>Qty</th>
             
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_cust_product_specification WHERE CustId='$id' ORDER BY id DESC";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  ?>
           <tr>
             <td><?php echo $srno; ?></td>
            
             <td><?php echo $nx['ProdName']; ?></td>
             <td><?php echo $nx['Unit']; ?></td>
            <td><?php echo $nx['Qty'];?></td>
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>

</div>

                                </div>

                                <div class="tab-pane fade" id="tab3">

                                   <div class="table-responsive">
                                    <table class="table user-view-table m-0">
                                        <tbody>
<tr>
                                                <th>Front Aadhar Card:</th>
                                                <td><?php if($row7["AadharCard"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["AadharCard"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <th>Back Aadhar Card:</th>
                                                <td><?php if($row7["AadharCard2"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["AadharCard2"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Aadhar Card No:</th>
                                                <td><?php echo $row7["AadharNo"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>PAN Card No:</th>
                                                <td><?php echo $row7["PanNo"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Front Pan Card:</th>
                                                <td><?php if($row7["PanCard"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["PanCard"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Back Pan Card:</th>
                                                <td><?php if($row7["PanCard2"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["PanCard2"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>GSTIN No:</th>
                                                <td><?php echo $row7["GstNo"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>GST Certificate:</th>
                                                <td><?php if($row7["GstCertificate"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["GstCertificate"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Gumasta No:</th>
                                                <td><?php echo $row7["GumastaNo"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Gumasta Certificate:</th>
                                                <td><?php if($row7["Gumasta"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["Gumasta"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>MSME No:</th>
                                                <td><?php echo $row7["MsmeNo"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>MSME Certificate:</th>
                                                <td><?php if($row7["Msme"] != ''){?>
                                                    <a href="../uploads/<?php echo $row7["Msme"]; ?>">View Doc</a>
                                                <?php } else { ?>
                                                    <span style="color:red;">No Document Found!</span>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                           
                                            </tbody>
                                    </table>
                                 </div>

                                </div>

                                <div class="tab-pane fade" id="tab4">

                                   <div class="table-responsive">
                                    <table class="table user-view-table m-0">
                                        <tbody>
<tr>
                                                <th>Bank Holder Name:</th>
                                                <td><?php echo $AccountName; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Bank Name:</th>
                                                <td><?php echo $BankName;  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Account No:</th>
                                                <td><?php echo $AccountNo; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Branch:</th>
                                                <td><?php echo $Branch; ?></td>
                                            </tr>
                                            <tr>
                                                <th>IFSC Code:</th>
                                                <td><?php echo $IfscCode; ?></td>
                                            </tr>
                                            <tr>
                                                <th>UPI ID:</th>
                                                <td><?php echo $UpiNo; ?></td>
                                            </tr>
                                           
                                            </tbody>
                                    </table>
                                 </div>

                                </div>

<?php if($row7["Delivered"] == 1){?>
                                 <div class="tab-pane fade" id="tab5">

                                   <div class="form-row" id="custresult" style="padding: 15px;"> 
                                   <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Unit</th>
               <th>Qty</th>
             
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
  $sql = "SELECT * FROM tbl_sell_products WHERE SellId='$SellId' AND UserId='$id' AND SerialNo='N/A' ORDER BY id";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
            
             <td><?php echo $nx['ProductName']; ?></td>
             <td><?php echo $nx['Purity']; ?></td>
            
            <td><?php echo $nx['Qty'];?></td>
           
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>
 </div>

    <div class="form-row" style="padding: 15px;"> 
<h5>Serial No Products</h5>
 
                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Serial No</th>
              <!-- <th>Unit</th> -->
               <th>Qty</th>
             
             
            </tr>
        </thead>
        <tbody>
          <?php 
 $srno = 1;
   $sql = "SELECT * FROM tbl_sell_products WHERE SellId='$SellId' AND UserId='$id' AND SerialNo!='N/A' ORDER BY id";
   $rx = $conn->query($sql);
  while($nx = $rx->fetch_assoc()){
  

  ?>
           <tr>
             <td><?php echo $srno; ?></td>
            
             <td><?php echo $nx['ProductName']; ?></td>
              <td><?php echo $nx['SerialNo']; ?></td>
             <!-- <td><?php echo $nx['Purity']; ?></td> -->
            
            <td><?php echo $nx['Qty'];?></td>
         
            </tr>
             <?php $srno++;} ?>
        </tbody>
    </table>


    </div>

</div>

                                </div>
<?php } ?>


                            </div>
                        </div>

                      
                    </div>

                    <!-- [ content ] End -->

                    <!-- [ Layout footer ] Start -->
                    <?php include_once 'footer.php'; ?>
                    <!-- [ Layout footer ] End -->

                </div>
                </div>
                <!-- [ Layout content ] Start -->

            </div>
            <!-- [ Layout container ] End -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

<?php include_once '../footer_script.php'; ?>


</body>

</html>
