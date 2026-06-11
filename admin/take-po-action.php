<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-po-assignment-activity-log.php';
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Purchase-Order";
$Page = "View-Purchase-Order";

$id = $_GET['id'];
$sql7 = "SELECT tpo.*,tu.Fname FROM tbl_purchase_order tpo LEFT JOIN tbl_users tu ON tpo.EmpId=tu.id WHERE tpo.id='$id'";
$row7 = getRecord($sql7);
$InvoiceNo = $row7['InvoiceNo'];

function po_apply_complete($row)
{
    return intval(isset($row['ApplyStatus']) ? $row['ApplyStatus'] : 0) === 1;
}

function po_send_complete($row)
{
    return intval(isset($row['SendStatus']) ? $row['SendStatus'] : 0) === 1;
}

function po_receive_complete($row)
{
    return intval(isset($row['ReceiveStatus']) ? $row['ReceiveStatus'] : 0) === 1;
}

function po_delivery_steps_complete($row)
{
    return po_apply_complete($row) && po_send_complete($row) && po_receive_complete($row);
}

function po_delivered_complete($row)
{
    return intval(isset($row['DeliveredStatus']) ? $row['DeliveredStatus'] : 0) === 1;
}

function po_mark_purchase_order_delivered($conn, $poId, $deliveredDate, $billNo, $customerId, $branchId, $vehicalDate, $vehicalNo)
{
    $poId = intval($poId);
    $DeliveredTime = date('H:i:s');
    $DeliveredDate = mysqli_real_escape_string($conn, (string) $deliveredDate);
    $BillNo = mysqli_real_escape_string($conn, (string) $billNo);
    $CustomerId = mysqli_real_escape_string($conn, (string) $customerId);
    $BranchId = mysqli_real_escape_string($conn, (string) $branchId);
    $VehicalDate = mysqli_real_escape_string($conn, (string) $vehicalDate);
    $VehicalNo = mysqli_real_escape_string($conn, (string) $vehicalNo);
    $sql = "UPDATE tbl_purchase_order SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',DeliveredDate='$DeliveredDate',DeliveredTime='$DeliveredTime',DeliveredStatus=1,BranchId='$BranchId',BillNo='$BillNo' WHERE id='$poId'";
    return $conn->query($sql);
}

function po_is_allowed_excel_upload($file)
{
    if (empty($file['name'])) {
        return false;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['xls', 'xlsx', 'csv'], true)) {
        return true;
    }
    $allowedTypes = [
        'application/vnd.ms-excel',
        'text/xls',
        'text/xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/octet-stream',
        'application/zip',
        'text/csv',
        'application/csv',
    ];
    return !empty($file['type']) && in_array($file['type'], $allowedTypes, true);
}

$poSendUnlocked = po_apply_complete($row7);
$poReceiveUnlocked = po_send_complete($row7);
$poDeliveryUnlocked = po_delivery_steps_complete($row7);
$poStoreAssignUnlocked = po_delivered_complete($row7);
$poSerialStockCount = getRow("SELECT id FROM tbl_stocks WHERE SellId='".intval($id)."' AND ProdType='1' AND SellType='Purchase'");

/**
 * Whether this PO stock line already exists in tbl_distibute_item_details and which store (branch) it was assigned to.
 *
 * @return array{assigned: bool, store_name: string}
 */
function po_line_store_assignment_info($conn, $stk, $poVehicalDate, $poVehicalNo)
{
    $empty = ['assigned' => false, 'store_name' => ''];
    $pid = mysqli_real_escape_string($conn, (string) $stk['ProductId']);
    $vd = mysqli_real_escape_string($conn, (string) $poVehicalDate);
    $vn = mysqli_real_escape_string($conn, (string) $poVehicalNo);
    $pt = isset($stk['ProdType']) ? intval($stk['ProdType']) : 0;

    if ($pt === 1 || $pt === 2) {
        $sn = isset($stk['SerialNo']) ? trim((string) $stk['SerialNo']) : '';
        if ($sn === '' || strcasecmp($sn, 'N/A') === 0) {
            return $empty;
        }
        $snEsc = mysqli_real_escape_string($conn, $sn);
        $sql = "SELECT d.id, tb.Name AS StoreName FROM tbl_distibute_item_details d LEFT JOIN tbl_branch tb ON d.BranchId = tb.id WHERE d.ProductId='$pid' AND d.SerialNo='$snEsc' AND (d.ProdType='1' OR d.ProdType='2' OR d.ProdType=1 OR d.ProdType=2) LIMIT 1";
        $r = getRecord($sql);
        if (is_array($r) && !empty($r['id'])) {
            $name = isset($r['StoreName']) ? trim((string) $r['StoreName']) : '';
            return ['assigned' => true, 'store_name' => $name];
        }
        return $empty;
    }

    $qty = floatval($stk['Qty']);
    if ($qty <= 0) {
        return $empty;
    }
    $mn = isset($stk['ModelNo']) ? trim((string) $stk['ModelNo']) : '';
    $mnEsc = mysqli_real_escape_string($conn, $mn);
    $qtyLit = mysqli_real_escape_string($conn, (string) $qty);
    $qtyClause = "ABS(CAST(d.Qty AS DECIMAL(18,6)) - CAST('$qtyLit' AS DECIMAL(18,6))) < 0.000001";
    $sql = "SELECT d.id, tb.Name AS StoreName FROM tbl_distibute_item_details d LEFT JOIN tbl_branch tb ON d.BranchId = tb.id WHERE d.ProductId='$pid' AND d.VehicalDate='$vd' AND d.VehicalNo='$vn' AND $qtyClause AND (d.ProdType='0' OR d.ProdType=0 OR d.ProdType IS NULL OR d.ProdType='')";
    if ($mn !== '') {
        $sql .= " AND d.ModelNo='$mnEsc'";
    }
    $r = getRecord($sql);
    if (is_array($r) && !empty($r['id'])) {
        $name = isset($r['StoreName']) ? trim((string) $r['StoreName']) : '';
        return ['assigned' => true, 'store_name' => $name];
    }
    return $empty;
}

/**
 * @return array{rows: array, blocked: bool, has_transferable: bool}
 */
function po_collect_po_stock_preview($conn, $poId, $poRow)
{
    $rows = [];
    $hasTransferable = false;
    $poId = intval($poId);
    $vd = isset($poRow['VehicalDate']) ? $poRow['VehicalDate'] : '';
    $vn = isset($poRow['VehicalNo']) ? $poRow['VehicalNo'] : '';

    $sqlStk = "SELECT ts.*, tp.Unit AS TPUnit FROM tbl_stocks ts LEFT JOIN tbl_products tp ON ts.ProductId = tp.id WHERE ts.SellId='$poId' AND ts.SellType='Purchase' ORDER BY ts.id ASC";
    $resStk = $conn->query($sqlStk);
    if (!$resStk) {
        return ['rows' => [], 'blocked' => true, 'has_transferable' => false];
    }
    while ($stk = $resStk->fetch_assoc()) {
        $qty = floatval($stk['Qty']);
        if ($qty <= 0) {
            continue;
        }
        $pt = isset($stk['ProdType']) ? intval($stk['ProdType']) : 0;
        $asgInfo = po_line_store_assignment_info($conn, $stk, $vd, $vn);
        $assigned = !empty($asgInfo['assigned']);
        if (!$assigned) {
            $hasTransferable = true;
        }
        $typeLabel = ($pt === 1 || $pt === 2) ? 'Serial' : 'Regular';
        $serialDisp = isset($stk['SerialNo']) ? $stk['SerialNo'] : '';
        $rows[] = [
            'stk' => $stk,
            'stock_id' => intval($stk['id']),
            'qty' => $qty,
            'type_label' => $typeLabel,
            'prod_type' => $pt,
            'assigned' => $assigned,
            'assigned_store_name' => isset($asgInfo['store_name']) ? $asgInfo['store_name'] : '',
            'serial_disp' => $serialDisp,
        ];
    }
    return ['rows' => $rows, 'blocked' => !$hasTransferable, 'has_transferable' => $hasTransferable];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title."-PO-".$InvoiceNo; ?>
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

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

    fieldset legend {
        background: inherit;
        font-family: "Lato", sans-serif;
        color: #650812;
        font-size: 15px;
        left: 10px;
        padding: 0 10px;
        position: absolute;
        top: -12px;
        font-weight: 400;
        width: auto !important;
        border: none !important;
    }

    fieldset {
        background: #ffffff;
        border: 1px solid #4FAFB8;
        border-radius: 5px;
        margin: 20px 0 1px 0;
        padding: 20px;
        position: relative;
    }


.bs-vertical-wizard {
    border-right: 1px solid #eaecf1;
    padding-bottom: 50px;
}

.bs-vertical-wizard ul {
    margin: 0;
    padding: 0;
    list-style: none;
}

.bs-vertical-wizard ul>li {
    display: block;
    position: relative;
}

.bs-vertical-wizard ul>li>a {
    display: block;
    padding: 10px 10px 10px 40px;
    color: #333c4e;
    font-size: 17px;
    font-weight: 400;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li>a:before {
    content: '';
    position: absolute;
    width: 1px;
    height: calc(100% - 25px);
    background-color: #bdc2ce;
    left: 13px;
    bottom: -9px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .ico {
    pointer-events: none;
    font-size: 14px;
    position: absolute;
    left: 10px;
    top: 15px;
    z-index: 2;
}

.bs-vertical-wizard ul>li>a:after {
    content: '';
    position: absolute;
    border: 2px solid #bdc2ce;
    border-radius: 50%;
    top: 14px;
    left: 6px;
    width: 16px;
    height: 16px;
    z-index: 3;
}

.bs-vertical-wizard ul>li>a .desc {
    display: block;
    color: #bdc2ce;
    font-size: 11px;
    font-weight: 400;
    line-height: 1.8;
    letter-spacing: .8px;
}

.bs-vertical-wizard ul>li.complete>a:before {
    background-color: #5cb85c;
    opacity: 1;
    height: calc(100% - 25px);
    bottom: -9px;
}

.bs-vertical-wizard ul>li.complete>a:after {display:none;}
.bs-vertical-wizard ul>li.locked>a:after {display:none;}
.bs-vertical-wizard ul>li:last-child>a:before {display:none;}

.bs-vertical-wizard ul>li.complete>a .ico {
    left: 8px;
}

.bs-vertical-wizard ul>li>a .ico.ico-green {
    color: #5cb85c;
}

.bs-vertical-wizard ul>li>a .ico.ico-muted {
    color: #bdc2ce;
}

.bs-vertical-wizard ul>li.current {
    background-color: #fff;
}

.bs-vertical-wizard ul>li.current>a:before {
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current>a:after {
    border-color: #ffe357;
    background-color: #ffe357;
    opacity: 1;
}

.bs-vertical-wizard ul>li.current:after, .bs-vertical-wizard ul>li.current:before {
    left: 100%;
    top: 50%;
    border: solid transparent;
    content: " ";
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
}

.bs-vertical-wizard ul>li.current:after {
    border-color: rgba(255,255,255,0);
    border-left-color: #fff;
    border-width: 10px;
    margin-top: -10px;
}

.bs-vertical-wizard ul>li.current:before {
    border-color: rgba(234,236,241,0);
    border-left-color: #eaecf1;
    border-width: 11px;
    margin-top: -11px;
}

.po-regular-products-table .po-qty-col {
    min-width: 110px;
    width: 110px;
    white-space: nowrap;
}
.po-regular-products-table .po-qty-input {
    min-width: 100px;
    width: 100px;
    text-align: left;
}
.po-table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
}
.po-regular-products-table {
    min-width: 960px;
    margin-bottom: 0;
}
.po-regular-products-table .po-product-col {
    min-width: 320px;
}

    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php 


if(isset($_POST['submit'])){
    $EmpId = $_POST['EmpId'];
    $ApplyDate = $_POST['ApplyDate'];
    $ApplyTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET EmpId='$EmpId',ApplyDate='$ApplyDate',ApplyTime='$ApplyTime',ApplyStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Applied Successfully');window.location.href='take-po-action.php?id=$id';</script>";
    exit;
}

if(isset($_POST['submit2'])){
    if (!po_apply_complete($row7)) {
        echo "<script>alert('Please complete Apply Order first.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
    $SendDate = $_POST['SendDate'];
    $SendTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET SendDate='$SendDate',SendTime='$SendTime',SendStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Send To Manufacture Successfully');window.location.href='take-po-action.php?id=$id';</script>";
    exit;
}

if(isset($_POST['submit3'])){
    if (!po_send_complete($row7)) {
        echo "<script>alert('Please complete Send Order Details first.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
    $ReceiveDate = $_POST['ReceiveDate'];
    $ReceiveTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET ReceiveDate='$ReceiveDate',ReceiveTime='$ReceiveTime',ReceiveStatus=1 WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Order Receive From Manufacture Successfully');window.location.href='take-po-action.php?id=$id';</script>";
    exit;
}

if(isset($_POST['submit4'])){
    if (!po_delivery_steps_complete($row7)) {
        echo "<script>alert('Please complete Apply Order, Send Order Details and Received Order Details before delivering the order.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
    $CustomerId = isset($_POST['CustomerId']) ? $_POST['CustomerId'] : (isset($row7['CustomerId']) ? $row7['CustomerId'] : '');
    $DeliveredDate = isset($_POST['DeliveredDate']) ? trim($_POST['DeliveredDate']) : '';
    $VehicalDate = isset($_POST['VehicalDate']) ? trim($_POST['VehicalDate']) : '';
    $VehicalNo = isset($_POST['VehicalNo']) ? trim($_POST['VehicalNo']) : '';
    $BillNo = isset($_POST['BillNo']) ? addslashes(trim($_POST['BillNo'])) : '';
    if ($DeliveredDate === '' || $VehicalDate === '' || $VehicalNo === '' || $BillNo === '') {
        echo "<script>alert('Please fill all required fields: Delivered Date, Vehicle Date, Vehicle No and Bill No.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
    $BranchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : (isset($row7['BranchId']) ? $row7['BranchId'] : '');
    $DeliveredTime = date('H:i:s');
    $sql = "UPDATE tbl_purchase_order SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',DeliveredDate='$DeliveredDate',DeliveredTime='$DeliveredTime',DeliveredStatus=1,BranchId='$BranchId',BillNo='$BillNo' WHERE id='$id'";
    $conn->query($sql);

$sql = "DELETE FROM tbl_stocks WHERE SellId='$id' AND ProdType='0' AND SellType='Purchase'";
$conn->query($sql);

    $number = isset($_POST['ProductId']) && is_array($_POST['ProductId']) ? count($_POST['ProductId']) : 0;
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if (isset($_POST['ProductId'][$i]) && trim((string) $_POST['ProductId'][$i]) !== '')  
              {
                $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                $Qty = addslashes(trim($_POST['Qty'][$i]));
                $ProductId = addslashes(trim($_POST['ProductId'][$i]));
                $CompId = addslashes(trim($_POST['CompId'][$i]));
                //$BranchId = addslashes(trim($_POST['BranchId'][$i]));
                $PostId = addslashes(trim($_POST['PostId'][$i]));
                $SerialNo = addslashes(trim($_POST['SerialNo'][$i]));
                $ModelNo = addslashes(trim($_POST['ModelNo'][$i]));
                $SrNo = addslashes(trim($_POST['SrNo'][$i]));
                $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                $UnitIns = isset($_POST['Unit'][$i]) ? trim((string) $_POST['Unit'][$i]) : '';
                if ($UnitIns === '') {
                    $UnitIns = '-';
                }
                $UnitIns = addslashes($UnitIns);

                
                $sql22 = "INSERT INTO tbl_stocks SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',CompId='$CompId',SellId='$id',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$DeliveredDate',Narration='Stock Added',PostId='$PostId',BranchId='$BranchId',FromBranchId='$BranchId',SellType='Purchase',SerialNo='$SerialNo',ModelNo='$ModelNo',SrNo='$SrNo',ProdType='0',BillNo='$BillNo',Unit='$UnitIns',BagId='0',Structure='0',BuyStatus='0'";
                $conn->query($sql22);

            }
        }
    }
    echo "<script>alert('Order Delivered to Customer Successfully');window.location.href='take-po-action.php?id=$id';</script>";
    exit;
}

if(isset($_POST['submit5'])){
    if (!po_delivery_steps_complete($row7)) {
        echo "<script>alert('Please complete Apply Order, Send Order Details and Received Order Details before delivering the order.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
    $CustomerId = isset($_POST['CustomerId']) ? $_POST['CustomerId'] : (isset($row7['CustomerId']) ? $row7['CustomerId'] : '');
    $DeliveredDate = isset($_POST['DeliveredDate']) ? trim($_POST['DeliveredDate']) : '';
    $BranchId = isset($_POST['BranchId']) ? $_POST['BranchId'] : (isset($row7['BranchId']) ? $row7['BranchId'] : '');
    $BillNo = isset($_POST['BillNo']) ? trim($_POST['BillNo']) : '';
    if ($DeliveredDate === '' || $BillNo === '') {
        echo "<script>alert('Please fill all required fields: Delivered Date and Bill No.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }

    $BillNoEsc = addslashes($BillNo);
    $hasExistingSerialStocks = getRow("SELECT id FROM tbl_stocks WHERE SellId='$id' AND ProdType='1' AND SellType='Purchase' LIMIT 1") > 0;
    $hasUploadedFile = !empty($_FILES['file']['name']) && isset($_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK;

    if (!$hasUploadedFile && !$hasExistingSerialStocks) {
        echo "<script>alert('Please upload Excel file for serial products.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }

    $importedCount = 0;
    $skippedSerials = [];

    if ($hasUploadedFile) {
        if (!po_is_allowed_excel_upload($_FILES['file'])) {
            echo "<script>alert('Invalid File Type. Upload Excel File (.xls, .xlsx or .csv).');window.location.href='take-po-action.php?id=$id';</script>";
            exit;
        }

        $targetPath = '../uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);
        $sheetCount = count($Reader->sheets());
        $sql = "DELETE FROM tbl_stocks WHERE SellId='$id' AND ProdType='1' AND SellType='Purchase'";
        $conn->query($sql);
        $seenInFile = [];
        for ($i = 0; $i < $sheetCount; $i++) {
            $Reader->ChangeSheet($i);
            foreach ($Reader as $Row) {
                $SrNo = isset($Row[0]) ? mysqli_real_escape_string($conn, $Row[0]) : '';
                $ProductId = isset($Row[1]) ? mysqli_real_escape_string($conn, $Row[1]) : '';
                $ProductName = isset($Row[2]) ? mysqli_real_escape_string($conn, $Row[2]) : '';
                $SerialNo = isset($Row[3]) ? mysqli_real_escape_string($conn, $Row[3]) : '';
                $Unit = isset($Row[4]) ? mysqli_real_escape_string($conn, $Row[4]) : '';
                $Qty = isset($Row[5]) ? mysqli_real_escape_string($conn, $Row[5]) : '';
                $ModelNo = isset($Row[6]) ? mysqli_real_escape_string($conn, $Row[6]) : '';
                $CompId = isset($Row[7]) ? mysqli_real_escape_string($conn, $Row[7]) : '';
                $PostId = isset($Row[8]) ? mysqli_real_escape_string($conn, $Row[8]) : '';
                $VehicalDate = isset($Row[9]) ? mysqli_real_escape_string($conn, $Row[9]) : '';
                $VehicalNo = isset($Row[10]) ? mysqli_real_escape_string($conn, $Row[10]) : '';
                $OemVedName = isset($Row[11]) ? mysqli_real_escape_string($conn, $Row[11]) : '';

                if (!empty($SrNo) || !empty($ProductId) || !empty($ProductName) || !empty($SerialNo) || !empty($Qty) || !empty($ModelNo) || !empty($CompId) || !empty($PostId)) {
                    $shouldInsert = true;
                    $serialPlain = isset($Row[3]) ? trim((string) $Row[3]) : '';
                    if ($serialPlain !== '' && strcasecmp($serialPlain, 'N/A') !== 0 && strcasecmp($serialPlain, 'Serial No') !== 0 && strcasecmp($serialPlain, 'SerialNo') !== 0) {
                        $serialKey = strtoupper($serialPlain);
                        if (isset($seenInFile[$serialKey])) {
                            $skippedSerials[] = $serialPlain;
                            $shouldInsert = false;
                        } else {
                            $snChk = mysqli_real_escape_string($conn, $serialPlain);
                            $chkSql = "SELECT id FROM tbl_stocks WHERE ProdType='1' AND SerialNo!='' AND SerialNo!='N/A' AND SerialNo='$snChk' LIMIT 1";
                            if (getRow($chkSql) > 0) {
                                $skippedSerials[] = $serialPlain;
                                $shouldInsert = false;
                            } else {
                                $seenInFile[$serialKey] = true;
                            }
                        }
                    }

                    if ($shouldInsert) {
                        $UnitSql = ($Unit !== '') ? $Unit : mysqli_real_escape_string($conn, '-');
                        $sql22 = "INSERT INTO tbl_stocks SET VehicalDate='$VehicalDate',VehicalNo='$VehicalNo',CustomerId='$CustomerId',CompId='$CompId',SellId='$id',ProductId='$ProductId',ProductName='$ProductName',Qty='$Qty',Status='1',CrDr='cr',CreatedBy='$user_id',CreatedDate='$DeliveredDate',Narration='Stock Added',PostId='$PostId',BranchId='$BranchId',FromBranchId='$BranchId',SellType='Purchase',SerialNo='$SerialNo',ModelNo='$ModelNo',SrNo='$SrNo',ProdType='1',Unit='$UnitSql',BillNo='$BillNoEsc',OemVedName='$OemVedName',BagId='0',Structure='0',BuyStatus='0'";
                        $conn->query($sql22);
                        $importedCount++;
                    }
                }
            }
        }

        $conn->query("DELETE FROM tbl_stocks WHERE SrNo='SrNo' AND ProdType='1'");
        $hasExistingSerialStocks = getRow("SELECT id FROM tbl_stocks WHERE SellId='$id' AND ProdType='1' AND SellType='Purchase' LIMIT 1") > 0;
        if (!$hasExistingSerialStocks) {
            $skippedUnique = array_values(array_unique($skippedSerials));
            $alertMsg = !empty($skippedUnique)
                ? 'No serial numbers were imported. Existing serial(s) were skipped.'
                : 'No serial numbers were imported from Excel.';
            if (!empty($skippedUnique)) {
                $alertMsg .= "\n\nThe following serial number(s) were NOT imported because they already exist:\n" . implode("\n", $skippedUnique);
            }
            echo "<script>alert(" . json_encode($alertMsg) . ");window.location.href='take-po-action.php?id=$id';</script>";
            exit;
        }
    }

    $VehicalDate = isset($row7['VehicalDate']) ? trim((string) $row7['VehicalDate']) : '';
    $VehicalNo = isset($row7['VehicalNo']) ? trim((string) $row7['VehicalNo']) : '';
    $stkRow = getRecord("SELECT VehicalDate, VehicalNo FROM tbl_stocks WHERE SellId='$id' AND SellType='Purchase' AND ProdType='1' ORDER BY id ASC LIMIT 1");
    if (is_array($stkRow)) {
        if ($VehicalDate === '' && !empty($stkRow['VehicalDate'])) {
            $VehicalDate = trim((string) $stkRow['VehicalDate']);
        }
        if ($VehicalNo === '' && !empty($stkRow['VehicalNo'])) {
            $VehicalNo = trim((string) $stkRow['VehicalNo']);
        }
    }

    po_mark_purchase_order_delivered($conn, $id, $DeliveredDate, $BillNo, $CustomerId, $BranchId, $VehicalDate, $VehicalNo);
    $conn->query("UPDATE tbl_stocks SET BillNo='$BillNoEsc',CreatedDate='$DeliveredDate' WHERE SellId='$id' AND ProdType='1' AND SellType='Purchase'");

    if ($hasUploadedFile) {
        $skippedUnique = array_values(array_unique($skippedSerials));
        if ($importedCount > 0) {
            $alertMsg = 'Serial products delivered successfully. ' . $importedCount . ' serial line(s) imported.';
        } else {
            $alertMsg = 'Serial products delivered successfully.';
        }
        if (!empty($skippedUnique)) {
            $alertMsg .= "\n\nThe following serial number(s) were NOT imported because they already exist:\n" . implode("\n", $skippedUnique);
        }
    } else {
        $alertMsg = 'Serial products delivered successfully.';
    }
    echo "<script>alert(" . json_encode($alertMsg) . ");window.location.href='take-po-action.php?id=$id';</script>";
    exit;
}

if (isset($_POST['submit_po_store_preview']) || isset($_POST['submit_po_store_transfer_confirm'])) {
    if (!po_delivered_complete($row7)) {
        echo "<script>alert('Please complete Delivered Order before assigning items to a store.');window.location.href='take-po-action.php?id=$id';</script>";
        exit;
    }
}

if (isset($_POST['submit_po_store_transfer_confirm'])) {
    $poId = intval($id);
    $BranchIdStore = intval($_POST['PoStoreBranchId']);
    $TransferDate = mysqli_real_escape_string($conn, trim($_POST['PoStoreTransferDate']));

    $poRow = getRecord("SELECT * FROM tbl_purchase_order WHERE id='$poId'");
    if (empty($poRow) || intval($poRow['DeliveredStatus']) !== 1) {
        echo "<script>alert('Order must be delivered before assigning items to a store.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }
    if ($BranchIdStore <= 0 || empty($TransferDate)) {
        echo "<script>alert('Please select store and transfer date.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }

    $dup = getRecord("SELECT id FROM tbl_distibute_items WHERE Narration LIKE '%__POID".$poId."__%' LIMIT 1");
    if (!empty($dup['id'])) {
        echo "<script>alert('This purchase order was already transferred to a store.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }

    $previewData = po_collect_po_stock_preview($conn, $poId, $poRow);
    if (empty($previewData['rows'])) {
        echo "<script>alert('No delivered stock lines found for this PO. Complete delivery first.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }
    if (empty($previewData['has_transferable'])) {
        echo "<script>alert('All items or serial numbers are already assigned to a store.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }

    $selectedStockIds = [];
    if (isset($_POST['PoStoreStockIds']) && is_array($_POST['PoStoreStockIds'])) {
        foreach ($_POST['PoStoreStockIds'] as $sid) {
            $sid = intval($sid);
            if ($sid > 0) {
                $selectedStockIds[$sid] = true;
            }
        }
    }
    if (empty($selectedStockIds)) {
        echo "<script>alert('Please select at least one line with status OK to transfer.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }

    $linesToTransfer = [];
    foreach ($previewData['rows'] as $line) {
        $stockId = isset($line['stock_id']) ? intval($line['stock_id']) : intval($line['stk']['id']);
        if (empty($selectedStockIds[$stockId])) {
            continue;
        }
        if (!empty($line['assigned'])) {
            echo "<script>alert('One or more selected lines are already assigned to a store. Review items and try again.');window.location.href='take-po-action.php?id=$poId';</script>";
            exit;
        }
        $linesToTransfer[] = $line;
    }
    if (empty($linesToTransfer)) {
        echo "<script>alert('No valid lines selected for transfer.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }

    $VehicalDateEsc = mysqli_real_escape_string($conn, $poRow['VehicalDate']);
    $VehicalNoEsc = mysqli_real_escape_string($conn, $poRow['VehicalNo']);
    $NarrationEsc = mysqli_real_escape_string($conn, 'Purchase Order '.$poRow['InvoiceNo'].' · Store transfer __POID'.$poId.'__');

    $conn->begin_transaction();
    try {
        $sqlIns = "INSERT INTO tbl_distibute_items SET VehicalNo='$VehicalNoEsc',VehicalDate='$VehicalDateEsc',BranchId='$BranchIdStore',StoreInchId='0',CreatedDate='$TransferDate',Narration='$NarrationEsc',Status='1'";
        if (!$conn->query($sqlIns)) {
            throw new Exception($conn->error);
        }
        $DistId = mysqli_insert_id($conn);

        $detailRows = 0;
        foreach ($linesToTransfer as $line) {
            $stk = $line['stk'];
            $qty = floatval($stk['Qty']);
            if ($qty <= 0) {
                continue;
            }
            if (!empty(po_line_store_assignment_info($conn, $stk, $poRow['VehicalDate'], $poRow['VehicalNo'])['assigned'])) {
                throw new Exception('Item already assigned');
            }
            $detailRows++;
            $ProductName = mysqli_real_escape_string($conn, $stk['ProductName']);
            $purityRaw = $stk['Unit'] !== null && $stk['Unit'] !== '' ? $stk['Unit'] : (isset($stk['TPUnit']) ? $stk['TPUnit'] : '');
            if ($purityRaw === '' || $purityRaw === null) {
                $purityRaw = '-';
            }
            $Purity = mysqli_real_escape_string($conn, (string) $purityRaw);
            $ProductId = mysqli_real_escape_string($conn, $stk['ProductId']);
            $ModelNo = mysqli_real_escape_string($conn, isset($stk['ModelNo']) ? $stk['ModelNo'] : '');
            $SerialNo = mysqli_real_escape_string($conn, $stk['SerialNo'] !== null && $stk['SerialNo'] !== '' ? $stk['SerialNo'] : 'N/A');
            $pt = isset($stk['ProdType']) ? intval($stk['ProdType']) : 0;
            $ptDb = ($pt === 1) ? '1' : (($pt === 2) ? '2' : '0');
            $qtyEsc = mysqli_real_escape_string($conn, (string) $qty);
            $detailCode = mysqli_real_escape_string($conn, substr('PO' . $poId . 'D' . $DistId . '_' . $detailRows . '_' . bin2hex(random_bytes(6)), 0, 255));

            $sqlDet = "INSERT INTO tbl_distibute_item_details SET VehicalNo='$VehicalNoEsc',VehicalDate='$VehicalDateEsc',BranchId='$BranchIdStore',DistId='$DistId',StoreInchId='0',ProductName='$ProductName',Purity='$Purity',Qty='$qtyEsc',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$TransferDate',SerialNo='$SerialNo',ProdType='$ptDb',code='$detailCode'";
            if (!$conn->query($sqlDet)) {
                throw new Exception($conn->error);
            }
        }
        if ($detailRows === 0) {
            throw new Exception('No quantities to transfer');
        }

        $storeBr = getRecord("SELECT Name FROM tbl_branch WHERE id='$BranchIdStore' LIMIT 1");
        $storeLabel = (is_array($storeBr) && !empty($storeBr['Name'])) ? trim((string) $storeBr['Name']) : '';
        $logRemarks = 'PO ' . $poRow['InvoiceNo'] . ' assigned to store';
        if ($storeLabel !== '') {
            $logRemarks .= ': ' . $storeLabel;
        }
        $logRemarks .= ' (regular/serial lines: ' . (int) $detailRows . ')';
        if (!logPoAssignmentActivity($conn, $poId, (int) $DistId, 'po_to_store', $BranchIdStore, (int) $user_id, (int) $detailRows, $logRemarks)) {
            throw new Exception('Could not write activity log.');
        }

        $conn->commit();
        echo "<script>alert('Selected PO item(s) assigned to store only. Dispatch officer assignment is a separate step from Assign Item To Store list.');window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $errMsg = trim($e->getMessage() . ($conn->error ? ' — ' . $conn->error : ''));
        if ($errMsg === '') {
            $errMsg = 'Transfer failed. Please try again.';
        }
        echo "<script>alert(" . json_encode($errMsg, JSON_HEX_TAG | JSON_HEX_APOS) . ");window.location.href='take-po-action.php?id=$poId';</script>";
        exit;
    }
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Purchase Order Status</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 
                                <fieldset>
                                 <legend>Apply Order</legend>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Purchase Manager<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="EmpId" id="EmpId" required>
<option selected="" value="">Select Purchase Manager</option>
 <?php 
  $sql12 = "SELECT Fname,Phone,id FROM tbl_users WHERE Status='1' AND Roll IN(28)";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["EmpId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Apply Date <span class="text-danger">*</span></label>
     <input type="date" name="ApplyDate" id="ApplyDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ApplyDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
</fieldset>


 <fieldset>
                                 <legend>Send Order Details</legend>
<?php if (!$poSendUnlocked) { ?>
                                <div class="alert alert-warning mb-0">
                                    <i class="fa fa-lock"></i> Complete <strong>Apply Order</strong> first. Send Order Details will unlock after step 1 is submitted.
                                </div>
<?php } else { ?>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Send To Manufacturer</label>
 <select class="form-control">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3 AND id='".$row7["CustId"]."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Send Date <span class="text-danger">*</span></label>
     <input type="date" name="SendDate" id="SendDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["SendDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit2" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
<?php } ?>
</fieldset>


<fieldset>
                                 <legend>Received Order Details</legend>
<?php if (!$poReceiveUnlocked) { ?>
                                <div class="alert alert-warning mb-0">
                                    <i class="fa fa-lock"></i> Complete <strong>Send Order Details</strong> first. Received Order Details will unlock after step 2 is submitted.
                                </div>
<?php } else { ?>
                                 <form id="validation-form" method="post" autocomplete="off">
                                    <div class="form-row">

<div class="form-group col-md-6" >
<label class="form-label"> Received From Manufacturer</label>
 <select class="form-control">
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=3 AND id='".$row7["CustId"]."'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Received Date <span class="text-danger">*</span></label>
     <input type="date" name="ReceiveDate" id="ReceiveDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["ReceiveDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-md-3" style="padding-top:30px;">
                                    <button type="submit" name="submit3" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form>
<?php } ?>
</fieldset>


<fieldset>
                                 <legend>Tracking Order</legend>
              
              <div class="row pt-3 pb-4">
        
        <div class="col">
          
          <div class="bs-vertical-wizard" style="padding-bottom: 10px;">
                            <ul>

                                <li class="complete">
                                    <a href="#">Order Placed . <i class="ico fa fa-check ico-green"></i>
                                         <span class="desc">Placed at <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['InvoiceDate'])));?></span>
                                    </a>
                                </li>
                               
                                  <?php if($row7['ApplyStatus'] == 1) {?>
                                  <li class="complete prev-step">
                                    <a href="#">Applied Order By <?php echo $row7['Fname'];?><i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['ApplyTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['ApplyDate'])));?></span>
                                    </a>
                                  </li> 
                                  <?php } else{?>  
                                  <li class="locked">
                                    <a href="#">Step 2 :<i class="ico fa fa-lock ico-muted"></i></a>
                                  </li>
                                  <?php } ?>
                                 
                                
                                <?php if($row7['SendStatus'] == 1) {?>  
                                <li class="complete prev-step">
                                    <a href="#">Order Send To Manufacture <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['SendTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['SendDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 3 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>   

                                 <?php if($row7['ReceiveStatus'] == 1) {?>  
                               <li class="complete prev-step">
                                    <a href="#">Order Receive From Manufacturer <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['ReceiveTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['ReceiveDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 4 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>    

                                <?php if($row7['DeliveredStatus'] == 1) {?>  
                               <li class="complete prev-step">
                                    <a href="#">Order Delivered To Customer <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Updated at <?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['DeliveredTime'])));?> , <?php echo date("d M Y", strtotime(str_replace('-', '/',$row7['DeliveredDate'])));?></span>
                                    </a>
                                </li>   
                                <?php } else{?>  
                                <li class="locked">
                                    <a href="#">Step 5 :<i class="ico fa fa-lock ico-muted"></i></a>
                                </li> 
                                <?php } ?>    
                             </ul>
                        </div>
                       
                   
        </div>
      </div>                  
</fieldset>


<fieldset>
                                 <legend>Delivered Order</legend>
<?php if (!$poDeliveryUnlocked) { ?>
                                <div class="alert alert-warning mb-0">
                                    <i class="fa fa-lock"></i> Complete <strong>Apply Order</strong>, <strong>Send Order Details</strong> and <strong>Received Order Details</strong> first. Delivered Order will unlock after step 3 is submitted.
                                </div>
<?php } else { ?>
                               
                               <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="CustomerId" value="<?php echo htmlspecialchars(isset($row7['CustomerId']) ? (string) $row7['CustomerId'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="BranchId" value="<?php echo htmlspecialchars(isset($row7['BranchId']) ? (string) $row7['BranchId'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="form-row">
<h5 class="col-12">Regular Products</h5>
<div class="col-12 po-table-scroll">
<table class="table table-striped table-bordered dt-responsive nowrap po-regular-products-table" style="width:100%">
        <thead>
            <tr>
              <th class="po-product-col">Product</th>
              <th class="po-qty-col">Qty</th>
              <th>Model No.</th>
              <th>Unit</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i2=1;
            $sql66 = "SELECT tpo.*,tp.ModelNo FROM tbl_purchase_order_products tpo INNER JOIN tbl_products tp ON tpo.ProductId=tp.id WHERE tpo.SellId='".$_GET['id']."' AND tp.Roll=0";
            $row66 = getList($sql66);
            foreach ((is_array($row66) ? $row66 : []) as $result) {
               

                    
                ?>
            <tr>
                <td class="po-product-col"><?php echo $result['ProductName'];?></td>
               <td class="po-qty-col"><input type="text" value="<?php echo $result['Qty'];?>" name="Qty[]" class="form-control po-qty-input"></td>
                <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $result['Purity'];?></td>
                <input type="hidden" name="ProductName[]" value='<?php echo $result['ProductName'];?>'>
<input type="hidden" value="N/A" name="SerialNo[]" class="form-control">
                 <input type="hidden" name="SrNo[]" value="<?php echo (int) $i2; ?>">
                <input type="hidden" name="ModelNo[]" value='<?php echo $result['ModelNo'];?>'>
                <input type="hidden" name="ProductId[]" value="<?php echo $result['ProductId'];?>">
                <!-- <input type="hidden" name="Qty[]" value="1"> -->
                <input type="hidden" name="CompId[]" value="<?php echo $row7['CompId'];?>">
                <!-- <input type="hidden" name="BranchId[]" value="<?php echo $row7['BranchId'];?>"> -->
                <input type="hidden" name="ProdType[]" value="0">
                <input type="hidden" name="Unit[]" value="<?php echo htmlspecialchars(isset($result['Purity']) ? (string) $result['Purity'] : '-', ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="PostId[]" value="<?php echo $result['id'];?>">
            </tr>
        <?php $i2++; }  ?>
        </tbody>
    </table>
</div>

   

<div class="form-group col-md-3">
   <label class="form-label">Delivered Date <span class="text-danger">*</span></label>
     <input type="date" name="DeliveredDate" id="DeliveredDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["DeliveredDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-lg-2">
<label class="form-label">Vehicle Date <span class="text-danger">*</span></label>
<input type="date" name="VehicalDate" id="VehicalDate" class="form-control" value="<?php echo $row7["VehicalDate"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Vehicle No <span class="text-danger">*</span></label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" value="<?php echo $row7["VehicalNo"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-3">
<label class="form-label">Bill No <span class="text-danger">*</span></label>
<input type="text" name="BillNo" id="BillNo" class="form-control" value="<?php echo $row7["BillNo"]; ?>" required>
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-2" style="padding-top:30px;">
                                    <button type="submit" name="submit4" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>

                                    </div>
</form> <br>
<hr><br>
<form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <input type="hidden" name="CustomerId" value="<?php echo htmlspecialchars(isset($row7['CustomerId']) ? (string) $row7['CustomerId'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="hidden" name="BranchId" value="<?php echo htmlspecialchars(isset($row7['BranchId']) ? (string) $row7['BranchId'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <div class="form-row">
 <h5>Serial No Products
        <!--<?php for($i=1;$i<=200;$i++){?>&nbsp;<?php } ?><a href="generate-excel.php?id=<?php echo $_GET['id'];?>&compid=<?php echo $row7['CompId'];?>&invno=<?php echo $row7['InvoiceNo'];?>" style="float:right;" >Download Excel</a>--></h5>
<table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
        <thead>
            <tr>
              <th>SrNo</th>
               <th>Product Id</th>
              <th>Product</th>
              <th>SERIAL NO.</th> 
              <th>Unit</th>
              <th>Qty</th>
              <th>MODEL NO.</th>
              <th>Company Id</th>
              <!--<th>Product Type</th>-->
              <th>Post Id</th>
              <th>Vehicle Date</th>
              <th>Vehicle No</th>
             <!-- <th>Bill No</th>-->
             <th>OEM Vendor</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i3=1;
            $sql = "SELECT * FROM tbl_stocks WHERE SellId='".$_GET['id']."' AND ProdType='1' AND SellType='Purchase' ORDER BY ProductId ASC";
            $rncnt = getRow($sql);
            if($rncnt > 0){
            $sql66 = "SELECT tpo.* FROM tbl_stocks tpo WHERE tpo.SellId='".$_GET['id']."' AND ProdType='1' AND SellType='Purchase'";
            
             $row66 = getList($sql66);
            foreach ((is_array($row66) ? $row66 : []) as $result) {
               
                ?>
            <tr>
                <td><?php echo $result['SrNo'];?></td>
                 <td><?php echo $result['ProductId'];?></td>
                <td><?php echo $result['ProductName'];?></td>
               
                <td><?php echo $result['SerialNo'];?></td>
                <td><?php echo $result['Unit'];?></td>
                 <td><?php echo $result['Qty'];?></td>
                 <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $row7['CompId'];?></td>
               <!-- <td>1</td>-->
                <td><?php echo $result['PostId'];?></td>
                <td><?php echo $result['VehicalDate'];?></td>
                <td><?php echo $result['VehicalNo'];?></td>
                 <td><?php echo $result['OemVedName'];?></td>
               <!-- <td><?php echo $result['BillNo'];?></td>-->
            </tr>
        <?php 
        
            } }
            else{
                $sql66 = "SELECT tpo.*,tp.ModelNo FROM tbl_purchase_order_products tpo INNER JOIN tbl_products tp ON tpo.ProductId=tp.id WHERE tpo.SellId='".$_GET['id']."' AND tp.Roll=1";
                 $row66 = getList($sql66);
            foreach ((is_array($row66) ? $row66 : []) as $result) {
               for($i=1;$i<=$result['Qty'];$i++){

                    $sql = "SELECT SerialNo, VehicalDate, VehicalNo, OemVedName FROM tbl_stocks WHERE SellId='".$_GET['id']."' AND ProductId='".$result['ProductId']."' AND SrNo='$i' AND SellType='Purchase'";
                    $row = getRecord($sql);
                    $row = is_array($row) ? $row : [];
                    $SerialNo = isset($row['SerialNo']) ? $row['SerialNo'] : '';
                ?>
            <tr>
                <td><?php echo $i;?></td>
                 <td><?php echo $result['ProductId'];?></td>
                <td><?php echo $result['ProductName'];?></td>
               
                <td><?php echo $SerialNo;?></td>
                <td><?php echo $result['Purity'];?></td>
                <td>1</td>
                 <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $row7['CompId'];?></td>
               <!-- <td>1</td>-->
                <td><?php echo $result['id'];?></td>
                <td><?php echo isset($row['VehicalDate']) && $row['VehicalDate'] !== '' ? $row['VehicalDate'] : (isset($row7['VehicalDate']) ? $row7['VehicalDate'] : ''); ?></td>
                <td><?php echo isset($row['VehicalNo']) && $row['VehicalNo'] !== '' ? $row['VehicalNo'] : (isset($row7['VehicalNo']) ? $row7['VehicalNo'] : ''); ?></td>
                <!--<td><?php echo $row['BillNo'];?></td>-->
                <td><?php echo isset($row['OemVedName']) ? $row['OemVedName'] : ''; ?></td>
            </tr>
        <?php $i3++; }
            }
            }  ?>
        </tbody>
    </table>
    
    


<div class="form-group col-md-3">
   <label class="form-label">Delivered Date <span class="text-danger">*</span></label>
     <input type="date" name="DeliveredDate" id="DeliveredDate2" class="form-control"
                                                placeholder="" value="<?php echo $row7["DeliveredDate"]; ?>"
                                                autocomplete="off" required>
    <div class="clearfix"></div>
 </div> 

 <div class="form-group col-lg-3">
<label class="form-label">Bill No <span class="text-danger">*</span></label>
<input type="text" name="BillNo" id="BillNo2" class="form-control" value="<?php echo $row7["BillNo"]; ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
   <label class="form-label">Upload Excel File <?php if ($poSerialStockCount < 1) { ?><span class="text-danger">*</span><?php } ?></label>
     <input type="file" name="file" id="SerialImportFile" class="form-control"
                                                placeholder=""
                                                autocomplete="off" <?php if ($poSerialStockCount < 1) { ?>required<?php } ?>>
    
    <div class="clearfix"></div>
 </div>
 
 <div class="form-group col-md-2" style="padding-top:30px;">
                                    <button type="submit" name="submit5" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                    </div>
                                    
    </div>
</form> 
<?php } ?>
</fieldset>

<?php
$poAlreadyTransfer = getRecord("SELECT di.id, tb.Name AS StoreName, di.CreatedDate FROM tbl_distibute_items di LEFT JOIN tbl_branch tb ON di.BranchId = tb.id WHERE di.Narration LIKE '%__POID".intval($id)."__%' LIMIT 1");
if (!is_array($poAlreadyTransfer)) {
    $poAlreadyTransfer = [];
}

$poPreviewShow = false;
$poPreviewLines = [];
$poPreviewBlocked = false;
$poPreviewBranchId = 0;
$poPreviewTransferDate = '';
$poPreviewPostBranch = isset($_POST['PoStoreBranchId']) ? intval($_POST['PoStoreBranchId']) : 0;
$poPreviewPostDate = isset($_POST['PoStoreTransferDate']) ? trim($_POST['PoStoreTransferDate']) : '';
$poPreviewTransferDateRaw = '';

if (isset($_POST['submit_po_store_preview']) && $poStoreAssignUnlocked && empty($poAlreadyTransfer['id'])) {
    $poPreviewBranchId = intval($_POST['PoStoreBranchId']);
    $poPreviewTransferDateRaw = isset($_POST['PoStoreTransferDate']) ? trim($_POST['PoStoreTransferDate']) : '';
    if ($poPreviewBranchId > 0 && $poPreviewTransferDateRaw !== '') {
        $pv = po_collect_po_stock_preview($conn, intval($id), $row7);
        $poPreviewLines = $pv['rows'];
        $poPreviewHasTransferable = !empty($pv['has_transferable']);
        $poPreviewBlocked = empty($poPreviewHasTransferable);
        $poPreviewShow = true;
    }
}
?>
<fieldset>
    <legend>Assign Purchase Order items to Store</legend>
<?php if (!$poStoreAssignUnlocked) { ?>
    <div class="alert alert-warning mb-0">
        <i class="fa fa-lock"></i> Complete <strong>Delivered Order</strong> first. Store assignment will unlock after delivery is submitted.
    </div>
<?php } elseif (!empty($poAlreadyTransfer['id'])) { ?>
        <div class="alert alert-success mb-0">
            Already assigned to store: <strong><?php echo htmlspecialchars($poAlreadyTransfer['StoreName']); ?></strong>
            <?php if (!empty($poAlreadyTransfer['CreatedDate'])) { ?>
                &nbsp;·&nbsp; Date: <strong><?php echo date('d M Y', strtotime(str_replace('-', '/', $poAlreadyTransfer['CreatedDate']))); ?></strong>
            <?php } ?>
            <p class="small mt-2 text-muted">Items are at the store only until you use <strong>Assign Item To Store List</strong> → <em>Assign to dispatch</em> (not done automatically from this PO screen).</p>
            <div class="mt-2">
                <a href="view-distribute-item-store.php" class="btn btn-sm btn-outline-primary">Open assign list (dispatch step)</a>
                <a href="view-assigning-items.php?id=<?php echo intval($poAlreadyTransfer['id']); ?>" class="btn btn-sm btn-outline-secondary">View line items</a>
            </div>
        </div>
        <div class="mt-3">
            <h6 class="font-weight-bold">Assignment activity log</h6>
            <?php echo renderPoAssignmentActivityLogHtml($conn, intval($id)); ?>
        </div>
<?php } else {
        $poStockLines = getRow("SELECT id FROM tbl_stocks WHERE SellId='".intval($id)."' AND SellType='Purchase' LIMIT 1");
        if ($poStockLines < 1) { ?>
            <div class="alert alert-warning mb-0">Deliver stock items first (regular and/or serial products above). Transfer becomes available once stock lines exist for this PO.</div>
        <?php } else {
            $defaultTransferDate = !empty($row7['DeliveredDate']) ? $row7['DeliveredDate'] : date('Y-m-d');
        ?>

        <?php if ($poPreviewShow) { ?>
            <?php if ($poPreviewBlocked) { ?>
                <div class="alert alert-danger">
                    <strong>Cannot assign to store:</strong> all lines are already assigned to a store (existing record in store assignment).
                    Regular items are matched by product, vehicle date/no, qty and model; serial lines by product + serial number.
                </div>
            <?php } else { ?>
                <div class="alert alert-info mb-3">
                    Review the lines below. Lines with status <strong>OK</strong> are selected by default.
                    Click <strong>Submit</strong> to assign only the checked regular / serial lines to the <strong>store</strong> (not to a dispatch officer).
                    Dispatch assignment is done later from <a href="view-distribute-item-store.php">Assign Item To Store List</a>.
                    Or <a href="take-po-action.php?id=<?php echo intval($id); ?>">change store / date</a>.
                </div>
                <?php
                $poPreviewSomeAssigned = false;
                foreach ($poPreviewLines as $pline) {
                    if (!empty($pline['assigned'])) {
                        $poPreviewSomeAssigned = true;
                        break;
                    }
                }
                if ($poPreviewSomeAssigned) { ?>
                <div class="alert alert-warning">
                    Some lines are already in a store and cannot be selected. Only checked <strong>OK</strong> lines will be transferred.
                </div>
                <?php } ?>
            <?php } ?>

            <?php if ($poPreviewShow && !$poPreviewBlocked && !empty($poPreviewLines)) { ?>
            <form method="post" autocomplete="off" id="poStoreTransferForm" onsubmit="return poStoreTransferConfirm();">
                <input type="hidden" name="PoStoreBranchId" value="<?php echo intval($poPreviewBranchId); ?>">
                <input type="hidden" name="PoStoreTransferDate" value="<?php echo htmlspecialchars($poPreviewTransferDateRaw); ?>">
            <?php } ?>
            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <?php if (!$poPreviewBlocked && !empty($poPreviewLines)) { ?>
                            <th class="text-center" style="width:50px;">Select</th>
                            <?php } ?>
                            <th>#</th>
                            <th>Type</th>
                            <th>Product</th>
                            <th>Model no.</th>
                            <th>Serial no.</th>
                            <th class="text-right">Qty</th>
                            <th>Status</th>
                            <th>Assigned store</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pr = 1;
                        foreach ($poPreviewLines as $pline) {
                            $stk = $pline['stk'];
                            $stockId = isset($pline['stock_id']) ? intval($pline['stock_id']) : intval($stk['id']);
                            $asn = isset($pline['assigned_store_name']) ? trim((string) $pline['assigned_store_name']) : '';
                            if ($pline['assigned']) {
                                $st = '<span class="text-danger">Already in store</span>';
                                $storeCol = $asn !== '' ? htmlspecialchars($asn) : '<span class="text-muted">—</span>';
                            } else {
                                $st = '<span class="text-success">OK</span>';
                                $storeCol = '<span class="text-muted">—</span>';
                            }
                        ?>
                        <tr>
                            <?php if (!$poPreviewBlocked && !empty($poPreviewLines)) { ?>
                            <td class="text-center">
                                <?php if (!$pline['assigned']) { ?>
                                <input type="checkbox" name="PoStoreStockIds[]" value="<?php echo $stockId; ?>" checked>
                                <?php } else { ?>
                                <input type="checkbox" disabled title="Already assigned to a store">
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <td><?php echo $pr++; ?></td>
                            <td><?php echo htmlspecialchars($pline['type_label']); ?></td>
                            <td><?php echo htmlspecialchars(isset($stk['ProductName']) ? $stk['ProductName'] : ''); ?></td>
                            <td><?php echo htmlspecialchars(isset($stk['ModelNo']) ? $stk['ModelNo'] : ''); ?></td>
                            <td><?php echo htmlspecialchars($pline['serial_disp']); ?></td>
                            <td class="text-right"><?php echo htmlspecialchars((string) $pline['qty']); ?></td>
                            <td><?php echo $st; ?></td>
                            <td><?php echo $storeCol; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if ($poPreviewShow && !$poPreviewBlocked && !empty($poPreviewLines)) { ?>
                <button type="submit" name="submit_po_store_transfer_confirm" class="btn btn-success">Submit</button>
                <a class="btn btn-outline-secondary ml-2" href="take-po-action.php?id=<?php echo intval($id); ?>">Cancel</a>
            </form>
            <script>
            function poStoreTransferConfirm() {
                var form = document.getElementById('poStoreTransferForm');
                if (!form) return false;
                var checked = form.querySelectorAll('input[name="PoStoreStockIds[]"]:checked');
                if (checked.length < 1) {
                    alert('Please select at least one line with status OK.');
                    return false;
                }
                return confirm('Assign ' + checked.length + ' selected line(s) to the store?');
            }
            </script>
            <?php } ?>

            <?php if ($poPreviewBlocked || empty($poPreviewLines)) { ?>
            <form method="post" autocomplete="off" class="mt-3">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label class="form-label">Store <span class="text-danger">*</span></label>
                        <select class="form-control" name="PoStoreBranchId" required>
                            <option value="">Select store</option>
                            <?php
                            $sqlBr = "SELECT * FROM tbl_branch WHERE Status='1' ORDER BY Name ASC";
                            $rowBr = getList($sqlBr);
                            if (!empty($rowBr) && is_array($rowBr)) {
                                foreach ($rowBr as $br) {
                                    $sel = ($poPreviewPostBranch === intval($br['id'])) ? ' selected' : '';
                            ?>
                            <option value="<?php echo intval($br['id']); ?>"<?php echo $sel; ?>><?php echo htmlspecialchars($br['Name']); ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">Transfer date <span class="text-danger">*</span></label>
                        <input type="date" name="PoStoreTransferDate" class="form-control" required value="<?php echo htmlspecialchars($poPreviewPostDate !== '' ? $poPreviewPostDate : $defaultTransferDate); ?>">
                    </div>
                    <div class="form-group col-md-3" style="padding-top:24px;">
                        <button type="submit" name="submit_po_store_preview" class="btn btn-primary">Review items</button>
                    </div>
                </div>
            </form>
            <?php } ?>

        <?php } else { ?>
        <form method="post" autocomplete="off">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4">
                    <label class="form-label">Store <span class="text-danger">*</span></label>
                    <select class="form-control" name="PoStoreBranchId" id="PoStoreBranchId" required>
                        <option value="">Select store</option>
                        <?php
                        $sqlBr = "SELECT * FROM tbl_branch WHERE Status='1' ORDER BY Name ASC";
                        $rowBr = getList($sqlBr);
                        if (!empty($rowBr) && is_array($rowBr)) {
                            foreach ($rowBr as $br) {
                        ?>
                        <option value="<?php echo intval($br['id']); ?>"><?php echo htmlspecialchars($br['Name']); ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label class="form-label">Transfer date <span class="text-danger">*</span></label>
                    <input type="date" name="PoStoreTransferDate" class="form-control" required value="<?php echo htmlspecialchars($defaultTransferDate); ?>">
                </div>
                <div class="form-group col-md-3" style="padding-top:24px;">
                    <button type="submit" name="submit_po_store_preview" class="btn btn-primary">Review items</button>
                </div>
            </div>
            <p class="text-muted small mb-0">Choose store and date, then review all lines before confirming. Assignment goes to the store only; dispatch officer is assigned separately later. Already-assigned serials or regular lines cannot be transferred again.</p>
        </form>
        <?php } ?>

        <?php } ?>
<?php } ?>
</fieldset>

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
 
    $(document).ready(function() {
    if ($.fn.dataTable.isDataTable('#example')) {
        $('#example').DataTable().destroy();
    }
    $('#example').DataTable({
         "scrollX": true,
         retrieve: true,
         order: [[1, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>
</body>

</html>