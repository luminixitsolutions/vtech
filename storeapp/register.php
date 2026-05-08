<?php session_start();
require_once 'config.php';
$PageName = "New Registration";
?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php //include_once 'back-header.php'; ?> 
   
<style type="text/css">
        .imgcenter {
  display: block;
  margin-left: auto;
  margin-right: auto;
}
    </style>     
        <div class="main-container">

            <div class="container">
                <img src="logo2.png" class="imgcenter"><br>
                <div class="alert alert-success" style="font-size: 15px;" align="center">
                          If Already Registered! <a href="login.php" >Sign In</a></div>
                <div class="card">
                  <form id="validation-form" method="post" autocomplete="off">
                   <input type="hidden" name="action" value="Save" id="action">
                    <div class="card-body">

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
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>">
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
                                           <select class="form-control" id="StoreAvailability" name="StoreAvailability" required="" onchange="showStoreOption(this.value)">
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["StoreAvailability"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["StoreAvailability"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>  

                                        <div class="form-group col-md-6 showhide" style="display:none;">
                                            <label class="form-label">Store Address</label>
                                            <input type="text" name="StoreAddress" id="StoreAddress" class="form-control"
                                                placeholder="" value="<?php echo $row7["StoreAddress"]; ?>"
                                                autocomplete="off" >
                                        </div> 
                                        <div class="form-group col-md-3 showhide" style="display:none;">
                                            <label class="form-label">Area in Sqft</label>
                                            <input type="text" name="AreaSqft" id="AreaSqft" class="form-control"
                                                placeholder="" value="<?php echo $row7["AreaSqft"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3 showhide" style="display:none;">
                                            <label class="form-label">Covered or Open</label>
                                            <input type="text" name="Covered" id="Covered" class="form-control"
                                                placeholder="" value="<?php echo $row7["Covered"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3 showhide" style="display:none;">
                                            <label class="form-label">Security</label>
                                            <input type="text" name="Security" id="Security" class="form-control"
                                                placeholder="" value="<?php echo $row7["Security"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3 showhide" style="display:none;">
                                            <label class="form-label">Store Manager Details</label>
                                            <input type="text" name="StoreManager" id="StoreManager" class="form-control"
                                                placeholder="" value="<?php echo $row7["StoreManager"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3 showhide" style="display:none;">
                                            <label class="form-label">Capacity for Number Pumps</label>
                                            <input type="text" name="CapacityPump" id="CapacityPump" class="form-control"
                                                placeholder="" value="<?php echo $row7["CapacityPump"]; ?>"
                                                autocomplete="off" >
                                        </div>

                                        <div class="form-group col-md-3 showhide" style="display:none;">
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


                                        <div class="form-group col-md-3">
            <label class="form-label">Agency Operation <span class="text-danger">*</span></label>
<select class="form-control" id="AgencyId" name="AgencyId" required>
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
    </div>

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
                                           <select class="form-control" id="WorkDone" name="WorkDone" required="">
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
                                           <select class="form-control" id="OwnedVehicle" name="OwnedVehicle" required="">
                                                <option selected="" disabled="" value="">Select</option>
                                                <option value="Yes" <?php if($row7["OwnedVehicle"]=='Yes') {?> selected
                                                    <?php } ?>>Yes</option>
                                                <option value="No" <?php if($row7["OwnedVehicle"]=='No') {?> selected
                                                    <?php } ?>>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Rented</label>
                                           <select class="form-control" id="RentedVehicle" name="RentedVehicle" required="">
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
                                           <select class="form-control" id="WorkmenCompensation" name="WorkmenCompensation" required="">
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
                                      
                                       
                  
                
                    </div>
     
                   
                    <div class="card-footer">
                        <button class="btn btn-block btn-default rounded" type="submit" id="submit">Register</button>
                    </div>
                </form>
                </div>
            </div>
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
    
    <script type="text/javascript">
    $(document).ready(function() {

             $('#validation-form').on('submit', function(e){
      e.preventDefault();    
      var Fname = $('#Fname').val();
      
                var Phone = $('#Phone').val();

                if(Fname.trim() == ''){
                    toastr.error('Please Enter First Name', 'Error', {timeOut: 2000}); 
                    $('#Fname').focus();
                }
              
                else if(Phone.trim() == ''){
                    toastr.error('Please Enter Phone No', 'Error', {timeOut: 2000}); 
                    $('#Phone').focus();
                }
                else if(! /^([0-9]{10})+$/.test(Phone)) {
                    toastr.error('Mobile Number must be 10 Digit!', 'Error', {timeOut: 2000});
                  $('#Phone').focus();
                }
               
    else { 
      
         $.ajax({  
                url :"ajax_files/ajax_installer.php",  
                method:"POST",  
                data:new FormData(this),  
                contentType:false,  
                processData:false,  
                 beforeSend:function(){
     $('#submit').attr('disabled','disabled');
     $('#submit').text('Please Wait...');
    },
                success:function(data){ 
                  //console.log(data);exit();
                   var res = JSON.parse(data);
                    var status = res.status;
                    var roll = res.roll;
                    var Username = res.Username;
                    var uid = res.uid;
                     if(status == 0){
                        toastr.error('Phone Already Exist', 'Error', {timeOut: 1000}); 
                     
                     }
                     else{
                    //Android.loginUser(Username,uid);    
                    toastr.success('OTP Sent On Your Registered Mobile No.! Please Verify.', 'Success', {timeOut: 5000}); 
                    window.location.href="login-otp-verify.php?roll="+roll+"&uid="+uid+"&Username="+Username;
                     }
                      $('#submit').attr('disabled',false);
                    $('#submit').text('Register');
                }  
           })  



    }

  });

        
});

function showStoreOption(val){
    if(val == 'Yes'){
        $('.showhide').show();

    }
    else{
        $('.showhide').hide();
        $('#StoreAddress').val('');
        $('#AreaSqft').val('');
        $('#Covered').val('');
        $('#Security').val('');
        $('#StoreManager').val('');
        $('#CapacityPump').val('');
        $('#NoOperationalGate').val('');
    }
}
</script>
</body>

</html>
