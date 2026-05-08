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

            


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_sell WHERE id='$id'";
$row7 = getRecord($sql7);

$query22 = "SELECT count(*) as totrec FROM tbl_sell_products WHERE SellId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;

if($_GET["action"]=="deleteprd")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_sell_products WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_stocks WHERE PostId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="edit-purchase-order.php?id=<?php echo $bid;?>";
    </script>
<?php } 


if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$Address = addslashes(trim($_POST['Address']));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST['InvoiceDate']));
$PayType = addslashes(trim($_POST['PayType']));
$Narration = addslashes(trim($_POST['Narration']));
$ProdType = addslashes(trim($_POST['ProdType']));
$PayMode = addslashes(trim($_POST['PayMode']));
$DeliveryDate = addslashes(trim($_POST['DeliveryDate']));

$GrossAmt = addslashes(trim($_POST['GrossAmt']));
$CgstPer = addslashes(trim($_POST['CgstPer']));
$CgstAmt = addslashes(trim($_POST['CgstAmt']));
$SgstPer = addslashes(trim($_POST['SgstPer']));
$SgstAmt = addslashes(trim($_POST['SgstAmt']));
$IgstPer = addslashes(trim($_POST['IgstPer']));
$IgstAmt = addslashes(trim($_POST['IgstAmt']));
$SubTotal = addslashes(trim($_POST['SubTotal']));
$UcdAmt = addslashes(trim($_POST['UcdAmt']));
$Discount = addslashes(trim($_POST['Discount']));
$Total = addslashes(trim($_POST['Total']));
$ChequeNo = addslashes(trim($_POST['ChequeNo']));
$ChqDate = addslashes(trim($_POST['ChqDate']));
$BankName = addslashes(trim($_POST['BankName']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$BranchId = addslashes(trim($_POST['BranchId']));

$WarrantyPeriod = addslashes(trim($_POST['WarrantyPeriod']));
$PayStatus = addslashes(trim($_POST['PayStatus']));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');


$sql = "UPDATE tbl_sell SET CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Challan',WarrantyPeriod='$WarrantyPeriod',PayStatus='$PayStatus' WHERE id='$id'";
$conn->query($sql);
$SellId = $id;

$sql = "DELETE FROM tbl_sell_products WHERE SellId='$id'";
$conn->query($sql);
$sql = "DELETE FROM tbl_stocks WHERE SellId='$id' AND SellType='Challan'";
$conn->query($sql);

$number = count($_POST["ProductId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProductId"][$i] != ''))  
              {
                $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                $Purity = addslashes(trim($_POST['Purity'][$i]));
                $Weight = addslashes(trim($_POST['Weight'][$i]));
                $Price = addslashes(trim($_POST['Price'][$i]));
                $Making = addslashes(trim($_POST['Making'][$i]));
                $HmCharge = addslashes(trim($_POST['HmCharge'][$i]));
                $Qty = addslashes(trim($_POST['Qty'][$i]));
                $TotalRate = addslashes(trim($_POST['TotalRate'][$i]));
                $ProductId = addslashes(trim($_POST['ProductId'][$i]));
                $ModelNo = addslashes(trim($_POST['ModelNo'][$i]));
                
                $sql22 = "INSERT INTO tbl_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

                $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan'";
                $conn->query($sql22);
              }  

          }
      }

$sql22 = "DELETE FROM tbl_general_ledger WHERE SellId='$id' AND SellType='Challan'";
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

    $sql3 = "INSERT INTO tbl_general_ledger SET SrNo='0',Code='',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='dr',Roll=5,Type='INV',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Challan',BranchId='$BranchId'";
    $conn->query($sql3);

    $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='cr',Roll=5,Type='PW',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Challan',BranchId='$BranchId'";
    $conn->query($sql4);

    if($CgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$CgstAmt',GstPer='$CgstPer',CrDr='cr',Roll=5,Type='CGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Challan',BranchId='$BranchId'";
    $conn->query($sql4);
    }
    if($SgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$SgstAmt',GstPer='$SgstPer',CrDr='cr',Roll=5,Type='SGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Challan',BranchId='$BranchId'";
    $conn->query($sql4);
    }
    if($IgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$IgstAmt',GstPer='$IgstPer',CrDr='cr',Roll=5,Type='IGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Challan',BranchId='$BranchId'";
    $conn->query($sql4);
    }


echo "<script>alert('Delivery Challan Updated Successfully!');window.location.href='view-sells.php';</script>";
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Delivery Challan</h4>

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
<label class="form-label"> Agency<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Agency</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=11";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>


<!-- <div class="form-group col-md-2" style="padding-top: 30px;">
<label class="form-label">&nbsp;</label>
<button class="btn btn-secondary" type="button" onclick="addVendor()">+</button>
</div> -->

<div class="form-group col-md-12">
                                            <label class="form-label">Contact No </label>
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
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7['InvoiceNo']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">Invoice Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 



</div>


<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;">Purchase Product Details</label>
<table class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
        <th>Stock Qty </th>
        <th>Qty </th>
        <th>Unit</th>
       <!--  <th>Rate</th>
        <th>Amount</th> -->
        <!-- <th></th> -->
    </tr>
     </thead>

      <tbody>
 
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_sell_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
 <tr>
    <td>


<select name="ProductId[]" id="ProductId<?php echo $i; ?>" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i; ?>').value)" class="form-control">
    <option value="" selected>Select Product</option>
    <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
     <option <?php if($result['id'] == $result12['ProductId']){?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
 <?php } ?>
</select>




</td>

  <input type="hidden" name="ProductName[]" id="ProductName<?php echo $i; ?>" value="<?php echo $result12['ProductName']; ?>">
 <input type="hidden" name="ModelNo[]" id="ModelNo<?php echo $i; ?>" value="<?php echo $result12['ModelNo']; ?>">

<td>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="<?php echo $result12["Qty"];?>" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Purity"];?>" autocomplete="off" oninput="getGoldRate(document.getElementById('Purity<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</td>



<!-- <td>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Price"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)" required>
</td>
 -->


<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
<!-- <td>
<input type="text" name="TotalRate[]" id="Total<?php echo $i; ?>" class="form-control txt" placeholder="" value="<?php echo $result12["TotalRate"];?>" autocomplete="off" readonly>
</td> -->
<td>
<a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result12['id']; ?>&action=deleteprd&oid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="feather icon-x"></i></a>
</td>

</tr>
<?php $i++;} ?>
 </tbody>

        <tbody id="dynamic_field" >
    <tr>
        <td>


<select name="ProductId[]" id="ProductId<?php echo $row_cnt; ?>" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $row_cnt; ?>').value)" class="form-control">
    <option value="" selected>Select Product</option>
    <?php 
  $sql12 = "SELECT * FROM tbl_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
     <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
 <?php } ?>
</select>

</td>
       <input type="hidden" name="ProductName[]" id="ProductName<?php echo $row_cnt; ?>" value="">
 <input type="hidden" name="ModelNo[]" id="ModelNo<?php echo $row_cnt; ?>" value="">
 <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $row_cnt; ?>" value="<?php echo $row_cnt; ?>">
 <input type="hidden" class="form-control" name="rncnt" id="rncnt" value="<?php echo $row_cnt; ?>">
<td><input type="number" name="Qty[]" id="Qty<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value)"></td>
        <td><input type="text" name="Purity[]" id="Purity<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off"></td>
      
       <!--  <td><input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value)" required></td>
       
        <td><input type="text" name="TotalRate[]" id="Total<?php echo $row_cnt; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td> -->
        <td><button class="btn btn-secondary" type="button" id="add_more">+</button></td>
    </tr>
    </tbody>
    </table>
</div>



                                     

   <div class="form-row">
       <!--  <div class="form-group col-md-2">
<label class="form-label">Gross Amt <span class="text-danger">*</span></label>
<input type="text" name="GrossAmt" id="GrossAmt" class="form-control" placeholder="" value="<?php echo $row7['GrossAmt'];?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-1">
<label class="form-label">CGST% <span class="text-danger">*</span></label>
<input type="text" name="CgstPer" id="CgstPer" class="form-control" placeholder="" value="<?php echo $row7['CgstPer'];?>" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

<div class="form-group col-md-1">
<label class="form-label">SGST% <span class="text-danger">*</span></label>
<input type="text" name="SgstPer" id="SgstPer" class="form-control" placeholder="" value="<?php echo $row7['SgstPer'];?>" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

<div class="form-group col-md-1">
<label class="form-label">IGST% <span class="text-danger">*</span></label>
<input type="text" name="IgstPer" id="IgstPer" class="form-control" placeholder="" value="<?php echo $row7['IgstPer'];?>" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

 <div class="form-group col-md-2">
<label class="form-label">Sub Total <span class="text-danger">*</span></label>
<input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="<?php echo $row7['SubTotal'];?>" autocomplete="off" readonly>
</div>


<div class="form-group col-md-2">
<label class="form-label">Discount <span class="text-danger">*</span></label>
<input type="text" name="Discount" id="Discount" class="form-control" placeholder="" value="<?php echo $row7['Discount'];?>" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div> 

<div class="form-group col-md-2">
<label class="form-label">Net Payable </label>
<input type="text" name="Total" id="Total" class="form-control" placeholder="" value="<?php echo $row7['Total'];?>" autocomplete="off" readonly>
</div>
<input type="hidden" name="CgstAmt" id="CgstAmt" value="<?php echo $row7['CgstAmt'];?>">
<input type="hidden" name="SgstAmt" id="SgstAmt" value="<?php echo $row7['SgstAmt'];?>">
<input type="hidden" name="IgstAmt" id="IgstAmt" value="<?php echo $row7['IgstAmt'];?>">
-->
 <!-- <div class="form-group col-md-3">
<label class="form-label">Payment Status <span class="text-danger">*</span></label>
  <select class="form-control" id="PayStatus" name="PayStatus" required="">
<option selected="" disabled="" value="">Select Payment Status</option>
<option <?php if($row7['PayStatus'] == 'Payment Mode ON') {?> selected <?php } ?> value="Payment Mode ON
">Payment Mode ON
</option>
<option <?php if($row7['PayStatus'] == 'Payment Mode OFF') {?> selected <?php } ?> value="Payment Mode OFF">Payment Mode OFF</option>


</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Payment Type <span class="text-danger">*</span></label>
  <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
<option selected="" disabled="" value="">Select Payment Type</option>
<option <?php if($row7['PayType'] == 'RTGS') {?> selected <?php } ?> value="RTGS">RTGS</option>
<option <?php if($row7['PayType'] == 'NEFT') {?> selected <?php } ?> value="NEFT">NEFT</option>
<option <?php if($row7['PayType'] == 'Cash') {?> selected <?php } ?> value="Cash">Cash</option>
<option <?php if($row7['PayType'] == 'Cheque') {?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
<option <?php if($row7['PayType'] == 'UPI') {?> selected <?php } ?> value="UPI">UPI</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2 chequeoption" <?php if($row7['PayType'] == 'Cheque') {?> style="display: block;" <?php } else{?>style="display: none;" <?php } ?>>
<label class="form-label">Cheque No <span class="text-danger">*</span></label>
<input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2 chequeoption" <?php if($row7['PayType'] == 'Cheque') {?> style="display: block;" <?php } else{?>style="display: none;" <?php } ?>>
<label class="form-label">Cheque Date <span class="text-danger">*</span></label>
<input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" <?php if($row7['PayType'] == 'Cheque') {?> style="display: block;" <?php } else{?>style="display: none;" <?php } ?>>
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-10 upioption" <?php if($row7['PayType'] == 'UPI') {?> style="display: block;" <?php } else{?>style="display: none;" <?php } ?>>
<label class="form-label">UPI No/ Transaction Id <span class="text-danger">*</span></label>
<input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
<div class="clearfix"></div>
</div>


<div class="form-group col-md-2">
<label class="form-label">Delivery Date <span class="text-danger">*</span></label>
<input type="date" name="DeliveryDate" id="DeliveryDate" class="form-control" placeholder="" value="<?php echo $row7['DeliveryDate']; ?>" autocomplete="off" required>
</div>
 -->
<div class="form-group col-md-4">
<label class="form-label">Warranty Period <span class="text-danger">*</span></label>
  <select class="form-control" id="WarrantyPeriod" name="WarrantyPeriod" required="">
<option selected="" disabled="" value="">Select Warranty Period</option>
<option <?php if($row7['WarrantyPeriod'] == '1') {?> selected <?php } ?> value="1">For Government projects - Full Systems warranty
</option>
<option <?php if($row7['WarrantyPeriod'] == '2') {?> selected <?php } ?> value="2">For Retail Work - Warranty Should be given on the basis of products</option>


</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-8">
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
 getSubTotal();
        commonTotal();
        var i=$('#rncnt').val();
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
                    $('#CustName').val(data.Fname+" "+data.Lname);
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