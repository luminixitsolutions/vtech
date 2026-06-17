<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-po-assignment-activity-log.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, BranchId FROM tbl_users WHERE id='$user_id'");
$Roll = isset($row77['Roll']) ? (int) $row77['Roll'] : 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];
$canRevertPo = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
if (!$canRevertPo) {
    echo "<script>alert('Access denied.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$storeDistId = isset($_POST['store_dist_id']) ? (int) $_POST['store_dist_id'] : 0;
if ($storeDistId < 1) {
    echo "<script>alert('Invalid assignment.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$result = revertStoreDistAssignmentToPo($conn, $storeDistId, (int) $user_id);
if (!empty($result['ok']) && !empty($result['po_id'])) {
    $poId = (int) $result['po_id'];
    echo "<script>alert('Reverted to purchase order successfully. You can assign items to store again.');window.location.href='take-po-action.php?id=" . $poId . "';</script>";
    exit;
}

$err = isset($result['error']) ? $result['error'] : 'Revert to PO failed.';
echo "<script>alert(" . json_encode($err) . ");window.location.href='view-distribute-item-store.php';</script>";
exit;
