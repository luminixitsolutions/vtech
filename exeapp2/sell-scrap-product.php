<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Sell-Sell";
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
$sql7 = "SELECT * FROM tbl_scrap_stock WHERE id='$id'";
$row7 = getRecord($sql7);
if($_GET['id'] == ''){
    $TotStockQty = "0";
}
else{
    $BranchId = $row7['BranchId'];
$sql = "SELECT SUM(Qty) AS CreditStock FROM tbl_scrap_stock WHERE CrDr='cr' AND BranchId='$BranchId'";
$row = getRecord($sql);

$sql2 = "SELECT SUM(Qty) AS DebitStock FROM tbl_scrap_stock WHERE CrDr='dr' AND BranchId='$BranchId'";
$row2 = getRecord($sql2);

$TotStockQty = $row['CreditStock'] - $row2['DebitStock'];
}

if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$SellDate = addslashes(trim($_POST['SellDate']));
$ScrapProdDetails = addslashes(trim($_POST['ScrapProdDetails']));
$BranchId = addslashes(trim($_POST['BranchId']));
$CrDr = addslashes(trim($_POST['CrDr']));
$Qty = addslashes(trim($_POST['Qty']));
$Address = addslashes(trim($_POST['Address']));
$CreatedDate = date('Y-m-d');
if($_GET['id'] == ''){
$sql = "INSERT INTO tbl_scrap_stock SET BranchId='$BranchId',SellId='$SellId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',ScrapProdDetails='$ScrapProdDetails',SellDate='$SellDate',CrDr='$CrDr',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate',Qty='$Qty',Address='$Address'";
$conn->query($sql);
}
else{
$sql = "UPDATE tbl_scrap_stock SET BranchId='$BranchId',SellId='$SellId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',ScrapProdDetails='$ScrapProdDetails',SellDate='$SellDate',CrDr='$CrDr',Status='1',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',Qty='$Qty',Address='$Address' WHERE id='$id'";
$conn->query($sql); 
}

echo "<script>alert('Record Saved Successfully!');window.location.href='view-scrap-products.php';</script>";
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Sell Scrap Product</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-7">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    

  
  <div class="form-group col-md-8">
<label class="form-label"> Branch<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Branch</option>
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


<div class="form-group col-md-4">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="SellDate" id="SellDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 



<div class="form-group col-md-12">
   <label class="form-label">Scrap Product Details</label>
     <textarea name="ScrapProdDetails" id="ScrapProdDetails" class="form-control"  
                                                ><?php echo $row7['ScrapProdDetails']; ?></textarea>
    <div class="clearfix"></div>
 </div>   

 <div class="form-group col-md-4">
                                            <label class="form-label">Total Stock Qty </label>
                                            <input type="text" name="TotStockQty" id="TotStockQty" class="form-control"
                                                placeholder="" value="<?php echo $TotStockQty; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-4">
                                            <label class="form-label">Qty </label>
                                            <input type="text" name="Qty" id="Qty" class="form-control"
                                                placeholder="" value="<?php echo $row7['Qty']; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

 <div class="form-group col-md-4">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="CrDr" name="CrDr" required="">
                                               
                                               <!--  <option value="cr" <?php if($row7["CrDr"]=='cr') {?> selected
                                                    <?php } ?>>Add</option> -->
                                                <option value="dr" <?php if($row7["CrDr"]=='dr') {?> selected
                                                    <?php } ?>>Sell</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <div class="form-group col-md-12">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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
  function addVendor(){
        setTimeout(function() {
        window.open(
            'add-customer2.php', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=600,left=350,top=40,right=200'
        );
    }, 1);
    }
    function checkProdType(val){
        if(val == 1){
           
            $('.svprd').hide();
            $('.nsvprd').show();
        }
        else{
             $('.svprd').show();
            $('.nsvprd').hide();
        }
    }
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
        var action = "calEmi";
            $.ajax({
                url: "ajax_files/ajax_sell.php",
                method: "POST",
                data: {
                    action: action,
                    Balance: Balance,
                    EmiMonth:EmiMonth,
                    InvoiceDate:InvoiceDate
                },
                
                success: function(data) {
                    $('#emidetails').html(data);
                    getSubTotal();
                    
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
                    $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    
                }
            });

    }
     $(document).ready(function() {
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
                     $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                }
            });

        });



$(document).on("change", "#BranchId", function(event) {
            var val = this.value;
            var action = "getTotScrapStock";
            $.ajax({
                url: "ajax_files/ajax_accessories.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                        $('#TotStockQty').val(data);
                    }
            });

        });

    });

     function getTotal(qty,price,Total,ProcFees){
    //console.log(qty,vedprice,srno);
var Total2 = (Number(qty) * Number(price)) - Number(ProcFees);
$('#Total').val(parseFloat(Total2).toFixed(2));
/*var Balance = Number(Total2) - Number(DownPayment);
$('#Balance').val(parseFloat(Balance).toFixed(2));*/
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
                dataType:"json",
                success: function(data) {
                    
                    $('#ProductName').val(data.ProductName);
                    $('#ProdDetails').val(data.Details);
                    $('#ModelNo').val(data.ModelNo);
                    $('#Price').val(data.Price); 
                    getTotal(qty,data.Price,Total,ProcFees)
                }
            });
}
 </script>
</body>

</html>