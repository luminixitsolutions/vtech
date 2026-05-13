<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Order-Store";
$Page = "Assign-Order-2";
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
$sql7 = "SELECT * FROM tbl_distibute_items WHERE id='$id'";
$row7 = getRecord($sql7);

?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Distribute Item To Store</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" action="save-distribute-item-store-2.php">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
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
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> 

                                    <!-- <div class="form-group col-md-4">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required>
    <option selected="" value="">Select</option>
    <?php
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND BranchId='".$_REQUEST['BranchId']."'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreInchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-3">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" id="CreatedDate" class="form-control" value="<?php echo $_REQUEST["CreatedDate"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Vehical Date <span class="text-danger">*</span></label>
<input type="date" name="VehicalDate" id="VehicalDate" class="form-control" value="<?php echo $_REQUEST["VehicalDate"]; ?>" required onchange="getVehicalNos(this.value)">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label"> Vehical Nos<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="VehicalNo" id="VehicalNo" required onchange="getItems(this.value)">
    <option selected="" value="">Select</option>
    <?php
     if($_REQUEST['VehicalDate']!=''){
        $sql12 = "SELECT DISTINCT(VehicalNo) AS VehicalNo FROM tbl_stocks WHERE VehicalNo!='' AND CrDr='cr' AND VehicalDate='".$_REQUEST['VehicalDate']."'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["VehicalNo"] == $result['VehicalNo']) {?> selected <?php } ?> value="<?php echo $result['VehicalNo']; ?>">
        <?php echo $result['VehicalNo']; ?></option>
        <?php } } ?>
</select>

<div class="clearfix"></div>
</div> 

</div>

<?php if($_REQUEST['action'] == 'search'){?>
<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
<table class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
       <!--  <th>Serial No </th> -->
        <th>Stock Qty </th>
        <th>Qty </th>
        <th>Unit</th>
       <!--  <th>Rate</th>
        <th>Amount</th> -->
        <!-- <th></th> -->
    </tr>
     </thead>
        <tbody id="dynamic_field" >
            <?php 
                $i=1;
             $sql12 = "SELECT ts.*,tp.ProductName AS Product_Name,tp.ModelNo AS Model_No,tp.Unit FROM tbl_stocks ts INNER JOIN tbl_products tp ON ts.ProductId=tp.id WHERE ts.CrDr='cr' AND ts.ProdType=0 AND ts.VehicalDate='".$_REQUEST['VehicalDate']."' AND ts.VehicalNo='".$_REQUEST["VehicalNo"]."' GROUP BY ts.ProductId ORDER BY ts.ProductId";
             $rncnt2 = getRow($sql12);
             $row12 = getList($sql12);
  foreach($row12 as $result){
    $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_stocks WHERE CrDr='cr' AND ProductId='".$result['ProductId']."' AND ProdType=0";
    $row11 = getRecord($sql11);
    $CrQty = $row11['CrQty'];

    $sql12 = "SELECT SUM(Qty) AS DrQty FROM tbl_distibute_item_details WHERE ProductId='".$result['ProductId']."' AND ProdType=0";
    $row12 = getRecord($sql12);
    $DrQty = $row12['DrQty'];

    $BalQty = $CrQty - $DrQty;
    if($BalQty > 0){
            ?>
    <tr>
        <td><?php echo $result['Product_Name'];?></td>
<!-- <td><?php echo $result['SerialNo'];?></td> -->
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">
<input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result['ProductId'];?>">


 <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
       <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result['Product_Name'];?>'>
        <input type="hidden" name="SerialNo[]" id="SerialNo1" value='<?php echo $result['SerialNo'];?>'>
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result['Model_No'];?>">
<td style="width: 100px;"><input type="number" name="BalQty[]" id="BalQty1" class="form-control" placeholder="e.g.,1" value="<?php echo $BalQty;?>" autocomplete="off" min="1" readonly></td>
<td style="width: 100px;"><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="0" autocomplete="off" min="0"></td>
        <td style="width: 100px;"><input type="text" name="Purity[]" id="Purity1" class="form-control" placeholder="" value="<?php echo $result['Unit'];?>" autocomplete="off"></td>
      
      
    </tr>
<?php } $i++;} ?>
    </tbody>

    
    </table>
</div>

<?php 
    $sql22 = "SELECT * FROM tbl_stocks WHERE CrDr='cr' AND ProdType='1' AND SerialNo!='' AND VehicalDate='".$_REQUEST['VehicalDate']."' AND VehicalNo='".$_REQUEST["VehicalNo"]."'";
    $rncnt22 = getRow($sql22);
?>
 <input type="hidden" name="Rncnt2" id="Rncnt2" value="<?php echo $rncnt22;?>">
<div class="form-row"> 
 <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Serial No Products</label>
<div class="col-lg-12">
    <table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th style="width: 10px;">#</th>
        <th width="50%">Product</th>
       <th>Serial No </th>
     </tr>
     </thead>
     <tbody>
        <?php 
           $i=1;                              
            $row22 = getList($sql22);
            foreach($row22 as $result){
            $sql33 = "SELECT * FROM tbl_distibute_item_details WHERE ProdType=1 AND SerialNo='".$result['SerialNo']."'";
            $rncnt33 = getRow($sql33);
            if($rncnt33 > 0){}
            else{
        ?>
        
         <tr>
            <td><label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['id']; ?>)">
                    <span class="custom-control-label"></span>
                 </label></td>
            <input type="hidden" name="SerialProd[]" value="<?php echo $result['id'];?>">     
            <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $result['id']; ?>">      
            <td style="width: 100px;"><?php echo $result['ProductName'];?></td>
            <td style="width: 100px;"><?php echo $result['SerialNo'];?></td>
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
   <label class="form-label">Product Head</label>
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
       function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
 function getItems(vehno){
     var BranchId = $('#BranchId').val();
     var StoreInchId = $('#StoreInchId').val();
     var CreatedDate = $('#CreatedDate').val();
     var VehicalDate = $('#VehicalDate').val();
     var VehicalNo = $('#VehicalNo').val();
     window.location.href="distribute-item-store-2.php?action=search&BranchId="+BranchId+"&StoreInchId="+StoreInchId+"&CreatedDate="+CreatedDate+"&VehicalDate="+VehicalDate+"&VehicalNo="+VehicalNo;
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