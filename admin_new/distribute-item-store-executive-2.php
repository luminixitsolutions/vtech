<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Items-Store-Executive";
$Page = "Assign-Store-Executive-2";
//echo "<pre>";print_r($_SESSION["cart_item"]);
unset($_SESSION["cart_item"]);


function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 


$sql = "SELECT * FROM tbl_distibute_item_details WHERE code=''";
$row = getList($sql);
foreach($row as $result){
    $id = $result['id'];
    $n = 10;
    $Code = RandomStringGenerator($n); 
    $Code2 = $Code."".$id;
    $sql = "UPDATE tbl_distibute_item_details SET code='$Code2' WHERE id='$id'";
    $conn->query($sql);
}
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
                
                if($_REQUEST['CreatedDate']==''){
                    $Created_Date = date('Y-m-d');
                }
                else{
                    $Created_Date = $_REQUEST['CreatedDate'];
                }
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_distibute_items2 WHERE id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
 
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Distribute Item To Dispatch Officier</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" action="save-distribute-item-store-executive-2.php">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-2">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required onchange="getItems(this.value)">
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
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> 

<?php if($Roll == 1 || $Roll == 7){?>


<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required>
    <option selected="" value="">Select</option>
    <?php
        $BranchId = $_REQUEST['BranchId'];
        if($BranchId==''){
            $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26";
        }
        else{
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND MulBranchId IN ($BranchId)";
        }
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> 
<?php } else{?>

<!--<div class="form-group col-md-4">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required onchange="getItems(this.value)">
   
    <?php
     
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND id='$user_id'";
       
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreInchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> -->


<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required onchange="getItems2(this.value)">
    <option selected="" value="">Select</option>
    <?php
     /*$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=32 AND UnderUser='$user_id'";*/
     $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND UnderUser='$user_id'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div>

<?php } ?>

<div class="form-group col-lg-2">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" id="CreatedDate" class="form-control" value="<?php echo $Created_Date; ?>" required>
<div class="clearfix"></div>
</div>



</div>

<?php if($_REQUEST['action'] == 'search'){?>
<!--<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
<table id="example2" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
      
        <th>Stock Qty </th>
        <th>Qty </th>
        <th>Unit</th>
      
    </tr>
     </thead>
        <tbody id="dynamic_field" >
            <?php 
                $i=1;
              $sql12 = "SELECT ts.*,tp.ProductName AS Product_Name,tp.ModelNo AS Model_No,tp.Unit FROM tbl_distibute_item_details ts INNER JOIN tbl_products tp ON ts.ProductId=tp.id WHERE ts.ProdType=0 AND ts.BranchId='".$_REQUEST['BranchId']."' GROUP BY ts.ProductId ORDER BY ts.ProductId";
             $rncnt2 = getRow($sql12);
             $row12 = getList($sql12);
  foreach($row12 as $result){
    $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_distibute_item_details WHERE ProductId='".$result['ProductId']."' AND ProdType=0 AND BranchId='".$_REQUEST['BranchId']."'";
    $row11 = getRecord($sql11);
    $CrQty = $row11['CrQty'];

    $sql12 = "SELECT SUM(Qty) AS DrQty FROM tbl_distibute_item_details2 WHERE ProductId='".$result['ProductId']."' AND ProdType=0 AND BranchId='".$_REQUEST['BranchId']."'";
    $row12 = getRecord($sql12);
    $DrQty = $row12['DrQty'];

    $BalQty = $CrQty - $DrQty;
    if($BalQty > 0){
            ?>
    <tr>
        <td><?php echo $result['Product_Name'];?></td>

<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">
<input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result['ProductId'];?>">


 <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
       <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result['Product_Name'];?>'>
        <input type="hidden" name="SerialNo[]" id="SerialNo1" value='<?php echo $result['SerialNo'];?>'>
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result['Model_No'];?>">
<td><input type="number" name="BalQty[]" id="BalQty1" class="form-control" placeholder="e.g.,1" value="<?php echo $BalQty;?>" autocomplete="off" min="1" readonly></td>
<td><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="0" autocomplete="off" min="0"></td>
        <td><input type="text" name="Purity[]" id="Purity1" class="form-control" placeholder="" value="<?php echo $result['Unit'];?>" autocomplete="off"></td>
      
      
    </tr>
<?php } $i++;} ?>
    </tbody>

    
    </table>
</div>-->

<br>
<?php 
                                        $sql223 = "SELECT * FROM tbl_distibute_item_details WHERE ProdType='2' AND SerialNo!='' AND BranchId='".$_REQUEST['BranchId']."'";
                                        $rncnt223 = getRow($sql223);
                                        ?>
                                        <input type="hidden" name="Rncnt3" id="Rncnt3" value="<?php echo $rncnt223;?>">
  <div class="form-row"> 
 <label class="form-label d-flex align-items-center" style="font-size: 18px; color: #0dc30d;">
  
  Bag Serial No Products &nbsp;|&nbsp;&nbsp;&nbsp; <input type="checkbox" id="selectAll2" onclick="toggleAllCheckboxes2(this)" style="margin-right: 8px;">&nbsp;Select All 
</label>
<div class="col-lg-12">                                      
 <table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
       <th style="width: 10px;">
      <input type="checkbox" id="selectAll2" onclick="toggleAllCheckboxes2(this)">
    </th>
        <th width="50%">Product</th>
       <th>Serial No </th>
     </tr>
     </thead>
     <tbody>

  <?php $row223 = getList($sql223);
    foreach($row223 as $result){
    $sql33 = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType=2 AND SerialNo='".$result['SerialNo']."' AND BranchId='".$_REQUEST['BranchId']."'";
    $rncnt33 = getRow($sql33);
    if($rncnt33 > 0){}
    else{?>
<tr>
    
    <td>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" id="Check_Id2<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['id']; ?>)">
      <span class="custom-control-label"></span>
    </label>
  </td>
  
            <!--<td><label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['id']; ?>)">
                    <span class="custom-control-label"></span>
                 </label></td>-->
            <input type="hidden" name="SerialProd2[]" value="<?php echo $result['id'];?>">     
            <input type="hidden" value="0" name="CheckId2[]" id="CheckId<?php echo $result['id']; ?>">      
            <td><?php echo $result['ProductName'];?></td>
            <td><?php echo $result['SerialNo'];?></td>
         </tr>
         <?php $i++;} } ?>
     </tbody>
</table>

   
</div>

    </div>
    
<br>
<?php 
                                        $sql22 = "SELECT * FROM tbl_distibute_item_details WHERE ProdType='1' AND SerialNo!='' AND BranchId='".$_REQUEST['BranchId']."'";
                                        $rncnt22 = getRow($sql22);
                                        ?>
                                        <input type="hidden" name="Rncnt2" id="Rncnt2" value="<?php echo $rncnt22;?>">
  <div class="form-row"> 
 <label class="form-label d-flex align-items-center" style="font-size: 18px; color: #0dc30d;">
  
  Serial No Products &nbsp;|&nbsp;&nbsp;&nbsp; <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)" style="margin-right: 8px;">&nbsp;Select All 
</label>
<div class="col-lg-12">                                      
 <table id="example3" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
       <th style="width: 10px;">
      <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)">
    </th>
        <th width="50%">Product</th>
       <th>Serial No </th>
     </tr>
     </thead>
     <tbody>

  <?php $row22 = getList($sql22);
    foreach($row22 as $result){
    $sql33 = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType=1 AND SerialNo='".$result['SerialNo']."' AND BranchId='".$_REQUEST['BranchId']."'";
    $rncnt33 = getRow($sql33);
    if($rncnt33 > 0){}
    else{?>
<tr>
    
    <td>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['id']; ?>)">
      <span class="custom-control-label"></span>
    </label>
  </td>
  
            <!--<td><label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['id']; ?>)">
                    <span class="custom-control-label"></span>
                 </label></td>-->
            <input type="hidden" name="SerialProd[]" value="<?php echo $result['id'];?>">     
            <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $result['id']; ?>">      
            <td><?php echo $result['ProductName'];?></td>
            <td><?php echo $result['SerialNo'];?></td>
         </tr>
         <?php $i++;} } ?>
     </tbody>
</table>

   
</div>

    </div>

                                     

   <div class="form-row">
     

<!-- <div class="form-group col-md-4">
<label class="form-label">Warranty Period <span class="text-danger">*</span></label>
  <select class="form-control" id="WarrantyPeriod" name="WarrantyPeriod" required="">
<option selected="" disabled="" value="">Select Warranty Period</option>
<option <?php if($row7['WarrantyPeriod'] == '1') {?> selected <?php } ?> value="1">For Government projects - Full Systems warranty
</option>
<option <?php if($row7['WarrantyPeriod'] == '2') {?> selected <?php } ?> value="2">For Retail Work - Warranty Should be given on the basis of products</option>


</select>
<div class="clearfix"></div>
</div>-->

<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
    <div class="clearfix"></div>
 </div>   


 

</div>

                                   <div class="form-row">
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                
                                    </div>
                                    <?php } ?>
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
function saveCart(id){
     var action = "saveCart";
     var quantity = "1";
      $.ajax({
      url:"assign-serial-no-distribute-session.php",
      type:"POST",
      data:{action:action,quantity:quantity,id:id},
      success:function(data){
          console.log(data);
          //alert(data);
      },
    });
 }
 
 function delete_prod(id){
     var action = "delete_shop_prod";
     var quantity = 1;
      $.ajax({
      url:"assign-serial-no-distribute-session.php",
      type:"POST",
      data:{action:action,id:id},
      success:function(data){
          console.log(data);
      },

    });
 }
 
 
 // === Universal Checkbox Group Handler ===
function setupCheckboxGroup(groupPrefix, selectAllId) {
  const selectAll = document.getElementById(selectAllId);
  const checkboxes = document.querySelectorAll(`input[type='checkbox'][id^='${groupPrefix}']`);

  // --- Select All handler ---
  selectAll?.addEventListener('change', function () {
    const isChecked = this.checked;
    checkboxes.forEach(cb => {
      const id = cb.id.replace(groupPrefix, '');
      cb.checked = isChecked;
      $(`#CheckId${id}`).val(isChecked ? 1 : 0);

      if (isChecked) saveCart(id);
      else delete_prod(id);
    });
  });

  // --- Individual checkbox handler ---
  checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {
      const id = this.id.replace(groupPrefix, '');
      const isChecked = this.checked;
      $(`#CheckId${id}`).val(isChecked ? 1 : 0);
      if (isChecked) saveCart(id);
      else delete_prod(id);

      // update Select All state
      const allChecked = [...checkboxes].every(x => x.checked);
      if (selectAll) selectAll.checked = allChecked;
    });
  });
}

// === Initialize both groups ===
setupCheckboxGroup('Check_Id', 'selectAll');   // For normal serial products
setupCheckboxGroup('Check_Id2', 'selectAll2'); // For bag serial products

        /*function featured(id){
            //alert(id);
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            saveCart(id);
        }
        else{
           $('#CheckId'+id).val(0);
          delete_prod(id);
            }
        }*/
 function getItems(BranchId){
     
     //var BranchId = $('#BranchId').val();
   
     var CreatedDate = $('#CreatedDate').val();

     window.location.href="distribute-item-store-executive-2.php?action=search&BranchId="+BranchId+"&CreatedDate="+CreatedDate;
 }
 
 function getItems2(StoreExeId){
     var BranchId = $('#BranchId').val();
   var StoreInchId = $('#StoreInchId').val();
     var CreatedDate = $('#CreatedDate').val();

     window.location.href="distribute-item-store-executive-2.php?action=search&BranchId="+BranchId+"&StoreInchId="+StoreInchId+"&CreatedDate="+CreatedDate+"&StoreExeId="+StoreExeId;
 }
 function getVehicalNos(vehdate){
     var action = "getVehicalNos";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    vehdate: vehdate
                },
                
                success: function(data) {
                    //alert(data);
                   $('#VehicalNo').html(data);
                    
                }
            });

 }
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
$('#example').DataTable({
    "pageLength":1000,
        "scrollX": true,
        "scrollY": "500px"
    });

$('#example3').DataTable({
    "pageLength":1000,
        "scrollX": true,
        "scrollY": "500px"
    });
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

        $(document).on("change", "#BranchId", function(event) {
            var val = this.value;
            var action = "getStoreIncharge";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    //alert(data);
                    $('#StoreInchId').html(data);
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