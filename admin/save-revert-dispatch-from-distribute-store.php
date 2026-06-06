<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-store-dist-dispatch-status.php';
require_once __DIR__ . '/inc-po-assignment-activity-log.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, BranchId FROM tbl_users WHERE id='$user_id'");
$Roll = isset($row77['Roll']) ? (int) $row77['Roll'] : 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];
$canAssignDispatch = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
if (!$canAssignDispatch) {
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

$h = getRecord("SELECT id FROM tbl_distibute_items WHERE id='$storeDistId' AND Status=1 LIMIT 1");
if (!is_array($h) || empty($h['id'])) {
    echo "<script>alert('Store assignment not found or inactive.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$assignment = getStoreDistDispatchAssignment($conn, $storeDistId);
$result = revertStoreDistDispatchAssignment($conn, $storeDistId, (int) $user_id);
if (!empty($result['ok'])) {
    if (is_array($assignment)) {
        $remarks = 'Reverted to store';
        if (!empty($assignment['OfficerName'])) {
            $remarks .= ' (was: ' . $assignment['OfficerName'] . ')';
        }
        logPoDispatchActivityFromStoreDist(
            $conn,
            $storeDistId,
            'dispatch_revert',
            (int) ($assignment['StoreExeId'] ?? 0),
            (int) $user_id,
            0,
            $remarks
        );
    }
    echo "<script>alert('Reverted to store successfully. You can assign to dispatch again.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$err = isset($result['error']) ? $result['error'] : 'Revert failed.';
echo "<script>alert(" . json_encode($err) . ");window.location.href='view-distribute-item-store.php';</script>";
exit;
