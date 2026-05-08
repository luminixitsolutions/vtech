<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Quotation";
$Page = "Add-Quotation";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Quotation
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
$sql7 = "SELECT * FROM tbl_quotation WHERE id='$id'";
$row7 = getRecord($sql7);
if($_GET['id'] == ''){
    $TermsCondition = "<ol>
<li><strong>Price Basis : </strong>Price is inclusive of transportation charges and transit insurance charges up to central godown</li>
<li><strong>Packing &amp; Forwarding Charges : </strong>Included in the above</li>
<li><strong>Taxes &amp; Duties :- </strong>GST Extra as applicable</li>
<li><strong>Payment Term : </strong>50% Advance and 50% against lot wise</li>
<li><strong>Delivery : </strong>Total Quantity of Supply and installation shall be delivered as per schedule mentioned in annexure sheet of priority basis</li>
<li><strong>Warranty : </strong>62 months from the date of commissioning as per EESL &amp; MNRE</li>
<li><strong>Validity : </strong>The price is valid for 15 Days from the date of Quotation issue</li>
<li><strong>Installation&amp; Commisioning: </strong>I &amp; C charges are included in the above Site Survey ,Storage charges,storage insurance charges,local transportation charges,Labour welfare cess,PF &amp; ESI (as applicable),Documentation as per the BID/SNA and submission of documents and Departmental Co-ordination for payment collection are included in the price.</li>
</ol>";

$Details = '<table border="1px solid">
<tbody>
<tr>
<td width="54">
<p><strong>Sr. No</strong></p>
</td>
<td width="353">
<p><strong>Item Particulars</strong></p>
</td>
<td width="80">
<p><strong>Unit</strong></p>
</td>
<td width="80">
<p><strong>Qty</strong></p>
</td>
<td width="73">
<p><strong>Rate/Unit</strong></p>
</td>
<td width="92">
<p><strong>Amount</strong></p>
</td>
</tr>
<tr>
<td width="54">
<p>&nbsp;</p>
</td>
<td width="353">
<p><strong>Supply , Installation and commissioning of 3HP/5HP/7.5 HP/10 HP SOLAR WATER AC/DC SURFACE/ Submersible Pump Set for Solar Water Pumping Set and Controller with Remote monitoring system and without Solar PV Module as per EESL- 2&amp; MNRE 2020-19 Guidelines upto JCC at PO office.</strong></p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="73">
<p>&nbsp;</p>
</td>
<td width="92">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>1</strong></p>
</td>
<td width="353">
<p>3 HP DC Solar Surface Pumping System with complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>40</p>
</td>
<td width="73">
<p>67910</p>
</td>
<td width="92">
<p>2716400.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>2</strong></p>
</td>
<td width="353">
<p>3 HP DC Solar Submersible water filled Pumping System with</p>
<p>complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>1</p>
</td>
<td width="73">
<p>72590</p>
</td>
<td width="92">
<p>72590.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>3</strong></p>
</td>
<td width="353">
<p>3 HP AC Solar Submersible water filled Pumping System with</p>
<p>complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>16</p>
</td>
<td width="73">
<p>73640</p>
</td>
<td width="92">
<p>1178240.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>4</strong></p>
</td>
<td width="353">
<p>5 HP DC Solar Surface Pumping System with complete</p>
<p>system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>26</p>
</td>
<td width="73">
<p>90160</p>
</td>
<td width="92">
<p>2344160.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>5</strong></p>
</td>
<td width="353">
<p>5 HP AC Solar Submersible water filled Pumping System with</p>
<p>complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>25</p>
</td>
<td width="73">
<p>94690</p>
</td>
<td width="92">
<p>2367250.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>6</strong></p>
</td>
<td width="353">
<p>5 HP DC Solar Submersible water filled Pumping System with</p>
<p>complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>7</p>
</td>
<td width="73">
<p>91940</p>
</td>
<td width="92">
<p>643580.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>7</strong></p>
</td>
<td width="353">
<p>7.5 HP DC Solar Surface Pumping System with complete</p>
<p>system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>109</p>
</td>
<td width="73">
<p>111170</p>
</td>
<td width="92">
<p>12117530.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>8</strong></p>
</td>
<td width="353">
<p>7.5 HP DC Solar Submersible water filled Pumping System</p>
<p>with complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>7</p>
</td>
<td width="73">
<p>118530</p>
</td>
<td width="92">
<p>829710.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>9</strong></p>
</td>
<td width="353">
<p>7.5 HP AC Solar Submersible water filled Pumping System</p>
<p>with complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>33</p>
</td>
<td width="73">
<p>119580</p>
</td>
<td width="92">
<p>3946140.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>10</strong></p>
</td>
<td width="353">
<p>10 HP DC Solar Surface Pumping System with complete</p>
<p>system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>34</p>
</td>
<td width="73">
<p>139795.0</p>
</td>
<td width="92">
<p>4753030.00</p>
</td>
</tr>

<tr>
<td width="54">
<p><strong>11</strong></p>
</td>
<td width="353">
<p>10 HP DC Solar Submersible water filled Pumping System</p>
<p>with complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>19</p>
</td>
<td width="73">
<p>146055.00</p>
</td>
<td width="92">
<p>2775045.00</p>
</td>
</tr>
<tr>
<td width="54">
<p><strong>12</strong></p>
</td>
<td width="353">
<p>10 HP AC Solar Submersible water filled Pumping System</p>
<p>with complete system</p>
</td>
<td width="80">
<p>Set</p>
</td>
<td width="80">
<p>303</p>
</td>
<td width="73">
<p>146580.00</p>
</td>
<td width="92">
<p>44413740.00</p>
</td>
</tr>
<tr>
<td width="54">
<p>&nbsp;</p>
</td>
<td width="353">
<p>&nbsp;</p>
</td>
<td width="80">
<p>&nbsp;</p>
</td>
<td width="80">
<p>620</p>
</td>
<td width="73">
<p><strong>Total</strong></p>
</td>
<td width="92">
<p><strong>78157415.00</strong></p>
</td>
</tr>
</tbody>
</table>';

$QtnSubject = "Quotation for Supply , Installation and commissioning of 3HP/5HP/7.5 HP/10 HP SOLAR WATER AC/DC SURFACE/ Submersible Pump Set for Solar Water Pumping Set and Controller with Remote monitoring system nd without Solar PV Module as per EESL-2& MNRE 2020-19 Guidelines upto JCC at PO office.";

$InvoiceDate = date('Y-m-d');
    $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_quotation";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$preyr = date('y')-1;
$curryr = date('y');
$Invoice_No = "VE/EESL/".$preyr."-".$curryr."/PI/".$MaxId;

}
else{
   $TermsCondition = $row7['TermsCondition'];
   $Details = $row7['Details'];
   $QtnSubject = $row7['QtnSubject'];
   $InvoiceDate = $row7['InvoiceDate'];
$Invoice_No = $row7['InvoiceNo'];
}


   
$query22 = "SELECT count(*) as totrec FROM tbl_quotation_order_products WHERE SellId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;

if($_GET["action"]=="deleteprd")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_quotation_order_products WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="add-quotation.php?id=<?php echo $bid;?>";
    </script>
<?php } 

if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
     $CellNo = addslashes(trim($_POST["CellNo"]));
    $CustName = addslashes(trim($_POST["CustName"]));
$Status = 1;
$Address = addslashes(trim($_POST["Address"]));
$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST["InvoiceDate"]));
$QtnSubject = addslashes(trim($_POST["QtnSubject"]));
$RefEnqNo = addslashes(trim($_POST["RefEnqNo"]));
$Details = addslashes(trim($_POST["Details"]));
$TermsCondition = addslashes(trim($_POST["TermsCondition"]));
$Details = addslashes(trim($_POST["Details"]));
$CompId = addslashes(trim($_POST["CompId"]));
$KindAttn = addslashes(trim($_POST["KindAttn"]));
$TotalAmt = addslashes(trim($_POST["TotalAmt"]));
$GstAmt = addslashes(trim($_POST["GstAmt"]));
$ProdSpec = addslashes(trim($_POST["ProdSpec"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');

$Address2 = addslashes(trim($_POST["Address2"]));
$SupplierRef = addslashes(trim($_POST["SupplierRef"]));
$BuyerOrderNo = addslashes(trim($_POST["BuyerOrderNo"]));
$BuyerDate = addslashes(trim($_POST["BuyerDate"]));
$CustomizeAmt = addslashes(trim($_POST["CustomizeAmt"]));
$SubTotal = addslashes(trim($_POST["SubTotal"]));

$PaymentTerms = addslashes(trim($_POST["PaymentTerms"]));
$Freight = addslashes(trim($_POST["Freight"]));
$Delivery = addslashes(trim($_POST["Delivery"]));

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_quotation SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',CreatedDate='$CreatedDate',CreatedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn',TotalAmt='$TotalAmt',ProdSpec='$ProdSpec',Address2='$Address2',SupplierRef='$SupplierRef',BuyerOrderNo='$BuyerOrderNo',BuyerDate='$BuyerDate',CustomizeAmt='$CustomizeAmt',GstAmt='$GstAmt',SubTotal='$SubTotal',PaymentTerms='$PaymentTerms',Freight='$Freight',Delivery='$Delivery'";
  $conn->query($qx);
  $SellId = mysqli_insert_id($conn);
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
                $SgstAmt = addslashes(trim($_POST['SgstAmt'][$i]));
                $CGST = addslashes(trim($_POST['CGST'][$i]));
                $IGST = addslashes(trim($_POST['IGST'][$i]));
                
                $sql22 = "INSERT INTO tbl_quotation_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SgstAmt='$SgstAmt'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

          
              }  

          }
      }
      
  echo "<script>alert('PI Created Successfully!');window.location.href='view-quotation.php';</script>";
}
else{
 
    $query2 = "UPDATE tbl_quotation SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn',TotalAmt='$TotalAmt',ProdSpec='$ProdSpec',Address2='$Address2',SupplierRef='$SupplierRef',BuyerOrderNo='$BuyerOrderNo',BuyerDate='$BuyerDate',CustomizeAmt='$CustomizeAmt',GstAmt='$GstAmt',SubTotal='$SubTotal',PaymentTerms='$PaymentTerms',Freight='$Freight',Delivery='$Delivery' WHERE id = '$id'";
  $conn->query($query2);
  $SellId = $id;
  $sql = "DELETE FROM tbl_quotation_order_products WHERE SellId='$id'";
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
                $SGST = addslashes(trim($_POST['SGST'][$i]));
                $CGST = addslashes(trim($_POST['CGST'][$i]));
                $IGST = addslashes(trim($_POST['IGST'][$i]));
                 $SgstAmt = addslashes(trim($_POST['SgstAmt'][$i]));
                
                $sql22 = "INSERT INTO tbl_quotation_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SgstAmt='$SgstAmt'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

          
              }  

          }
      }
      
  echo "<script>alert('PI Updated Successfully!');window.location.href='view-quotation.php';</script>";

}
    //header('Location:courses.php'); 

  }
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Create
                            <?php } ?> Performa Invoice (PI)</h4>

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
 <select class="select2-demo form-control" name="CompId" id="CompId" required>
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
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
 if($Roll==1 || $Roll==7){
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5 AND CoordinatorStatus=1";
   }
   else{
     $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=5 AND CoordinatorStatus=1 AND CoordinatorId='$user_id'";
 }
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

 <div class="form-group col-md-12">
   <label class="form-label">Buyer Address (if other than consignee)</label>
     <textarea name="Address2" id="Address2" class="form-control"  
                                                ><?php echo $row7['Address2']; ?></textarea>
    <div class="clearfix"></div>
 </div>   


<div class="form-group col-lg-4">
<label class="form-label">Proforma invoice NO <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
    <label class="form-label">Dated </label>
    <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo $InvoiceDate; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
</div> 

<div class="form-group col-lg-4">
<label class="form-label">Suppliers Ref <span class="text-danger">*</span></label>
<input type="text" name="SupplierRef" class="form-control" id="SupplierRef" placeholder="" value="<?php echo $row7['SupplierRef']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Ref Enquiry No <span class="text-danger">*</span></label>
<input type="text" name="RefEnqNo" class="form-control" id="RefEnqNo" placeholder="" value="<?php echo $row7['RefEnqNo']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Buyer Order No <span class="text-danger">*</span></label>
<input type="text" name="BuyerOrderNo" class="form-control" id="BuyerOrderNo" placeholder="" value="<?php echo $row7['BuyerOrderNo']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
    <label class="form-label">Dated </label>
    <input type="date" name="BuyerDate" id="BuyerDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['BuyerDate']; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
</div> 

<div class="form-group col-lg-12">
<label class="form-label">Kind Attn <span class="text-danger">*</span></label>
<input type="text" name="KindAttn" class="form-control" id="KindAttn" placeholder="" value="<?php echo $row7['KindAttn']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Subject</label>
     <textarea  type="text" name="QtnSubject" id="QtnSubject" class="form-control"><?php echo $QtnSubject; ?></textarea>
    <div class="clearfix"></div>
 </div>   
 

<div class="form-group col-md-12">
   <label class="form-label">Product Specification</label>
     <textarea  type="text" name="ProdSpec" id="ProdSpec" class="form-control"><?php echo $row7['ProdSpec']; ?></textarea>
    <div class="clearfix"></div>
 </div>   
</div>


<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;">PI Product Details</label>
<table class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
        <th>Qty </th>
        <th>Unit</th>
        <th>Rate</th>
       <th>GST %</th>
       <th>GST Amt</th>
        <th>Amount</th>
        <th></th>
    </tr>
     </thead>

      <tbody>
 
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_quotation_order_products WHERE SellId='$id'";
  $row12 = getList($sql12);
  foreach($row12 as $result12){
     ?>
 <tr>
    <td>


<select name="ProductId[]" id="ProductId<?php echo $i; ?>" onchange="getProdDetails(this.value,document.getElementById('srno<?php echo $i; ?>').value)" class="form-control">
    <option value="" selected>Select Product</option>
    <?php 
  $sql12 = "SELECT * FROM tbl_qtn_products WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
     <option <?php if($result['id'] == $result12['ProductId']){?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
 <?php } ?>
</select>




</td>

  <input type="hidden" name="ProductName[]" id="ProductName<?php echo $i; ?>" value='<?php echo $result12['ProductName']; ?>'>
 <input type="hidden" name="ModelNo[]" id="ModelNo<?php echo $i; ?>" value="<?php echo $result12['ModelNo']; ?>">

<td>
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="<?php echo $result12["Qty"];?>" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value)">
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Purity"];?>" autocomplete="off" >
</td>

 

<td>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Price"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)" required>
</td>

<td><input type="text" name="SGST[]" id="SGST1" class="form-control" placeholder="" value="<?php echo $result12["SGST"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value,document.getElementById('SGST<?php echo $i; ?>').value)" required></td>

<td><input type="text" name="SgstAmt[]" id="SgstAmt1" class="form-control txt2" placeholder="" value="<?php echo $result12["SgstAmt"];?>" autocomplete="off" readonly></td>

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
  $sql12 = "SELECT * FROM tbl_qtn_products WHERE Status='1'";
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
<td><input type="number" name="Qty[]" id="Qty<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value)"></td>
        <td><input type="text" name="Purity[]" id="Purity<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off"></td>
      
        <td><input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value)" required></td>
       
      
       <td><input type="text" name="SGST[]" id="SGST<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value,document.getElementById('SGST<?php echo $row_cnt; ?>').value)" required></td>

       <td><input type="text" name="SgstAmt[]" id="SgstAmt<?php echo $row_cnt; ?>" class="form-control txt2" placeholder="" value="" autocomplete="off" readonly></td>
 
 
        <td><input type="text" name="TotalRate[]" id="Total<?php echo $row_cnt; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td>
        <td><button class="btn btn-secondary" type="button" id="add_more">+</button></td>
    </tr>
    </tbody>
    </table>
</div>   
 
 <div class="form-row">
      <div class="form-group col-md-3">
<label class="form-label">Sub Total<span class="text-danger">*</span></label>
<input type="text" name="SubTotal" id="SubTotal" class="form-control" placeholder="" value="<?php echo $row7['SubTotal'];?>" autocomplete="off" readonly>
</div>

      

 <div class="form-group col-md-3">
<label class="form-label">GST Amount<span class="text-danger">*</span></label>
<input type="text" name="GstAmt" id="GstAmt" class="form-control" placeholder="" value="<?php echo $row7['GstAmt'];?>" autocomplete="off" readonly>
</div> 

<div class="form-group col-md-3">
<label class="form-label">Total Amount<span class="text-danger">*</span></label>
<input type="text" name="TotalAmt" id="TotalAmt" class="form-control" placeholder="" value="<?php echo $row7['TotalAmt'];?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-3">
<label class="form-label">Customization Amount<span class="text-danger">*</span></label>
<input type="text" name="CustomizeAmt" id="CustomizeAmt" class="form-control" placeholder="" value="<?php echo $row7['CustomizeAmt'];?>" autocomplete="off">
</div>

<div class="form-group col-lg-12">
<label class="form-label">Payment Terms </label>
<input type="text" name="PaymentTerms" class="form-control" id="PaymentTerms" placeholder="" value="<?php echo $row7['PaymentTerms']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Freight </label>
<input type="text" name="Freight" class="form-control" id="Freight" placeholder="" value="<?php echo $row7['Freight']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Delivery </label>
<input type="text" name="Delivery" class="form-control" id="Delivery" placeholder="" value="<?php echo $row7['Delivery']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Delivery Note </label>
<input type="text" name="DeliveryNote" class="form-control" id="DeliveryNote" placeholder="" value="<?php echo $row7['DeliveryNote']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Despatch Document No. </label>
<input type="text" name="DispatchDocNo" class="form-control" id="DispatchDocNo" placeholder="" value="<?php echo $row7['DispatchDocNo']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Delivery Note Date </label>
<input type="date" name="DeliveryDate" class="form-control" id="DeliveryDate" placeholder="" value="<?php echo $row7['DeliveryDate']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Despatched through </label>
<input type="text" name="DispatchThrough" class="form-control" id="DispatchThrough" placeholder="" value="<?php echo $row7['DispatchThrough']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Destination </label>
<input type="text" name="Destination" class="form-control" id="Destination" placeholder="" value="<?php echo $row7['Destination']; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Terms & Conditions Delivery</label>
     <textarea  type="text" name="TermsCondition" id="editor2" class="form-control"><?php echo $TermsCondition; ?></textarea>
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
// CKEDITOR.replace( 'editor1');
    CKEDITOR.replace( 'editor2');
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
    
     function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
   $('#SubTotal').val(parseFloat(sum).toFixed(2));
   getGstTotal();
    }

    function getGstTotal(){
     var sum = 0;
      $(".txt2").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
   $('#GstAmt').val(parseFloat(sum).toFixed(2));
    
    var SubTotal = $('#SubTotal').val();
    var GstAmt = $('#GstAmt').val();
    var TotalAmt = Number(SubTotal) + Number(GstAmt);
    $('#TotalAmt').val(parseFloat(TotalAmt).toFixed(2))
    
    }


     $(document).ready(function() {
 getSubTotal();

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
                    $('#Address2').val(data.Address);
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



        var i=$('#rncnt').val();
    $('#add_more').click(function(){  
           i++;  
       var action = "getCustRow";
    $.ajax({
    url:"ajax_files/ajax_quotation_products.php",
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
           }
      }); 
      
      
      
      
    });


function getProdTotal(qty,price,srno,sgstper){
        var Total = (Number(qty) * Number(price));
        var SgstAmt = Number(Total)*(Number(sgstper)/100);
    var CgstAmt = 0;
        //var SgstAmt = 0;
        var IgstAmt = 0;
        $('#SgstAmt'+srno).val(parseFloat(SgstAmt).toFixed(2));
       var prdTotal = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt) + Number(Total);
$('#Total'+srno).val(parseFloat(prdTotal).toFixed(2));
getSubTotal();
}

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
     var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_quotation_products.php",
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
                     getProdTotal(qty,data.Price,srno,data.SGST);
                }
            });
}
     
 </script>
</body>

</html>