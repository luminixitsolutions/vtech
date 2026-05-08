<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "service";
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
$CompId = $_GET['qid'];
$sql7 = "SELECT * FROM tbl_complaint_engg_actions WHERE id='$id'";
$row7 = getRecord($sql7);

$sql77 = "SELECT * FROM tbl_rooftop_service_complaint WHERE id='$CompId'";
$row77 = getRecord($sql77);


if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d H:i:s');
$CreatedTime = date('h:i a');
$BeneficiaryId = addslashes(trim($_POST['BeneficiaryId']));
$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$CustName = addslashes(trim($_POST['CustName']));
$Specify = addslashes(trim($_POST['Specify']));
$ServiceDate = addslashes(trim($_POST['ServiceDate']));
$RelatedIssue = addslashes(trim($_POST['RelatedIssue']));
$Issue = addslashes(trim($_POST['Issue']));
$ClainStatus = addslashes(trim($_POST['ClainStatus']));
$Remark = addslashes(trim($_POST['Remark']));

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}


if($_GET['id']==''){
     $sql = "INSERT INTO tbl_complaint_engg_actions SET EnggId='$user_id',CompId='$id',CustId='$CustId',BeneficiaryId='$BeneficiaryId',CustName='$CustName',ServiceDate='$ServiceDate',RelatedIssue='$RelatedIssue',Issue='$Issue',ClainStatus='$ClainStatus',Specify='$Specify',Remark='$Remark',Photo='$Photo',Lattitude='$Lattitude',Longitude='$Longitude',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
$conn->query($sql);

$sql = "UPDATE tbl_rooftop_service_complaint SET ClainStatus='$ClainStatus' WHERE id='$id'";
$conn->query($sql);
  echo "<script>alert('Service Complaint Created Successfully!');window.location.href='view-service-complaint-action.php?id=$id';</script>";
}
else{
    $query2 = "UPDATE tbl_complaint_engg_actions SET EnggId='$user_id',CompId='$id',CustId='$CustId',BeneficiaryId='$BeneficiaryId',CustName='$CustName',ServiceDate='$ServiceDate',RelatedIssue='$RelatedIssue',Issue='$Issue',ClainStatus='$ClainStatus',Specify='$Specify',Remark='$Remark',Photo='$Photo',Lattitude='$Lattitude',Longitude='$Longitude',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id' WHERE id = '$id'";
  $conn->query($query2);

  $sql = "UPDATE tbl_rooftop_service_complaint SET ClainStatus='$ClainStatus' WHERE id='$id'";
  $conn->query($sql);

  echo "<script>alert('Service Complaint Updated Successfully!');window.location.href='view-service-complaint-action.php?id=$id';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Take
                            <?php } ?> Action On Service Complaint</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <input type="hidden" name="CustId" id="CustId" class="form-control" placeholder="" value="<?php echo $row77['CustId']; ?>" autocomplete="off" readonly>
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" disabled>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5 AND id='".$row77['CustId']."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-3">
                                            <label class="form-label">Consumer No </label>
                                            <input type="text" name="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $row77['BeneficiaryId']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 


<div class="form-group col-md-3">
                                            <label class="form-label">Contact No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row77["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" readonly>
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-6">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row77["CustName"]; ?>"
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-12">
   <label class="form-label">Address</label>
     <textarea name="Address" id="Address" class="form-control"  disabled
                                                ><?php echo $row77['Address']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


<div class="form-group col-md-2">
                                            <label class="form-label"> Date </label>
                                            <input type="date" name="ServiceDate" id="ServiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-2">
<label class="form-label"> Service Type<span class="text-danger">*</span></label>
 <select class="form-control" name="ServiceType" id="ServiceType" disabled>

<option selected="" value="">Select Service Type</option>

  <option value="Insurance" <?php if($row77['ServiceType'] == 'Insurance'){?> selected <?php } ?>>Insurance</option>
    <option value="Maintaince" <?php if($row77['ServiceType'] == 'Maintaince'){?> selected <?php } ?>>Maintaince</option>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-2">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" required>

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($row77['RelatedIssue'] == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($row77['RelatedIssue'] == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label"> Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="Issue" id="Issue" required>
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row77['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3 Pump">
                                            <label class="form-label">Status </label>
                                            <select class="form-control" id="ClainStatus" name="ClainStatus">
<option <?php if($row7['ClainStatus']=='Issue Solved'){ ?> selected <?php } ?> value="Issue Solved">Issue Solved</option>
 <option <?php if($row7['ClainStatus']=='Not Solved'){ ?> selected <?php } ?> value="Not Solved">Not Solved</option>
            
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-6">
                                            <label class="form-label">If Not Solved, Specify </label>
                                            <textarea name="Specify" id="Specify" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo $row7['Specify']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                           <div class="form-group col-md-6">
                                            <label class="form-label">Remark </label>
                                            <textarea name="Remark" id="Remark" class="form-control"
                                                placeholder=""
                                                autocomplete="off"></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                          <div class="form-group col-md-6">
                                            <label class="form-label">Photo from site <span
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


                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="Lattitude" id="Lattitude" class="form-control" placeholder="" value="<?php echo $Latitude; ?>" >
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="Longitude" id="Longitude" class="form-control" placeholder="" value="<?php echo $Longitude; ?>" >
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
                    
                    $('#Address').val(data.Taluka+", "+data.Village+", "+data.District);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                     $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
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