<?php session_start();
require_once '../config.php';
require_once '../auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity'])));

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
    //echo $_GET['city_id'];
    if($_GET['city_id']==0 || $_GET['city_id']==''){
    $city_id = $row11['CityId'];  
    
}
else{
  $city_id = $_REQUEST['city_id'];
}
//echo $city_id;
/* $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$UserId'";
    $conn->query($sql);*/
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
    <link rel="apple-touch-icon" href="../img/favicon180.png" sizes="180x180">
    <link rel="icon" href="../img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="../vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet" id="style">
     <?php include_once 'header_script.php'; ?>
     <script src="../ckeditor/ckeditor.js"></script>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once '../sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once '../top_header.php'; ?>
    
   
    
        <div class="main-container">
           
            
          <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_purchase_order WHERE id='$id'";
$row7 = getRecord($sql7);


    $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_purchase_order";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$Invoice_No = "00".$MaxId;


if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CompId = $_POST['CompId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$Address = addslashes(trim($_POST['Address']));
//$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
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

$PurchaseSubject = addslashes(trim($_POST['PurchaseSubject']));
$Ref1 = addslashes(trim($_POST['Ref1']));
$Ref2 = addslashes(trim($_POST['Ref2']));
$Details = addslashes(trim($_POST['Details']));
$TermsCondition = addslashes(trim($_POST['TermsCondition']));
$ConsigneeAddress = addslashes(trim($_POST['ConsigneeAddress']));
$SpecialConditions = addslashes(trim($_POST['SpecialConditions']));

$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

 $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_purchase_order";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$InvoiceNo = "00".$MaxId;

$sql = "INSERT INTO tbl_purchase_order SET CompId='$CompId',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Purchase',PurchaseSubject='$PurchaseSubject',Ref1='$Ref1',Ref2='$Ref2',Details='$Details',TermsCondition='$TermsCondition',ConsigneeAddress='$ConsigneeAddress',SpecialConditions='$SpecialConditions'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);

 $number = count($_POST['Ref']);
  if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Ref"][$i] != ''))  
                     {
                        $Links = addslashes($_POST['Ref'][$i]);
                        $sql = "INSERT INTO tbl_po_references SET SellId='$SellId',Ref='$Links'";
                        $conn->query($sql);

                     }
                }
             }

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
                
                $SGST = addslashes(trim($_POST['SGST'][$i]));
                $CGST = addslashes(trim($_POST['CGST'][$i]));
                $IGST = addslashes(trim($_POST['IGST'][$i]));
                
                $sql22 = "INSERT INTO tbl_purchase_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

                /*$sql22 = "INSERT INTO tbl_stocks SET CompId='$CompId',SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Purchase'";
                $conn->query($sql22);*/
              }  

          }
      }



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

    $sql3 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='0',Code='',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='cr',Roll=3,Type='PO',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
    $conn->query($sql3);

    $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='dr',Roll=3,Type='PW',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
    $conn->query($sql4);

    if($CgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$CgstAmt',GstPer='$CgstPer',CrDr='dr',Roll=3,Type='CGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
    $conn->query($sql4);
    }
    if($SgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$SgstAmt',GstPer='$SgstPer',CrDr='dr',Roll=3,Type='SGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
    $conn->query($sql4);
    }
    if($IgstPer > 0){
        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$IgstAmt',GstPer='$IgstPer',CrDr='dr',Roll=3,Type='IGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
    $conn->query($sql4);
    }


echo "<script>window.location.href='view-purchase-order.php';</script>";
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Purchase Order</h4>

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
<label class="form-label"> Company<span class="text-danger">*</span></label>
 <select class="form-control" name="CompId" id="CompId" required>
<option selected="" value="">Select Company</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CompId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                    <div class="form-group col-md-12" style="padding-top:10px;">
<label class="form-label"> Manufacture<span class="text-danger">*</span></label>
 <select class="form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Manufacture</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3";
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
   <label class="form-label">Manufacture Name </label>
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
<label class="form-label">Purchase Order No <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">Purchase Order Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 



</div>


<div class="form-row" style="overflow-x: auto;">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;">Purchase Product Details</label>
<table class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
        <th>Qty </th>
        <th>Unit</th>
        <th>Rate</th>
        <th>SGST</th>
        <th>CGST</th>
        <th>IGST</th>
        <th>Amount</th>
        <th></th>
    </tr>
     </thead>
        <tbody id="dynamic_field" >
    <tr>
        <td>


<select name="ProductId[]" id="ProductId1" onchange="getProdDetails(this.value,document.getElementById('srno1').value)" class="form-control">
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
       <input type="hidden" name="ProductName[]" id="ProductName1" value="">
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="">
 <input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">
<td><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)"></td>
        <td><input type="text" name="Purity[]" id="Purity1" class="form-control" placeholder="" value="" autocomplete="off"></td>
      
        <td><input type="text" name="Price[]" id="Price1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" required></td>
       
       <td><input type="text" name="SGST[]" id="SGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" required></td>
       <td><input type="text" name="CGST[]" id="CGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" required></td>
       <td><input type="text" name="IGST[]" id="IGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" required></td>
        <td><input type="text" name="TotalRate[]" id="Total1" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td>
        <td><button class="btn btn-secondary" type="button" id="add_more">+</button></td>
    </tr>
    </tbody>
    </table>
</div>



                                     

   <div class="form-row">
       <input type="hidden" name="CgstPer" id="CgstPer" value="0">
        <input type="hidden" name="SgstPer" id="SgstPer" value="0">
         <input type="hidden" name="IgstPer" id="IgstPer" value="0">
        <div class="form-group col-md-2">
<label class="form-label">Gross Amt <span class="text-danger">*</span></label>
<input type="text" name="GrossAmt" id="GrossAmt" class="form-control" placeholder="" value="" autocomplete="off" readonly>
</div>

<!--<div class="form-group col-md-1">
<label class="form-label">CGST% <span class="text-danger">*</span></label>
<input type="text" name="CgstPer" id="CgstPer" class="form-control" placeholder="" value="0" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

<div class="form-group col-md-1">
<label class="form-label">SGST% <span class="text-danger">*</span></label>
<input type="text" name="SgstPer" id="SgstPer" class="form-control" placeholder="" value="0" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

<div class="form-group col-md-1">
<label class="form-label">IGST% <span class="text-danger">*</span></label>
<input type="text" name="IgstPer" id="IgstPer" class="form-control" placeholder="" value="0" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>-->

 <div class="form-group col-md-2">
<label class="form-label">Sub Total <span class="text-danger">*</span></label>
<input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="" autocomplete="off" readonly>
</div>


<div class="form-group col-md-2">
<label class="form-label">Discount <span class="text-danger">*</span></label>
<input type="text" name="Discount" id="Discount" class="form-control" placeholder="" value="0" autocomplete="off" oninput="getTotal(document.getElementById('GrossAmt').value,document.getElementById('CgstPer').value,document.getElementById('SgstPer').value,document.getElementById('IgstPer').value,document.getElementById('SubTotal').value,document.getElementById('Discount').value)" required>
</div>

<div class="form-group col-md-2">
<label class="form-label">Net Payable </label>
<input type="text" name="Total" id="Total" class="form-control" placeholder="" value="" autocomplete="off" readonly>
</div>
<input type="hidden" name="CgstAmt" id="CgstAmt" value="">
<input type="hidden" name="SgstAmt" id="SgstAmt" value="">
<input type="hidden" name="IgstAmt" id="IgstAmt" value="">

 <div class="form-group col-md-2">
<label class="form-label">Payment Type <span class="text-danger">*</span></label>
  <select class="form-control" id="PayType" name="PayType" required="" onchange="getPayType(this.value);">
<option selected="" disabled="" value="">Select Payment Type</option>
<!--<option <?php if($row7['PayType'] == 'Cash') {?> selected <?php } ?> value="Cash">Cash</option>
<option <?php if($row7['PayType'] == 'Cheque') {?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
<option <?php if($row7['PayType'] == 'UPI') {?> selected <?php } ?> value="UPI">UPI</option>-->
<option <?php if($row7['PayType'] == 'NEFT') {?> selected <?php } ?> value="NEFT">NEFT</option>
<option <?php if($row7['PayType'] == 'RTGS') {?> selected <?php } ?> value="RTGS">RTGS</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2 chequeoption" style="display: none;">
<label class="form-label">Cheque No <span class="text-danger">*</span></label>
<input type="text" name="ChequeNo" class="form-control" id="ChequeNo" placeholder="Cheque No" value="<?php echo $row7['ChequeNo']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2 chequeoption" style="display: none;">
<label class="form-label">Cheque Date <span class="text-danger">*</span></label>
<input type="date" name="ChqDate" class="form-control" id="ChqDate" placeholder="Cheque Date" value="<?php echo $row7['ChqDate']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4 chequeoption" style="display: none;">
<label class="form-label">Bank Name <span class="text-danger">*</span></label>
<input type="text" name="BankName" class="form-control" id="BankName" placeholder="Bank Name" value="<?php echo $row7['BankName']; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-10 upioption" style="display: none;">
<label class="form-label">UPI No/ Transaction Id <span class="text-danger">*</span></label>
<input type="text" name="UpiNo" class="form-control" id="UpiNo" placeholder="UPI No/ Transaction Id" value="<?php echo $row7['UpiNo']; ?>">
<div class="clearfix"></div>
</div>


<div class="form-group col-md-2">
<label class="form-label">Delivery Date <span class="text-danger">*</span></label>
<input type="date" name="DeliveryDate" id="DeliveryDate" class="form-control" placeholder="" value="" autocomplete="off" required>
</div>



<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
    <div class="clearfix"></div>
 </div>   


 <div class="form-group col-md-12">
   <label class="form-label">Subject</label>
     <textarea name="PurchaseSubject" id="PurchaseSubject" class="form-control">Purchase Order for Supply of 1 C X 4 SQ.MM SOLAR DC CABLE (ORISOL ) As per ISI 694 for the 
application of solar water pumping system as per EESL-2 & MNRE2020-19 Guidelines. All material for Nagpur 
(MH) locations.</textarea>
    <div class="clearfix"></div>
 </div> 
  </div> 
  
   <div id="dynamic_field2">
  <div class="form-row">

<div class="form-group col-md-12">
<label class="form-label">Ref <span class="text-danger">*</span></label>
<div class="input-group">
    <label class="custom-file">
<input type="text" name="Ref[]" class="form-control" placeholder="" value="" autocomplete="off" >
</label>
<div class="clearfix"></div>
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more2"><i class="fa fa-plus"></i></button>
  </span>
</div>
</div>
</div>
</div> 
<!--
  <div class="form-group col-md-12">
   <label class="form-label">Ref 1</label>
     <input type="text" name="Ref1" id="Ref1" class="form-control" value="Offer asreceived on dated : OIPL/22-23/E-2384 Dated.17.03.2023">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Ref 2</label>
     <input type="text" name="Ref2" id="Ref2" class="form-control" value="EESL Tender No. 1712& all corrigendum’sfor solar water pumping programme">
    <div class="clearfix"></div>
 </div>-->

<div class="form-row">
 <div class="form-group col-md-12">
   <label class="form-label">Details</label>
     <textarea name="Details" id="editor1" class="form-control"  
                                                ><p>Dear Sir,</p>

<p>We are pleased to place this PO for for Supply&nbsp;<strong>of&nbsp;</strong><strong>1 C X 4 SQ.MM SOLAR DC CABLE (WAACAB)As per ISI 694 for the application of solar water pumping system as per EESL-2 &amp; MNRE 2020-19 Guidelines. All material for Nagpur (MH) locations&nbsp;</strong>as per beneficiary list issued from department on our behalf subject to the following annexures:</p>

<p>Annexure &ldquo;A&rdquo;: Rate &amp; Qty&rsquo;s Details.;</p>

<p>Annexure &ldquo;B&rdquo;: Standard Terms &amp; Conditions</p>

<p>Annexure &ldquo;C&rdquo;: Commercial Terms &amp; Conditions</p>

<h3>Annexure &ldquo;A&rdquo; &ndash; Rate &amp; Qty Details:</h3>
</textarea>
    <div class="clearfix"></div>
 </div>   


  <div class="form-group col-md-12">
   <label class="form-label">Terms & Conditions</label>
     <textarea name="TermsCondition" id="editor2" class="form-control"  
                                                ><ol>
    <li style="text-align: justify;"><strong><u>Approval</u></strong>: The material should be strictly of EESL-2 (tender as mentioned above) &amp; MNRE Specifications as per latest guidelines</li>
</ol>

<ol start="2">
    <li style="text-align: justify;"><strong><u>Guarantee</u></strong>: You shall guarantee the performance of your product against any manufacturing/wear tear defect as replacement guarantee for 62 months from the date of commissioning - considered by EESL &amp;</li>
</ol>

<ol start="3">
    <li style="text-align: justify;"><strong><u>Penalty</u></strong>: Liquidate damages @ 0.25% per week shall be levied subject to a maximum of 5% of total order value only would be levied in case of delay in supply material contracted period as per ESSL-2 &amp; MNRE agreement arrangement done with our</li>
</ol>

<ol start="4">
    <li style="text-align: justify;"><strong><u>Delivery</u></strong>:&nbsp;<strong>Total Quantity shall be delivered at Nagpur (MH) DT.18.03.2023,&nbsp;</strong>Inspectioncall shall be given 3 days in advance. The entire quantities must be dispatched within 2 days of successful inspection.</li>
</ol>

<ol start="5">
    <li style="text-align: justify;"><strong><u>Inspection</u>:&nbsp;</strong>You shall offer all the material for VTECH inspection as per the requirement of EESL-2 &amp; MNRE. Please note that inspection call shall be raised 1 days in advance so that timely delivery can be ensured. Inspection is at&nbsp;<strong>Orisol&nbsp;</strong><strong>Orisol&nbsp;</strong>to send us the inspection reports in totality after completion. PDI shall be managed by&nbsp;<strong>Orisol&nbsp;</strong>if required.</li>
</ol>

<p style="text-align:justify"><strong>6.&nbsp;&nbsp;&nbsp;&nbsp;</strong><strong><u>Arbitration: -</u>&nbsp;</strong>If at any time any question, and dispute and /or differences whatsoever shall arise between the parties due to any conditions and failing amicable settlement the same shall be referred to an arbitrator under the Indian Arbitration &amp; Conciliation Act 1996 or any statutory modifications of the same prevalent at the time. The venue of such arbitration will be Nagpur.</p>

<ol start="7">
    <li style="text-align: justify;"><strong><u>Jurisdiction: -</u></strong>&nbsp;Any dispute arising out of the order against this offer letter shall be subject to the jurisdiction of the court in the city of</li>
</ol>

<ol start="8">
    <li style="text-align: justify;"><strong><u>Cancellation: -</u></strong>&nbsp;Work Order once placed will not be cancelled except with our written consent and after compensating the loss, if any to us .</li>
</ol>

<p style="text-align:justify">&nbsp;</p>

</textarea>
    <div class="clearfix"></div>
 </div> 


 <div class="form-group col-md-12">
   <label class="form-label">Consignee Address</label>
     <textarea name="ConsigneeAddress" id="editor3" class="form-control"  
                                                ><p style="text-align:justify"><strong>&nbsp;8.&nbsp;&nbsp;&nbsp;&nbsp; Consignee Address:</strong></p>

<p style="text-align:justify"><strong>VTECH ENGINEERS</strong></p>

<p style="text-align:justify">Plot no. 25-26,Rishabh</p>

<p style="text-align:justify">Industrial Estate</p>

<p style="text-align:justify">Mouza-Kapsi(Bujurg)</p>

<p style="text-align:justify">Bhandara Road,</p>

<p style="text-align:justify">Kamptee Nagpur</p>

<h3 style="text-align:justify"><strong>GST NO &ndash; NO 27AGSPA0235F1ZQ)</strong></h3>

<ol start="9">
    <li style="text-align: justify;"><strong><u>Billing Address</u>:</strong></li>
</ol>

<p style="text-align:justify"><strong>VTECH Synergy India Pvt.Ltd.&nbsp;</strong>Kumbharpura,</p>

<p style="text-align:justify">Badkas Chowk, Near Small Ayachit Mandir,</p>

<p style="text-align:justify">Nagpur-440032</p>

<h3 style="text-align:justify">(GST NO - 27AAHCV1400D1ZU)</h3>

<ol start="10">
    <li style="text-align: justify;"><strong><u>Invoice copy Dispatch Address: -</u></strong><br />
    VTECH Synergy India Pvt. Ltd.<br />
    Kumbharpura , Badkas Chowk,<br />
    Near Small Ayachit Mandir&nbsp;<strong>, Nagpur-440032</strong></li>
</ol>

<p style="text-align:justify"><strong><u>Annexure &ldquo;C&rdquo; &ndash; Standard Terms &amp; Conditions:</u></strong></p>

<p style="text-align:justify">&nbsp;</p>

<ol>
    <li style="text-align: justify;"><strong><u>Prices</u></strong>: Prices are on firm basis till the time of completion of the entire supplies. Packingand forwarding will be inclusive in basic price, adequate packing shall be done as such there must be no damage in</li>
    <li style="text-align: justify;"><strong><u>GST</u>:&nbsp;</strong>Extra @ 18%</li>
</ol>

<ol start="3">
    <li style="text-align: justify;"><strong>F<u>reight</u></strong>: NILL</li>
</ol>

<ol start="4">
    <li style="text-align: justify;"><strong><u>Transit &amp; Storage Insurance</u>:&nbsp;</strong>Transit Insurance including in basic</li>
    <li style="text-align: justify;"><strong><u>Payment</u></strong>: 50% advance &amp; 50% against the material received at Kapsi Factory</li>
    <li style="text-align: justify;"><strong><u>Guarantee:</u>&nbsp;</strong>62 Months from the date of material delivered at</li>
</ol>

</textarea>
    <div class="clearfix"></div>
 </div>  


 <div class="form-group col-md-12">
   <label class="form-label">Special conditions</label>
     <textarea name="SpecialConditions" id="editor4" class="form-control"  
                                                ><h3 style="text-align:justify">7.&nbsp;&nbsp;&nbsp;&nbsp; Special conditions for payment:</h3>

<p style="text-align:justify">Payment confirmation shall be done immediately after receipt offollowing mandatory enclosures:</p>

<ol>
    <li style="text-align: justify;">Clear tax invoice raised to VTECH from&nbsp;<strong>Orisol&nbsp;</strong>in 4 copies</li>
    <li style="text-align: justify;">Complete delivery challans</li>
</ol>

<ul>
    <li style="text-align: justify;">Dispatch clearance letter issued from VTECH to&nbsp;<strong>Orisol&nbsp;</strong>afterinspection</li>
</ul>

<ol>
    <li style="text-align: justify;">Transit Insurance</li>
    <li style="text-align: justify;">Test reports of all equipment&rsquo;s from manufacturer duly approvedfrom concerned engineer in</li>
</ol>

<ol start="8">
    <li style="text-align: justify;"><strong><u>Inspection Charges:</u>&nbsp;(PDI)&nbsp;</strong>Inspection at your factory will be your own</li>
</ol>

<ol start="9">
    <li style="text-align: justify;"><strong><u>Test Certificate:</u>&nbsp;</strong>The ISI 694 Certificate required with bill.</li>
</ol>

<h3 style="text-align:justify">Please acknowledge one copy of this PO as a token of your acceptance along with detailedtechnical specifications &amp; copy of agreement signed between our client &amp; EESL/MNRE duly signed on each page and oblige.</h3>

<p style="text-align:justify"><strong>&nbsp;</strong></p>

<p style="text-align:justify">Thanking You,</p>

<p style="text-align:justify">Yours faithfully,</p>

<h3 style="text-align:justify">FOR VTECH SYNERGY INDIA PRIVATE LIMITED</h3>

<p style="text-align:justify"><strong>&nbsp;</strong></p>

<p style="text-align:justify"><strong>&nbsp;</strong>PRATIK AGRAWAL DIRECTOR</p>


</textarea>
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
  
            
        </div>
    </main>

    <?php include_once '../footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
   <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="../js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="../vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="../js/main.js"></script>
    <script src="../js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="../js/app.js"></script>
     <?php include_once 'footer_script.php'; ?>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
     
    <script type="text/javascript">
    CKEDITOR.replace( 'editor1');
    CKEDITOR.replace( 'editor2');
    CKEDITOR.replace( 'editor3');
    CKEDITOR.replace( 'editor4');
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
   $('#GrossAmt').val(parseFloat(sum).toFixed(2));
   
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
            getSubTotal();
            commonTotal();
           }
      }); 


var i2=1;  
          $('#add_more2').click(function(){  
           i2++;  
           var html = '';
           html+='<div class="form-row" id="row'+i2+'">'; 
          
 html+='<div class="form-group col-md-12">'; 
 html+='<label class="form-label">Ref <span class="text-danger">*</span></label>'; 
 html+='<div class="input-group">    <label class="custom-file">'; 
   html+='<input type="text" name="Ref[]" class="form-control" placeholder="" value="" autocomplete="off" ></label>'; 
 html+='<div class="clearfix"></div>'; 
 html+='<span class="input-group-append">'; 
     html+='<button class="btn btn-danger btn_remove" type="button" id="'+i2+'"><i class="fa fa-times"></i></button>'; 
   html+='</span>'; 
 html+='</div>'; 
 html+='</div>';
 html+='</div>'; 
           $('#dynamic_field2').append(html);
        });  

      $(document).on('click', '.btn_remove2', function(){  
           var button_id2 = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row2'+button_id2+'').remove();  
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

function getProdTotal(qty,price,srno,sgst,cgst,igst){
    var Total = (Number(qty) * Number(price));
    var CgstAmt = Number(Total)*(Number(cgst)/100);
        var SgstAmt = Number(Total)*(Number(sgst)/100);
        var IgstAmt = Number(Total)*(Number(igst)/100);
       var prdTotal = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt) + Number(Total);
$('#Total'+srno).val(parseFloat(prdTotal).toFixed(2));
getSubTotal();
commonTotal();
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
                dataType:"json",
                success: function(data) {
                
                    $('#ProductName'+srno).val(data.ProductName);
                    $('#ModelNo'+srno).val(data.ModelNo);
                    $('#Purity'+srno).val(data.Unit); 
                    
                    $('#Price'+srno).val(data.Price); 
                    $('#SGST'+srno).val(data.SGST); 
                    $('#CGST'+srno).val(data.CGST); 
                    $('#IGST'+srno).val(data.IGST); 
                     getProdTotal(qty,data.Price,srno,data.SGST,data.CGST,data.IGST);
                }
            });
}


 </script>
</body>

</html>
