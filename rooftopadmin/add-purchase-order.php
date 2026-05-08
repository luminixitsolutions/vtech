<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "Add-Purchase-Order";
//echo "<pre>";print_r($_SESSION["cart_item"]);

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Raw Stock
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
    <script src="ckeditor/ckeditor.js"></script>
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
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php
                $id = $_GET['id'];
                $sql7 = "SELECT * FROM tbl_rooftop_purchase_order WHERE id='$id'";
                $row7 = getRecord($sql7);


                $sql8 = "SELECT MAX(id) AS MaxId FROM tbl_rooftop_purchase_order";
                $row8 = getRecord($sql8);
                $MaxId = $row8['MaxId'] + 1;
                $Invoice_No = "00" . $MaxId;


                if (isset($_POST['submit'])) {
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
                    $ProductHead = addslashes(trim($_POST['ProductHead']));

                    $CreatedDate = date('Y-m-d');
                    $CreatedTime = date('h:i a');

                    $sql8 = "SELECT MAX(SrNo) AS MaxId FROM tbl_rooftop_purchase_order";
                    $row8 = getRecord($sql8);
                    $MaxId = $row8['MaxId'] + 1;
                    $InvoiceNo = "00" . $MaxId;

                    $sql = "INSERT INTO tbl_rooftop_purchase_order SET CompId='$CompId',SrNo='$MaxId',CustId='$CustId',CustName='$CustName',CellNo='$CellNo',Address='$Address',InvoiceNo='$InvoiceNo',InvoiceDate='$InvoiceDate',PayType='$PayType',Narration='$Narration',ProdType='$ProdType',PayMode='$PayMode',DeliveryDate='$DeliveryDate',GrossAmt='$GrossAmt',CgstPer='$CgstPer',CgstAmt='$CgstAmt',SgstPer='$SgstPer',SgstAmt='$SgstAmt',IgstPer='$IgstPer',IgstAmt='$IgstAmt',SubTotal='$SubTotal',UcdAmt='$UcdAmt',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Discount='$Discount',Total='$Total',ChequeNo='$ChequeNo',ChqDate='$ChqDate',BankName='$BankName',UpiNo='$UpiNo',CreatedTime='$CreatedTime',BranchId='$BranchId',SellType='Purchase',PurchaseSubject='$PurchaseSubject',Ref1='$Ref1',Ref2='$Ref2',Details='$Details',TermsCondition='$TermsCondition',ConsigneeAddress='$ConsigneeAddress',SpecialConditions='$SpecialConditions',ProductHead='$ProductHead'";
                    $conn->query($sql);
                    $SellId = mysqli_insert_id($conn);

                   
                
                    foreach ($_SESSION["cart_item"] as $product) {
                        $ProductName = addslashes(trim($product['ProductName']));
                        $Purity = addslashes(trim($product['Unit']));
                        $Weight = "";
                        $Price = addslashes(trim($product['Price']));
                        $Making = "";
                        $HmCharge = "";
                        $Qty = addslashes(trim($product['Qty']));
                        $TotalRate = addslashes(trim($product['TotalRate']));
                        $ProductId = addslashes(trim($product['id']));
                        $ModelNo = addslashes(trim($product['ModelNo']));

                        $SGST = addslashes(trim($product['SGST']));
                        $CGST = addslashes(trim($product['CGST']));
                        $IGST = addslashes(trim($product['IGST']));

                        $sql22 = "INSERT INTO tbl_rooftop_purchase_order_products SET SGST='$SGST',CGST='$CGST',IGST='$IGST',UserId='$CustId',SellId='$SellId',ProductName='$ProductName',Purity='$Purity',Weight='$Weight',Price='$Price',Making='$Making',HmCharge='$HmCharge',Qty='$Qty',TotalRate='$TotalRate',ProductId='$ProductId',ModelNo='$ModelNo',SellDate='$InvoiceDate'";
                        $conn->query($sql22);
                        $PostId = mysqli_insert_id($conn);
                    }



                    $sql2 = "SELECT MAX(SrNo) as maxid FROM tbl_general_ledger WHERE Type='PW'";
                    $row2 = getRecord($sql2);
                    if ($row2['maxid'] == '') {
                        $SrNo = 1;
                        $Code = "PW" . $SrNo;
                    } else {
                        $SrNo = $row2['maxid'] + 1;
                        $Code = "PW" . $SrNo;
                    }

                    $sql3 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='0',Code='',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='cr',Roll=3,Type='PO',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
                    $conn->query($sql3);

                    $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$Total',CrDr='dr',Roll=3,Type='PW',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
                    $conn->query($sql4);

                    if ($CgstPer > 0) {
                        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$CgstAmt',GstPer='$CgstPer',CrDr='dr',Roll=3,Type='CGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
                        $conn->query($sql4);
                    }
                    if ($SgstPer > 0) {
                        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$SgstAmt',GstPer='$SgstPer',CrDr='dr',Roll=3,Type='SGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
                        $conn->query($sql4);
                    }
                    if ($IgstPer > 0) {
                        $sql4 = "INSERT INTO tbl_general_ledger SET CompId='$CompId',SrNo='$SrNo',Code='$Code',UserId='$CustId',AccountName='$CustName',InvoiceNo='$InvoiceNo',Amount='$IgstAmt',GstPer='$IgstPer',CrDr='dr',Roll=3,Type='IGST',CreatedDate='$CreatedDate',SellId='$SellId',PaymentDate='$InvoiceDate',SellType='Purchase',BranchId='$BranchId'";
                        $conn->query($sql4);
                    }

                    unset($_SESSION["cart_item"]);
                    echo "<script>alert('New Purchase Order Created Successfully!');window.location.href='view-purchase-order.php';</script>";
                }
                unset($_SESSION["cart_item"]);
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add
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
                                                    <select class="select2-demo form-control" name="CompId" id="CompId" required>
                                                        <option selected="" value="">Select Company</option>
                                                        <?php
                                                        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($row7["CompId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-12" style="padding-top:10px;">
                                                    <label class="form-label"> Manufacture<span class="text-danger">*</span></label>
                                                    <select class="select2-demo form-control" name="CustId" id="CustId" required>
                                                        <option selected="" value="">Select Manufacture</option>
                                                        <?php
                                                        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3";
                                                        $row12 = getList($sql12);
                                                        foreach ($row12 as $result) {
                                                        ?>
                                                            <option <?php if ($row7["CustId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                                <?php echo $result['Fname'] . " (" . $result['Phone'] . ")"; ?></option>
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
                                                    <textarea name="Address" id="Address" class="form-control"><?php echo $row7['Address']; ?></textarea>
                                                    <div class="clearfix"></div>
                                                </div>


                                               

                                                <div class="form-group col-lg-4">
                                                    <label class="form-label">Purchase Order No <span class="text-danger">*</span></label>
                                                    <input type="text" name="InvoiceNo" class="form-control" id="InvoiceNo" placeholder="" value="<?php echo $Invoice_No; ?>">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Purchase Order Date </label>
                                                    <input type="date" name="InvoiceDate" id="InvoiceDate" class="form-control"
                                                        placeholder="" value="<?php echo date('Y-m-d'); ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-lg-4">
                                                    <label class="form-label">Product Head <span class="text-danger">*</span></label>
                                                    <input type="text" name="ProductHead" class="form-control" id="ProductHead" placeholder="" value="<?php echo $row7["ProductHead"]; ?>">
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>


                                            <div class="form-row">
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
                                                    <tbody id="dynamic_field">
                                                        <tr>
                                                            <td>


                                                                <select name="ProductId[]" id="ProductId1" onchange="getProdDetails(this.value,document.getElementById('srno1').value)" class="select2-demo form-control">
                                                                    <option value="" selected>Select Product</option>
                                                                    <?php
                                                                    $sql12 = "SELECT * FROM tbl_rooftop_products WHERE Status='1' ORDER BY ProductName";
                                                                    $row12 = getList($sql12);
                                                                    foreach ($row12 as $result) {
                                                                    ?>
                                                                        <option value="<?php echo $result['id']; ?>"><?php echo $result['ProductName']; ?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            </td>
                                                            <input type="hidden" name="ProductName" id="ProductName1" value="">
                                                            <input type="hidden" name="ModelNo" id="ModelNo1" value="">
                                                            <input type="hidden" name="Code" id="Code1" value="">
                                                            <input type="hidden" class="form-control" name="srno" id="srno1" value="1">
                                                            <td><input type="number" name="Qty" id="Qty1" class="form-control" placeholder="e.g.,1" value="1" autocomplete="off" min="1" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)"></td>
                                                            <td><input type="text" name="Purity" id="Purity1" class="form-control" placeholder="" value="" autocomplete="off"></td>

                                                            <td><input type="text" name="Price" id="Price1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" ></td>

                                                            <td><input type="text" name="SGST" id="SGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" ></td>
                                                            <td><input type="text" name="CGST" id="CGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" ></td>
                                                            <td><input type="text" name="IGST" id="IGST1" class="form-control" placeholder="" value="" autocomplete="off" oninput="getProdTotal(document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value)" ></td>
                                                            <td><input type="text" name="TotalRate" id="Total1" class="form-control txt" placeholder="" value="" autocomplete="off" readonly></td>
                                                            <td><button class="btn btn-secondary" type="button"
                                                                    onclick="addToCart(document.getElementById('ProductId1').value,document.getElementById('Code1').value,document.getElementById('ProductName1').value,document.getElementById('ModelNo1').value,document.getElementById('Qty1').value,document.getElementById('Price1').value,document.getElementById('srno1').value,document.getElementById('SGST1').value,document.getElementById('CGST1').value,document.getElementById('IGST1').value,document.getElementById('Total1').value,document.getElementById('Purity1').value)">+</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="form-row">
                                                <div id="displaycart"></div>
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
                                                        <!--<option <?php if ($row7['PayType'] == 'Cash') { ?> selected <?php } ?> value="Cash">Cash</option>
<option <?php if ($row7['PayType'] == 'Cheque') { ?> selected <?php } ?> value="Cheque">Cheque/Bank Transfer</option>
<option <?php if ($row7['PayType'] == 'UPI') { ?> selected <?php } ?> value="UPI">UPI</option>-->
                                                        <option <?php if ($row7['PayType'] == 'NEFT') { ?> selected <?php } ?> value="NEFT">NEFT</option>
                                                        <option <?php if ($row7['PayType'] == 'RTGS') { ?> selected <?php } ?> value="RTGS">RTGS</option>
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
                                                    <textarea name="PurchaseSubject" id="PurchaseSubject" class="form-control"></textarea>
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

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
        function displayCart() {
            var action = "displayCart";
            $.ajax({
                url: "purchase-order-session.php",
                type: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    console.log(data);
                    $('#displaycart').html(data);
                },

            });
        }

        function addToCart(pid, code, pname, modelno, qty, price, srno, sgst, cgst, igst, total, unit) {
            if (pid == '') {
                alert("Please Select Product");
            } else if (qty == '') {
                alert("Please Enter Qty");
            } else {
                var action = "saveCart";
                $.ajax({
                    url: "purchase-order-session.php",
                    type: "POST",
                    data: {
                        action: action,
                        id: pid,
                        code: code,
                        pname: pname,
                        modelno: modelno,
                        quantity: qty,
                        price: price,
                        srno: srno,
                        sgst: sgst,
                        cgst: cgst,
                        igst: igst,
                        total: total,
                        unit: unit
                    },
                    success: function(data) {
                        calTotal();
                        displayCart();
                        console.log(data);
                        $('#ProductId1').val('').attr("selected", true);
                        $('#Qty1').val('');
                        $('#Purity1').val('');
                        $('#Price1').val('');
                        $('#SGST1').val('');
                        $('#CGST1').val('');
                        $('#IGST1').val('');
                        $('#Total1').val('');
                        //alert(data);
                    },
                });
            }
        }

        function delete_prod(code) {
            if (confirm("Are you sure you want to delete Record?")) {
                var action = "delete_shop_prod";
                $.ajax({
                    url: "purchase-order-session.php",
                    type: "POST",
                    data: {
                        action: action,
                        id: code
                    },
                    success: function(data) {
                        console.log(data);
                        displayCart();
                        calTotal();
                    },

                });
            }
        }

        function calTotal(){
            var action = "calculate_total";
                $.ajax({
                    url: "purchase-order-session.php",
                    type: "POST",
                    data: {
                        action: action
                    },
                    success: function(data) {
                        console.log(data);
                        $('#GrossAmt').val(parseFloat(data).toFixed(2));
                        commonTotal();
                    },

                });
        }

        function addVendor() {
            setTimeout(function() {
                window.open(
                    'add-customer2.php', 'stickerPrint',
                    'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=600,left=350,top=40,right=200'
                );
            }, 1);
        }

        function getPayType(val) {
            if (val == 'Cheque') {
                $('.chequeoption').show();
                $('.upioption').hide();
            } else if (val == 'UPI') {
                $('.chequeoption').hide();
                $('.upioption').show();
            } else {
                $('.chequeoption').hide();
                $('.upioption').hide();
            }
        }

        function getSubTotal() {
            var sum = 0;
            $(".txt").each(function() {
                if (!isNaN(this.value) && this.value.length != 0) {
                    sum += parseFloat(this.value);
                }
            });
            $('#GrossAmt').val(parseFloat(sum).toFixed(2));

        }


        function getUserDetails() {
            var CellNo = $('#CellNo').val();
            var action = "getUserDetails2";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    CellNo: CellNo
                },
                dataType: "json",
                success: function(data) {
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.Fname + " " + data.Lname);
                    $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);

                }
            });

        }
        $(document).ready(function() {

            var i = 1;
            $('#add_more').click(function() {
                i++;
                var action = "getCustRow";
                $.ajax({
                    url: "ajax_files/ajax_products.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: i
                    },
                    success: function(data) {
                        $('#dynamic_field').append(data);
                    }
                });
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                if (confirm("Are you sure you want to delete?")) {
                    $('#row' + button_id + '').remove();
                    getSubTotal();
                    commonTotal();
                }
            });


            var i2 = 1;
            $('#add_more2').click(function() {
                i2++;
                var html = '';
                html += '<div class="form-row" id="row' + i2 + '">';

                html += '<div class="form-group col-md-12">';
                html += '<label class="form-label">Ref <span class="text-danger">*</span></label>';
                html += '<div class="input-group">    <label class="custom-file">';
                html += '<input type="text" name="Ref[]" class="form-control" placeholder="" value="" autocomplete="off" ></label>';
                html += '<div class="clearfix"></div>';
                html += '<span class="input-group-append">';
                html += '<button class="btn btn-danger btn_remove" type="button" id="' + i2 + '"><i class="fa fa-times"></i></button>';
                html += '</span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                $('#dynamic_field2').append(html);
            });

            $(document).on('click', '.btn_remove2', function() {
                var button_id2 = $(this).attr("id");
                if (confirm("Are you sure you want to delete?")) {
                    $('#row2' + button_id2 + '').remove();
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
                    dataType: "json",
                    success: function(data) {
                        $('#Address').val(data.Address);
                        $('#CustName').val(data.Fname + " " + data.Lname);
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



        function getBrand(catid) {
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

        function getProd(brandid) {
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

        function getTotal(GrossAmt, CgstPer, SgstPer, IgstPer, SubTotal, Discount) {
            //console.log(qty,vedprice,srno);
            var CgstAmt = Number(GrossAmt) * (Number(CgstPer) / 100);
            var SgstAmt = Number(GrossAmt) * (Number(SgstPer) / 100);
            var IgstAmt = Number(GrossAmt) * (Number(IgstPer) / 100);
            $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
            $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
            $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
            var SubTotal = Number(GrossAmt) + Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
            $('#SubTotal').val(parseFloat(SubTotal).toFixed(2));
            var Total = Number(SubTotal) - Number(Discount);
            $('#Total').val(parseFloat(Total).toFixed(2));
        }

        function commonTotal() {
            var GrossAmt = $('#GrossAmt').val();
            var CgstPer = $('#CgstPer').val();
            var SgstPer = $('#SgstPer').val();
            var IgstPer = $('#IgstPer').val();
            var SubTotal = $('#SubTotal').val();
            var UcdAmt = 0;
            var Discount = $('#Discount').val();
            getTotal(GrossAmt, CgstPer, SgstPer, IgstPer, SubTotal, Discount);
        }

        function getProdTotal(qty, price, srno, sgst, cgst, igst) {
            //console.log(qty,price,srno,sgst,cgst,igst);
            var Total = (Number(qty) * Number(price));
            var CgstAmt = Number(Total) * (Number(cgst) / 100);
            var SgstAmt = Number(Total) * (Number(sgst) / 100);
            var IgstAmt = Number(Total) * (Number(igst) / 100);
            var prdTotal = Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt) + Number(Total);
            $('#Total' + srno).val(parseFloat(prdTotal).toFixed(2));
            getSubTotal();
            commonTotal();
        }

        function getProdDetails(val, srno) {
            var qty = $('#Qty' + srno).val();
            var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType: "json",
                success: function(data) {

                    $('#ProductName' + srno).val(data.ProductName);
                    $('#ModelNo' + srno).val(data.ModelNo);
                    $('#Purity' + srno).val(data.Unit);
                    $('#Code' + srno).val(data.Code);
                    $('#Price' + srno).val(data.Price);
                    $('#SGST' + srno).val(data.SGST);
                    $('#CGST' + srno).val(data.CGST);
                    $('#IGST' + srno).val(data.IGST);
                    getProdTotal(qty, data.Price, srno, data.SGST, data.CGST, data.IGST);
                }
            });
        }
    </script>
</body>

</html>