<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
require_once __DIR__ . '/../inc-item-transfer-workflow-access.php';
header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['Admin']['id'];
$workflowUser = itemTransferWorkflowUserContext($user_id);
$Roll = $workflowUser['roll'];
$Options = $workflowUser['options'];
if (!itemTransferWorkflowCanAccessDispatch($Roll, $Options)) {
    itemTransferWorkflowDeny('Access denied.', true);
}

$tid = (int)($_GET['transfer_id'] ?? 0);
if ($tid <= 0) {
    echo json_encode(array('ok' => false, 'message' => 'Invalid transfer.'));
    exit;
}

$w = "t.id='" . (int)$tid . "'";
if ($Roll == 26) {
    $w .= " AND t.DispatchOfficerId='" . (int)$user_id . "'";
}

$t = getRecord("SELECT t.id, t.TransferDate, tb.Name AS ToStoreName
FROM tbl_dispatch_to_store_transfer t
LEFT JOIN tbl_branch tb ON t.ToBranchId = tb.id
WHERE $w");
if (!$t || empty($t['id'])) {
    echo json_encode(array('ok' => false, 'message' => 'Transfer not found or access denied.'));
    exit;
}

$lines = array();
$q = $conn->query("SELECT d.id, d.ProductName, d.Qty, d.SerialNo, d.Unit
FROM tbl_dispatch_to_store_transfer_details d
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
        );
    }
}

echo json_encode(array(
    'ok' => true,
    'transferId' => (int)$tid,
    'transferDate' => $t['TransferDate'],
    'toStore' => $t['ToStoreName'],
    'lines' => $lines,
));
