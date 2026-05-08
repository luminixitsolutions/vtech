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


$sql = "SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE code is null";
$row = getList($sql);
foreach ($row as $result) {
    $n = 10;
    $Code = RandomStringGenerator($n);
    $Code2 = $Code . "" . $result['id'];
    $sql2 = "UPDATE tbl_rooftop_distibute_item_details2 SET code='$Code2' WHERE id='" . $result['id'] . "'";
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


                        $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_rooftop_sell";
                        $row8 = getRecord($sql8);
                        $MaxId = $row8['MaxId'] + 1;
                        $Invoice_No = "00" . $MaxId;
                        if (isset($_POST['submit'])) {
                            $AgencyId = $_POST['AgencyId'];
                            $CompId = $_POST['CompId'];
                            $CustId = $_POST['CustId'];
                            $CustName = addslashes(trim($_POST['CustName']));
                            $CellNo = addslashes(trim($_POST['CellNo']));
                            $Address = addslashes(trim($_POST['Address']));

                            //$InvoiceNo = addslashes(trim($_POST['InvoiceNo']));
                            $InvoiceDate = addslashes(trim($_POST['InvoiceDate']));

                            $PayType = addslashes(trim($_POST['PayType']));

                            $Narration = addslashes(trim($_POST['Narration']));
                            $MaterialDispatchStatus = addslashes(trim($_POST['MaterialDispatchStatus']));

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
                            $ProjectCode = addslashes(trim($_POST['ProjectCode']));
                            $DriverId = addslashes(trim($_POST['DriverId']));
                            $CreatedDate = date('Y-m-d');
                            $CreatedTime = date('h:i a');

                            $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_rooftop_sell";
                            $row8 = getRecord($sql8);
                            $MaxId = $row8['MaxId'] + 1;
                            $InvoiceNo = "00" . $MaxId;

                            $sql = "INSERT INTO tbl_rooftop_sell SET ProjectCode='$ProjectCode',CompId='$CompId',AgencyId='$AgencyId',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Challan',WarrantyPeriod='$WarrantyPeriod',PayStatus='$PayStatus',LrNo='$LrNo',LrDate='$LrDate',Transport='$Transport',ConsigneeName='$ConsigneeName',ConsigneeAddress='$ConsigneeAddress',SiteEngineerName='$SiteEngineerName',SiteEngineerContactNo='$SiteEngineerContactNo',SiteManagerName='$SiteManagerName',SiteManagerContactNo='$SiteManagerContactNo',Weight='$Weight',DriverId='$DriverId',MaterialDispatchStatus='$MaterialDispatchStatus'";
                            $conn->query($sql);
                            $SellId = mysqli_insert_id($conn);

                            $number = count($_POST["CheckId"]);
                            if ($number != '') {
                                if ($number > 0) {
                                    for ($i = 0; $i < $number; $i++) {
                                        if (trim($_POST["CheckId"][$i] != '')) {
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
                                            if ($CheckId == 1) {
                                                $sql22 = "INSERT INTO tbl_rooftop_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId'";
                                                $conn->query($sql22);
                                                $PostId = mysqli_insert_id($conn);

                                                $sql22 = "INSERT INTO tbl_rooftop_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='0'";
                                                $conn->query($sql22);
                                            }
                                        }
                                    }
                                }
                            }

                            foreach ($_SESSION["cart_item"] as $product) {
                                $ProductId = $product['id'];
                                $ProductName = $product['ProductName'];
                                $Purity = $product['Unit'];
                                $SerialNo = $product['SerialNo'];
                                $ModelNo = $product['ModelNo'];
                                $sql22 = "INSERT INTO tbl_rooftop_sell_products SET UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='1',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate',SerialNo='$SerialNo',BranchId='$BranchId'";
                                $conn->query($sql22);
                                $PostId = mysqli_insert_id($conn);

                                $sql22 = "INSERT INTO tbl_rooftop_stocks SET SellId='$SellId',ProductId='$ProductId',ProductName='$ProductName',Qty='1',Status='1',CrDr='dr',CreatedBy='$user_id',CreatedDate='$InvoiceDate',Narration='$Narration',PostId='$PostId',BranchId='$BranchId',SellType='Challan',SerialNo='$SerialNo',ModelNo='$ModelNo',ProdType='1'";
                                $conn->query($sql22);

                                $sql33 = "UPDATE tbl_rooftop_distibute_item_details2 SET SellId='$SellId',SellStatus=1 WHERE id='$ProductId'";
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
                                                            $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
                                                        } else if ($Roll == 26) {
                                                            $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='" . $_SESSION['storeid'] . "'";
                                                        } else {
                                                            $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='$RooftopBranchId'";
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
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.Roll=5 AND tu.ProjectType=2";
                                                        } else if ($Roll == 26) {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=2 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id' AND tu.BranchId='" . $_SESSION['storeid'] . "'";
                                                        } else {
                                                            $sql12 = "SELECT tu.id,tu.Fname,tu.Lname,tu.Phone FROM tbl_users tu WHERE tu.DispatchOfficerStatus=1 AND tu.ProjectType=2 AND tu.Roll=5 AND tu.DispatchOfficerId='$user_id'";
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

                                            <?php if($_REQUEST['action'] == 'search'){?>
<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
  <div class="col-lg-12"> 
<table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th>#</th>
        <th width="30%">Product</th>
       <!--  <th>Serial No </th> -->
        <th>Stock Qty </th>
        <th>Assign Qty </th>
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
        /*$sql12 = "SELECT tcp.*,tp.ProductName AS Product_Name,tp.ModelNo AS Model_No,tp.Unit FROM tbl_cust_product_specification tcp 
                  INNER JOIN tbl_rooftop_products tp ON tcp.ProdId=tp.id 
                  WHERE tcp.CustId='".$_GET['CustId']."' AND tp.Roll=0";*/
                  $sql12 = "SELECT tcp.* FROM tbl_rooftop_products tcp WHERE tcp.Roll='0'";
       $rncnt2 = getRow($sql12);           
        $row12 = getList($sql12);
        foreach($row12 as $result){

            $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_rooftop_distibute_item_details2 WHERE ProductId='".$result['id']."'";
    $row11 = getRecord($sql11);
    $CrQty = $row11['CrQty'];

   $sql12 = "SELECT COALESCE(SUM(Qty), 0) AS DrQty FROM tbl_rooftop_stocks WHERE CrDr='dr' AND ProductId='".$result['id']."'";
    $row12 = getRecord($sql12);
    $DrQty = $row12['DrQty'];

    $BalQty = $CrQty - $DrQty;
    // $Qty = $result['Qty'];
    // if($BalQty >= $Qty){
    //     $bgcolor = "";
    //     $checkstatus="checked";
    //     $disabled = "";
    //     $checkval = 1;
    // }
    // else{
    //     $bgcolor = "background-color: #fbe9e9;";
    //     $nostock+=1;
    //     $checkstatus="";
    //     $disabled = "disabled";
    //     $checkval = 0;
        
    // }

    $sql22 = "SELECT COALESCE(SUM(Qty), 0) AS DelQty FROM tbl_rooftop_sell_products WHERE UserId='".$_GET['CustId']."' AND ProductId='".$result['id']."'";
    $row22 = getRecord($sql22);
    $DeliveredQty = $row22['DelQty'];
    // if($DeliveredQty == $Qty){}
    //     else{
if($BalQty > 0){
    ?>
     <tr style="<?php echo $bgcolor;?>">
         <td>
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured2(<?php echo $result['id']; ?>)" <?php echo $checkstatus." ".$disabled;?>>
                    <span class="custom-control-label">&nbsp;</span>
                 </label></td>
                 <input type="hidden" value="<?php echo $checkval;?>" name="CheckId22[]" id="CheckId<?php echo $result['id']; ?>">
        <td><?php echo $result['ProductName'];?></td>
        <input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result['id'];?>">
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">

 <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
       <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result['ProductName'];?>'>
        <input type="hidden" name="SerialNo[]" id="SerialNo1" value='N/A'>
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result['ModelNo'];?>">
<td><input type="text" name="BalQty[]" id="BalQty1" class="form-control" placeholder="e.g.,1" value="<?php echo $BalQty;?>" autocomplete="off" readonly style="width:100px;"></td>
<td><?php echo $DeliveredQty;?></td>
<td><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="<?php echo $result['Qty'];?>" autocomplete="off" min="0" style="width:100px;"></td>
        <td><input type="text" name="Purity[]" id="Purity1" class="form-control" placeholder="" value="<?php echo $result['Unit'];?>" autocomplete="off" style="width:100px;"></td>
      


     </tr>
            <?php } } ?>
     </tbody>

    
    </table>
    </div>
</div>

                                                <div class="form-row">
                                                    <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Serial No Products</label>
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

                <!-- [ Layout footer ] End -->
            </div>
            <!-- [ Layout content ] Start -->
        </div>
        <!-- [ Layout container ] End -->
    </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.min.js"></script>
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
    </script>
</body>

</html>