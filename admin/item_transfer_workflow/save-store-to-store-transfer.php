<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];

function RandomStringGenerator($n) {
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    $s = "";
    for ($i = 0; $i < $n; $i++) $s .= $domain[rand(0, $len - 1)];
    return $s;
}

$FromBranchId = (int)$_POST['FromBranchId'];
$ToBranchId = (int)$_POST['ToBranchId'];
$TransferDate = $conn->real_escape_string($_POST['TransferDate']);
$Narration = $conn->real_escape_string(trim($_POST['Narration'] ?? ''));
$CreatedDate = date('Y-m-d H:i:s');

if ($FromBranchId <= 0 || $ToBranchId <= 0) {
    echo "<script>alert('Invalid store.'); history.back();</script>";
    exit;
}

$conn->query("INSERT INTO tbl_store_to_store_transfer SET FromBranchId='$FromBranchId', ToBranchId='$ToBranchId', TransferDate='$TransferDate', Narration='$Narration', CreatedBy='$user_id', CreatedDate='$CreatedDate'");
$TransferId = mysqli_insert_id($conn);
if ($TransferId <= 0) {
    echo "<script>alert('Error creating transfer.'); history.back();</script>";
    exit;
}

if (!empty($_POST['QtyProduct']) && is_array($_POST['QtyProduct'])) {
    foreach ($_POST['QtyProduct'] as $ProductId => $qty) {
        $qty = (float)$qty;
        if ($qty <= 0) continue;
        $ProductId = (int)$ProductId;
        $pr = getRecord("SELECT ProductName, Unit FROM tbl_products WHERE id='$ProductId'");
        if (!$pr) continue;
        $ProductName = $conn->real_escape_string($pr['ProductName']);
        $Unit = $conn->real_escape_string($pr['Unit'] ?? '');
        $ModelNo = '';
        $conn->query("INSERT INTO tbl_store_to_store_transfer_details SET TransferId='$TransferId', ProductId='$ProductId', ProductName='$ProductName', Qty='$qty', SerialNo='', ProdType=0, Unit='$Unit', ModelNo='$ModelNo'");
        $conn->query("INSERT INTO tbl_distibute_item_details2 SET BranchId='$FromBranchId', StoreExeId='0', DistId='0', StoreInchId='0', ProductId='$ProductId', ProductName='$ProductName', Purity='$Unit', Qty='$qty', SerialNo='', ProdType=0, ModelNo='$ModelNo', CreatedDate='$CreatedDate'");
        $code = RandomStringGenerator(10) . $TransferId . $ProductId;
        $conn->query("INSERT INTO tbl_distibute_item_details SET BranchId='$ToBranchId', ProductId='$ProductId', ProductName='$ProductName', Qty='$qty', SerialNo='', ProdType=0, ModelNo='$ModelNo', code='$code'");
    }
}

if (!empty($_POST['SerialDetailIds']) && is_array($_POST['SerialDetailIds'])) {
    foreach ($_POST['SerialDetailIds'] as $DetailId) {
        $DetailId = (int)$DetailId;
        if ($DetailId <= 0) continue;
        $dr = getRecord("SELECT ProductId, ProductName, SerialNo, ProdType, ModelNo, Qty FROM tbl_distibute_item_details WHERE id='$DetailId' AND BranchId='$FromBranchId'");
        if (!$dr) continue;
        $ProductId = (int)$dr['ProductId'];
        $ProductName = $conn->real_escape_string($dr['ProductName']);
        $SerialNo = $conn->real_escape_string($dr['SerialNo'] ?? '');
        $ProdType = (int)$dr['ProdType'];
        $Unit = $conn->real_escape_string($dr['Unit'] ?? '');
        $ModelNo = $conn->real_escape_string($dr['ModelNo'] ?? '');
        $Qty = (float)$dr['Qty'];
        $conn->query("INSERT INTO tbl_store_to_store_transfer_details SET TransferId='$TransferId', ProductId='$ProductId', ProductName='$ProductName', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType', Unit='$Unit', ModelNo='$ModelNo'");
        $conn->query("INSERT INTO tbl_distibute_item_details2 SET BranchId='$FromBranchId', StoreExeId='0', DistId='0', StoreInchId='0', ProductId='$ProductId', ProductName='$ProductName', Purity='$Unit', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType', ModelNo='$ModelNo', CreatedDate='$CreatedDate'");
        $code = RandomStringGenerator(10) . $DetailId . $TransferId;
        $conn->query("INSERT INTO tbl_distibute_item_details SET BranchId='$ToBranchId', ProductId='$ProductId', ProductName='$ProductName', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType',  ModelNo='$ModelNo', code='$code'");
    }
}

echo "<script>alert('Store to store transfer saved successfully.'); window.location.href='view-store-to-store-transfers.php';</script>";
exit;
