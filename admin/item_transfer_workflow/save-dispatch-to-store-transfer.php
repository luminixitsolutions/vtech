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

$ToBranchId = (int)($_POST['ToBranchId'] ?? 0);
$TransferDate = $conn->real_escape_string($_POST['TransferDate'] ?? date('Y-m-d'));
$Narration = $conn->real_escape_string(trim($_POST['Narration'] ?? ''));
$CreatedDate = date('Y-m-d H:i:s');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request.'); window.location.href='dispatch-to-store-transfer.php';</script>";
    exit;
}

if ($ToBranchId <= 0) {
    echo "<script>alert('Invalid store.'); history.back();</script>";
    exit;
}

$hasDetail2Id = false;
$chkCol = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
if ($chkCol && $chkCol->num_rows > 0) {
    $hasDetail2Id = true;
}

$conn->begin_transaction();
$ok = true;

$ok = $ok && $conn->query("INSERT INTO tbl_dispatch_to_store_transfer SET DispatchOfficerId='$user_id', ToBranchId='$ToBranchId', TransferDate='$TransferDate', Narration='$Narration', CreatedBy='$user_id', CreatedDate='$CreatedDate'");
$TransferId = mysqli_insert_id($conn);
if ($TransferId <= 0) {
    $conn->rollback();
    echo "<script>alert('Error creating transfer.'); history.back();</script>";
    exit;
}

if (!empty($_POST['QtyProduct']) && is_array($_POST['QtyProduct'])) {
    foreach ($_POST['QtyProduct'] as $ProductId => $qty) {
        $qty = (float)$qty;
        if ($qty <= 0) continue;
        $ProductId = (int)$ProductId;
        if ($hasDetail2Id) {
            $excludeClause = "AND id NOT IN (SELECT Detail2Id FROM tbl_dispatch_to_store_transfer_details)";
        } else {
            $excludeClause = "";
        }
        $avail = $conn->query("SELECT id, ProductName, Purity, Qty, ModelNo FROM tbl_distibute_item_details2 
            WHERE StoreExeId='$user_id' AND ProductId='$ProductId' AND ProdType=0 
            $excludeClause ORDER BY id");
        if (!$avail) {
            $ok = false;
            break;
        }
        $transferred = 0;
        while ($dr = $avail->fetch_assoc()) {
            if ($transferred >= $qty) break;
            $Detail2Id = (int)$dr['id'];
            $use_qty = min((float)$dr['Qty'], $qty - $transferred);
            if ($use_qty <= 0) continue;
            $transferred += $use_qty;
            $ProductName = $conn->real_escape_string($dr['ProductName']);
            $Purity = $conn->real_escape_string($dr['Purity'] ?? '');
            $ModelNo = $conn->real_escape_string($dr['ModelNo'] ?? '');
            if ($hasDetail2Id) {
                $ok = $ok && $conn->query("INSERT INTO tbl_dispatch_to_store_transfer_details SET TransferId='$TransferId', Detail2Id='$Detail2Id', ProductId='$ProductId', ProductName='$ProductName', Qty='$use_qty', SerialNo='', ProdType=0, Unit='$Purity', ModelNo='$ModelNo'");
            } else {
                $ok = $ok && $conn->query("INSERT INTO tbl_dispatch_to_store_transfer_details SET TransferId='$TransferId', ProductId='$ProductId', ProductName='$ProductName', Qty='$use_qty', SerialNo='', ProdType=0, Unit='$Purity', ModelNo='$ModelNo'");
            }
            $code = RandomStringGenerator(10) . $Detail2Id . $TransferId;
            $ok = $ok && $conn->query("INSERT INTO tbl_distibute_item_details SET BranchId='$ToBranchId', ProductId='$ProductId', ProductName='$ProductName', Qty='$use_qty', SerialNo='', ProdType=0,  ModelNo='$ModelNo', code='$code'");
        }
    }
}

if (!empty($_POST['SerialIds']) && is_array($_POST['SerialIds'])) {
    foreach ($_POST['SerialIds'] as $Detail2Id) {
        $Detail2Id = (int)$Detail2Id;
        if ($Detail2Id <= 0) continue;
        $dr = getRecord("SELECT id, ProductId, ProductName, Purity, Qty, SerialNo, ProdType, ModelNo FROM tbl_distibute_item_details2 WHERE id='$Detail2Id' AND StoreExeId='$user_id'");
        if (!$dr) continue;
        $ProductId = (int)$dr['ProductId'];
        $ProductName = $conn->real_escape_string($dr['ProductName']);
        $Purity = $conn->real_escape_string($dr['Purity'] ?? '');
        $SerialNo = $conn->real_escape_string($dr['SerialNo'] ?? '');
        $ModelNo = $conn->real_escape_string($dr['ModelNo'] ?? '');
        $ProdType = (int)$dr['ProdType'];
        $Qty = (float)$dr['Qty'];
        if ($hasDetail2Id) {
            $ok = $ok && $conn->query("INSERT INTO tbl_dispatch_to_store_transfer_details SET TransferId='$TransferId', Detail2Id='$Detail2Id', ProductId='$ProductId', ProductName='$ProductName', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType', Unit='$Purity', ModelNo='$ModelNo'");
        } else {
            $ok = $ok && $conn->query("INSERT INTO tbl_dispatch_to_store_transfer_details SET TransferId='$TransferId', ProductId='$ProductId', ProductName='$ProductName', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType', Unit='$Purity', ModelNo='$ModelNo'");
        }
        $code = RandomStringGenerator(10) . $Detail2Id . $TransferId;
       
        $ok = $ok && $conn->query("INSERT INTO tbl_distibute_item_details SET BranchId='$ToBranchId', ProductId='$ProductId', ProductName='$ProductName', Qty='$Qty', SerialNo='$SerialNo', ProdType='$ProdType', ModelNo='$ModelNo', code='$code'");
    }
}

if ($ok) {
    $conn->commit();
    echo "<script>alert('Transfer to store saved successfully.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
} else {
    $conn->rollback();
    echo "<script>alert('Transfer save failed. Please try again.'); history.back();</script>";
}
exit;
