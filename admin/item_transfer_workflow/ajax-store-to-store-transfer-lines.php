<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, BranchId, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$BranchId = $row77['BranchId'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 27 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_allowed) {
    echo json_encode(array('ok' => false, 'message' => 'Access denied.'));
    exit;
}

$tid = (int)($_GET['transfer_id'] ?? 0);
if ($tid <= 0) {
    echo json_encode(array('ok' => false, 'message' => 'Invalid transfer.'));
    exit;
}

$w = "t.id='" . (int)$tid . "'";
if ($Roll == 27) {
    $w .= " AND (t.FromBranchId='" . (int)$BranchId . "' OR t.ToBranchId='" . (int)$BranchId . "')";
}

$t = getRecord("SELECT t.id, t.TransferDate, tb1.Name AS FromStoreName, tb2.Name AS ToStoreName
FROM tbl_store_to_store_transfer t
LEFT JOIN tbl_branch tb1 ON t.FromBranchId = tb1.id
LEFT JOIN tbl_branch tb2 ON t.ToBranchId = tb2.id
WHERE $w");
if (!$t || empty($t['id'])) {
    echo json_encode(array('ok' => false, 'message' => 'Transfer not found or access denied.'));
    exit;
}

$lines = array();
$q = $conn->query("SELECT d.id, d.ProductName, d.Qty, d.SerialNo, d.Unit, d.ModelNo
FROM tbl_store_to_store_transfer_details d
WHERE d.TransferId='" . (int)$tid . "'
ORDER BY d.id ASC");
if ($q) {
    while ($r = $q->fetch_assoc()) {
        $lines[] = array(
            'id' => (int)$r['id'],
            'ProductName' => $r['ProductName'],
            'Qty' => $r['Qty'],
            'SerialNo' => isset($r['SerialNo']) ? trim((string)$r['SerialNo']) : '',
            'Unit' => isset($r['Unit']) ? $r['Unit'] : '',
            'ModelNo' => isset($r['ModelNo']) ? $r['ModelNo'] : '',
        );
    }
}

echo json_encode(array(
    'ok' => true,
    'transferId' => (int)$tid,
    'transferDate' => $t['TransferDate'],
    'fromStore' => $t['FromStoreName'],
    'toStore' => $t['ToStoreName'],
    'lines' => $lines,
));
