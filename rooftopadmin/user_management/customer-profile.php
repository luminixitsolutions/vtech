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
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?> <?php } else{?> Add <?php } ?> 
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once '../header_script.php'; ?>
</head>
<style>
		.stepper-wrapper {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  font-size: 12px;
}
.stepper-item {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;

  @media (max-width: 768px) {
    font-size: 10px;
  }
}

.stepper-item::before {
  position: absolute;
  content: "";
  border-bottom: 2px solid #ccc;
  width: 100%;
  top: 20px;
  left: -50%;
  z-index: 2;
}

.stepper-item::after {
  position: absolute;
  content: "";
  border-bottom: 2px solid #ccc;
  width: 100%;
  top: 20px;
  left: 50%;
  z-index: 2;
}

.stepper-item .step-counter {
  position: relative;
  z-index: 5;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #ccc;
  margin-bottom: 6px;
}

.stepper-item.active {
  font-weight: bold;
}

.stepper-item.completed .step-counter {
  background-color: #4bb543;
  color:#fff;
}

.stepper-item.completed::after {
  position: absolute;
  content: "";
  border-bottom: 2px solid #4bb543;
  width: 100%;
  top: 20px;
  left: 50%;
  z-index: 3;
}

.stepper-item:first-child::before {
  content: none;
}
.stepper-item:last-child::after {
  content: none;
}
	</style>
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

$CoOrdSurvey = $row7['SurveyDetails'];
$FieldSurvey = $row7['FieldSurveyDetails'];

$sql88 = "SELECT * FROM tbl_rooftop_sell WHERE CustId='$id'";
$row88 = getRecord($sql88);
$rncnt88 = getRow($sql88);

$sql99 = "SELECT * FROM tbl_rooftop_installations WHERE CustId='$id'";
$row99 = getRecord($sql99);
$rncnt99 = getRow($sql99);

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
                       <!-- <h4 class="font-weight-bold py-3 mb-0">Users view</h4>
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item">Users</li>
                                <li class="breadcrumb-item active">Users view</li>
                            </ol>
                        </div>-->

                        <div class="media align-items-center py-3 mb-3">
                            <img src="cust-user-icon.jpg" alt="" class="d-block ui-w-100 rounded-circle">
                            <div class="media-body ml-4">
                                <h4 class="font-weight-bold mb-0"><?php echo $row7["Fname"]; ?></h4>
                                <div class="text-muted mb-2">ID: <?php echo $row7["BeneficiaryId"]; ?></div>
                                <a href="add-customer.php?id=<?php echo $row7['id']; ?>" class="btn btn-primary btn-sm">Edit</a>&nbsp; 
                               
                            </div>
                        </div>

<div class="stepper-wrapper">
    <?php if($CoOrdSurvey == 1){?>
  <div class="stepper-item completed">
    <div class="step-counter">1</div>
    <div class="step-name" style="text-align: center;">Co-ordinator Survey <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row7['TelSurveyDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">1</div>
    <div class="step-name" style="text-align: center;">Co-ordinator Survey</div>
  </div>
  <?php } ?>
  
   <?php if($FieldSurvey == 1){?>
  <div class="stepper-item completed">
    <div class="step-counter">2</div>
    <div class="step-name" style="text-align: center;">Field Survey <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row7['FieldSurveyDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">2</div>
    <div class="step-name" style="text-align: center;">Field Survey</div>
  </div>
  <?php } ?>
  
  <?php if($row7['UnderProdStatus'] == 1){?>
  <div class="stepper-item completed">
    <div class="step-counter">3</div>
    <div class="step-name" style="text-align: center;">Under Production <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row7['UnderProdDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">3</div>
    <div class="step-name" style="text-align: center;">Under Production</div>
  </div>
  <?php } ?>
  
 <?php if($rncnt88 > 0){?>
  <div class="stepper-item completed">
    <div class="step-counter">4</div>
    <div class="step-name" style="text-align: center;">Delivery Challan <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row88['InvoiceDate']))); ?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">4</div>
    <div class="step-name" style="text-align: center;">Delivery Challan</div>
  </div>
  <?php } ?>
  
  <?php if($row88['Inst_Dispatcher_Otp_Verify'] == 1){?>
  <div class="stepper-item completed">
    <div class="step-counter">5</div>
    <div class="step-name" style="text-align: center;">Dispatch <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">5</div>
    <div class="step-name" style="text-align: center;">Dispatch</div>
  </div>
  <?php } ?>
  
  
  
   <?php if($row99['InstallStatus'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">6</div>
    <div class="step-name" style="text-align: center;">Installation <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">6</div>
    <div class="step-name" style="text-align: center;">Installation</div>
  </div>
  <?php } ?>
  
  <?php if($row99['DataUploadStatus'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">7</div>
    <div class="step-name" style="text-align: center;">Data Upload <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['DataUploadDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">7</div>
    <div class="step-name" style="text-align: center;">Data Upload</div>
  </div>
  <?php } ?>
  
  <?php if($row99['PoInspection'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">8</div>
    <div class="step-name" style="text-align: center;">Inspection <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">8</div>
    <div class="step-name" style="text-align: center;">Inspection</div>
  </div>
  <?php } ?>
  

</div>
<div class="stepper-wrapper">
    
    <?php if($row99['InspectionDiscrepancy'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">9</div>
    <div class="step-name" style="text-align: center;">Inspection Discrepancy <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InspectionDiscrepancyDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">9</div>
    <div class="step-name" style="text-align: center;">Inspection Discrepancy</div>
  </div>
  <?php } ?>
  
  
   <?php if($row99['InsuranceApproval'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">10</div>
    <div class="step-name" style="text-align: center;">Insurance Approval <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InsuranceApprovalDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">10</div>
    <div class="step-name" style="text-align: center;">Insurance Approval</div>
  </div>
  <?php } ?>
  
   <?php if($row99['PoApproval'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">11</div>
    <div class="step-name" style="text-align: center;">PO Note Putup Approval <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoApprovalDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">11</div>
    <div class="step-name" style="text-align: center;">PO Note Putup Approval</div>
  </div>
  <?php } ?>

  <?php if($row99['DgmApproval'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">12</div>
    <div class="step-name" style="text-align: center;">DGM Approval <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['DgmApprovalDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">12</div>
    <div class="step-name" style="text-align: center;">DGM Approval</div>
  </div>
  <?php } ?>
  
<?php if($row99['FileInHand'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">13</div>
    <div class="step-name" style="text-align: center;">File In Hand <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['FileInHandDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">13</div>
    <div class="step-name" style="text-align: center;">File In Hand</div>
  </div>
  <?php } ?>
  
  <?php if($row99['CircleOfficeStatus'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">14</div>
    <div class="step-name" style="text-align: center;">Bill Submit To Circle <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['CircleOfficeDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">14</div>
    <div class="step-name" style="text-align: center;">Bill Submit To Circle</div>
  </div>
  <?php } ?>
  
   <?php if($row99['RmsIntegrationStatus'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">15</div>
    <div class="step-name" style="text-align: center;">RMS Integration <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['RmsIntegrationDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">15</div>
    <div class="step-name" style="text-align: center;">RMS Integration</div>
  </div>
  <?php } ?>
  
  <?php if($row99['SentToHo'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">16</div>
    <div class="step-name" style="text-align: center;">Files Sent to HO <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['SentToHoDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">16</div>
    <div class="step-name" style="text-align: center;">Files Sent to HO</div>
  </div>
  <?php } ?>
  
  <?php if($row99['ForwardToPayment'] == 'Yes'){?>
  <div class="stepper-item completed">
    <div class="step-counter">17</div>
    <div class="step-name" style="text-align: center;">Files Forwarded to Payment <br><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['ForwardToPaymentDate'])));?></div>
  </div>
  <?php } else {?>
  <div class="stepper-item">
    <div class="step-counter">17</div>
    <div class="step-name" style="text-align: center;">Files Forwarded to Payment</div>
  </div>
  <?php } ?>
 

  
  
</div>
                       <div class="nav-tabs-top">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#user-edit-account">Customer Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab6">Survey Status</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tab2">Product Specification</a>
                                </li>
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
                                                <th>AC/DC:</th>
                                                <td><?php echo $row7["AcDc"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Type Of Pump:</th>
                                                <td><?php echo commonMaster($row7["Surface"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Capacity:</th>
                                                <td><?php echo commonMaster($row7["PumpCapacity"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Lattitude:</th>
                                                <td><?php echo $row7["Lattitude"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Longitude:</th>
                                                <td><?php echo $row7["Longitude"]; ?></td>
                                            </tr>
                                            <?php if($row7['ProjectType'] == 2){?>
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
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                 </div>

                                 <div class="col-lg-6">
                                     <div class="table-responsive">
                                    <table class="table user-view-table m-0">
                                        <tbody>
<tr>
                                                <th>Type Of Source:</th>
                                                <td><?php echo commonMaster($row7["WaterSource"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Bore Diameter:</th>
                                                <td><?php echo commonMaster($row7["BoreDia"]);  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Depth Of Source In Summer:</th>
                                                <td><?php echo $row7["SummerDepth"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>Depth Of Source In Winter:</th>
                                                <td><?php echo $row7["WinterDepth"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Pump Head:</th>
                                                <td><?php echo commonMaster($row7["PumpHead"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>BG Number:</th>
                                                <td><?php echo $row7["BgNumber"]; ?></td>
                                            </tr>
                                             <tr>
                                                <th>BG Validity:</th>
                                                <td><?php echo $row7["BgValidity"]; ?></td>
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
                                            <tr>
                                                <th>Customer Type:</th>
                                                <td><?php echo commonMaster($row7["CustType"]); ?></td>
                                            </tr>
                                            <?php if($row7["CustType"] == 34){?>
                                            <tr>
                                                <th>Address:</th>
                                                <td><?php echo commonMaster($row7["CompAddress"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Authorised Person Name:</th>
                                                <td><?php echo commonMaster($row7["AuthorName"]); ?></td>
                                            </tr>
                                        
                                            <tr>
                                                <th>Company Name:</th>
                                                <td><?php echo commonMaster($row7["CompName"]); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile No:</th>
                                                <td><?php echo commonMaster($row7["CompPhone"]); ?></td>
                                            </tr>
                                        <?php } ?>
                                            </tbody>
                                    </table>
                                 </div>
                                  </div>

                                </div>
                                </div>
                                
                                  <div class="tab-pane fade" id="tab6">
                                      <div class="card-body">
                                      <h4 class="font-weight-bold py-3 mb-0">Telephonic Survey Status</h4>
                                      
                                      <div class="form-row">


<div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="FieldLattitude" id="FieldLattitude" class="form-control" placeholder="" value="<?php echo $row7["FieldLattitude"]; ?>" disabled>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="FieldLongitude" id="FieldLongitude" class="form-control" placeholder="" value="<?php echo $row7["FieldLongitude"]; ?>" disabled>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="FieldWaterSource" name="FieldWaterSource" onchange="source(this.value)" disabled>
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
                                         <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="FieldBoreDia" name="FieldBoreDia" disabled>
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
<input type="text" name="FieldTotalDepth" id="FieldTotalDepth" class="form-control" placeholder="" value="<?php echo $row7["FieldTotalDepth"]; ?>" oninput="calDepthPumpHead()" disabled>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Summer (in Meter) </label>
<input type="text" name="FieldSummerWaterLevel" id="FieldSummerWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["FieldSummerWaterLevel"]; ?>" oninput="calSummerPumpHead()" disabled>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Winter (in Meter) </label>
<input type="text" name="FieldWinterWaterLevel" id="FieldWinterWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["FieldWinterWaterLevel"]; ?>" disabled>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Pump Head </label>
<input type="text" name="FieldPumpHead" id="FieldPumpHead" class="form-control" placeholder="" value="<?php echo $row7["FieldPumpHead"]; ?>" disabled>
<div class="clearfix"></div>
</div>



<div class="form-group col-md-12">
  <label class="form-label">Attach PDF File </label>
<?php if($row7['SurveyFile']=='') {} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row7['SurveyFile'];?>" alt="" target="_new"><?php echo $row7['SurveyFile'];?></a></div>
</span>
<?php } ?>
</div>


<!-- <input type="hidden" id="Status" name="Status" value="1"> -->
 <div class="form-group col-md-12">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="FieldSurveyDetails" name="FieldSurveyDetails" disabled>

<option value="1" <?php if($row7["FieldSurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row7["FieldSurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
</select>
<div class="clearfix"></div>
</div> 
</div>
</div>

<div class="card-body">
                                      <h4 class="font-weight-bold py-3 mb-0">Field Survey Status</h4>
                                      
                                      <div class="form-row">


<div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="TelLattitude" id="TelLattitude" class="form-control" placeholder="" value="<?php echo $row7["TelLattitude"]; ?>" disabled>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="TelLongitude" id="TelLongitude" class="form-control" placeholder="" value="<?php echo $row7["TelLongitude"]; ?>" disabled>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="TelWaterSource" name="TelWaterSource" onchange="source(this.value)" disabled>
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
                                            <select class="form-control" id="TelBoreDia" name="TelBoreDia" disabled>
<option selected="" disabled="" value="">Select Bore Diameter</option>
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
<input type="text" name="TelTotalDepth" id="TelTotalDepth" class="form-control" placeholder="" value="<?php echo $row7["TelTotalDepth"]; ?>" oninput="calDepthPumpHead()" disabled>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Summer (in Meter) </label>
<input type="text" name="TelSummerWaterLevel" id="TelSummerWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["TelSummerWaterLevel"]; ?>" oninput="calSummerPumpHead()" disabled>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Water Level In Winter (in Meter) </label>
<input type="text" name="TelWinterWaterLevel" id="TelWinterWaterLevel" class="form-control" placeholder="" value="<?php echo $row7["TelWinterWaterLevel"]; ?>" disabled>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Pump Head </label>
<input type="text" name="TelPumpHead" id="TelPumpHead" class="form-control" placeholder="" value="<?php echo $row7["TelPumpHead"]; ?>" disabled>
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
<?php if($row7['SurveyFile']=='') {} else{?>
  <span id="show_photo2">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="../uploads/<?php echo $row7['SurveyFile'];?>" alt="" target="_new"><?php echo $row7['SurveyFile'];?></a></div>
</span>
<?php } ?>
</div>


<!-- <input type="hidden" id="Status" name="Status" value="1"> -->
 <div class="form-group col-md-12">
<label class="form-label">Survey Status <span class="text-danger">*</span></label>
  <select class="form-control" id="SurveyDetails" name="SurveyDetails" disabled>

<option value="1" <?php if($row7["SurveyDetails"]=='1') {?> selected <?php } ?>>Survey Done</option>
<option value="0" <?php if($row7["SurveyDetails"]=='0') {?> selected <?php } ?>>Survey Not Done</option>
</select>
<div class="clearfix"></div>
</div> 
</div>

                                      </div>
                                      </div>
                                      
                                      <div class="tab-pane fade" id="tab7">
                                      <div class="card-body">
                                          
                                      <h4 class="font-weight-bold py-3 mb-0">Dispatched Calling Confirmation</h4>
                                      <?php 
                                      $CustId = $id;
                                          $sql = "SELECT * FROM tbl_rooftop_dispatch_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>

                                            <div class="form-row">
                                                <div class="form-group col-md-2">
   <label class="form-label">Calling Date </label>
     <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $row7['CreatedDate']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>
                                             <div class="form-group col-md-10">
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
  $sql3 = "SELECT * FROM tbl_rooftop_dispatch_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
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
                                              <div class="form-group col-md-2">
   <label class="form-label">Calling Date </label>
     <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $row7['CreatedDate']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>
                                             <div class="form-group col-md-10">
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
                                          $sql = "SELECT * FROM tbl_rooftop_after_installation_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                        <div class="form-row">
                                             <div class="form-group col-md-2">
   <label class="form-label">Calling Date </label>
     <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $row7['CreatedDate']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>
                                             <div class="form-group col-md-10">
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
  $sql3 = "SELECT * FROM tbl_rooftop_after_installation_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
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
                                          $sql = "SELECT * FROM tbl_rooftop_before_inspection_calling_info WHERE CustId='$id' LIMIT 1";
$rncnt7 = getRow($sql);
$row7 = getRecord($sql);
if($rncnt7 > 0){
?>
                                         <div class="form-row">
                                              <div class="form-group col-md-2">
   <label class="form-label">Calling Date </label>
     <input type="text" class="form-control"
                                                placeholder="" value="<?php echo $row7['CreatedDate']; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>
                                             <div class="form-group col-md-10">
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
  $sql3 = "SELECT * FROM tbl_rooftop_before_inspection_calling_info WHERE QuesId='".$result["id"]."' AND CustId='$CustId'";
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
  $sql = "SELECT * FROM tbl_cust_product_specification WHERE CustId='$id' ORDER BY ProdName ASC";
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
  $sql = "SELECT * FROM tbl_rooftop_sell_products WHERE SellId='$SellId' AND UserId='$id' AND SerialNo='N/A' ORDER BY id"; 
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
   $sql = "SELECT * FROM tbl_rooftop_sell_products WHERE SellId='$SellId' AND UserId='$id' AND SerialNo!='N/A' ORDER BY id";
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
  <script src="<?php echo $SiteUrl;?>/assets/js/pages/ui_navs.js"></script>

</body>

</html>
