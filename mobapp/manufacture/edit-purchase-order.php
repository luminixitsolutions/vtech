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

$query22 = "SELECT count(*) as totrec FROM tbl_purchase_order_products WHERE SellId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;

if($_GET["action"]=="deleteprd")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_purchase_order_products WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_stocks WHERE PostId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
      window.location.href="edit-purchase-order.php?id=<?php echo $bid;?>";
    </script>
<?php }  

if($_REQUEST["action"]=="deletelink")
{
  $id = $_REQUEST["id"];
  $pid = $_REQUEST["pid"];
  $sql11 = "DELETE FROM tbl_po_references WHERE id = '$id'";
  $conn->query($sql11);
?>
    <script type="text/javascript">
      //alert("Deleted Successfully!");
       window.location.href="edit-purchase-order.php?id=<?php echo $pid;?>";
    </script>
<?php }


if(isset($_POST['submit'])){
$CustId = $_POST['CustId'];
$CompId = $_POST['CompId'];
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

$PurchaseSubject = addslashes(trim($_POST['PurchaseSubject']));
$Ref1 = addslashes(trim($_POST['Ref1']));
$Ref2 = addslashes(trim($_POST['Ref2']));
$Details = addslashes(trim($_POST['Details']));
$TermsCondition = addslashes(trim($_POST['TermsCondition']));
$ConsigneeAddress = addslashes(trim($_POST['ConsigneeAddress']));
$SpecialConditions = addslashes(trim($_POST['SpecialConditions']));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');


$sql = "UPDATE tbl_purchase_order SET CompId='$CompId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Purchase',PurchaseSubject='$PurchaseSubject',Ref1='$Ref1',Ref2='$Ref2',Details='$Details',TermsCondition='$TermsCondition',ConsigneeAddress='$ConsigneeAddress',SpecialConditions='$SpecialConditions' WHERE id='$id'";
$conn->query($sql);
$SellId = $id;

$sql = "DELETE FROM tbl_po_references WHERE SellId='$id'";
$conn->query($sql);
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

$sql = "DELETE FROM tbl_purchase_order_products WHERE SellId='$id'";
$conn->query($sql);
/*$sql = "DELETE FROM tbl_stocks WHERE SellId='$id'";
$conn->query($sql);*/

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

              /*  $sql22 = "INSERT INTO tbl_stocks SET CompId='$CompId',SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Purchase'";
                $conn->query($sql22);*/
              }  

          }
      }

$sql22 = "DELETE FROM tbl_general_ledger WHERE SellId='$id' AND SellType='Purchase'";
$conn->query($sql22);


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
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $row7['InvoiceNo']; ?>" >
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

      <tbody>
 
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_purchase_order_products WHERE SellId='$id'";
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
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="<?php echo $result12["Qty"];?>" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)">
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Purity"];?>" autocomplete="off" oninput="getGoldRate(document.getElementById('Purity<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)">
</td>



<td>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Price"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)" required>
</td>

<td><input type="text" name="SGST[]" id="SGST<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["SGST"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)" required></td>
<td><input type="text" name="CGST[]" id="CGST<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["CGST"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)" required></td>
<td><input type="text" name="IGST[]" id="IGST<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["IGST"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value,document.getElementById('CGST<?php echo $i; ?>').value,document.getElementById('IGST<?php echo $i; ?>').value)" required></td>
        

<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i; ?>" value="<?php echo $i; ?>">
<td>
<input type="text" name="TotalRate[]" id="Total<?php echo $i; ?>" class="form-control txt" placeholder="" value="<?php echo $result12["TotalRate"];?>" autocomplete="off" readonly>
</td>
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
<td><input type="number" name="Qty[]" id="Qty<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value,document.getElementById('CGST<?php echo $row_cnt; ?>').value,document.getElementById('IGST<?php echo $row_cnt; ?>').value)"></td>
        <td><input type="text" name="Purity[]" id="Purity<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off"></td>
      
        <td><input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value,document.getElementById('CGST<?php echo $row_cnt; ?>').value,document.getElementById('IGST<?php echo $row_cnt; ?>').value)" required></td>
       
       <td><input type="text" name="SGST[]" id="SGST<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value,document.getElementById('CGST<?php echo $row_cnt; ?>').value,document.getElementById('IGST<?php echo $row_cnt; ?>').value)" required></td>
<td><input type="text" name="CGST[]" id="CGST<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value,document.getElementById('CGST<?php echo $row_cnt; ?>').value,document.getElementById('IGST<?php echo $row_cnt; ?>').value)" required></td>
<td><input type="text" name="IGST[]" id="IGST<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value,document.getElementById('CGST<?php echo $row_cnt; ?>').value,document.getElementById('IGST<?php echo $row_cnt; ?>').value)" required></td>
 
 
        <td><input type="text" name="TotalRate[]" id="Total<?php echo $row_cnt; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td>
        <td><button class="btn btn-secondary" type="button" id="add_more">+</button></td>
    </tr>
    </tbody>
    </table>
</div>



                                     

   <div class="form-row">
              <input type="hidden" name="CgstPer" id="CgstPer" value="<?php echo $row7['CgstPer'];?>">
        <input type="hidden" name="SgstPer" id="SgstPer" value="<?php echo $row7['SgstPer'];?>">
         <input type="hidden" name="IgstPer" id="IgstPer" value="<?php echo $row7['IgstPer'];?>">
        <div class="form-group col-md-2">
<label class="form-label">Gross Amt <span class="text-danger">*</span></label>
<input type="text" name="GrossAmt" id="GrossAmt" class="form-control" placeholder="" value="<?php echo $row7['GrossAmt'];?>" autocomplete="off" readonly>
</div>

<!--<div class="form-group col-md-1">
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
</div>-->

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



<div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
    <div class="clearfix"></div>
 </div>  


 <div class="form-group col-md-12">
   <label class="form-label">Subject</label>
     <textarea name="PurchaseSubject" id="PurchaseSubject" class="form-control"><?php echo $row7['PurchaseSubject']; ?></textarea>
    <div class="clearfix"></div>
 </div> 
 </div> 

<?php 
$sql_1 = "SELECT * FROM tbl_po_references WHERE SellId='$id'";
$row_1 = getList($sql_1);
foreach($row_1 as $result){
 ?>
<div class="form-row">
  
<div class="form-group col-md-12">
<label class="form-label">Ref <span class="text-danger">*</span></label>
<div class="input-group">
     <label class="custom-file">
<input type="text" class="form-control" placeholder="" value="<?php echo $result["Ref"]; ?>" autocomplete="off" name="Ref[]">
</label>
<div class="clearfix"></div>
<span class="input-group-append">
 <a onClick="return confirm('Are you sure you want delete this Record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $result['id']; ?>&action=deletelink&pid=<?php echo $_GET['id']; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
  </span>
</div>


</div>
</div>

<?php } ?>

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

<div class="form-row">
 <!-- <div class="form-group col-md-12">
   <label class="form-label">Ref 1</label>
     <input type="text" name="Ref1" id="Ref1" class="form-control" value="<?php echo $row7['Ref1']; ?>">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Ref 2</label>
     <input type="text" name="Ref2" id="Ref2" class="form-control" value="<?php echo $row7['Ref2']; ?>">
    <div class="clearfix"></div>
 </div>-->

 <div class="form-group col-md-12">
   <label class="form-label">Details</label>
     <textarea name="Details" id="editor1" class="form-control"  
                                                ><?php echo $row7['Details']; ?>
</textarea>
    <div class="clearfix"></div>
 </div>   


  <div class="form-group col-md-12">
   <label class="form-label">Terms & Conditions</label>
     <textarea name="TermsCondition" id="editor2" class="form-control"  
                                                ><?php echo $row7['TermsCondition']; ?>

</textarea>
    <div class="clearfix"></div>
 </div> 


 <div class="form-group col-md-12">
   <label class="form-label">Consignee Address</label>
     <textarea name="ConsigneeAddress" id="editor3" class="form-control"  
                                                ><?php echo $row7['ConsigneeAddress']; ?>

</textarea>
    <div class="clearfix"></div>
 </div>  


 <div class="form-group col-md-12">
   <label class="form-label">Special conditions</label>
     <textarea name="SpecialConditions" id="editor4" class="form-control"  
                                                ><?php echo $row7['SpecialConditions']; ?>

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
 getSubTotal();
        commonTotal();
        var i=$('#rncnt').val();
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
