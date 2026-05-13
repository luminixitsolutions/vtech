<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Quotation";
$Page = "Add-Quotation";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
    <script src="<?php echo $SiteUrl;?>/ckeditor/ckeditor.js"></script>
</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'lead-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Quotation</h5>
                        
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_lead_quotation WHERE id='$id'";
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
    $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_lead_quotation";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$Invoice_No = "00".$MaxId;

}
else{
   $TermsCondition = $row7['TermsCondition'];
   $Details = $row7['Details'];
   $QtnSubject = $row7['QtnSubject'];
   $InvoiceDate = $row7['InvoiceDate'];
$Invoice_No = $row7['InvoiceNo'];
}


   
$query22 = "SELECT count(*) as totrec FROM tbl_lead_quotation_order_products WHERE SellId = '$id'";
$data22 = getRecord($query22);
$row_cnt = $data22['totrec'] + 1;

if($_GET["action"]=="deleteprd")
{
  $id = $_GET["id"];
  $bid = $_GET["oid"];
  $sql11 = "DELETE FROM tbl_lead_quotation_order_products WHERE id = '$id'";
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
$ProdSpec = addslashes(trim($_POST["ProdSpec"]));
$CreatedDate = date('Y-m-d');
$ModifiedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

if($_GET['id']==''){
     $qx = "INSERT INTO tbl_lead_quotation SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',CreatedDate='$CreatedDate',CreatedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn',TotalAmt='$TotalAmt',ProdSpec='$ProdSpec'";
  $conn->query($qx);
  $SellId = mysqli_insert_id($conn);
  
  /*$Steps = "Quotation Sent to Customer";
  
  $sql = "INSERT INTO tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='$CustId',QtnId='$SellId'";
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
                
                $sql22 = "INSERT INTO tbl_lead_quotation_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

          
              }  

          }
      }
      
  echo "<script>alert('Quotation Created Successfully!');window.location.href='lead-quotation.php';
  window.open('print-lead-quotation.php?id=$SellId');
  </script>";
}
else{
 
    $query2 = "UPDATE tbl_lead_quotation SET CompId='$CompId',CustId='$CustId',CellNo='$CellNo',CustName = '$CustName',Status='$Status',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate = '$InvoiceDate',QtnSubject='$QtnSubject',Details='$Details',TermsCondition='$TermsCondition',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',RefEnqNo='$RefEnqNo',KindAttn='$KindAttn',TotalAmt='$TotalAmt',ProdSpec='$ProdSpec' WHERE id = '$id'";
  $conn->query($query2);
  $SellId = $id;
  
  /*$Steps = "Quotation Sent to Customer";
  
  $sql = "UPDATE tbl_steps SET CustId='0',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='$CustId' WHERE QtnId='$SellId'";
  $conn->query($sql);*/
  
  $sql = "DELETE FROM tbl_lead_quotation_order_products WHERE SellId='$id'";
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
                
                $sql22 = "INSERT INTO tbl_lead_quotation_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

          
              }  

          }
      }
      
  echo "<script>alert('Quotation Updated Successfully!');window.location.href='lead-quotation.php';
window.open('print-lead-quotation.php?id=$SellId');
  </script>";

}
    //header('Location:courses.php'); 

  }
?>

<div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                     <div class="form-group col-md-6" style="padding-top:10px;">
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

                                    <div class="form-group col-md-6" style="padding-top:10px;">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required>
<option selected="" value="">Select Customer</option>
 <?php 
    if($Roll == 1 || $Roll == 7){
    $sql12 = "SELECT ts.* FROM tbl_leads ts WHERE ts.ClainStatus='Completed'";
   }
    else{
    $sql12 = "SELECT ts.* FROM tbl_leads ts WHERE ts.ClainStatus='Completed' AND ts.AllocateId='$user_id'";    
    }
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['CustName']." (".$result['CellNo'].")"; ?></option>
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
                                                autocomplete="off">
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


<div class="form-group col-lg-4">
<label class="form-label">QTN NO <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
    <label class="form-label">QTN Date </label>
    <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo $InvoiceDate; ?>"
                                                autocomplete="off">
    <div class="clearfix"></div>
</div> 

<div class="form-group col-lg-4">
<label class="form-label">Ref Enquiry No <span class="text-danger">*</span></label>
<input type="text" name="RefEnqNo" class="form-control" id="RefEnqNo" placeholder="" value="<?php echo $row7['RefEnqNo']; ?>" >
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
  <label class="form-label" style="font-size: 18px;color: #0dc30d;">Quotation Product Details</label>
<table class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th width="30%">Product</th>
        <th>Qty </th>
        <th>Unit</th>
        <th>Rate</th>
       
        <th>Amount</th>
        <th></th>
    </tr>
     </thead>

      <tbody>
 
<?php 
$i=1;
  $sql12 = "SELECT * FROM tbl_lead_quotation_order_products WHERE SellId='$id'";
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
<input type="number" name="Qty[]" id="Qty<?php echo $i; ?>" class="form-control" placeholder="e.g.,1" value="<?php echo $result12["Qty"];?>" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)">
</td>

<td>
<input type="text" name="Purity[]" id="Purity<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Purity"];?>" autocomplete="off" >
</td>



<td>
<input type="text" name="Price[]" id="Price<?php echo $i; ?>" class="form-control" placeholder="" value="<?php echo $result12["Price"];?>" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $i; ?>').value,document.getElementById('Price<?php echo $i; ?>').value,document.getElementById('srno<?php echo $i; ?>').value)" required>
</td>


        

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
<td><input type="number" name="Qty[]" id="Qty<?php echo $row_cnt; ?>" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value)"></td>
        <td><input type="text" name="Purity[]" id="Purity<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off"></td>
      
        <td><input type="text" name="Price[]" id="Price<?php echo $row_cnt; ?>" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty<?php echo $row_cnt; ?>').value,document.getElementById('Price<?php echo $row_cnt; ?>').value,document.getElementById('srno<?php echo $row_cnt; ?>').value)" required></td>
       
      
 
 
        <td><input type="text" name="TotalRate[]" id="Total<?php echo $row_cnt; ?>" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td>
        <td><button class="btn btn-secondary" type="button" id="add_more">+</button></td>
    </tr>
    </tbody>
    </table>
</div>   
 
 <div class="form-row">
      <div class="form-group col-md-12">
<label class="form-label">Total Amount<span class="text-danger">*</span></label>
<input type="text" name="TotalAmt" id="SubTotal" class="form-control" placeholder="" value="<?php echo $row7['TotalAmt'];?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-12">
   <label class="form-label">Terms & Conditions</label>
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




  
                                

 </div>
 </form>





                            </div>
                        </div>
                        



					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>

      <script type="text/javascript">
    CKEDITOR.replace( 'editor2');
    function getUserDetails(){
        var CellNo = $('#CellNo').val();
        var action = "getLeadUserDetails2";
            $.ajax({
                url: "../ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    CellNo: CellNo
                },
                dataType:"json",  
                success: function(data) {
                     $('#Address').val(data.Address);
                    $('#CustName').val(data.CustName);
                    //$('#CellNo').val(data.CellNo);
                    
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
   
    }


     $(document).ready(function() {
 getSubTotal();

     $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            var action = "getLeadUserDetails";
            $.ajax({
                url: "../ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    console.log(data);
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.CustName);
                    $('#CellNo').val(data.CellNo);
                   
                }
            });

        });



        var i=$('#rncnt').val();
    $('#add_more').click(function(){  
           i++;  
       var action = "getCustRow";
    $.ajax({
    url:"../ajax_files/ajax_lead_quotation_products.php",
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


function getProdTotal(qty,price,srno){
        var Total = (Number(qty) * Number(price));
    var CgstAmt = 0;
        var SgstAmt = 0;
        var IgstAmt = 0;
       var prdTotal = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt) + Number(Total);
$('#Total'+srno).val(parseFloat(prdTotal).toFixed(2));
getSubTotal();
}

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
     var action = "getProdDetails";
            $.ajax({
                url: "../ajax_files/ajax_quotation_products.php",
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
