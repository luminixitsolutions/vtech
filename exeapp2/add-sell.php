<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Add-Sell";
//print_r($_SESSION["cart_item"]);

function RandomStringGenerator($n)
{
    $generated_string = "";
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
}


$sql = "SELECT * FROM tbl_distibute_item_details2 WHERE code is null";
$row = getList($sql);
foreach ($row as $result) {
    $n = 10;
    $Code = RandomStringGenerator($n);
    $Code2 = $Code . "" . $result['id'];
    $sql2 = "UPDATE tbl_distibute_item_details2 SET code='$Code2' WHERE id='" . $result['id'] . "'";
    $conn->query($sql2);
}
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
$MaterialDispatchStatus = addslashes(trim($_POST['MaterialDispatchStatus'] ?? ''));
$ProjectCode = addslashes(trim($_POST['ProjectCode'] ?? ''));
$DriverId = addslashes(trim($_POST['DriverId'] ?? ''));

$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');

  $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_sell";
$row8 = getRecord($sql8);
$MaxId = $row8['MaxId'] + 1;
$InvoiceNo = "00".$MaxId;

 $sql = "INSERT INTO tbl_sell SET ProjectCode='$ProjectCode',DriverId='$DriverId',MaterialDispatchStatus='$MaterialDispatchStatus',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Challan',WarrantyPeriod='$WarrantyPeriod',PayStatus='$PayStatus',LrNo='$LrNo',LrDate='$LrDate',Transport='$Transport',ConsigneeName='$ConsigneeName',ConsigneeAddress='$ConsigneeAddress',SiteEngineerName='$SiteEngineerName',SiteEngineerContactNo='$SiteEngineerContactNo',SiteManagerName='$SiteManagerName',SiteManagerContactNo='$SiteManagerContactNo',Weight='$Weight'";
$conn->query($sql);
 $SellId = mysqli_insert_id($conn);


 foreach ($_SESSION["cart_item"] as $product) {
                                $ProductId = $product['id'];
                                $MainProdId = $product['MainProdId'];
                                $ProductName = addslashes(trim($product['ProductName']));
                                $Purity = $product['Unit'];
                                $SerialNo = $product['SerialNo'];
                                $ModelNo = $product['ModelNo'];
                                $ProdType = $product['ProdType'];
                                
                                 $sql22 = "INSERT INTO tbl_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='1',TotalRate='$TotalRate',ProductId='$MainProdId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId',ProdType='$ProdType'";
                                $conn->query($sql22);
                                $PostId = mysqli_insert_id($conn);

                                $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$MainProdId',ProductName='$ProductName',Qty='1',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='$ProdType'";
                                $conn->query($sql22);

                                $sql33 = "UPDATE tbl_distibute_item_details2 SET SellId='$SellId',SellStatus=1 WHERE id='$ProductId'";
                                $conn->query($sql33);
                                
                                if($ProdType == 2){
                                    $sql = "SELECT * FROM tbl_bag_items WHERE BagId='$MainProdId'";
                                    $row = getList($sql);
                                    foreach($row as $result){
                                        $Prod_Name = $result['ProductName'];
                                        $SubQty = $result['Qty'];
                                        $ProdId = $result['ProdId'];
                                        $sql22 = "INSERT INTO tbl_sell_products 
                  SET UserId='$CustId',SellId='$SellId',ProductName='$Prod_Name',
                      Qty='$SubQty',ProductId='$ProdId',ModelNo='$ModelNo',
                      SellDate='$InvoiceDate',BranchId='$BranchId',
                      ProdType='0',BagId='$MainProdId'";
        $conn->query($sql22);
        $PostId = mysqli_insert_id($conn);
        
        $sqlStock = "INSERT INTO tbl_stocks 
                     SET SellId='$SellId',ProductId='$ProdId',ProductName='$Prod_Name',
                         Qty='$SubQty',Status='1',CrDr='dr',CreatedBy='$user_id',
                         CreatedDate='$InvoiceDate',Narration='$Narration',
                         PostId='$PostId',BranchId='$BranchId',SellType='Challan',
                         ModelNo='$ModelNo',ProdType='0',BagId='$MainProdId'";
                         $conn->query($sqlStock);
                         
                         $sql33 = "UPDATE tbl_distibute_item_details2 SET SellId='$SellId',SellStatus=1 WHERE id='$ProdId'";
                                $conn->query($sql33);
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

unset($_SESSION["cart_item"]);
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
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' ";
                                                        } else {
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' ";
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
 <select class="select2-demo form-control" name="CustId" id="CustId" onchange="getItem2(this.value)">
<option selected="" value="">Select Customer</option>
 <?php
                                                        if ($Roll == 1 || $Roll == 7) {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=1";
                                                        } else if ($Roll == 26) {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id' ";
                                                        } else {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 ";
                                                          
                                                        }
                                                        echo $sql12;
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname'] . " (" . $result['Phone'] . ")"; ?></option>
                                                        <?php } ?>
                                                    </select>
<div class="clearfix"></div>
</div> 
<input type="hidden" id="cust_id" value="<?php echo $_REQUEST["CustId"];?>">


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

<div class="form-group col-lg-3">
                                                    <label class="form-label">Project Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="ProjectCode" class="form-control" id="ProjectCode" placeholder="" value="<?php echo rand(1000, 9999); ?>" required>
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
<br>
<div class="form-row">
  <button type="button" class="btn btn-success btn-finish" id="viewSpecBtn" data-custid="<?= $_REQUEST["CustId"]; ?>">
    View Customer Specification
  </button>
</div>
<br>
<?php if($_REQUEST['action'] == 'search'){?>

<div class="form-row">  

<div class="form-group col-md-4">
<label class="form-label">Barcode No / Serial No </label>
<div class="input-group">
                               <input type="text" list="browsers" name="BarcodeNo[]" id="BarcodeNo1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getSerialProdDetails(document.getElementById('BarcodeNo1').value)">
                               <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" onclick="scanQrCode()"><i class="fas fa-barcode"></i></button></div>

</div>
</div>

<div id="cartContainer" class="mt-3"></div>
</div>
   



                                     

   <div class="form-row">
     

<div class="form-group col-md-6" style="padding-top:10px;">
                                                    <label class="form-label"> Driver<span class="text-danger">*</span></label>
                                                    <select class="select2-demo form-control" name="DriverId" id="DriverId" required>
                                                        <option selected="" value="">Select Driver</option>
                                                        <?php
                                                        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=39";
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["DriverId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname'] . " (" . $result['VehicalNo'] . ")"; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>
                                                
                                                
                                                <div class="form-group col-md-6" style="padding-top:10px;">
                                                    <label class="form-label"> Material Dispatch Status<span class="text-danger">*</span></label>
                                                    <select class="form-control" name="MaterialDispatchStatus" id="MaterialDispatchStatus" required>
                                        <option selected="" value="">Select Material Dispatch Status</option>

                                        <option <?php if($_REQUEST["MaterialDispatchStatus"] == 1) { ?> selected <?php } ?> value="1">
                                        Material Dispatch</option>
                                        
                                        <option <?php if($_REQUEST["MaterialDispatchStatus"] == 2) { ?> selected <?php } ?> value="2">
                                        Parital Material Dispatch</option>
                                        
                                        
                                                        
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>

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


     <!-- Modal -->
<div class="modal fade" id="specModal" tabindex="-1" aria-labelledby="specModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable"> <!-- scrollable modal -->
    <div class="modal-content">
      
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="specModalLabel">Customer Specification</h5>
        <!-- ✅ Correct close button for Bootstrap 5 -->
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> <!-- force scroll if too tall -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light sticky-top">
              <tr>
                <th style="width: 60px;">#</th>
                <th>Item</th>
                <th style="width: 100px;">Qty</th>
                <th style="width: 100px;">Unit</th>
              </tr>
            </thead>
            <tbody id="specTableBody">
              <tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


       <?php include_once 'footer_script.php'; ?>

<!--<script type="text/javascript">
function scanQrCode(){
    Android.scanQrCode();
}
          
function getBarcodeValue(value,id){
    $('#BarcodeNo'+id).val(value);
    getSerialProdDetails(value);
}

$(document).on('click', '#viewSpecBtn', function() {
    var custId = $(this).data('custid');

    // Open modal first
    $('#specModal').modal('show');

    // Reset content
    $('#specTableBody').html('<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>');
    $('#specModalLabel').text('Customer Specification');

    // Fetch data via AJAX
    $.ajax({
        url: 'get_customer_specification.php',
        type: 'POST',
        data: { custId: custId },
        dataType: 'json',
        success: function(response) {
            console.log(response);
            let bagName = response.BagName || '';
            let specData = response.Specification || [];
            let rows = '';

            // ✅ Add Bag Name as the first product row
            if (bagName !== '') {
                rows += `
                    <tr >
                        <td>1</td>
                        <td>${bagName}</td>
                        <td>1</td>
                        <td>SET</td>
                    </tr>`;
            }

            // ✅ Add all other specifications
            if (specData.length > 0) {
                $.each(specData, function(i, item) {
                    rows += `
                        <tr>
                            <td>${bagName !== '' ? (i + 2) : (i + 1)}</td>
                            <td>${item.ItemName}</td>
                            <td>${item.Qty}</td>
                            <td>${item.Unit}</td>
                        </tr>`;
                });
            }

            // ✅ Handle empty case
            if (rows === '') {
                rows = '<tr><td colspan="4" class="text-center text-danger">No specifications found</td></tr>';
            }

            $('#specTableBody').html(rows);
        },
        error: function() {
            $('#specTableBody').html('<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
});




  function getSerialProdDetails(barcodeno){
     var cust_id = $('#cust_id').val();
  var action = "getSerialProdDetails";

            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    barcodeno: barcodeno,
                    cust_id:cust_id
                },
                success: function(data) {
                  console.log(data);
                  try {
                var res = JSON.parse(data);

                if (res.status == 1) {
                    toastr.success(res.message || "Product added!");
                    displayCart(); // ✅ Refresh cart after adding
                } 
                else if (res.status == 0) {
                    toastr.error(res.message);
                                   }
                else {
                    // toastr.warning(res.message || "Product not found or already in stock.");
                }
            } catch (e) {
                console.error("Invalid JSON:", data);
            }
                  
                    
                }
            });
}


function displayCart() {
    var cust_id = $('#cust_id').val();
    $.ajax({
        url: "ajax_files/ajax_products.php",
        method: "POST",
        data: { action: "displayCart",cust_id:cust_id },
        success: function (response) {
            $("#cartContainer").html(response);
        }
    });
}

    
  
 function getItem(BranchId){
    var CustId = $('#CustId').val();
      window.location.href="add-sell.php?action=search&BranchId="+BranchId+"&CustId="+CustId;
 }

 function getItem2(CustId){
    var BranchId = $('#BranchId').val();
      window.location.href="add-sell.php?action=search&BranchId="+BranchId+"&CustId="+CustId;
 }
  

    

     

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



    });

    
// 🗑️ Remove item with SweetAlert confirmation
$(document).on('click', '.remove-item', function() {
    const code = $(this).data('code');

    Swal.fire({
        title: 'Remove item?',
        text: "Are you sure you want to delete this serial from the cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax_files/ajax_products.php',
                method: 'POST',
                data: { action: 'removeItem', code: code },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.status == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: res.message,
                                timer: 1000,
                                showConfirmButton: false
                            });
                            displayCart(); // ✅ refresh cart
                        } else {
                            Swal.fire('Warning', res.message, 'warning');
                        }
                    } catch (e) {
                        console.error("Invalid response:", response);
                    }
                }
            });
        }
    });
});


 </script>-->
 
 <script type="text/javascript">
$(document).ready(function () {

  // ----------------------------
  // 🔹 QR Code Scanning
  // ----------------------------
  function scanQrCode() {
    if (typeof Android !== "undefined" && Android.scanQrCode) {
      Android.scanQrCode();
    } else {
      toastr.warning("QR scanning not supported on this device.");
    }
  }

  window.scanQrCode = scanQrCode; // make callable from button

  window.getBarcodeValue = function (value, id) {
    $(`#BarcodeNo${id}`).val(value);
    getSerialProdDetails(value);
  };

  // ----------------------------
  // 🔹 Load Customer Specification
  // ----------------------------
  $(document).on("click", "#viewSpecBtn", function () {
    const custId = $(this).data("custid");
    if (!custId) {
      Swal.fire("Please select a customer first!", "", "warning");
      return;
    }

    $("#specModal").modal("show");
    $("#specTableBody").html(
      '<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>'
    );

    $.ajax({
      url: "get_customer_specification.php",
      type: "POST",
      data: { custId },
      dataType: "json",
      success: function (response) {
        const bagName = response?.BagName || "";
        const specData = response?.Specification || [];
        let rows = "";

        // Add Bag Name row
        if (bagName) {
          rows += `
            <tr>
              <td>1</td>
              <td>${bagName}</td>
              <td>1</td>
              <td>SET</td>
            </tr>`;
        }

        // Add Specification items
        specData.forEach((item, i) => {
          rows += `
            <tr>
              <td>${bagName ? i + 2 : i + 1}</td>
              <td>${item.ItemName}</td>
              <td>${item.Qty}</td>
              <td>${item.Unit}</td>
            </tr>`;
        });

        // Handle empty case
        if (!rows) {
          rows = `<tr><td colspan="4" class="text-center text-danger">No specifications found</td></tr>`;
        }

        $("#specTableBody").html(rows);
      },
      error: function () {
        $("#specTableBody").html(
          `<tr><td colspan="4" class="text-center text-danger">Error loading data</td></tr>`
        );
      },
    });
  });

  // ----------------------------
  // 🔹 Fetch Serial Product Details
  // ----------------------------
  function getSerialProdDetails(barcodeno) {
    const cust_id = $("#cust_id").val();
    $.ajax({
      url: "ajax_files/ajax_products.php",
      type: "POST",
      data: {
        action: "getSerialProdDetails",
        barcodeno,
        cust_id,
      },
      success: function (data) {
        try {
          const res = JSON.parse(data);
          if (res.status === 1) {
              
            toastr.success(res.message || "Product added!");
            displayCart();
          } else {
            toastr.error(res.message || "Product not found or already exists.");
          }
        } catch (e) {
          console.error("Invalid JSON:", data);
        }
        $('#BarcodeNo1').val('');
      },
      error: function () {
        toastr.error("Error fetching product details.");
      },
    });
  }
  window.getSerialProdDetails = getSerialProdDetails;

  // ----------------------------
  // 🔹 Display Cart
  // ----------------------------
  function displayCart() {
    const cust_id = $("#cust_id").val();
    $.ajax({
      url: "ajax_files/ajax_products.php",
      type: "POST",
      data: { action: "displayCart", cust_id },
      success: function (response) {
        $("#cartContainer").html(response);
      },
      error: function () {
        $("#cartContainer").html(
          '<div class="text-danger text-center py-2">Failed to load cart.</div>'
        );
      },
    });
  }

  // ----------------------------
  // 🔹 Customer Info Auto Fill
  // ----------------------------
  $("#CustId").change(function () {
    const id = $(this).val();
    $.ajax({
      url: "ajax_files/ajax_vendor.php",
      type: "POST",
      dataType: "json",
      data: { action: "getUserDetails", id },
      success: function (data) {
        $("#Address").val(data.Address || "");
        $("#CustName").val(data.Fname || "");
        $("#CellNo").val(data.Phone || "");
      },
      error: function () {
        toastr.error("Unable to fetch customer details.");
      },
    });
  });

  // ----------------------------
  // 🔹 Branch & Customer Filter Reload
  // ----------------------------
  window.getItem = function (BranchId) {
    const CustId = $("#CustId").val();
    if (BranchId && CustId) {
      window.location.href = `add-sell.php?action=search&BranchId=${BranchId}&CustId=${CustId}`;
    }
  };

  window.getItem2 = function (CustId) {
    const BranchId = $("#BranchId").val();
    if (BranchId && CustId) {
      window.location.href = `add-sell.php?action=search&BranchId=${BranchId}&CustId=${CustId}`;
    }
  };

  // ----------------------------
  // 🔹 Remove Item from Cart (SweetAlert)
  // ----------------------------
  $(document).on("click", ".remove-item", function () {
    const code = $(this).data("code");

    Swal.fire({
      title: "Remove item?",
      text: "Are you sure you want to delete this serial from the cart?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Yes, remove it",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "ajax_files/ajax_products.php",
          type: "POST",
          data: { action: "removeItem", code },
          success: function (response) {
            try {
              const res = JSON.parse(response);
              if (res.status === 1) {
                Swal.fire({
                  icon: "success",
                  title: "Removed!",
                  text: res.message,
                  timer: 1000,
                  showConfirmButton: false,
                });
                displayCart();
              } else {
                Swal.fire("Warning", res.message, "warning");
              }
            } catch (e) {
              console.error("Invalid response:", response);
            }
          },
          error: function () {
            Swal.fire("Error", "Failed to remove item!", "error");
          },
        });
      }
    });
  });

}); // document.ready end
</script>

</body>

</html>