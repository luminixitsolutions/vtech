<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
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
    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_sell WHERE id='$id'";
$row7 = getRecord($sql7);

$sql8 = "SELECT * FROM tbl_emi WHERE SellId='$id' AND PayStatus=1";
$rncnt8 = getRow($sql8);

if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST['InvoiceDate']));
$PayType = addslashes(trim($_POST['PayType']));
$CatId = addslashes(trim($_POST['CatId']));
$BrandId = addslashes(trim($_POST['BrandId']));
$ProductId = addslashes(trim($_POST['ProductId']));
$Qty = addslashes(trim($_POST['Qty']));
$Price = addslashes(trim($_POST['Price']));
$ProcFees = addslashes(trim($_POST['ProcFees']));
$Total = addslashes(trim($_POST['Total']));
$DownPayment = addslashes(trim($_POST['DownPayment']));
$Balance = addslashes(trim($_POST['Balance']));
$EmiMonth = addslashes(trim($_POST['EmiMonth']));
$Gname = addslashes(trim($_POST['Gname']));
$Gphone = addslashes(trim($_POST['Gphone']));
$Gname2 = addslashes(trim($_POST['Gname2']));
$Gphone2 = addslashes(trim($_POST['Gphone2']));
$ProdDetails = addslashes(trim($_POST['ProdDetails']));
$Address = addslashes(trim($_POST['Address']));
$Narration = addslashes(trim($_POST['Narration']));
$CreatedDate = date('Y-m-d');

$sql = "UPDATE tbl_sell SET CustId='$CustId',CustName='$CustName',CellNo='$CellNo',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',CatId='$CatId',BrandId='$BrandId',ProductId='$ProductId',Qty='$Qty',Price='$Price',ProcFees='$ProcFees',Total='$Total',DownPayment='$DownPayment',Balance='$Balance',EmiMonth='$EmiMonth',Status=1,ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Gname='$Gname',Gphone='$Gphone',Gname2='$Gname2',Gphone2='$Gphone2',ProdDetails='$ProdDetails',Address='$Address',Narration='$Narration' WHERE id='$id'";
$conn->query($sql);
$sql = "DELETE FROM tbl_emi WHERE SellId='$id'";
$conn->query($sql);
if($PayType == 'EMI'){
     $number = count($_POST["EmiDate"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["EmiDate"][$i] != ''))  
              {
                $EmiChequeNo = $_POST['EmiChequeNo'][$i];
                $EmiDate = $_POST['EmiDate'][$i];
                $EmiAmt = $_POST['EmiAmt'][$i];
                
                $sql22 = "INSERT INTO tbl_emi SET SellId='$id',CustId='$CustId',EmiDate='$EmiDate',EmiAmt='$EmiAmt',EmiChequeNo='$EmiChequeNo'";
                $conn->query($sql22);
              }  

          }
      }
}

$sql22 = "DELETE FROM tbl_general_ledger WHERE SellId='$id'";
$conn->query($sql22);
$sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PR'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PR".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PR".$SrNo;
    }

    $sql3 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',TotAmt='$Total',TotPaidAmt='0',TotBalAmt='0',PaidAmt='$Total',BalAmt='0',CrDr='dr',Roll=5,Type='INV',ProcFees='$ProcFees',CreatedDate='$CreatedDate',SellId='$id'";
    $conn->query($sql3);

    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',TotAmt='$Total',TotPaidAmt='$DownPayment',TotBalAmt='$Balance',PaidAmt='$DownPayment',BalAmt='$Balance',CrDr='cr',Roll=5,Type='PR',CreatedDate='$CreatedDate',SellId='$id'";
    $conn->query($sql4);
echo "<script>alert('New Sell Added Successfully!');window.location.href='view-sells.php';</script>";
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> New Sell</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-7">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" id="SellId" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5 AND id='".$row7["CustId"]."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
                                            <label class="form-label">Cell No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
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
                                            <label class="form-label">Ref No </label>
                                            <input type="text" name="Gphone" id="Gphone" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gphone"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-8">
   <label class="form-label">Ref Name </label>
     <input type="text" name="Gname" id="Gname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gname"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 

  <div class="form-group col-md-4">
                                            <label class="form-label">Ref No 2</label>
                                            <input type="text" name="Gphone2" id="Gphone2" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gphone2"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-8">
   <label class="form-label">Ref Name 2</label>
     <input type="text" name="Gname2" id="Gname2" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gname2"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

<div class="form-group col-lg-6">
<label class="form-label">Invoice No <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7["InvoiceNo"]; ?>" required readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 


 <div class="form-group col-md-6">
<label class="form-label"> Payment Type<span class="text-danger">*</span></label>
 <select class="form-control" name="PayType" id="PayType" required onchange="checkPayType(this.value)">
<option selected="" value="" disabled>Select Payment Type</option>
  <option value="Cash" <?php if($row7["PayType"] == 'Cash') {?> selected <?php } ?>>
    By Cash</option>
<option value="EMI" <?php if($row7["PayType"] == 'EMI') {?> selected <?php } ?>>
    By EMI</option>

</select>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-6">
<label class="form-label"> Category<span class="text-danger">*</span></label>
 <select class="form-control" name="CatId" id="CatId" required onchange="getBrand(this.value)">
<option selected="" value="">Select Category</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_category WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CatId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-6">
<label class="form-label"> Brand<span class="text-danger">*</span></label>
 <select class="form-control" name="BrandId" id="BrandId" required onchange="getProd(this.value)">
<option selected="" value="">Select Brand</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_sub_category WHERE CatId='".$row7["CatId"]."' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["BrandId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
                                             <div class="form-group col-md-6">
<label class="form-label">Product</label>
<select class="select2-demo form-control" id="ProductId" name="ProductId" onchange="getProdDetails(this.value)">
<option selected="" disabled="" value="">Select Product</option>
 <?php 
     $sql4 = "SELECT * FROM tbl_products WHERE BrandId='".$row7["BrandId"]."' AND Status=1";
     $row4 = getList($sql4);
     foreach($row4 as $result)
      {
      ?>
    <option <?php if($row7["ProductId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (".$result['PrdNo'].")"; ?></option>
<?php } ?>
</select>

                                       </div>

  <div class="form-group col-md-12">
   <label class="form-label">Product Details</label>
     <textarea name="ProdDetails" id="ProdDetails" class="form-control"  
                                                ><?php echo $row7['ProdDetails']; ?></textarea>
    <div class="clearfix"></div>
 </div>   
                                        <div class="form-group col-md-3">
<label class="form-label">Qty </label>
<input type="number" name="Qty" id="Qty" class="form-control" placeholder="e.g.,1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" value="<?php echo $row7['Qty']; ?>">
</div>



<div class="form-group col-md-3">
<label class="form-label">Price </label>
<input type="text" name="Price" id="Price" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Price']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" >
</div>

<div class="form-group col-md-3">
<label class="form-label">Processing Fees </label>
<input type="text" name="ProcFees" id="ProcFees" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['ProcFees']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)">
</div>

<div class="form-group col-md-3">
<label class="form-label">Total </label>
<input type="text" name="Total" id="Total" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Total']; ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-4">
<label class="form-label">Down Payment </label>
<input type="text" name="DownPayment" id="DownPayment" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['DownPayment']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)">
</div>

<div class="form-group col-md-4">
<label class="form-label">Balance </label>
<input type="text" name="Balance" id="Balance" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Balance']; ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-4">
<label class="form-label">EMI Month </label>
<input type="number" name="EmiMonth" id="EmiMonth" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['EmiMonth']; ?>" autocomplete="off" min="1" oninput="calEmi()">
</div>

<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>  

                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <?php if($rncnt8 > 0){?>
                                        <div class="form-group col-md-2">
                                    <button type="button" class="btn btn-primary btn-finish" disabled>Save</button>
                                    </div>
                                    <div class="form-group col-md-10" style="display:block;color:red;padding-top: 10px">
                                    You Cant Update Record!
                                    </div>
                                    <?php } else{?>
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>
                                    <div class="form-group col-md-10" id="errormsg2" style="display:none;color:red;padding-top: 10px">
                                    Amount must be equal to balance amt
                                    </div>
                                <?php } ?>

                                    
                                    </div>
                               </div>


 <div class="col-lg-5" id="emidetails" <?php if($row7['PayType'] == 'EMI'){?> style="display:block;" <?php } else{?> style="display:none;"<?php } ?>>
    

 </div>

  
                                

 </div>
 </form>





                            </div>
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
     function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
    var EmiBalance = $('#EmiBalance').val();
    if(EmiBalance == sum){
        $('#errormsg').hide();
        $('#errormsg2').hide();
        $('#submit').attr("disabled",false);
    }
    else{
        $('#errormsg').show();
        $('#errormsg2').show();
        $('#submit').attr("disabled",true);
    }
     $('#TotalEmiAmt').val(sum);
     $('.TotalEmiAmt').html(sum);
   
    }

    function checkEmiAmt(srno){
        var EmiAmt = $('#EmiAmt'+srno).val();
        getSubTotal();
    }
    function calEmi(){
        var Balance = $('#Balance').val();
        var EmiMonth = $('#EmiMonth').val();
        var InvoiceDate = $('#InvoiceDate').val();
        var SellId = $('#SellId').val();
        var action = "calEmi";
            $.ajax({
                url: "ajax_files/ajax_sell.php",
                method: "POST",
                data: {
                    action: action,
                    Balance: Balance,
                    EmiMonth:EmiMonth,
                    InvoiceDate:InvoiceDate,
                    SellId:SellId
                },
                
                success: function(data) {
                    $('#emidetails').html(data);
                    getSubTotal();
                    
                }
            });
    }

     function calEditEmi(){
        var Balance = $('#Balance').val();
        var EmiMonth = $('#EmiMonth').val();
        var InvoiceDate = $('#InvoiceDate').val();
        var SellId = $('#SellId').val();
        var action = "calEditEmi";
            $.ajax({
                url: "ajax_files/ajax_sell.php",
                method: "POST",
                data: {
                    action: action,
                    Balance: Balance,
                    EmiMonth:EmiMonth,
                    InvoiceDate:InvoiceDate,
                    SellId:SellId
                },
                
                success: function(data) {
                    $('#emidetails').html(data);
                    //getSubTotal();
                    
                }
            });
    }
    function checkPayType(val){
        if(val == 'Cash'){
            $('#emidetails').hide();
        }
        else{
            $('#emidetails').show();
            calEmi();
        }
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
                    
                }
            });

    }
     $(document).ready(function() {
        calEditEmi();
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
                    $('#CustName').val(data.Fname+" "+data.Lname);
                    $('#CellNo').val(data.Phone);
                }
            });

        });


    });

     function getTotal(qty,price,DownPayment,Total,ProcFees){
    //console.log(qty,vedprice,srno);
var Total2 = (Number(qty) * Number(price)) + Number(ProcFees);
$('#Total').val(parseFloat(Total2).toFixed(2));
var Balance = Number(Total2) - Number(DownPayment);
$('#Balance').val(parseFloat(Balance).toFixed(2));
}

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

function getProdDetails(val){
    var qty = $('#Qty').val();
    var DownPayment = $('#DownPayment').val();
    var Total = $('#Total').val();
    var ProcFees = $('#ProcFees').val();
     var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    var res = JSON.parse(data);
                       $('#Price').val(res.Price); 
                       getTotal(qty,res.Price,DownPayment,Total,ProcFees)
                }
            });
}
 </script>
</body>

</html>