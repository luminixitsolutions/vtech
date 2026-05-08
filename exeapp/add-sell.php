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
<title><?php echo $Proj_Title; ?> | View Delivery Challan List</title>
 <!-- manifest meta -->
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

            


            

                

             <?php 
$id = $_GET['id'];
$CustId = $_GET['CustId'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$CustId'";
$row7 = getRecord($sql7);


    $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_sell";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$Invoice_No = "00".$MaxId;


if(isset($_POST['submit'])){
    
$CustId = $_POST['CustId'];
$CustName = addslashes(trim($_POST['CustName']));
$CellNo = addslashes(trim($_POST['CellNo']));
$Address = addslashes(trim($_POST['Address']));

//$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
$InvoiceDate = addslashes(trim($_POST['InvoiceDate']));

$PayType = addslashes(trim($_POST['PayType']));

$Narration = addslashes(trim($_POST['Narration']));

//$ProdType = addslashes(trim($_POST['ProdType']));
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
$LrNo = addslashes(trim($_POST['LrNo']));
$LrDate = addslashes(trim($_POST['LrDate']));
$Transport = addslashes(trim($_POST['Transport']));
$ConsigneeName = addslashes(trim($_POST['ConsigneeName']));
$ConsigneeAddress = addslashes(trim($_POST['ConsigneeAddress']));
$SiteEngineerName = addslashes(trim($_POST['SiteEngineerName']));
$SiteEngineerContactNo = addslashes(trim($_POST['SiteEngineerContactNo']));
$SiteManagerName = addslashes(trim($_POST['SiteManagerName']));
$SiteManagerContactNo = addslashes(trim($_POST['SiteManagerContactNo']));
$Weight = addslashes(trim($_POST['Weight']));

$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

  $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_sell";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$InvoiceNo = "00".$MaxId;

 $sql = "INSERT INTO tbl_sell SET SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Challan',WarrantyPeriod='$WarrantyPeriod',PayStatus='$PayStatus',LrNo='$LrNo',LrDate='$LrDate',Transport='$Transport',ConsigneeName='$ConsigneeName',ConsigneeAddress='$ConsigneeAddress',SiteEngineerName='$SiteEngineerName',SiteEngineerContactNo='$SiteEngineerContactNo',SiteManagerName='$SiteManagerName',SiteManagerContactNo='$SiteManagerContactNo',Weight='$Weight'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);

$number = count($_POST["CheckId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
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
                $SerialNo = addslashes(trim($_POST['SerialNo'][$i]));
                $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
                if($CheckId == 1){
                $sql22 = "INSERT INTO tbl_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);

               /* $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='0'";
                $conn->query($sql22);*/

                $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='0'";
                $conn->query($sql22);
            }
              }  

          }
      }


$number2 = count($_POST["SerialProd"]);
if($number2 > 0)  
      {  
        for($i=0; $i<$number2; $i++)  
          {  
            if(trim($_POST["SerialProd"][$i] != ''))  
              {
                $StockId = addslashes(trim($_POST['SerialProd'][$i]));
                $sql = "SELECT * FROM tbl_distibute_item_details2 WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                $sql22 = "INSERT INTO tbl_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='1',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId'";
                $conn->query($sql22);
                $PostId = mysqli_insert_id($conn);
                
              /* $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='1',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='1'";
                $conn->query($sql22);*/

                 $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='1',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='1'";
                $conn->query($sql22);

             

            }
            }
        }

$Steps = "Delivery Challan Created & Order Dispatch Successfully";  
$sql = "SELECT * FROM tbl_steps WHERE CustId='$CustId' AND SrNo='4'";
  $rncnt = getRow($sql);
  if($rncnt > 0){
      $sql = "UPDATE tbl_steps SET Steps='$Steps' WHERE CustId='$CustId' AND SrNo='4'";
      $conn->query($sql);
  }
  else{
  $sql = "INSERT INTO tbl_steps SET SrNo=4,CustId='$CustId',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='0',LeadActId='0'";
  $conn->query($sql);
  }

echo "<script>alert('New Delivery Challan Created Successfully!');window.location.href='view-sells.php';</script>";
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
                                    
                                     <div class="form-group col-md-4">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required onchange="getItem(this.value)">
<?php
                                                        if ($Roll == 1 || $Roll == 7) { ?>
                                                            <option selected="" value="">Select Store</option>
                                                        <?php }
                                                        if ($Roll == 1 || $Roll == 7) {
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
                                                        } else if ($Roll == 26) {
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id IN ($MulBranchId)";
                                                        } else {
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
                                                        }
                                                        //echo $sql12;
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

                                     <div class="form-group col-md-8">
<label class="form-label"> Customer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="CustId" id="CustId" required onchange="getItem2(this.value)">
<option selected="" value="">Select Customer</option>
 <?php 
  if($Roll==1 || $Roll==7 || $Roll==12){
  /*$sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone,tp.CustId FROM tbl_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tp.PaidStatus=1 AND tp.DispatchOfficerStatus=1 AND tu.Roll=5 AND tu.ProjectType=2";*/
             $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu 
            WHERE tu.DispatchOfficerStatus=1 AND tu.Roll=5 AND tu.ProjectType=2";
            }
        else{
/*$sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone,tp.CustId FROM tbl_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tp.PaidStatus=1 AND tp.DispatchOfficerStatus=1 AND tu.Roll=5 AND tp.DispatchOfficerId='$user_id' AND tu.ProjectType=2";*/
            /*$sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_quotation tp 
            LEFT JOIN tbl_users tu ON tu.id=tp.CustId 
            WHERE tp.PaidStatus=1 AND tp.StoreInchStatus=1 AND tu.Roll=5";*/
            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu 
            
            WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=2 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id'";
        }
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>

<?php 
  if($Roll==1 || $Roll==7 || $Roll==12){
  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu 
            WHERE tu.DispatchOfficerStatus=1 AND tu.Roll=5 AND tu.ProjectType=1";
            }
        else{
$sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu 
            
            WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id'";
        }
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
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
                                                placeholder="" value="<?php echo $row7["Phone"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()">
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-12">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
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
<label class="form-label">DM NO <span class="text-danger">*</span></label>
<input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">Date </label>
                                            <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-lg-4">
<label class="form-label">L.R. NO <span class="text-danger">*</span></label>
<input type="text" name="LrNo" class="form-control" id="LrNo" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
    <label class="form-label">L.R. Date </label>
                                            <input type="date" name="LrDate" id="LrDate" class="form-control"
                                                placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 


<div class="form-group col-lg-4">
<label class="form-label">Transport  <span class="text-danger">*</span></label>
<input type="text" name="Transport" class="form-control" id="Transport" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-4">
<label class="form-label">Weight  <span class="text-danger">*</span></label>
<input type="text" name="Weight" class="form-control" id="Weight" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Consignee  </label>
<input type="text" name="ConsigneeName" class="form-control" id="ConsigneeName" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Consignee Address </label>
<input type="text" name="ConsigneeAddress" class="form-control" id="ConsigneeAddress" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Site Engineer  </label>
<input type="text" name="SiteEngineerName" class="form-control" id="SiteEngineerName" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Site Engineer Contact No </label>
<input type="text" name="SiteEngineerContactNo" class="form-control" id="SiteEngineerContactNo" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Site Manager   </label>
<input type="text" name="SiteManagerName" class="form-control" id="SiteManagerName" placeholder="" value="" >
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Site Manager Contact No  </label>
<input type="text" name="SiteManagerContactNo" class="form-control" id="SiteManagerContactNo" placeholder="" value="" >
<div class="clearfix"></div>
</div>

 

</div>

<?php if($_REQUEST['action'] == 'search'){?>
<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
<table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th>#</th>
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
    $nostock = 0;
        $sql12 = "SELECT tcp.*,tp.ProductName AS Product_Name,tp.ModelNo AS Model_No,tp.Unit FROM tbl_cust_product_specification tcp 
                  INNER JOIN tbl_products tp ON tcp.ProdId=tp.id 
                  WHERE tcp.CustId='".$_GET['CustId']."' AND tp.Roll=0";
       $rncnt2 = getRow($sql12);           
        $row12 = getList($sql12);
        foreach($row12 as $result){

            $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_distibute_item_details2 WHERE ProductId='".$result['ProdId']."'";
    $row11 = getRecord($sql11);
    $CrQty = $row11['CrQty'];

   $sql12 = "SELECT SUM(Qty) AS DrQty FROM tbl_stocks WHERE CrDr='dr' AND ProductId='".$result['ProdId']."'";
    $row12 = getRecord($sql12);
    $DrQty = $row12['DrQty'];

    $BalQty = $CrQty - $DrQty;
    $Qty = $result['Qty'];
    if($BalQty >= $Qty){
        $bgcolor = "";
        $checkstatus="";
        $disabled = "";
    }
    else{
        $bgcolor = "background-color: #fbe9e9;";
        $nostock+=1;
        $checkstatus="";
        $disabled = "disabled";
        
    }

    $sql22 = "SELECT * FROM tbl_sell_products WHERE UserId='".$_GET['CustId']."' AND ProductId='".$result['ProdId']."'";
    $row22 = getRecord($sql22);
    $DeliveredQty = $row22['Qty'];
    if($DeliveredQty == $Qty){}
        else{

    ?>
     <tr style="<?php echo $bgcolor;?>">
         <td>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $result['ProdId']; ?>" value="0" class="custom-control-input is-valid" onclick="featured(<?php echo $result['ProdId']; ?>)" <?php echo $checkstatus." ".$disabled;?>>
                    <span class="custom-control-label">&nbsp;</span>
                 </label></td>
                 <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $result['ProdId']; ?>">
        <td><?php echo $result['Product_Name'];?></td>
        <input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result['ProdId'];?>">
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">

 <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
       <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result['Product_Name'];?>'>
        <input type="hidden" name="SerialNo[]" id="SerialNo1" value='N/A'>
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result['Model_No'];?>">
<td><input type="text" name="BalQty[]" id="BalQty1" class="form-control" placeholder="e.g.,1" value="<?php echo $BalQty;?>" autocomplete="off" readonly style="width:100px;"></td>
<td><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="<?php echo $result['Qty'];?>" autocomplete="off" min="0" style="width:100px;"></td>
        <td><input type="text" name="Purity[]" id="Purity1" class="form-control" placeholder="" value="<?php echo $result['Unit'];?>" autocomplete="off" style="width:100px;"></td>
      


     </tr>
            <?php } } ?>
     </tbody>

    
    </table>
</div>


<div class="form-row"> 
 <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Serial No Products</label>
</div>
 <div id="dynamic_field2">
  <div class="form-row">  

<div class="form-group col-md-4">
<label class="form-label">Barcode No / Serial No </label>
<div class="input-group">
                               <input type="text" list="browsers" name="BarcodeNo[]" id="BarcodeNo1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getSerialProdDetails(document.getElementById('SrNo1').value,document.getElementById('BarcodeNo1').value)">
                               <datalist id="browsers">
  <?php 
                                    if($Roll==1 || $Roll==7){ 
                                        $sql22 = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND SerialNo!=''";
                                    }
                                     else if($Roll==27){
                                          $sql22 = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND StoreInchId='$user_id' AND SerialNo!=''";
                                     }
                                    else{
                                        $sql22 = "SELECT * FROM tbl_distibute_item_details2 WHERE ProdType='1' AND SerialNo!=''";
                                    }
                                        $row22 = getList($sql22);
                                        foreach($row22 as $result){
                                            $sql33 = "SELECT * FROM tbl_stocks WHERE CrDr='dr' AND ProdType=1 AND SerialNo='".$result['SerialNo']."'";
                                            $rncnt33 = getRow($sql33);
                                            if($rncnt33 > 0){}
                                                else{
                                    ?>
  <option value="<?php echo $result['SerialNo'];?>">
    <?php } } ?>
 
</datalist>
                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" onclick="scanQrCode()"><i class="fas fa-barcode"></i></button>
                               
                                </div>
                            </div>



<div class="clearfix"></div>
</div>
<input type="hidden" name="SerialProd[]" id="SerialProd1">
<input type="hidden" name="SrNo[]" id="SrNo1" class="form-control" placeholder="" value="1">
<div class="form-group col-md-6">
<label class="form-label">Product Name </label>
<div class="input-group">
<input type="text" name="SerialProductName[]" id="SerialProductName1" class="form-control" placeholder="" value="" autocomplete="off">
<span class="input-group-append">
    <button class="btn btn-secondary" type="button" id="add_more2"><i class="fa fa-plus"></i></button>
  </span>
</div>
<div class="clearfix"></div>
</div>

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
   <label class="form-label">Narration</label>
     <input type="text" name="Narration" id="Narration" class="form-control" value="<?php echo $row7['Narration']; ?>">
    <div class="clearfix"></div>
 </div>   


 

</div>

                                   <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <?php //if($nostock > 0){?>
                                            <!-- <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit" disabled>Save</button>
                                            <span style="color:red;">Products Not in Stock</span> -->
                                            <?php //} else {?>
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                            <?php //} ?>
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
function scanQrCode(){
              Android.scanQrCode();
             
          }
          
          function getBarcodeValue(value,id){
              $('#BarcodeNo'+id).val(value);
             getSerialProdDetails(id,value);
          }
  function getSerialProdDetails(srno,barcodeno){
  var action = "getSerialProdDetails";
            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    barcodeno: barcodeno
                },
               dataType:"json",  
                success: function(data) {
                  console.log(data);
                  if(data == 0){
                    $('#SerialProductName'+srno).val('');
                    $('#SerialProd'+srno).val('');
                  }
                  else{
                    $('#SerialProductName'+srno).val(data.ProductName);
                    $('#SerialProd'+srno).val(data.id);
                  }
                  
                    
                }
            });
}
    function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }
  
 function getItem(BranchId){
    var CustId = $('#CustId').val();
      window.location.href="add-sell.php?action=search&BranchId="+BranchId+"&CustId="+CustId;
 }

 function getItem2(CustId){
    var BranchId = $('#BranchId').val();
      window.location.href="add-sell.php?action=search&BranchId="+BranchId+"&CustId="+CustId;
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

    function addMore(i){
      i2=i+1;  

         var action = "getSerialProdRow";
    $.ajax({
    url:"ajax_files/ajax_sell_products.php",
    method:"POST",
    data : {action:action,id:i2},
    success:function(data)
    {
      $('#dynamic_field2').append(data);
    }   
    }); 
    }
     $(document).ready(function() {

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


var i2=1; 
    $('#add_more2').click(function(){  
           i2++;  
       var action = "getSerialProdRow";
    $.ajax({
    url:"ajax_files/ajax_sell_products.php",
    method:"POST",
    data : {action:action,id:i2},
    success:function(data)
    {
      $('#dynamic_field2').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove2', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row2'+button_id+'').remove();  
           
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


$('#example').DataTable({
    "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    searching:false
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