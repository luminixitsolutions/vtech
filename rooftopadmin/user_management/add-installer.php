<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Installer";
$Page = "Add-Installer";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Contractor Account
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

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id'";
$row7 = getRecord($sql7);
//$row7['Options'] = explode(',', $row7['Options']);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Contractor Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="../ajax_files/ajax_installer.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                              
<div class="form-group col-md-6">
                                            <label class="form-label">Company Name </label>
                                            <input type="text" name="CompName" id="CompName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CompName"]; ?>"
                                                autocomplete="off">
                                        </div>

                                       <div class="form-group col-md-6">
                                            <label class="form-label">Contractor/Owner Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off" required>
                                        </div>

                                       

 <div class="form-group col-md-12">
                                            <label class="form-label">Permanent Address </label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off"><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        
                                    
                                        <input type="hidden" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="12345">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>" required>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                      
                                    
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                      
                                        
                                      
                                        <div class="form-group col-md-8">
                                            <label class="form-label">Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>


 <div class="form-group col-md-4">
                                            <label class="form-label">Lead Team Member </label>
                                            <input type="text" name="LeadTeam" id="LeadTeam" class="form-control"
                                                placeholder="" value="<?php echo $row7["LeadTeam"]; ?>"
                                                autocomplete="off" >
                                        </div>

    
     <div class="form-group col-md-6">
                                            <label class="form-label">GST Registration No</label>
                                            <input type="text" name="GstRegNo" id="GstRegNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["GstRegNo"]; ?>"
                                                autocomplete="off" >
                                        </div>      

                                        <div class="form-group col-md-6">
                                           
                                            <label class="form-label">Copy GST Registration </label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="GstRegPhoto"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldGstRegPhoto"
                                                    value="<?php echo $row7['GstRegPhoto'];?>" id="OldGstRegPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['GstRegPhoto']=='') {} else{?>
                                                <a href="../uploads/<?php echo $row7['GstRegPhoto'];?>">View Copy GST Registration</a>
                                          <?php } ?>
                                        </div>                                 
                                       

                         
                         <div class="form-group col-md-6">
                                            <label class="form-label">PAN Card No</label>
                                            <input type="text" name="PanCardNo" id="PanCardNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanCardNo"]; ?>"
                                                autocomplete="off" >
                                        </div>      

                                        <div class="form-group col-md-6">
                                           
                                            <label class="form-label">Copy PAN Card </label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="PanCardPhoto"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPanCardPhoto"
                                                    value="<?php echo $row7['PanCardPhoto'];?>" id="OldGstRegPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['PanCardPhoto']=='') {} else{?>
                                                <a href="../uploads/<?php echo $row7['PanCardPhoto'];?>">View Copy GST Registration</a>
                                          <?php } ?>
                                        </div>   


                                         <div class="form-group col-md-6">
                                            <label class="form-label">Gumastha</label>
                                            <input type="text" name="Gumastha" id="Gumastha" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gumastha"]; ?>"
                                                autocomplete="off" >
                                        </div>      

                                        <div class="form-group col-md-6">
                                           
                                            <label class="form-label">Copy Gumastha </label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="GumasthaPhoto"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldGumasthaPhoto"
                                                    value="<?php echo $row7['GumasthaPhoto'];?>" id="OldGumasthaPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['GumasthaPhoto']=='') {} else{?>
                                                <a href="../uploads/<?php echo $row7['GumasthaPhoto'];?>">View Copy GST Registration</a>
                                          <?php } ?>
                                        </div>                 
   
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Area Of Operation</label>
                                            <input type="text" name="AreaOperation" id="AreaOperation" class="form-control"
                                                placeholder="" value="<?php echo $row7["AreaOperation"]; ?>"
                                                autocomplete="off" >
                                        </div>   

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Store Availability</label>
                                           <select class="form-control" id="StoreAvailability" name="StoreAvailability" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["StoreAvailability"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["StoreAvailability"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>  

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Store Address</label>
                                            <input type="text" name="StoreAddress" id="StoreAddress" class="form-control"
                                                placeholder="" value="<?php echo $row7["StoreAddress"]; ?>"
                                                autocomplete="off" >
                                        </div> 
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Area in Sqft</label>
                                            <input type="text" name="AreaSqft" id="AreaSqft" class="form-control"
                                                placeholder="" value="<?php echo $row7["AreaSqft"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Covered or Open</label>
                                            <input type="text" name="Covered" id="Covered" class="form-control"
                                                placeholder="" value="<?php echo $row7["Covered"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Security</label>
                                            <input type="text" name="Security" id="Security" class="form-control"
                                                placeholder="" value="<?php echo $row7["Security"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Store Manager Details</label>
                                            <input type="text" name="StoreManager" id="StoreManager" class="form-control"
                                                placeholder="" value="<?php echo $row7["StoreManager"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Capacity for Number Pumps</label>
                                            <input type="text" name="CapacityPump" id="CapacityPump" class="form-control"
                                                placeholder="" value="<?php echo $row7["CapacityPump"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Number of Operational Gate</label>
                                            <input type="text" name="NoOperationalGate" id="NoOperationalGate" class="form-control"
                                                placeholder="" value="<?php echo $row7["NoOperationalGate"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Number of Teams</label>
                                            <input type="text" name="NoOfTeams" id="NoOfTeams" class="form-control"
                                                placeholder="" value="<?php echo $row7["NoOfTeams"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Count of Members in Each Team</label>
                                            <input type="text" name="CountOfMembers" id="CountOfMembers" class="form-control"
                                                placeholder="" value="<?php echo $row7["CountOfMembers"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Name of Team Members</label>
                                            <input type="text" name="MemberName" id="MemberName" class="form-control"
                                                placeholder="" value="<?php echo $row7["MemberName"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Target Selection Qty Area Wise</label>
                                            <input type="text" name="AreaQty" id="AreaQty" class="form-control"
                                                placeholder="" value="<?php echo $row7["AreaQty"]; ?>"
                                                autocomplete="off" >
                                        </div>


                                        <!-- <div class="form-group col-md-3">
            <label class="form-label">Agency Operation <span class="text-danger">*</span></label>
<select class="form-control" id="AgencyId" name="AgencyId" >
<option selected="" disabled="">Select Project</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['AgencyId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div> -->

                                        <div class="form-group col-md-12">
                                            <label class="form-label" style="font-size: 17px;color: blue;"><u>Previous Work Experience</u></label>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">Agency Name</label>
                                            <input type="text" name="AgencyName" id="AgencyName" class="form-control"
                                                placeholder="" value="<?php echo $row7["AgencyName"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Year</label>
                                            <input type="text" name="Year" id="Year" class="form-control"
                                                placeholder="" value="<?php echo $row7["Year"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" name="Quantity" id="Quantity" class="form-control"
                                                placeholder="" value="<?php echo $row7["Quantity"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Work Done Certificate</label>
                                           <select class="form-control" id="WorkDone" name="WorkDone" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["WorkDone"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["WorkDone"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>

                                         <div class="form-group col-md-12">
                                            <label class="form-label" style="font-size: 17px;color: blue;"><u>Dispatch Vehicle</u></label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Owned</label>
                                           <select class="form-control" id="OwnedVehicle" name="OwnedVehicle" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["OwnedVehicle"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["OwnedVehicle"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Rented</label>
                                           <select class="form-control" id="RentedVehicle" name="RentedVehicle" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["RentedVehicle"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["RentedVehicle"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">No Of Vehicles</label>
                                            <input type="text" name="NoOfVehicles" id="NoOfVehicles" class="form-control"
                                                placeholder="" value="<?php echo $row7["NoOfVehicles"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Per Day Survey Capacity</label>
                                            <input type="text" name="PerDaySurvey" id="PerDaySurvey" class="form-control"
                                                placeholder="" value="<?php echo $row7["PerDaySurvey"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Per Day Dispatch Capacity</label>
                                            <input type="text" name="PerDayDispatch" id="PerDayDispatch" class="form-control"
                                                placeholder="" value="<?php echo $row7["PerDayDispatch"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Per Day I&C Capacity</label>
                                            <input type="text" name="PerDayIC" id="PerDayIC" class="form-control"
                                                placeholder="" value="<?php echo $row7["PerDayIC"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Per Day Inspection Capacity</label>
                                            <input type="text" name="PerDayInspection" id="PerDayInspection" class="form-control"
                                                placeholder="" value="<?php echo $row7["PerDayInspection"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label" style="font-size: 17px;color: blue;"><u>Financial Vehicle</u></label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Weekly Billing</label>
                                            <input type="text" name="WeeklyBilling" id="WeeklyBilling" class="form-control"
                                                placeholder="" value="<?php echo $row7["WeeklyBilling"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Every 15 Day Billing</label>
                                            <input type="text" name="15DayBilling" id="15DayBilling" class="form-control"
                                                placeholder="" value="<?php echo $row7["15DayBilling"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Monthly Billing</label>
                                            <input type="text" name="MonthlyBilling" id="MonthlyBilling" class="form-control"
                                                placeholder="" value="<?php echo $row7["MonthlyBilling"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Number Of Pumps Billing</label>
                                            <input type="text" name="PumpBilling" id="PumpBilling" class="form-control"
                                                placeholder="" value="<?php echo $row7["PumpBilling"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Aadhar Card of all Members</label>
                                            <input type="text" name="AadharCardAllMember" id="AadharCardAllMember" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharCardAllMember"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Workmen Compensation Policy</label>
                                           <select class="form-control" id="WorkmenCompensation" name="WorkmenCompensation" >
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["WorkmenCompensation"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["WorkmenCompensation"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label class="form-label" style="font-size: 17px;color: blue;"><u>Bank Account Details</u></label>
                                        </div>

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
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>
                        </div>






                    </div>


                    <?php include_once '../footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once '../footer_script.php'; ?>

    <script type="text/javascript">
        function getRoll(val){
            if(val == '0'){
                //$('.showall').show();
               // $('.showonly').hide();
            }
            else{
               // $('.showonly').show();
               // $('.showall').hide();
            }
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
            message: 'Email Id / Phone No Already Exists',
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
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            exit();
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "../ajax_files/ajax_employee.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {

                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-manufacture.php';
                            }, 2000);
                        }
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
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
                    url: "../ajax_files/ajax_employee.php",
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
        $(document).on("change", "#CountryId", function(event) {
            var val = this.value;
            var action = "getState";
            $.ajax({
                url: "../ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#StateId').html(data);
                }
            });

        });

        $(document).on("change", "#StateId", function(event) {
            var val = this.value;
            var action = "getCity";
            $.ajax({
                url: "../ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#CityId').html(data);
                }
            });

        });
    });
    </script>
    
</body>

</html>