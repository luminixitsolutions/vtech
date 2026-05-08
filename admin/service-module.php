<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "Add-Service-Complaint";
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

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
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

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_service_complaint WHERE id='$id'";
$row7 = getRecord($sql7);


if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $CellNo = addslashes(trim($_POST["CellNo"]));
    $CustName = addslashes(trim($_POST["CustName"]));
    $BeneficiaryId = addslashes(trim($_POST["BeneficiaryId"]));
$Status = 1;
$Address = addslashes(trim($_POST["Address"]));
$RelatedIssue = addslashes(trim($_POST['RelatedIssue']));
$Issue = addslashes(trim($_POST["Issue"]));
$Message = addslashes(trim($_POST["Message"]));
$ClainStatus = addslashes(trim($_POST["ClainStatus"]));
$BranchId = addslashes(trim($_POST["BranchId"]));
$ServiceType = addslashes(trim($_POST["ServiceType"]));
$Taluka = addslashes(trim($_POST["Taluka"]));
$Village = addslashes(trim($_POST["Village"]));
$District = addslashes(trim($_POST["District"]));
$InstallationDate = addslashes(trim($_POST["InstallationDate"]));
$RegistrationId = addslashes(trim($_POST["RegistrationId"]));
$ComissioningDate = addslashes(trim($_POST["ComissioningDate"]));
$WaterSource = addslashes(trim($_POST["WaterSource"]));
$Category = addslashes(trim($_POST["Category"]));
$ServiceSystem = addslashes(trim($_POST["ServiceSystem"]));
$AcDc = addslashes(trim($_POST["AcDc"]));
$Surface = addslashes(trim($_POST["Surface"]));
$RecentVfdNo = addslashes(trim($_POST["RecentVfdNo"]));
$RecentMotorNo = addslashes(trim($_POST["RecentMotorNo"]));
$RecentPumpNo = addslashes(trim($_POST["RecentPumpNo"]));
$Problem = addslashes(trim($_POST["Problem"]));
$Remark = addslashes(trim($_POST["Remark"]));
$Photos = addslashes(trim($_POST["Photos"]));
$InspectionDate = addslashes(trim($_POST["InspectionDate"]));
$InspectionBy = addslashes(trim($_POST["InspectionBy"]));
$LastDate = addslashes(trim($_POST["LastDate"]));
$SystemStatus = addslashes(trim($_POST["SystemStatus"]));
$Extra = addslashes(trim($_POST["Extra"]));
$ExtraPump = addslashes(trim($_POST["ExtraPump"]));
$ExtraVfd = addslashes(trim($_POST["ExtraVfd"]));
$PolicyNo = addslashes(trim($_POST["PolicyNo"]));
$ClainDone = addslashes(trim($_POST["ClainDone"]));
$Rms = addslashes(trim($_POST["Rms"]));

$VfdProblem = addslashes(trim($_POST["VfdProblem"]));
$PlateDamageInsurance = addslashes(trim($_POST["PlateDamageInsurance"]));
$PumpProblem = addslashes(trim($_POST["PumpProblem"]));
$MotorProblem = addslashes(trim($_POST["MotorProblem"]));
$PhotoReceived = addslashes(trim($_POST["PhotoReceived"]));
$VideoReceived = addslashes(trim($_POST["VideoReceived"]));
$LetterReceived = addslashes(trim($_POST["LetterReceived"]));
$LastCallUpdate = addslashes(trim($_POST["LastCallUpdate"]));
$RecentProblem = addslashes(trim($_POST["RecentProblem"]));
$SystemWorking = addslashes(trim($_POST["SystemWorking"]));
$PolicyPeriod = addslashes(trim($_POST["PolicyPeriod"]));
$Depth = addslashes(trim($_POST["Depth"]));
$PumpMake = addslashes(trim($_POST["PumpMake"]));
$MotorMake = addslashes(trim($_POST["MotorMake"]));
$InsuranceCompany = addslashes(trim($_POST["InsuranceCompany"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');


if($_GET['id']==''){
     $qx = "INSERT INTO tbl_service_complaint SET CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',RelatedIssue='$RelatedIssue',Issue = '$Issue',Message='$Message',CreatedDate='$CreatedDate',CreatedBy='$user_id',ClainStatus='$ClainStatus',BranchId='$BranchId',ServiceType='$ServiceType',Taluka='$Taluka',Village='$Village',District='$District',InstallationDate='$InstallationDate',RegistrationId='$RegistrationId',ComissioningDate='$ComissioningDate',WaterSource='$WaterSource',Category='$Category',ServiceSystem='$ServiceSystem',AcDc='$AcDc',Surface='$Surface',RecentVfdNo='$RecentVfdNo',RecentMotorNo='$RecentMotorNo',RecentPumpNo='$RecentPumpNo',Problem='$Problem',Remark='$Remark',Photos='$Photos',InspectionDate='$InspectionDate',InspectionBy='$InspectionBy',LastDate='$LastDate',SystemStatus='$SystemStatus',Extra='$Extra',ExtraPump='$ExtraPump',ExtraVfd='$ExtraVfd',PolicyNo='$PolicyNo',ClainDone='$ClainDone',Rms='$Rms',VfdProblem='$VfdProblem',PlateDamageInsurance='$PlateDamageInsurance',PumpProblem='$PumpProblem',MotorProblem='$MotorProblem',PhotoReceived='$PhotoReceived',VideoReceived='$VideoReceived',LetterReceived='$LetterReceived',LastCallUpdate='$LastCallUpdate',RecentProblem='$RecentProblem',SystemWorking='$SystemWorking',PolicyPeriod='$PolicyPeriod',Depth='$Depth',PumpMake='$PumpMake',MotorMake='$MotorMake',InsuranceCompany='$InsuranceCompany',BeneficiaryId='$BeneficiaryId'";
  $conn->query($qx);
  $PostId = mysqli_insert_id($conn);
  $TicketNo= "#".rand(1000,9999);
  $sql = "UPDATE tbl_service_complaint SET TicketNo='$TicketNo' WHERE id='$PostId'";
  $conn->query($sql);
  echo "<script>alert('Service Complaint Created Successfully!');window.location.href='view-service-module.php';</script>";
}
else{
 //$TicketNo= "#".rand(1000,9999);
    $query2 = "UPDATE tbl_service_complaint SET CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',RelatedIssue='$RelatedIssue',Issue = '$Issue',Message='$Message',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',ClainStatus='$ClainStatus',BranchId='$BranchId',ServiceType='$ServiceType',Taluka='$Taluka',Village='$Village',District='$District',InstallationDate='$InstallationDate',RegistrationId='$RegistrationId',ComissioningDate='$ComissioningDate',WaterSource='$WaterSource',Category='$Category',ServiceSystem='$ServiceSystem',AcDc='$AcDc',Surface='$Surface',RecentVfdNo='$RecentVfdNo',RecentMotorNo='$RecentMotorNo',RecentPumpNo='$RecentPumpNo',Problem='$Problem',Remark='$Remark',Photos='$Photos',InspectionDate='$InspectionDate',InspectionBy='$InspectionBy',LastDate='$LastDate',SystemStatus='$SystemStatus',Extra='$Extra',ExtraPump='$ExtraPump',ExtraVfd='$ExtraVfd',PolicyNo='$PolicyNo',ClainDone='$ClainDone',Rms='$Rms',VfdProblem='$VfdProblem',PlateDamageInsurance='$PlateDamageInsurance',PumpProblem='$PumpProblem',MotorProblem='$MotorProblem',PhotoReceived='$PhotoReceived',VideoReceived='$VideoReceived',LetterReceived='$LetterReceived',LastCallUpdate='$LastCallUpdate',RecentProblem='$RecentProblem',SystemWorking='$SystemWorking',PolicyPeriod='$PolicyPeriod',Depth='$Depth',PumpMake='$PumpMake',MotorMake='$MotorMake',InsuranceCompany='$InsuranceCompany',BeneficiaryId='$BeneficiaryId' WHERE id = '$id'";
  $conn->query($query2);
  echo "<script>alert('Service Complaint Updated Successfully!');window.location.href='view-service-module.php';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Service Complaint</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                      

                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_installations WHERE WarrantyReg='Yes'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['CustId']) {?> selected <?php } ?> value="<?php echo $result['CustId'];?>">
    <?php echo $result['CustName']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
                                            <label class="form-label">Beneficiary ID </label>
                                            <input type="text" name="BeneficiaryId" id="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-6">
   <label class="form-label">Customer/Farmer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 



 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  
                                                ><?php echo $row7['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>  

 <div class="form-group col-md-4">
   <label class="form-label">Taluka </label>
     <input type="text" name="Taluka" id="Taluka" class="form-control"
                                                placeholder="" value="<?php echo $row7["Taluka"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Village </label>
     <input type="text" name="Village" id="Village" class="form-control"
                                                placeholder="" value="<?php echo $row7["Village"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">District </label>
     <input type="text" name="District" id="District" class="form-control"
                                                placeholder="" value="<?php echo $row7["District"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 


<div class="form-group col-md-4">
   <label class="form-label">Date Of Installation </label>
     <input type="date" name="InstallationDate" id="InstallationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["InstallationDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Registration ID </label>
     <input type="text" name="RegistrationId" id="RegistrationId" class="form-control"
                                                placeholder="" value="<?php echo $row7["RegistrationId"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-4">
   <label class="form-label">Date Of Comissioning </label>
     <input type="date" name="ComissioningDate" id="ComissioningDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ComissioningDate"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

 

<div class="form-group col-md-3">
<label class="form-label"> Service Type<span class="text-danger">*</span></label>
 <select class="form-control" name="ServiceType" id="ServiceType" required>

<option selected="" value="">Select Service Type</option>

  <option value="Insurance" <?php if($row7['ServiceType'] == 'Insurance'){?> selected <?php } ?>>Insurance</option>
    <option value="Maintaince" <?php if($row7['ServiceType'] == 'Maintaince'){?> selected <?php } ?>>Maintaince</option>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" required>

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($row7['RelatedIssue'] == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($row7['RelatedIssue'] == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label"> Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="Issue" id="Issue" required>
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label"> Status<span class="text-danger">*</span></label>
 <select class="form-control" name="ClainStatus" id="ClainStatus" required>
<option selected="" value="">Select</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['ClainStatus'] == $result['Name']){?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Problem </label>
     <input type="text" name="Problem" id="Problem" class="form-control"
                                                placeholder="" value="<?php echo $row7["Problem"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Remark/Requirement </label>
     <input type="text" name="Remark" id="Remark" class="form-control"
                                                placeholder="" value="<?php echo $row7["Remark"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 


 

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

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

 <script type="text/javascript">
  function addVendor(){
        setTimeout(function() {
        window.open(
            'add-customer2.php', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=600,left=350,top=40,right=200'
        );
    }, 1);
    }

     function getPayType(val){
    if(val == 'Cheque'){
      $('.chequeoption').show();
      $('.upioption').hide();
    }
    else if(val == 'UPI'){
      $('.chequeoption').hide();
      $('.upioption').show();
    }
    else{
      $('.chequeoption').hide();
      $('.upioption').hide();
    }
  }

      function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
   $('#GrossAmt').val(sum);
   
    }


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

        var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getCustRow";
    $.ajax({
    url:"ajax_files/ajax_sell_products.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
            getSubTotal();
            commonTotal();
           }
      }); 


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
                    
                    $('#Address').val(data.Address);
                    $('#Taluka').val(data.Taluka);
                    $('#Village').val(data.Village);
                    $('#District').val(data.District);
                    $('#BeneficiaryId').val(data.BeneficiaryId);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                     $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                    $('#ComissioningDate').val(data.CommissioningDate);
                    $('#AcDc').val(data.AcDc);
                    $('#Source').val(data.Source);
                    
                }
            });

        });


    });

     

     function getBrand(catid){
var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: catid
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });
}

function getProd(brandid){
var action = "getProd";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: brandid
                },
                success: function(data) {
                    $('#ProductId').html(data);
                  
                }
            });
}

function getTotal(GrossAmt,CgstPer,SgstPer,IgstPer,SubTotal,Discount){
    //console.log(qty,vedprice,srno);
        var CgstAmt = Number(GrossAmt)*(Number(CgstPer)/100);
        var SgstAmt = Number(GrossAmt)*(Number(SgstPer)/100);
        var IgstAmt = Number(GrossAmt)*(Number(IgstPer)/100);
        $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
        $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
        $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
var SubTotal = Number(GrossAmt) + Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
$('#SubTotal').val(parseFloat(SubTotal).toFixed(2));
var Total = Number(SubTotal) - Number(Discount);
$('#Total').val(parseFloat(Total).toFixed(2));
}

    function commonTotal(){
        var GrossAmt = $('#GrossAmt').val();
        var CgstPer = $('#CgstPer').val();
        var SgstPer = $('#SgstPer').val();
        var IgstPer = $('#IgstPer').val();
        var SubTotal = $('#SubTotal').val();
        var UcdAmt = 0;
        var Discount = $('#Discount').val();
        getTotal(GrossAmt,CgstPer,SgstPer,IgstPer,SubTotal,Discount);
    }

function getProdTotal(qty,price,srno){
    var Total = (Number(qty) * Number(price));
$('#Total'+srno).val(parseFloat(Total).toFixed(2));
getSubTotal();
commonTotal();
}

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
     var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_sell_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",
                success: function(data) {
                
                    $('#ProductName'+srno).val(data.ProductName);
                    $('#ModelNo'+srno).val(data.ModelNo);
                    $('#Price'+srno).val(data.Price); 
                     getProdTotal(qty,data.Price,srno);
                }
            });
}
 </script>
</body>

</html>