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
$sql7 = "SELECT * FROM tbl_accessories_sell WHERE id='$id'";
$row7 = getRecord($sql7);


$query22 = "SELECT count(*) as totrec FROM tbl_accessories_stock WHERE SellId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;

if($_GET["action"]=="deleteacc")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_accessories_stock WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="edit-accessories-sell.php?id=<?php echo $bid;?>";
    </script>
<?php } 

if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST['InvoiceDate']));
$PayType = addslashes(trim($_POST['PayType']));
$CatId = addslashes(trim($_POST['CatId']));
$BrandId = addslashes(trim($_POST['BrandId']));
$ProductId = addslashes(trim($_POST['AccId']));
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
$ProductNo = addslashes(trim($_POST['ProductNo']));
$CatName = addslashes(trim($_POST['CatName']));
$BrandName = addslashes(trim($_POST['BrandName']));
$ProductName = addslashes(trim($_POST['AccName']));
$ModelNo = addslashes(trim($_POST['ModelNo']));
$ProdType = addslashes(trim($_POST['ProdType']));
$ScrapProdDetails = addslashes(trim($_POST['ScrapProdDetails']));
$BranchId = addslashes(trim($_POST['BranchId']));
$ExeId = addslashes(trim($_POST['ExeId']));
$SubTotal = addslashes(trim($_POST['SubTotal']));
$Discount = addslashes(trim($_POST['Discount']));
$TotalAmt = addslashes(trim($_POST['TotalAmt']));
$AgentName = addslashes(trim($_POST['AgentName']));
$CreatedDate = date('Y-m-d');

$sql = "UPDATE tbl_accessories_sell SET CustId='$CustId',CustName='$CustName',CellNo='$CellNo',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',CatId='$CatId',BrandId='$BrandId',ProductId='$ProductId',Qty='$Qty',Price='$Price',ProcFees='$ProcFees',Total='$Total',DownPayment='$DownPayment',Balance='$Balance',EmiMonth='$EmiMonth',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Gname='$Gname',Gphone='$Gphone',Gname2='$Gname2',Gphone2='$Gphone2',ProdDetails='$ProdDetails',Address='$Address',Narration='$Narration',ProductNo='$ProductNo',CatName='$CatName',BrandName='$BrandName',ProductName='$ProductName',ModelNo='$ModelNo',ProdType='$ProdType',ScrapProdDetails='$ScrapProdDetails',BranchId='$BranchId',ExeId='$ExeId',SubTotal='$SubTotal',Discount='$Discount',TotalAmt='$TotalAmt',AgentName='$AgentName' WHERE id='$id'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);

$sql = "DELETE FROM tbl_accessories_stock WHERE SellId='$id'";
$conn->query($sql);
$number = count($_POST['AccId']);
if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["AccId"][$i] != ''))  
                     {
                        $AccId = addslashes($_POST['AccId'][$i]);
                        $Qty = addslashes($_POST['Qty'][$i]);
                        $Price = addslashes($_POST['Price'][$i]);
                        $Total = addslashes($_POST['Total'][$i]);
                      
$sql22 = "INSERT INTO tbl_accessories_stock SET BranchId='$BranchId',SellId='$SellId',AccId='$AccId',Price='$Price',Qty='$Qty',Total='$Total',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$CreatedDate',Narration='$Narration'";
$conn->query($sql22);
}
}
}

$sql22 = "DELETE FROM tbl_general_ledger WHERE AccSellId='$id'";
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

    $sql3 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',TotAmt='$Total',TotPaidAmt='0',TotBalAmt='0',PaidAmt='$Total',BalAmt='0',CrDr='dr',Roll=5,Type='INV',ProcFees='$ProcFees',CreatedDate='$CreatedDate',AccSellId='$SellId'";
    $conn->query($sql3);

    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',TotAmt='$Total',TotPaidAmt='$DownPayment',TotBalAmt='$Balance',PaidAmt='$DownPayment',BalAmt='$Balance',CrDr='cr',Roll=5,Type='PR',CreatedDate='$CreatedDate',AccSellId='$SellId'";
    $conn->query($sql4);
echo "<script>alert('New Accessories Sell Updated Successfully!');window.location.href='view-accessories-sells.php';</script>";
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Accessories Sell</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-9">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-md-2" style="padding-top: 40px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

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

  <!-- <div class="form-group col-md-12">
   <label class="form-label">Agent Name </label>
     <input type="text" name="AgentName" id="AgentName" class="form-control"
                                                placeholder="" value="<?php echo $row7["AgentName"]; ?>"
                                                autocomplete="off" readonly>
    <div class="clearfix"></div>
 </div>  -->

 <div class="form-group col-md-7">
<label class="form-label"> Technician<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="ExeId" id="ExeId" required>
<option selected="" value="">Select Technician</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=20";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." ".$result['Lname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  
  <div class="form-group col-md-5">
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

<div class="form-group col-lg-4">
<label class="form-label">Invoice No <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7["InvoiceNo"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 


 <div class="form-group col-md-4">
<label class="form-label"> Payment Mode<span class="text-danger">*</span></label>
<select class="form-control" name="PayType" id="PayType" required onchange="checkPayType(this.value)">
<option selected="" value="" disabled>Select Payment Type</option>
  <option value="Cash" <?php if($row7["PayType"] == 'Cash') {?> selected <?php } ?>>
    By Cash</option>
<option value="Cheque" <?php if($row7["PayType"] == 'Cheque') {?> selected <?php } ?>>
    By Cheque</option>

</select>
<div class="clearfix"></div>
</div>
</div>

<input type="hidden" id="CatId" name="CatId" value="">
<input type="hidden" id="BrandId" name="BrandId" value="">   
<?php 
$i=1;
$sql_1 = "SELECT * FROM tbl_accessories_stock WHERE SellId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<div class="form-row">
<div class="form-group col-md-4 ">
<label class="form-label">Accessories</label>
 <select class="form-control" name="AccId[]" id="AccId<?php echo $i; ?>"  onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i; ?>').value)">
<option selected="" value="">Select Accessories</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_accessories WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result2){
     ?>
  <option <?php if($result["AccId"] == $result2['id']) {?> selected <?php } ?> value="<?php echo $result2['id'];?>">
    <?php echo $result2['AccName']; ?></option>
<?php } ?>
</select>
</div>



                                        <div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="<?php echo $result["Qty"];?>" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</div>



<div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="e.g.,150" value="<?php echo $result["Price"];?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</div>



<div class="form-group col-md-3">
<label class="form-label">Total </label>
<input type="text" name="Total[]" id="Total<?php echo $i; ?>" class="form-control txt" placeholder="e.g.,150" value="<?php echo $result["Total"];?>" autocomplete="off" readonly>
</div>
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">


<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=deleteacc&oid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
</div>

</div>
<?php $i++;} ?>

<div id="dynamic_field">
    <div class="form-row">
<div class="form-group col-md-4 ">
<label class="form-label">Accessories</label>
 <select class="form-control" name="AccId[]" id="AccId<?php echo $row_cnt; ?>"  onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $row_cnt; ?>').value)">
<option selected="" value="">Select Accessories</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_accessories WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["AccId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['AccName']; ?></option>
<?php } ?>
</select>
</div>



                                        <div class="form-group col-md-2">
<label class="form-label">Qty </label>
<input type="number" name="Qty[]" id="Qty<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value)">
</div>



<div class="form-group col-md-2">
<label class="form-label">Price </label>
<input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,150" value="" autocomplete="off" oninput="getTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value)">
</div>



<div class="form-group col-md-3">
<label class="form-label">Total </label>
<input type="text" name="Total[]" id="Total<?php echo $row_cnt; ?>" class="form-control txt" placeholder="e.g.,150" value="" autocomplete="off" readonly>
</div>
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $row_cnt; ?>" value="<?php echo $row_cnt; ?>">
<input type="hidden" class="form-control" name="rncnt" id="rncnt" value="<?php echo $row_cnt; ?>">

<div class="form-group col-md-1" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" id="add_more">+</button>
</div>

</div>
</div>

<div class="form-row">

    <div class="form-group col-md-3">
<label class="form-label">Sub Total <span class="text-danger">*</span></label>
<input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['SubTotal']; ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-3">
<label class="form-label">Service Charges <span class="text-danger">*</span></label>
<input type="text" name="ProcFees" id="ProcFees" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['ProcFees']; ?>" autocomplete="off" oninput="getGrandTotal(document.getElementById('SubTotal').value,document.getElementById('Discount').value,document.getElementById('ProcFees').value)" required>
</div>

<div class="form-group col-md-3">
<label class="form-label">Discount <span class="text-danger">*</span></label>
<input type="text" name="Discount" id="Discount" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Discount']; ?>" autocomplete="off" oninput="getGrandTotal(document.getElementById('SubTotal').value,document.getElementById('Discount').value,document.getElementById('ProcFees').value)" required>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total <span class="text-danger">*</span></label>
<input type="text" name="TotalAmt" id="TotalAmt" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['TotalAmt']; ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"  
                                                ><?php echo $row7['Narration']; ?></textarea>
    <div class="clearfix"></div>
 </div>  

                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <div class="form-group col-md-2">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                    </div>

                                    <div class="form-group col-md-10" id="errormsg2" style="display:none;color:red;padding-top: 10px">
                                    Amount must be equal to balance amt
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
    
     $('#SubTotal').val(parseFloat(sum).toFixed(2));
     var Discount = $('#Discount').val();
     var ProcFees = $('#ProcFees').val();
    getGrandTotal(sum,Discount,ProcFees);
    }

    function getGrandTotal(SubTotal,Discount,ProcFees){
        var TotalAmt = (Number(SubTotal) + Number(ProcFees)) - Number(Discount);
        $('#TotalAmt').val(parseFloat(TotalAmt).toFixed(2));
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


    });

   function getTotal(qty,price,srno){
    //console.log(qty,vedprice,srno);
var Total2 = (Number(qty) * Number(price));
$('#Total'+srno).val(parseFloat(Total2).toFixed(2));
/*var Balance = Number(Total2) - Number(DownPayment);
$('#Balance').val(parseFloat(Balance).toFixed(2));*/
getSubTotal();
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

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
    var DownPayment = $('#DownPayment'+srno).val();
    var Total = $('#Total'+srno).val();
    var ProcFees = $('#ProcFees'+srno).val();
     var action = "getAccDetails";
            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",
                success: function(data) {
                    
                    $('#AccName'+srno).val(data.AccName);
                    $('#ProdDetails'+srno).val(data.Details);
                    $('#ModelNo'+srno).val(data.ModelNo);
                    $('#Price'+srno).val(data.Price); 
                    getTotal(qty,data.Price,srno)
                }
            });
}

$(document).ready(function(){
  var i=$('#rncnt').val(); 
    $('#add_more').click(function(){  
           i++;  
       var action = "addMoreService";
    $.ajax({
    url:"ajax_files/ajax_accessories.php",
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

  }); 
 </script>
</body>

</html>