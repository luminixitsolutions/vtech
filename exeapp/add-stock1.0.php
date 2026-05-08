<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Stock";
$Page = "Add-Stock";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
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
    <link rel="stylesheet" href="example/css/slim.min.css">
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
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">







<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_vendor_invoice WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$InvoiceDate = date('Y-m-d');
?>

<?php 
  if(isset($_POST['submit'])){
    $VedId = $_POST['VedId'];
    $VedName = addslashes(trim($_POST['VedName']));
    $InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
    $InvoiceDate = $_POST['InvoiceDate'];
    $CellNo = addslashes(trim($_POST['CellNo']));
    $Address = addslashes(trim($_POST['Address']));
    $SubTotal = addslashes(trim($_POST['SubTotal']));
    $GstPer = addslashes(trim($_POST['GstPer']));
    $GstAmt = addslashes(trim($_POST['GstAmt']));
    $Discount = addslashes(trim($_POST['Discount']));
    $Amount = addslashes(trim($_POST['Amount']));
    $PaidAmt = addslashes(trim($_POST['PaidAmt']));
    $BalAmt = addslashes(trim($_POST['BalAmt']));
    $CreatedDate = date('Y-m-d');


     $qx = "INSERT INTO tbl_vendor_invoice SET VedId='$VedId',VedName='$VedName',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',CellNo='$CellNo',Address='$Address',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',SubTotal='$SubTotal',GstPer='$GstPer',GstAmt='$GstAmt',Discount='$Discount',Amount='$Amount',PaidAmt='$PaidAmt',BalAmt='$BalAmt'";
  $conn->query($qx);
  $InvId = mysqli_insert_id($conn);

  $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PW'";
    $row2 = getRecord($sql2);
    if($row2['maxid'] == ''){
        $SrNo = 1;
        $Code = "PW".$SrNo;
    }
    else{
        $SrNo = $row2['maxid']+1;
        $Code = "PW".$SrNo;
    }

    $sql3 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='',UserId='$VedId',AccountName='$VedName',InvoiceNo='$InvoiceNo',TotAmt='$Amount',TotPaidAmt='0',TotBalAmt='0',PaidAmt='$Amount',BalAmt='0',CrDr='cr',Roll=3,Type='INV',CreatedDate='$CreatedDate',OrdId='$InvId'";
    $conn->query($sql3);

    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$VedId',AccountName='$VedName',InvoiceNo='$InvoiceNo',TotAmt='$Amount',TotPaidAmt='$PaidAmt',TotBalAmt='$BalAmt',PaidAmt='$PaidAmt',BalAmt='$BalAmt',CrDr='dr',Roll=3,Type='PW',CreatedDate='$CreatedDate',OrdId='$InvId'";
    $conn->query($sql4);

    $number = count($_POST["ProductId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProductId"][$i] != ''))  
              {
                $CatId = $_POST['CatId'][$i];
                $BrandId = $_POST['BrandId'][$i];
                $ProdId = $_POST['ProductId'][$i];
                $Qty = $_POST['Qty'][$i];
                $Price = $_POST['Price'][$i];
                $Total = $_POST['Total'][$i];
                $sql22 = "INSERT INTO tbl_stocks SET InvId='$InvId',VedId='$VedId',InvoiceNo='$InvoiceNo',CatId='$CatId',BrandId='$BrandId',ProductId='$ProdId',Qty='$Qty',Price='$Price',Total='$Total',CreatedDate='$InvoiceDate',CreatedBy='$user_id'";
                $conn->query($sql22);
              }  

          }
      }
   echo "<script>alert('Product Added Successfully!');window.location.href='view-stocks.php';</script>";

    //header('Location:courses.php'); 

  }
 ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Masters</li>
<li class="breadcrumb-item active">Product</li>
</ol>
</div>

<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

  <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Vendor<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="VedId" id="VedId" required>
<option selected="" value="">Select Vendor</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["VedId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-12">
   <label class="form-label">Vendor Name </label>
     <input type="text" name="VedName" id="VedName" class="form-control"
                                                placeholder="" value="<?php echo $row7["VedName"]; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div> 
  
  <div class="form-group col-md-4">
                                            <label class="form-label">Cell No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-lg-4">
<label class="form-label">Invoice No <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7["InvoiceNo"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo $InvoiceDate; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

</div>


 <div id="dynamic_field">
                                        <div class="form-row">
                                          <div class="form-group col-md-2">
<label class="form-label"> Category<span class="text-danger">*</span></label>
 <select class="form-control" name="CatId[]" id="CatId1" required onchange="getBrand(this.value,1)">
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

  <div class="form-group col-md-2">
<label class="form-label"> Brand<span class="text-danger">*</span></label>
 <select class="form-control" name="BrandId[]" id="BrandId1" required onchange="getProd(this.value,1)">
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
                                             <div class="form-group col-md-3">
<label class="form-label">Product</label>
<select class="form-control" id="ProductId1" name="ProductId[]" onchange="getProdDetails(this.value,document.getElementById('srno1').value)">
<option selected="" disabled="" value="">Select Product</option>
 <?php 
     $sql4 = "SELECT * FROM tbl_products WHERE BrandId='".$row7["BrandId"]."' AND Status=1";
     $row4 = getList($sql4);
     foreach($row4 as $result)
      {
      ?>
    <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']." (".$result['PrdNo'].")"; ?></option>
<?php } ?>
</select>

                                       </div>

                                       <div class="form-group col-md-1">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value)">
</div>

<!-- <div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price1" class="form-control" placeholder="e.g.,200" value="" autocomplete="off" readonly>
</div> -->

<div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price1" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value)">
</div>
<input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">
<div class="form-group col-md-2">
<label class="form-label">Total </label>
<div class="input-group">
<input type="text" name="Total[]" id="Total1" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more"><i class="fa fa-plus"></i></button>
      </span>
</div>

</div>
</div>
                                        </div>



 <div class="form-row">
                                    <div class="form-group col-md-2">
                                            <label class="form-label">Sub Total </label>
                                            <input type="text" name="SubTotal" id="SubTotal" class="form-control"
                                                placeholder="" value="<?php echo $row7["SubTotal"]; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

                                        <div class="form-group col-md-1">
                                            <label class="form-label">GST % </label>
                                            <input type="text" name="GstPer" id="GstPer" class="form-control"
                                                placeholder="" value="0"
                                                autocomplete="off" oninput="getTotAmt(document.getElementById('SubTotal').value,document.getElementById('GstPer').value,document.getElementById('Discount').value)">
                                            <div class="clearfix"></div>
                                        </div> 

                                        <div class="form-group col-md-1">
                                            <label class="form-label">GST Amt </label>
                                            <input type="text" name="GstAmt" id="GstAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["GstAmt"]; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Discount </label>
                                            <input type="text" name="Discount" id="Discount" class="form-control"
                                                placeholder="" value="0"
                                                autocomplete="off" required oninput="getTotAmt(document.getElementById('SubTotal').value,document.getElementById('GstPer').value,document.getElementById('Discount').value)">
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Total Amount </label>
                                            <input type="text" name="Amount" id="Amount" class="form-control"
                                                placeholder="" value="<?php echo $row7["Amount"]; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

                                         <div class="form-group col-md-2">
                                            <label class="form-label">Paid Amount </label>
                                            <input type="text" name="PaidAmt" id="PaidAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaidAmt"]; ?>"
                                                autocomplete="off" oninput="getTotAmt(document.getElementById('SubTotal').value,document.getElementById('GstPer').value,document.getElementById('Discount').value,document.getElementById('PaidAmt').value)">
                                            <div class="clearfix"></div>
                                        </div>  

                                         <div class="form-group col-md-2">
                                            <label class="form-label">Balance Amount </label>
                                            <input type="text" name="BalAmt" id="BalAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["BalAmt"]; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div>   
  
                                    </div>



<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
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
 <script>
function getBrand(catid,srno){
var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: catid
                },
                success: function(data) {
                    $('#BrandId'+srno).html(data);
                  
                }
            });
}

function getProd(brandid,srno){
var action = "getProd";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: brandid
                },
                success: function(data) {
                    $('#ProductId'+srno).html(data);
                  
                }
            });
}

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
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
                       $('#Price'+srno).val(res.Price); 
                       getTotal(qty,res.Price,srno)
                }
            });
}

function getTotal(qty,vedprice,srno){
    //console.log(qty,vedprice,srno);
var Total = Number(qty) * Number(vedprice);
$('#Total'+srno).val(parseFloat(Total).toFixed(2));
getSubTotal();
}

  function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
     $('#SubTotal').val(sum);
     var GstPer = $('#GstPer').val();
     var Discount = $('#Discount').val();
     var PaidAmt = $('#PaidAmt').val();
     getTotAmt(sum,GstPer,Discount,PaidAmt);
    }

    function getTotAmt(SubTotal,GstPer,Discount,PaidAmt){
        var GstAmt = Number(SubTotal) * (Number(GstPer)/100);
        $('#GstAmt').val(parseFloat(GstAmt).toFixed(2));
        var Amount = (Number(SubTotal) + Number(GstAmt)) - Number(Discount);
        $('#Amount').val(parseFloat(Amount).toFixed(2));
        //$('#PaidAmt').val(parseFloat(Amount).toFixed(2));
        //var PaidAmt = Amount;
        var BalAmt = Number(Amount) - Number(PaidAmt);
        $('#BalAmt').val(parseFloat(BalAmt).toFixed(2));
    }
  $(document).ready(function() {


  var i=1;  
      $('#add_more').click(function(){  
           i++;  
     var action = "getCustRow";
    $.ajax({
    url:"ajax_files/ajax_products.php",
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
           }
      });


   $(document).on("change", "#VedId", function(event) {
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
                    $('#VedName').val(data.Fname+" "+data.Lname);
                    $('#CellNo').val(data.Phone);
                }
            });

        });


    });

            


        CKEDITOR.replace( 'editor1');
</script>
</body>
</html>