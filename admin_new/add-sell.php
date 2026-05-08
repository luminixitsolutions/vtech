<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Add-Sell-2";
//echo "<pre>";print_r($_SESSION["cart_item"]);
//unset($_SESSION["cart_item"]);

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
    <title><?php echo $Proj_Title; ?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->

    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/datatables/datatables.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/bootstrap-select/bootstrap-select.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/select2/select2.css">

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

            <?php include_once 'header.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Create Delivery Challan</h5>

                        <style>
                            .flex-wrap {
                                margin-bottom: -35px;
                            }

                            div.dataTables_wrapper div.dataTables_paginate {
                                margin-top: 1px;
                            }
                        </style>

                        <?php
                        $id = $_GET['id'];
                        $CustId = $_GET['CustId'];
                        $sql7 = "SELECT * FROM tbl_users WHERE id='$CustId'";
                        $row7 = getRecord($sql7);
                        $CustBagId = $row7['BagId'];

                        $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_sell";
                        $row8 = getRecord($sql8);
                        $MaxId = $row8['MaxId'] + 1;
                        $Invoice_No = "00" . $MaxId;
                        if (isset($_POST['submit'])) {
                           // ---------- STEP 1: Capture Master Invoice ----------
$AgencyId = $_POST['AgencyId'] ?? '';
$CompId = $_POST['CompId'] ?? '';
$CustId = $_POST['CustId'] ?? '';
$CustName = addslashes(trim($_POST['CustName'] ?? ''));
$CellNo = addslashes(trim($_POST['CellNo'] ?? ''));
$Address = addslashes(trim($_POST['Address'] ?? ''));
$InvoiceDate = addslashes(trim($_POST['InvoiceDate'] ?? date('Y-m-d')));
$PayType = addslashes(trim($_POST['PayType'] ?? ''));
$Narration = addslashes(trim($_POST['Narration'] ?? ''));
$MaterialDispatchStatus = addslashes(trim($_POST['MaterialDispatchStatus'] ?? ''));
$PayMode = addslashes(trim($_POST['PayMode'] ?? ''));
$DeliveryDate = addslashes(trim($_POST['DeliveryDate'] ?? ''));
$GrossAmt = addslashes(trim($_POST['GrossAmt'] ?? '0'));
$CgstPer = addslashes(trim($_POST['CgstPer'] ?? '0'));
$CgstAmt = addslashes(trim($_POST['CgstAmt'] ?? '0'));
$SgstPer = addslashes(trim($_POST['SgstPer'] ?? '0'));
$SgstAmt = addslashes(trim($_POST['SgstAmt'] ?? '0'));
$IgstPer = addslashes(trim($_POST['IgstPer'] ?? '0'));
$IgstAmt = addslashes(trim($_POST['IgstAmt'] ?? '0'));
$SubTotal = addslashes(trim($_POST['SubTotal'] ?? '0'));
$UcdAmt = addslashes(trim($_POST['UcdAmt'] ?? '0'));
$Discount = addslashes(trim($_POST['Discount'] ?? '0'));
$Total = addslashes(trim($_POST['Total'] ?? '0'));
$ChequeNo = addslashes(trim($_POST['ChequeNo'] ?? ''));
$ChqDate = addslashes(trim($_POST['ChqDate'] ?? ''));
$BankName = addslashes(trim($_POST['BankName'] ?? ''));
$UpiNo = addslashes(trim($_POST['UpiNo'] ?? ''));
$BranchId = addslashes(trim($_POST['BranchId'] ?? ''));
$WarrantyPeriod = addslashes(trim($_POST['WarrantyPeriod'] ?? ''));
$PayStatus = addslashes(trim($_POST['PayStatus'] ?? ''));
$LrNo = addslashes(trim($_POST['LrNo'] ?? ''));
$LrDate = addslashes(trim($_POST['LrDate'] ?? ''));
$Transport = addslashes(trim($_POST['Transport'] ?? ''));
$ConsigneeName = addslashes(trim($_POST['ConsigneeName'] ?? ''));
$ConsigneeAddress = addslashes(trim($_POST['ConsigneeAddress'] ?? ''));
$SiteEngineerName = addslashes(trim($_POST['SiteEngineerName'] ?? ''));
$SiteEngineerContactNo = addslashes(trim($_POST['SiteEngineerContactNo'] ?? ''));
$SiteManagerName = addslashes(trim($_POST['SiteManagerName'] ?? ''));
$SiteManagerContactNo = addslashes(trim($_POST['SiteManagerContactNo'] ?? ''));
$Weight = addslashes(trim($_POST['Weight'] ?? ''));
$ProjectCode = addslashes(trim($_POST['ProjectCode'] ?? ''));
$DriverId = addslashes(trim($_POST['DriverId'] ?? ''));
 $CreatedDate = date('Y-m-d');
                            $CreatedTime = date('h:i a');



// ---------- Generate Invoice No ----------
$row8 = getRecord("SELECT MAX(SrNo) AS MaxId FROM tbl_sell");
$MaxId = ($row8['MaxId'] ?? 0) + 1;
$InvoiceNo = "00" . $MaxId;


// ---------- Insert tbl_sell ----------
$sql = "INSERT INTO tbl_sell SET 
        ProjectCode='$ProjectCode',CompId='$CompId',AgencyId='$AgencyId',
        SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',
        InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',
        PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',
        CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',
        IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',
        Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',
        Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',
        UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Challan',
        WarrantyPeriod='$WarrantyPeriod',PayStatus='$PayStatus',LrNo='$LrNo',LrDate='$LrDate',
        Transport='$Transport',ConsigneeName='$ConsigneeName',ConsigneeAddress='$ConsigneeAddress',
        SiteEngineerName='$SiteEngineerName',SiteEngineerContactNo='$SiteEngineerContactNo',
        SiteManagerName='$SiteManagerName',SiteManagerContactNo='$SiteManagerContactNo',
        Weight='$Weight',DriverId='$DriverId',MaterialDispatchStatus='$MaterialDispatchStatus'";

if (!$conn->query($sql)) {
    die("Sell Insert Error: " . $conn->error);
}
$SellId = $conn->insert_id;
                           
                            

                     // ====================================================================
// 1️⃣ SAVE MAIN BAG ITEMS (ProdType = 2)
// ====================================================================
if (!empty($_POST["Check_Id"])) {
    foreach ($_POST["Check_Id"] as $checkedProdId) {
        $index = array_search($checkedProdId, $_POST["ProdId"]);
        if ($index === false) continue; // skip if mismatch

        $ProductId   = $_POST["ProdId"][$index];
        $ProductName = $_POST["ProdName"][$index];
        $SerialNo    = $_POST["SerialNo"][$index];
        $ModelNo     = $_POST["ModelNo"][$index] ?? '';
        $Qty         = 1;

        // Insert into tbl_sell_products
        $sql22 = "INSERT INTO tbl_sell_products 
                  SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',
                      Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',
                      SellDate='$InvoiceDate',SerialNo='$SerialNo',
                      BranchId='$BranchId',ProdType='2'";
        if (!$conn->query($sql22)) {
            echo json_encode(["status" => "error", "msg" => "Main Sell Product Insert Error: " . $conn->error]);
            exit;
        }
        $PostId = $conn->insert_id;

        // Insert into tbl_stocks
        $sqlStock = "INSERT INTO tbl_stocks 
                     SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',
                         Qty='$Qty',Status='1',CrDr='dr',CreatedBy='$user_id',
                         CreatedDate='$InvoiceDate',Narration='$Narration',
                         PostId='$PostId',BranchId='$BranchId',SellType='Challan',
                         SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='2'";
        if (!$conn->query($sqlStock)) {
            echo json_encode(["status" => "error", "msg" => "Main Stock Insert Error: " . $conn->error]);
            exit;
        }
        
        $conn->query("UPDATE tbl_distibute_item_details2 
                          SET SellStatus=1,SellId='$SellId' 
                          WHERE ProductId='$ProductId' AND SerialNo='$SerialNo'");
    }
}


// ====================================================================
// 2️⃣ SAVE SUB ITEMS (ProdType = 0, Linked via BagId)
// ====================================================================
if (!empty($_POST["SubCheckId"])) {
    foreach ($_POST["SubCheckId"] as $checkedProdId) {
        $index = array_search($checkedProdId, $_POST["ProductId"]);
        if ($index === false) continue;

        $ProductId   = $_POST["ProductId"][$index];
        $ProductName = $_POST["ProductName"][$index];
        $ModelNo     = $_POST["ModelNo"][$index] ?? '';
        $BagId       = $_POST["BagId"][$index];
        $Qty         = $_POST["Qty"][$index] ?? 1;

        // Insert into tbl_sell_products
        $sql22 = "INSERT INTO tbl_sell_products 
                  SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',
                      Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',
                      SellDate='$InvoiceDate',BranchId='$BranchId',
                      ProdType='0',BagId='$BagId'";
        if (!$conn->query($sql22)) {
            echo json_encode(["status" => "error", "msg" => "Sub Sell Product Insert Error: " . $conn->error]);
            exit;
        }
        $PostId = $conn->insert_id;

        // Insert into tbl_stocks
        $sqlStock = "INSERT INTO tbl_stocks 
                     SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',
                         Qty='$Qty',Status='1',CrDr='dr',CreatedBy='$user_id',
                         CreatedDate='$InvoiceDate',Narration='$Narration',
                         PostId='$PostId',BranchId='$BranchId',SellType='Challan',
                         ModelNo='$ModelNo',ProdType='0',BagId='$BagId'";
        if (!$conn->query($sqlStock)) {
            echo json_encode(["status" => "error", "msg" => "Sub Stock Insert Error: " . $conn->error]);
            exit;
        }
        
         $conn->query("UPDATE tbl_distibute_item_details2 
                          SET SellStatus=1,SellId='$SellId' 
                          WHERE ProductId='$ProductId'");
    }
}


// ====================================================================
// 4️⃣ SAVE STRUCTURE SERIAL NUMBERS (ProdType = 1)
// ====================================================================
// Each StructureSerialCheckId[$ProductId][] contains the checked serials
if (!empty($_POST['StructureSerialCheckId'])) {

    foreach ($_POST['StructureSerialCheckId'] as $productId => $serialArray) {

        // Loop through all selected serials for this product
        foreach ($serialArray as $key => $serialNo) {

            $ProductId   = $_POST['StructureSerialProductId'][$productId][$key];
            $ProductName = $_POST['StructureSerialProductName'][$productId][$key] ?? '';
            $ModelNo     = $_POST['StructureModelNo'][$productId] ?? ''; // optional
            $Qty         = 1; // one per serial
            $SerialNo    = $serialNo;

            // ✅ Insert into tbl_sell_products
            $sqlSell = "INSERT INTO tbl_sell_products 
                        SET UserId='$CustId',
                            SellId='$SellId',
                            ProductName='$ProductName',
                            ProductId='$ProductId',
                            Qty='$Qty',
                            ModelNo='$ModelNo',
                            SerialNo='$SerialNo',
                            SellDate='$InvoiceDate',
                            BranchId='$BranchId',
                            ProdType='1',Structure=1";
            if (!$conn->query($sqlSell)) {
                echo json_encode(["status" => "error", "msg" => "❌ Structure Serial Product Insert Error: " . $conn->error]);
                exit;
            }
            $PostId = $conn->insert_id;

            // ✅ Insert into tbl_stocks
            $sqlStock = "INSERT INTO tbl_stocks 
                         SET SellId='$SellId',
                             ProductId='$ProductId',
                             ProductName='$ProductName',
                             Qty='$Qty',
                             Status='1',
                             CrDr='dr',
                             CreatedBy='$user_id',
                             CreatedDate='$InvoiceDate',
                             Narration='$Narration',
                             PostId='$PostId',
                             BranchId='$BranchId',
                             SellType='Challan',
                             SerialNo='$SerialNo',
                             ModelNo='$ModelNo',
                             ProdType='1',Structure=1";
            if (!$conn->query($sqlStock)) {
                echo json_encode(["status" => "error", "msg" => "❌ Structure Serial Stock Insert Error: " . $conn->error]);
                exit;
            }

            // ✅ Optional: Mark this serial as sold in distribution table
            $conn->query("UPDATE tbl_distibute_item_details2 
                          SET SellStatus=1,SellId='$SellId' 
                          WHERE ProductId='$ProductId' AND SerialNo='$SerialNo'");
        }
    }
}

                            
                            

                            foreach ($_SESSION["cart_item"] as $product) {
                                $ProductId = $product['id'];
                                $ProductName = $product['ProductName'];
                                $Purity = $product['Unit'];
                                $SerialNo = $product['SerialNo'];
                                $ModelNo = $product['ModelNo'];
                                $MainProdId = $product['MainProdId'];
                                $sql22 = "INSERT INTO tbl_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='1',TotalRate='$TotalRate',ProductId='$MainProdId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId'";
                                $conn->query($sql22);
                                $PostId = mysqli_insert_id($conn);

                                $sql22 = "INSERT INTO tbl_stocks SET SellId='$SellId',ProductId='$MainProdId',ProductName='$ProductName',Qty='1',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='1'";
                                $conn->query($sql22);

                                $sql33 = "UPDATE tbl_distibute_item_details2 SET SellId='$SellId',SellStatus=1 WHERE id='$ProductId'";
                                $conn->query($sql33);
                            }

                            $Steps = "Delivery Challan Created & Order Dispatch Successfully";
                            $sql = "SELECT * FROM tbl_steps WHERE CustId='$CustId' AND SrNo='4'";
                            $rncnt = getRow($sql);
                            if ($rncnt > 0) {
                                $sql = "UPDATE tbl_steps SET Steps='$Steps' WHERE CustId='$CustId' AND SrNo='4'";
                                $conn->query($sql);
                            } else {
                                $sql = "INSERT INTO tbl_steps SET SrNo=4,CustId='$CustId',Steps='$Steps',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',CustName='$CustName',Address='$Address',Phone='$CellNo',LeadId='0',LeadActId='0'";
                                $conn->query($sql);
                            }

                            echo "<script>alert('New Delivery Challan Created Successfully!');window.location.href='view-sells.php';</script>";
                        }
                        unset($_SESSION["cart_item"]);
                        ?>
                        <div class="card mb-4">
                            <div class="card-body">

                                <form id="validation-form" method="post" autocomplete="off" action="">
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
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["CompId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-6" style="padding-top:10px;">
                                                    <label class="form-label"> Agency<span class="text-danger">*</span></label>
                                                    <select class="select2-demo form-control" name="AgencyId" id="AgencyId" required>
                                                        <option selected="" value="">Select Agency</option>
                                                        <?php
                                                        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=11";
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["AgencyId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>

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
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='" . $_SESSION['storeid'] . "'";
                                                        } else {
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
                                                        }
                                                        //echo $sql12;
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["BranchId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
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
                                                        if ($Roll == 1 || $Roll == 7) {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=1";
                                                        } else if ($Roll == 26) {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id' AND tu.BranchId='" . $_SESSION['storeid'] . "'";
                                                        } else {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id'";
                                                            /*  $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=1 AND tu.Roll=5 AND tu.BranchId='$BranchId'"; */
                                                        }
                                                        //echo $sql12;
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($_REQUEST["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname'] . " (" . $result['Phone'] . ")"; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Contact No </label>
                                                    <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                        placeholder="" value="<?php echo $row7["Phone"]; ?>"
                                                        autocomplete="off" oninput="getUserDetails()">
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="form-group col-md-8">
                                                    <label class="form-label">Customer Name </label>
                                                    <input type="text" name="CustName" id="CustName" class="form-control"
                                                        placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-12">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="Address" id="Address" class="form-control"><?php echo $row7['Address']; ?></textarea>
                                                    <div class="clearfix"></div>
                                                </div>




                                                <div class="form-group col-lg-4">
                                                    <label class="form-label">DM NO <span class="text-danger">*</span></label>
                                                    <input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>">
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
                                                    <input type="text" name="LrNo" class="form-control" id="LrNo" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="form-label">L.R. Date </label>
                                                    <input type="date" name="LrDate" id="LrDate" class="form-control"
                                                        placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>


                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Transport <span class="text-danger">*</span></label>
                                                    <input type="text" name="Transport" class="form-control" id="Transport" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Weight <span class="text-danger">*</span></label>
                                                    <input type="text" name="Weight" class="form-control" id="Weight" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Project Code <span class="text-danger">*</span></label>
                                                    <input type="text" name="ProjectCode" class="form-control" id="ProjectCode" placeholder="" value="<?php echo rand(1000, 9999); ?>" required>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="form-label">Consignee </label>
                                                    <input type="text" name="ConsigneeName" class="form-control" id="ConsigneeName" placeholder="" value="<?php echo $row7["Fname"]; ?>">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-12">
                                                    <label class="form-label">Consignee Address </label>
                                                    <input type="text" name="ConsigneeAddress" class="form-control" id="ConsigneeAddress" placeholder="" value="<?php echo $row7["Address"]; ?>">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Site Engineer </label>
                                                    <input type="text" name="SiteEngineerName" class="form-control" id="SiteEngineerName" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Site Engineer Contact No </label>
                                                    <input type="text" name="SiteEngineerContactNo" class="form-control" id="SiteEngineerContactNo" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Site Manager </label>
                                                    <input type="text" name="SiteManagerName" class="form-control" id="SiteManagerName" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-3">
                                                    <label class="form-label">Site Manager Contact No </label>
                                                    <input type="text" name="SiteManagerContactNo" class="form-control" id="SiteManagerContactNo" placeholder="" value="">
                                                    <div class="clearfix"></div>
                                                </div>

                                                
                                                
                                               

                                            </div>

                                            <?php if ($_REQUEST['action'] == 'search') { ?>
                                                <div class="form-row">
  <label class="form-label" style="font-size: 18px; color: #0dc30d;">Bag Details</label>
  <div class="col-lg-12">
    <table id="bagTable" class="table table-striped table-bordered" width="100%">
      <thead>
        <tr>
          <th>#</th>
          <th width="30%">Bag</th>
          <th width="30%">Serial No</th>
          <th>Stock Qty</th>
          <th>Qty</th>
          <th>Unit</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $branchId = $_REQUEST["BranchId"];
        $sql = "SELECT * FROM tbl_stocks WHERE ProdType=2 AND ProductId='$CustBagId'";
        $bags = getList($sql);

        if (!$bags) {
          echo '<tr><td colspan="6" class="text-center text-danger fw-bold">No Bag Products Found</td></tr>';
        } else {
          $i = 1;
          foreach ($bags as $bag) {
            $prodId   = $bag['ProductId'];
            $serialNo = $bag['SerialNo'];
            $productName = $bag['ProductName'];
            $unit = $bag['Unit'];

            // Check stock/distribution
            $sql2 = "SELECT * FROM tbl_distibute_item_details2 
                     WHERE ProdType=2 AND BranchId='$branchId' 
                     AND ProductId='$CustBagId' AND SerialNo='$serialNo'";
            $rncnt2 = getRow($sql2);

            $sql3 = "SELECT * FROM tbl_stocks 
                     WHERE CrDr='dr' AND ProdType=2 AND BranchId='$branchId' 
                     AND ProductId='$CustBagId' AND SerialNo='$serialNo'";
            $rncnt3 = getRow($sql3);

            if ($rncnt2 > 0 && $rncnt3 == 0) { ?>
              <tr id="bag_<?php echo $serialNo; ?>" class="bag-row" style="cursor:pointer;">
                <td>
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" 
                           class="custom-control-input bagCheckbox" 
                           name="Check_Id[]" 
                           value="<?php echo $prodId; ?>" 
                           id="bagCheck_<?php echo $serialNo; ?>"
                           data-serial="<?php echo $serialNo; ?>">
                    <span class="custom-control-label"></span>
                  </label>
                </td>
                <td><strong><?php echo htmlspecialchars($productName); ?></strong></td>
                <td><?php echo htmlspecialchars($serialNo); ?></td>
                <td>-</td>
                <td>-</td>
                <td><?php echo htmlspecialchars($unit); ?></td>

                <!-- Hidden inputs for main item -->
                <input type="hidden" name="ProdId[]" value="<?php echo $prodId; ?>">
                <input type="hidden" name="ProdName[]" value="<?php echo htmlspecialchars($productName); ?>">
                <input type="hidden" name="SerialNo[]" value="<?php echo htmlspecialchars($serialNo); ?>">
                <input type="hidden" name="ModelNo[]" value="<?php echo htmlspecialchars($bag['ModelNo'] ?? ''); ?>">
              </tr>

              <!-- 🔽 Sub Items for this Bag -->
              <tr class="subitems-row" id="subitems_<?php echo $serialNo; ?>" style="display:none; background:#f9f9f9;">
                <td colspan="6" class="p-0">
                  <div class="p-2">
                    <table class="table table-sm mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>#</th>
                          <th>Sub Item</th>
                          <th>Available Qty</th>
                          <th>Required Qty</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $bagItems = getList("SELECT * FROM tbl_bag_items WHERE BagId='$prodId'");
                        $subIndex = 1;

                        foreach ($bagItems as $item) {
                          $subName = $item['ProductName'];
                          $reqQty  = floatval($item['Qty']);
                          $prodId2 = $item['ProdId'];

                          // Customer available qty
                          $sqlStock = "SELECT Qty FROM tbl_cust_product_specification 
                                       WHERE CustId='" . $_GET['CustId'] . "' 
                                       AND ProdId='$prodId2'";
                          $custProd = getRecord($sqlStock);
                          $availQty = $custProd ? floatval($custProd['Qty']) : 0;

                          // Branch stock balance
                          $sqlCr = "SELECT SUM(Qty) AS CrQty 
                                    FROM tbl_distibute_item_details2 
                                    WHERE ProdType=0 AND ProductId='$prodId2' 
                                    AND BranchId='$branchId'";
                          $CrQty = floatval(getRecord($sqlCr)['CrQty']);

                          $sqlDr = "SELECT SUM(Qty) AS DrQty 
                                    FROM tbl_stocks 
                                    WHERE CrDr='dr' AND ProdType=0 
                                    AND ProductId='$prodId2' 
                                    AND BranchId='$branchId'";
                          $DrQty = floatval(getRecord($sqlDr)['DrQty']);

                          $BalQty = $CrQty - $DrQty;
                          $Qty = $availQty;

                          // Status & color
                          if ($BalQty >= $Qty) {
                            $bgcolor = "";
                            $disabled = "disabled";
                            $status = "<span class='badge bg-success'>In Stock</span>";
                          } elseif ($BalQty > 0) {
                            $bgcolor = "background-color:#fff3cd;";
                            $disabled = "disabled";
                            $status = "<span class='badge bg-warning text-dark'>Partial ($BalQty)</span>";
                          } else {
                            $bgcolor = "background-color:#f8d7da;";
                            $disabled = "disabled";
                            $status = "<span class='badge bg-danger'>Out of Stock</span>";
                          }
                          ?>
                          <tr style="<?php echo $bgcolor; ?>">
                            <td>
                              <label class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input subCheckbox sub_<?php echo $serialNo; ?>"
                                       name="SubCheckId[]"
                                       value="<?php echo $prodId2; ?>"
                                       data-bag="<?php echo $prodId; ?>"
                                       checked
           onclick="return false;">
                                <span class="custom-control-label"></span>
                              </label>
                            </td>
                            <td><?php echo htmlspecialchars($subName); ?></td>
                            <td><input type="text" name="BalQty[]" value="<?php echo $BalQty; ?>" readonly class="form-control" style="width:100px;"></td>
                            <td><input type="number" name="Qty[]" value="<?php echo $availQty; ?>" min="0" class="form-control" style="width:100px;"></td>
                            <td><?php echo $status; ?></td>

                            <!-- Hidden mapping values -->
                            <input type="hidden" name="BagId[]" value="<?php echo $prodId; ?>">
                            <input type="hidden" name="ProductId[]" value="<?php echo $prodId2; ?>">
                            <input type="hidden" name="ProductName[]" value="<?php echo htmlspecialchars($subName); ?>">
                            <input type="hidden" name="ModelNo[]" value="<?php echo htmlspecialchars($item['ModelNo'] ?? ''); ?>">
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </td>
              </tr>
        <?php $i++; } } } ?>
      </tbody>
    </table>
  </div>
</div>

<style>
    .table .table-dark, .table .table-dark > th, .table .table-dark > td {
    border-color: rgba(0, 0, 0, 0.035);
    background-color: rgb(7 7 7 / 96%);
    color: #fff;
}
</style>

<div class="form-row">
  <label class="form-label" style="font-size: 18px; color: #0dc30d;">Structure Serial No Products</label>

  <div class="table-responsive" style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px;">
    <table id="structureTable" class="table table-striped table-bordered mb-0" style="width:100%;">
      <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
        <tr>
          <th style="width: 10px;">#</th>
          <th width="50%">Product</th>
          <th>Required Qty</th>
          <th>Available Qty</th>
          <th>Transfer Qty</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i = 1;
        $custId = $_GET['CustId'];
        $branchId = $_REQUEST['BranchId'];

        // Step 1: Get all customer structure product specifications (SpecType = 1)
        $sqlSpec = "SELECT * FROM tbl_cust_product_specification 
                    WHERE CustId='$custId' AND SpecType=1";
        $specRows = getList($sqlSpec);

        if (!$specRows) {
          echo "<tr><td colspan='5' class='text-center text-danger fw-bold'>No structure products found for this customer.</td></tr>";
        } else {
          foreach ($specRows as $rowSpec) {
            $productId = $rowSpec['ProdId'];
            $reqQty    = floatval($rowSpec['Qty']);

            // Step 2: Fetch product info and available stock
            if ($Roll == 1 || $Roll == 7) {
              $sql = "SELECT ProductName, ProductId, COUNT(SerialNo) AS AvailQty 
                      FROM tbl_distibute_item_details2 
                      WHERE ProdType='1' AND SellStatus=0 AND SerialNo!='' 
                        AND ProductId='$productId'
                      GROUP BY ProductId";
            } else if ($Roll == 27) {
              $sql = "SELECT ProductName, ProductId, COUNT(SerialNo) AS AvailQty 
                      FROM tbl_distibute_item_details2 
                      WHERE ProdType='1' AND SellStatus=0 AND SerialNo!='' 
                        AND ProductId='$productId' AND StoreInchId='$user_id'
                      GROUP BY ProductId";
            } else {
              $sql = "SELECT ProductName, ProductId, COUNT(SerialNo) AS AvailQty 
                      FROM tbl_distibute_item_details2 
                      WHERE ProdType='1' AND SellStatus=0 AND SerialNo!='' 
                        AND ProductId='$productId' AND StoreExeId='$user_id'
                      GROUP BY ProductId";
            }

            $rowStock = getRecord($sql);
            $availQty = $rowStock ? floatval($rowStock['AvailQty']) : 0;

            // Step 3: Product name fallback
            if ($rowStock && isset($rowStock['ProductName'])) {
              $productName = $rowStock['ProductName'];
            } else {
              $rowProd = getRecord("SELECT ProductName FROM tbl_products WHERE id='$productId'");
              $productName = $rowProd ? $rowProd['ProductName'] : 'Unknown Product';
            }
            
             // Step 4: Already transferred qty (saved earlier)
          $sqlSaved = "SELECT COUNT(id) AS SavedQty 
                       FROM tbl_sell_products 
                       WHERE UserId='$custId' 
                         
                         AND ProductId='$productId' 
                         AND ProdType='1' AND Structure=1";
          $rowSaved = getRecord($sqlSaved);
          $savedQty = floatval($rowSaved['SavedQty'] ?? 0);


// Step 5: Determine stock status with gradient badges
if ($reqQty == $savedQty && $savedQty > 0) {
    // ✅ Already fully transferred
    $status = "
      <span class='badge rounded-pill shadow-sm'
            style='background: linear-gradient(135deg, #1ecb59, #1aa73c);
                   color: #fff; font-size:13px; padding:7px 14px; 
                   letter-spacing: 0.3px; font-weight:600;'>
        ✓ All Transferred
      </span>";
    $disabled = "disabled";
    $rowStyle = "background:linear-gradient(90deg,#e6ffe8,#d7ffd9);";
     $showcheckbox = 0;
}
else {
    if ($availQty == 0) {
        // 🔴 Out of stock
        $status = "
          <span class='badge rounded-pill shadow-sm'
                style='background: linear-gradient(135deg, #ff4e50, #f60000);
                       color: #fff; font-size:13px; padding:7px 14px; 
                       letter-spacing: 0.3px; font-weight:600;'>
            Out of Stock
          </span>";
        $disabled = "disabled";
        $rowStyle = "background:linear-gradient(90deg,#ffeaea,#ffdada);";
        $showcheckbox = 0;
    }
    elseif ($availQty < $reqQty) {
        // 🟠 Partial
        $status = "
          <span class='badge rounded-pill shadow-sm'
                style='background: linear-gradient(135deg, #f6d365, #fda085);
                       color: #333; font-size:13px; padding:7px 14px; 
                       letter-spacing: 0.3px; font-weight:600;'>
            Partial ($availQty)
          </span>";
        $disabled = "";
        $rowStyle = "background:linear-gradient(90deg,#fffbe6,#fff3cc);";
         $showcheckbox = 1;
    }
    else {
        // 🟢 In Stock
        $status = "
          <span class='badge rounded-pill shadow-sm'
                style='background: linear-gradient(135deg, #56ccf2, #2f80ed);
                       color: #fff; font-size:13px; padding:7px 14px; 
                       letter-spacing: 0.3px; font-weight:600;'>
            In Stock
          </span>";
        $disabled = "";
        $rowStyle = "background:linear-gradient(90deg,#f0f9ff,#d6edff);";
         $showcheckbox = 1;
    }
}


        ?>
        <tr style="<?php echo $rowStyle; ?>">
          <td>
              <?php if($showcheckbox == 1){?>
            <label class="custom-control custom-checkbox">
              <input type="checkbox" 
                     class="custom-control-input structureCheckbox" 
                     name="StructureCheckId[]" 
                     value="<?php echo $productId; ?>" 
                     id="structure_<?php echo $productId; ?>"
                     data-prodid="<?php echo $productId; ?>"
                     <?php echo $disabled; ?>>
              <span class="custom-control-label"></span>
            </label><?php } ?>
          </td>
          <td><strong><?php echo htmlspecialchars($productName); ?></strong></td>
          <td><?php echo $reqQty; ?></td>
          <td><?php echo $availQty; ?></td>
          <td><?php echo $savedQty;?></td>
          <td><?php echo $status; ?></td>

          <!-- Hidden inputs for structure item -->
          <input type="hidden" name="StructureProdId[]" value="<?php echo $productId; ?>">
          <input type="hidden" name="StructureProdName[]" value="<?php echo htmlspecialchars($productName); ?>">
          <input type="hidden" name="StructureReqQty[]" value="<?php echo $reqQty; ?>">
          <input type="hidden" name="StructureAvailQty[]" value="<?php echo $availQty; ?>">
          <input type="hidden" name="StructureModelNo[]" value="<?php echo htmlspecialchars($rowStock['ModelNo'] ?? ''); ?>">
        </tr>

        <!-- Optional row for serials if you plan to expand dynamically -->
        <tr id="serials_<?php echo $productId; ?>" class="serials-row" style="display:none; background:#f9f9f9;">
          <td colspan="5" class="p-0">
            <div class="p-2 text-center">
              <span class="text-secondary small">Loading serial numbers...</span>
            </div>
          </td>
        </tr>
        <?php 
            $i++;
          } // end foreach
        } // end else
        ?>
      </tbody>
    </table>
  </div>
</div>







                                               <div class="form-row">
                                                   <div class="d-flex justify-content-between align-items-center mb-2">
  <label class="form-label mb-0" style="font-size: 18px; color: #0dc30d;">
    Serial No Products
  </label>&nbsp;&nbsp;&nbsp;&nbsp;
 <a href="#" class="small text-danger fw-semibold text-decoration-none" 
   data-bs-toggle="modal" data-bs-target="#cartModal" id="viewCartBtn">
   View Selected Items
</a>
</div>
                                                    <div class="col-lg-12">
                                                        <table id='empTable' class="table table-striped table-bordered" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 10px;">#</th>
                                                                    <th width="50%">Product</th>
                                                                    <th>Serial No </th>
                                                                </tr>
                                                            </thead>

                                                        </table>

                                                    </div>
                                                    <input type="hidden" id="Roll" value="<?php echo $Roll; ?>">
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

                                                        <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>

                                                    </div>


                                                </div>
                                            <?php } ?>
                                        </div>




                                    </div>

                            </div>
                        </div>
                        </form>




                    </div>




                </div>
                <!-- [ content ] End -->
                <!-- [ Layout footer ] Start -->


  <style>
                    #cartContent table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto;
  white-space: nowrap;
}

#cartContent th, 
#cartContent td {
  padding: 8px 10px;
  text-align: left;
  vertical-align: middle;
}

#cartContent thead th {
  position: sticky;
  top: 0;
  background-color: #f8f9fa;
  z-index: 2;
}

#cartContent tbody tr:hover {
  background-color: #f1f1f1;
}
@media (max-width: 768px) {
  .modal-dialog {
    width: 95%;
    margin: auto;
  }
}
                </style>
                <!-- Bootstrap Modal -->
<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#ff4500; color:white;">
        <h5 class="modal-title" id="cartModalLabel">Selected Items in Cart</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="cartContent" style="max-height:70vh; overflow-y:auto; overflow-x:auto;">
        <div class="text-center py-3">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading items...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

                <!-- [ Layout footer ] End -->
            </div>
            <!-- [ Layout content ] Start -->
        </div>
        <!-- [ Layout container ] End -->
    </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $SiteUrl; ?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl; ?>/assets/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl; ?>/assets/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?php echo $SiteUrl; ?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>

    <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>


    <!-- Libs -->
    <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <!-- Demo -->
    <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/select2/select2.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="<?php echo $SiteUrl; ?>/assets/js/pages/forms_selects.js"></script>

    <script>
    $(document).ready(function() {
        $(document).on('click', '#viewCartBtn', function (e) {
    e.preventDefault();
    $('#cartContent').html('<div class="text-center py-3"><div class="spinner-border text-primary"></div><p class="mt-2">Loading...</p></div>');
    $.ajax({
        url: 'view_cart.php',
        type: 'GET',
        success: function (response) {
            $('#cartContent').html(response);
        },
        error: function () {
            $('#cartContent').html('<p class="text-danger text-center">Failed to load cart items.</p>');
        }
    });
});
});

    document.querySelectorAll('.structureCheckbox').forEach(cb => {
  cb.addEventListener('change', function() {
    const prodId = this.dataset.prodid;
    const serialRow = document.getElementById('serials_' + prodId);

    if (!serialRow) return;

    if (this.checked) {
      // ✅ Show row and load serials
      serialRow.style.display = '';
      const container = serialRow.querySelector('.p-2');
      container.innerHTML = "<span class='text-secondary small'>Loading serial numbers...</span>";

      fetch('fetch_serials.php?prodId=' + prodId)
        .then(res => res.text())
        .then(html => {
          container.innerHTML = html;
        })
        .catch(() => {
          container.innerHTML = "<span class='text-danger small'>Failed to load serial numbers.</span>";
        });
    } else {
      // ❌ Hide row and uncheck serials
      serialRow.style.display = 'none';
      const inputs = serialRow.querySelectorAll('input[type=checkbox]');
      inputs.forEach(chk => chk.checked = false);
    }
  });
});
 
    document.querySelectorAll('.bagCheckbox').forEach(bag => {
  bag.addEventListener('change', function() {
    const serial = this.dataset.serial;
    const subRow = document.getElementById('subitems_' + serial);

    if (!subRow) return;

    if (this.checked) {
      // Show sub-items
      subRow.style.display = '';

      // Auto-check all sub-items that are not disabled
      subRow.querySelectorAll('.subCheckbox:not(:disabled)').forEach(cb => cb.checked = true);
    } else {
      // Hide sub-items and uncheck all
      subRow.style.display = 'none';
      subRow.querySelectorAll('.subCheckbox').forEach(cb => cb.checked = false);
    }
  });
});
        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true,
                paging: false,
                ordering: false,
                info: false,
                searching: false
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
                    dataType: "json",
                    success: function(data) {

                        $('#Address').val(data.Address);
                        $('#CustName').val(data.Fname);
                        $('#CellNo').val(data.Phone);
                        $('#Gname').val(data.Gname);
                        $('#Gphone').val(data.Gphone);
                        $('#Gname2').val(data.Gname2);
                        $('#Gphone2').val(data.Gphone2);
                        $('#AgentName').val(data.AgentName);
                        $('#ConsigneeName').val(data.Address);
                        $('#ConsigneeAddress').val(data.Fname + " " + data.Lname);
                    }
                });

            });

            $.fn.myFunction = function(Roll) {

                var PageLength = 10;

                $('#empTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url': 'pagination/serial-no-products.php',
                        method: "POST",
                        data: {
                            Roll: Roll
                        },
                    },
                    'columns': [{
                            data: 'id'
                        },
                        {
                            data: 'Product'
                        },
                        {
                            data: 'SerialNo'
                        }


                    ],

                    "pageLength": PageLength,
                    "bDestroy": true,
                    "scrollX": true
                });
            }

            var Roll = $('#Roll').val();
            $.fn.myFunction(Roll);
        });

        function saveCart(id) {
            var action = "saveCart";
            var quantity = 1;
            $.ajax({
                url: "assign-serial-no-challan-session.php",
                type: "POST",
                data: {
                    action: action,
                    quantity: quantity,
                    id: id
                },
                success: function(data) {
                    //alert(data);
                },

            });
        }

        function featured2(id){
if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            //saveCart(id);
        }
        else{
           $('#CheckId'+id).val(0);
           //delete_prod(id);
            }
        }

        function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
                saveCart(id);
            } else {
                $('#CheckId' + id).val(0);
                delete_prod(id);
            }
        }

        function getItem2(CustId) {
            var BranchId = $('#BranchId').val();
            var AgencyId = $('#AgencyId').val();
            var CompId = $('#CompId').val();
            window.location.href = "add-sell.php?action=search&BranchId=" + BranchId + "&CustId=" + CustId + "&CompId=" + CompId + "&AgencyId=" + AgencyId;
        }
        
        
        document.addEventListener('input', function(e) {
  if (e.target && e.target.id.startsWith('searchSerial_')) {
    const input = e.target;
    const prodId = input.id.split('_')[1];
    const tableBody = document.getElementById('serialTableBody_' + prodId);
    if (!tableBody) return;

    const filter = input.value.trim().toLowerCase();
    const rows = tableBody.querySelectorAll('tr');

    rows.forEach(row => {
      const serialCell = row.cells[1];
      if (!serialCell) return;
      const serialText = serialCell.textContent.trim().toLowerCase();
      row.style.display = serialText.includes(filter) ? '' : 'none';
    });
  }
});
    </script>
</body>

</html>